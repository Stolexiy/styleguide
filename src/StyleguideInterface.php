<?php

namespace Drupal\styleguide;
/**
 * Styleguide plugin interface.
 */
interface StyleguideInterface {

  /**
   * Styleguide elements implementation.
   *
   * @return array
   *   An array of Styleguide elements.
   */
  public function items();

}
