<?php
/**
 * @file
 * Contains \Drupal\styleguide\Controller\StyleguideController.
 */

namespace Drupal\styleguide\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Extension\ThemeHandlerInterface;
use Drupal\Core\Link;
use Drupal\Core\Render\Element;
use Drupal\Core\Theme\ThemeManagerInterface;
use Drupal\Core\Url;
use Drupal\styleguide\StyleguidePluginManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class StyleguideController extends ControllerBase {

  /**
   * The theme handler service.
   *
   * @var ThemeHandlerInterface
   */
  protected $themeHandler;

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
   * The request stack.
   *
   * @var \Symfony\Component\HttpFoundation\RequestStack
   */
  protected $requestStack;

  /**
   * Constructs a new StyleguideController.
   *
   * @param ThemeHandlerInterface $theme_handler
   *   The theme handler.
   * @param \Drupal\styleguide\StyleguidePluginManager $styleguide_manager
   * @param \Drupal\Core\Theme\ThemeManagerInterface $theme_manager
   * @param \Symfony\Component\HttpFoundation\RequestStack $request_stack
   */
  public function __construct(ThemeHandlerInterface $theme_handler, StyleguidePluginManager $styleguide_manager, ThemeManagerInterface $theme_manager, RequestStack $request_stack) {
    $this->themeHandler = $theme_handler;
    $this->styleguideManager = $styleguide_manager;
    $this->themeManager = $theme_manager;
    $this->requestStack = $request_stack;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('theme_handler'),
      $container->get('plugin.manager.styleguide'),
      $container->get('theme.manager'),
      $container->get('request_stack')
    );
  }

  /**
   * @return array
   */
  public function page() {
    // Get active theme.
    $active_theme = $this->themeManager->getActiveTheme()->getName();
    $themes = $this->themeHandler->rebuildThemeData();

    // Get theme data.
    $theme_info = $themes[$active_theme]->info;

    $items = array();
    foreach ($this->styleguideManager->getDefinitions() as $plugin_id => $plugin_definition) {
      $plugin = $this->styleguideManager->createInstance($plugin_id, array('of' => 'configuration values'));
      $items = array_merge($items, $plugin->items());
    }

    $groups = array();
    foreach ($items as $key => $item) {
      if (!isset($item['group'])) {
        $item['group'] = t('Common');
      }
      else {
        $item['group'] = t('@group', array('@group' => $item['group']));
      }
      $item['title'] = t('@title', array('@title' => $item['title']));
      $groups[$item['group']->__toString()][$key] = $item;
    }

    ksort($groups);
    // Create a navigation header.
    $header = array();
    $head = '';
    $content = '';
    // Process the elements, by group.
    foreach ($groups as $group => $elements) {
      foreach ($elements as $key => $item) {
        $display = '';
        // Output a standard theme item.

        if (isset($item['theme'])) {
          $el = ['#theme' => $item['theme']];
          foreach ($item['variables'] as $param => $value) {
            $el['#' . $param] = $value;
          }
          $display = render($el);
        }
        // Output a standard HTML tag.
        elseif (isset($item['tag']) && isset($item['content'])) {
          $tag = [
            '#type' => 'html_tag',
            '#tag' => $item['tag'],
            '#value' => $item['content'],
          ];
          if (!empty($item['attributes'])) {
            $tag['#attributes'] = $item['attributes'];
          }
          $display = render($tag);
        }
        // Support a renderable array for content.
        elseif (isset($item['content']) && is_array($item['content'])) {
          $display = render($item['content']);
        }
        // Just print the provided content.
        elseif (isset($item['content'])) {
          $display = $item['content'];
        }
        // Add the content.
        $render_array = [
          '#theme' => 'styleguide_item',
          '#key' => $key,
          '#item' => $item,
          '#content' => $display,
        ];
        $content .= render($render_array);
        // Prepare the header link.
        $uri = $this->requestStack->getCurrentRequest()->getUri();
        $url = Url::fromUri($uri, ['fragment' => $key]);
        $link = Link::fromTextAndUrl($item['title'], $url);
        $to_render = $link->toRenderable();
        $header[$group][] = render($to_render);
      }
      $item_list = [
        '#theme' => 'item_list',
        '#items' => $header[$group],
        '#title' => $group,
      ];
      $head .= render($item_list);
    }

    return [
      '#title' => 'Style guide',
      'header' => [
        '#theme' => 'styleguide_header',
        '#theme_info' => $theme_info,
      ],
      'navigation' => [
        '#theme' => 'styleguide_links',
        '#items' => $head,
      ],
      'content' => [
        '#theme' => 'styleguide_content',
        '#content' => $content,
      ],
      '#attached' => [
        'library' => [
          'styleguide/styleguide_css',
        ],
      ]
    ];
  }

}
