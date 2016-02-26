<?php

/**
 * @file
 * Contains \Drupal\styleguide\Plugin\StyleguidePluginBase.
 */

namespace Drupal\styleguide\Plugin;

use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Plugin\PluginBase;
use Drupal\styleguide\StyleguideInterface;
use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * Base class for Styleguide plugins.
 */
abstract class StyleguidePluginBase extends PluginBase implements StyleguideInterface, ContainerFactoryPluginInterface {

  /**
   * Render a link.
   *
   * @param string $text
   *   Text displayed in the link.
   * @param string $uri
   *   Url used in the link.
   * @return string
   *  The rendered link.
   */
  public function createLink($text, $uri) {
    $url = Url::fromUserInput($uri);
    $link = Link::fromTextAndUrl($text, $url);
    $to_render = $link->toRenderable();

    return render($to_render);
  }

  /**
   * Render an element.
   *
   * @param string $name
   *  Name of element to render.
   * @param array $variables
   *  Element variables.
   * @return string
   *  The rendered element.
   */
  public function themeElement($name, $variables = array()) {
    $el = ['#theme' => $name];
    foreach ($variables as $key => $value) {
      $el['#' . $key] = $value;
    }

    return render($el);
  }

}
