<?php
/**
 * @file
 * Contains \Drupal\styleguide\Plugin\Derivative\StyleguideLocalTasks.
 */

namespace Drupal\styleguide\Plugin\Derivative;

use Drupal\Component\Plugin\Derivative\DeriverBase;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Extension\ThemeHandlerInterface;
use Drupal\Core\Plugin\Discovery\ContainerDeriverInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

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
   * @param \Drupal\Core\Extension\ThemeHandlerInterface $theme_handler
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
   * @param array $base_plugin_definition
   * @return array
   */
  public function getDerivativeDefinitions($base_plugin_definition) {
    $default_theme = $this->configFactory->get('system.theme')->get('default');
    $themes = $this->themeHandler->rebuildThemeData();

    foreach ($themes as &$theme) {
      if (!empty($theme->info['hidden'])) {
        continue;
      }
      if ($theme->status) {
        $theme_name = $theme->getName();
        if ($default_theme == $theme_name) {
          // Create default local task.
          $this->derivatives['styleguide.page'] = $base_plugin_definition;
          $this->derivatives['styleguide.page']['title'] = $theme->info['name'];
          $this->derivatives['styleguide.page']['route_name'] = 'styleguide.page';
          $this->derivatives['styleguide.page']['base_route'] = 'styleguide.page';
        } else {
          $this->derivatives['styleguide.' . $theme_name] = $base_plugin_definition;
          $this->derivatives['styleguide.' . $theme_name]['title'] = $theme->info['name'];
          $this->derivatives['styleguide.' . $theme_name]['route_name'] = 'styleguide.' . $theme_name;
          $this->derivatives['styleguide.' . $theme_name]['base_route'] = 'styleguide.page';
        }
      }
    }

    return $this->derivatives;
  }
}
