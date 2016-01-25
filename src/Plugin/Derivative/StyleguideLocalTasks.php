<?php
/**
 * @file
 * Contains \Drupal\styleguide\Plugin\Derivative\StyleguideLocalTasks.
 */

namespace Drupal\styleguide\Plugin\Derivative;

use Drupal\Component\Plugin\Derivative\DeriverBase;

class StyleguideLocalTasks extends DeriverBase {

  /**
   * @param array $base_plugin_definition
   * @return array
   */
  public function getDerivativeDefinitions($base_plugin_definition) {
    $default_theme = \Drupal::config('system.theme')->get('default');
    $themes = \Drupal::service('theme_handler')->rebuildThemeData();

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
