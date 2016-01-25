<?php
/**
 * @file
 * Contains \Drupal\styleguide\Theme\StyleguideThemeNegotiator.
 */

namespace Drupal\styleguide\Theme;

use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Theme\ThemeNegotiatorInterface;

class StyleguideThemeNegotiator implements ThemeNegotiatorInterface {

  public $themeName;

  /**
   * Whether this theme negotiator should be used to set the theme.
   *
   * @param \Drupal\Core\Routing\RouteMatchInterface $route_match
   *   The current route match object.
   *
   * @return bool
   *   TRUE if this negotiator should be used or FALSE to let other negotiators
   *   decide.
   */
  public function applies(RouteMatchInterface $route_match) {

    $themes = \Drupal::service('theme_handler')->rebuildThemeData();
    foreach ($themes as &$theme) {
      if (!empty($theme->info['hidden'])) {
        continue;
      }
      if ($theme->status) {
        if (\Drupal::routeMatch()->getRouteName() == 'styleguide.' . $theme->getName()) {
          $this->themeName = $theme->getName();
          return TRUE;
        }
      }
    }

    return FALSE;
  }

  /**
   * Determine the active theme for the request.
   *
   * @param \Drupal\Core\Routing\RouteMatchInterface $route_match
   *   The current route match object.
   *
   * @return string|null
   *   Returns the active theme name, else return NULL.
   */
  public function determineActiveTheme(RouteMatchInterface $route_match) {
    return $this->themeName;
  }
}
