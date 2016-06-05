<?php

namespace Drupal\styleguide;

/**
 * Interface GeneratorInterface.
 *
 * @package Drupal\styleguide
 */
interface GeneratorInterface {

  /**
   * Return a simple array of words.
   *
   * @param int $size
   *   The size of the list to return.
   * @param int $words
   *   The number of words to generate.
   *
   * @return array
   *   An array of words.
   */
  public function wordList($size = 5, $words = 3);

  /**
   * Return a random word or words.
   *
   * @param int $size
   *   The number of words to return.
   * @param string $case
   *   A string indicating the case to return.
   *   This is the name of a PHP function.
   *   options are 'ucfirst', 'ucwords', 'strtoupper', and 'strtolower'.
   *   Defaults to return strtolower().
   */
  public function words($size = 1, $case = 'strtolower');

  /**
   * Return a random table header array.
   *
   * @param int $size
   *   The size of the list to return.
   *
   * @return array
   *   An array of header elements.
   */
  public function tableHeader($size = 5);

  /**
   * Return a random table row array.
   *
   * @param int $size
   *   The size of the list to return.
   *
   * @return array
   *   An array of row elements.
   */
  public function tableRows($size = 5);

  /**
   * Lorum ipsum text, used to generate words and phrases.
   *
   * @param int $size
   *   The size of the list to return.
   * @param int $words
   *   The number of words to return. Pass 0 for a whole paragraph.
   * @param string $case
   *   The case of the text. Options are 'mixed', 'upper' and 'lower'.
   * @param bool $returns
   *   Indicates whether line returns should not be stripped out of the result.
   * @param bool $punctuation
   *   Indicates whether punctuation should not be stripped out of the result.
   * @param bool $array
   *   Indicates that the return value should be an array instead of a string.
   *
   * @return string|array
   *   A string or array of content.
   */
  public function lorem($size = 5, $words = 0, $case = 'mixed', $returns = TRUE, $punctuation = TRUE, $array = FALSE);

  /**
   * Generate paragraph(s) of random text.
   *
   * @param int $size
   *   The number of paragraphs to return.
   * @param bool $render
   *   Allow to choose render the paragraph or return renderable array.
   *
   * @return array|string HTML paragraphs.
   *   Renderable array or string of HTML paragraphs.
   */
  public function paragraphs($size = 5, $render = FALSE);

  /**
   * Generate a default image for display.
   *
   * @param string $image
   *   The name of the image. Will be prefixed with 'styleguide-image-'.
   * @param string $type
   *   The file type, (jpg, png, gif). Do not include a dot.
   *
   * @return string
   *    The Drupal path to the file.
   */
  public function image($image = 'vertical', $type = 'jpg');

  /**
   * Generate a random sentence.
   *
   * @param string $link
   *   The link to add to the sentence.
   */
  public function sentence($link = FALSE);

  /**
   * Simulate Drupal pagination,.
   *
   * @param int $size
   *   The number of page numbers to display.
   * @param int $total
   *   The total number of pages to simulate.
   *
   * @return array
   *   A Drupal pager HTML element.
   */
  public function pager($size = 8, $total = 20);

  /**
   * Generate a array of random links.
   *
   * @param string $url
   *   The internal path or external URL being linked to.
   * @param int $size
   *   The total number of links to generate .
   *
   * @return array
   *   A array of random links
   */
  public function links($url, $size = 4);

  /**
   * Generate a random menu item.
   *
   * @param string $url
   *   The internal path or external URL being linked to.
   *
   * @return array
   *   A random menu item, see menu_tree_page_data for a description of
   *   the data structure.
   */
  public function menuItem($url);

  /**
   * Generate a links array for theme_links.
   */
  public function ulLinks();

}
