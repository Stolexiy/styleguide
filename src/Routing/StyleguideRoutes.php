<?php
/**
 * @file
 * Contains \Drupal\styleguide\Routing\StyleguideRoutes.
 */

namespace Drupal\styleguide\Routing;

use Symfony\Component\Routing\Route;

class StyleguideRoutes {

  /**
   * {@inheritdoc}
   */
  public function routes() {
    $routes = array();

    $themes = \Drupal::service('theme_handler')->rebuildThemeData();
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
            '_permission'  => 'access content',
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