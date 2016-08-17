<?php

namespace Drupal\styleguide;

use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Extension\ThemeHandlerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Routing\Route;

/**
 * The Styleguide routers.
 */
class StyleguideRoutes implements ContainerInjectionInterface {

  /**
   * The theme handler service.
   *
   * @var \Drupal\Core\Extension\ThemeHandlerInterface
   */
  protected $themeHandler;

  /**
   * StyleguideRoutes constructor.
   *
   * @param \Drupal\Core\Extension\ThemeHandlerInterface $theme_handler
   */
  public function __construct(ThemeHandlerInterface $theme_handler) {
    $this->themeHandler = $theme_handler;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    new static($container->get('theme_handler'));
  }

  /**
   * {@inheritdoc}
   */
  public function routes() {
    $routes = array();

    $themes = $this->themeHandler->rebuildThemeData();
    foreach ($themes as &$theme) {
      if (!empty($theme->info['hidden'])) {
        continue;
      }
      if ($theme->status) {
        $name = $theme->getName();
        $routes['styleguide.' . $name] = new Route(
          '/admin/appearance/styleguide/' . $name,
          array(
            '_controller' => 'Drupal\styleguide\Controller\StyleguideController::page',
            '_title' => $theme->info['name'],
          ),
          array(
            '_permission'  => 'view style guides',
          ),
          array(
            '_admin_route' => FALSE,
          )
        );
        $routes['styleguide.maintenance_page.' . $name] = new Route(
          '/admin/appearance/styleguide/maintenance-page/' . $name,
          array(
            '_controller' => 'Drupal\styleguide\Controller\StyleguideMaintenancePageController::page',
            '_title' => $theme->info['name'],
          ),
          array(
            '_permission'  => 'view style guides',
          ),
          array(
            '_admin_route' => FALSE,
          )
        );
      }
    }

    return $routes;
  }

}
