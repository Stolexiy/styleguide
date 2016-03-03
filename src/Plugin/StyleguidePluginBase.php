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
   * Build a link.
   *
   * @param string $text
   *   Text displayed in the link.
   * @param string $uri
   *   Url used in the link.
   * @return string
   *  The rendered link.
   */
  public function buildLink($text, $uri) {
    $url = Url::fromUserInput($uri);
    $link = Link::fromTextAndUrl($text, $url);
    return $link->toRenderable();
  }

}
