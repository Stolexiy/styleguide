<?php

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
   * Build a link.
   *
   * @param string $text
   *   Text displayed in the link.
   * @param string $uri
   *   Url used in the link.
   *
   * @return string
   *   The renderable array.
   */
  public function buildLink($text, $uri) {
    $url = Url::fromUserInput($uri);
    $link = Link::fromTextAndUrl($text, $url);
    return $link->toRenderable();
  }

  /**
   * Build a link from a given route name and parameters.
   *
   * @param string $text
   *   Text displayed in the link.
   * @param string $route_name
   *   The name of the route.
   * @param array $route_parameters
   *   (optional) An associative array of parameter names and values.
   * @param array $options
   *   (optional) An associative array of additional options.
   *
   * @return array
   *   The renderable array.
   */
  public function buildLinkFromRoute($text, $route_name, $route_parameters = array(), $options = array()) {
    $link = Link::createFromRoute($text, $route_name, $route_parameters, $options);
    return $link->toRenderable();
  }

}
