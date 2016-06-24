<?php

namespace Drupal\styleguide\Plugin\Styleguide;

use Drupal\styleguide\GeneratorInterface;
use Drupal\styleguide\Plugin\StyleguidePluginBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Theme\ThemeManagerInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\image\Entity\ImageStyle;

/**
 * Image styles Styleguide items implementation.
 *
 * @Plugin(
 *   id = "image_styleguide",
 *   label = @Translation("Image styles Styleguide elements")
 * )
 */
class ImageStyleguide extends StyleguidePluginBase {

  /**
   * The styleguide generator service.
   *
   * @var \Drupal\styleguide\Generator
   */
  protected $generator;

  /**
   * The theme manager service.
   *
   * @var \Drupal\Core\Theme\ThemeManagerInterface
   */
  protected $themeManager;

  /**
   * The module handler service.
   *
   * @var ModuleHandlerInterface
   */
  protected $moduleHandler;

  /**
   * Constructs a new imageStyleguide.
   *
   * @param array $configuration
   * @param string $plugin_id
   * @param mixed $plugin_definition
   * @param \Drupal\styleguide\GeneratorInterface $styleguide_generator
   * @param ThemeManagerInterface $theme_manager
   * @param ModuleHandlerInterface $module_handler
   *
   * @internal param \Drupal\Core\Breadcrumb\ChainBreadcrumbBuilderInterface $breadcrumb
   * @internal param \Drupal\styleguide\GeneratorInterface $generator
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, GeneratorInterface $styleguide_generator, ThemeManagerInterface $theme_manager, ModuleHandlerInterface $module_handler) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);

    $this->generator = $styleguide_generator;
    $this->themeManager = $theme_manager;
    $this->moduleHandler = $module_handler;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('styleguide.generator'),
      $container->get('theme.manager'),
      $container->get('module_handler')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function items() {
    $items = [];
    if ($this->moduleHandler->moduleExists('image')) {
      // Get the sample file provided by the module.
      $preview_img_path = 'public://styleguide-preview.jpg';
      if (file_destination($preview_img_path, FILE_EXISTS_ERROR) !== FALSE) {
        // Move the image so that styles may be applied.
        file_unmanaged_copy($this->generator->image('vertical'), $preview_img_path, FILE_EXISTS_ERROR);
      }

      // Iterate through the image styles on the site.
      foreach (ImageStyle::loadMultiple() as $stylename => $style) {
        $details = [];
        foreach ($style->getEffects() as $effect) {
          $summary = $effect->getSummary();
          $summary = render($summary);
          $label = $effect->label();
          if ($summary) {
            $details[] = format_string('%label: @summary', array(
              '%label' => $label,
              '@summary' => $summary,
            ));
          }
          else {
            $details[] = format_string('%label', array(
              '%label' => $label,
            ));
          }
        }

        $title = $this->t('Image style, @stylename', ['@stylename' => $style->get('label')]);
        $items['image_' . $stylename] = [
          'title' => $title,
          'description' => [
            '#theme' => 'item_list',
            '#items' => $details,
          ],
          'content' => [
            '#theme' => 'image_style',
            '#uri' => $preview_img_path,
            '#style_name' => $stylename,
            '#alt' => $title,
            '#title' => $title,
          ],
          'group' => $this->t('Media')
        ];
      }
    }

    return $items;
  }

}
