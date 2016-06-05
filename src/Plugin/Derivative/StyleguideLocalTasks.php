<?php

namespace Drupal\styleguide\Plugin\Derivative;

use Drupal\Component\Plugin\Derivative\DeriverBase;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Extension\ThemeHandlerInterface;
use Drupal\Core\Plugin\Discovery\ContainerDeriverInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Styleguide local tasks.
 */
class StyleguideLocalTasks extends DeriverBase implements ContainerDeriverInterface {

  /**
   * The configuration object factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * The theme handler.
   *
   * @var \Drupal\Core\Extension\ThemeHandlerInterface
   */
  protected $themeHandler;

  /**
   * StyleguideLocalTasks constructor.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The Drupal config factory.
   * @param \Drupal\Core\Extension\ThemeHandlerInterface $theme_handler
   *   The Drupal theme habdler.
   */
  public function __construct(ConfigFactoryInterface $config_factory, ThemeHandlerInterface $theme_handler) {
    $this->configFactory = $config_factory;
    $this->themeHandler = $theme_handler;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, $base_plugin_id) {
    return new static(
      $container->get('config.factory'),
      $container->get('theme_handler')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getDerivativeDefinitions($base_plugin_definition) {
    $default_theme = $this->configFactory->get('system.theme')->get('default');
    $themes = $this->themeHandler->rebuildThemeData();
    $weight = 0;

    foreach ($themes as &$theme) {
      if (!empty($theme->info['hidden'])) {
        continue;
      }
      if ($theme->status) {
        $route_name = 'styleguide.' . $theme->getName();
        $this->derivatives[$route_name] = $base_plugin_definition + array(
          'title' => $theme->info['name'],
          'route_name' => $route_name,
          'parent_id' => 'styleguide.page',
          'weight' => $weight++,
        );
        if ($default_theme == $theme->getName()) {
          $this->derivatives[$route_name]['route_name'] = 'styleguide.page';
        }
      }
    }

    return $this->derivatives;
  }

}
