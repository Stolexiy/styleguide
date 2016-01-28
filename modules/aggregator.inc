<?php

/**
 * Implements hook_styleguide().
 */
function aggregator_styleguide() {
  $items = array();
  $generator = \Drupal::service('styleguide.generator');

  $items['aggregator_item'] = array(
    'title' => t('Aggregator item'),
    'theme' => 'aggregator_item',
    'variables' => array(
      'item' => (object)array(
        'iid' => 1,
        'fid' => 1,
        'title' => $generator->words(10, 'ucfirst'),
        'link' => 'http://drupal.org/project/styleguide',
        'author' => $generator->words(2, 'ucfirst'),
        'description' => $generator->paragraphs(2),
        'timestamp' => time() - 3600,
        'guid' => '',
        'ftitle' => $generator->words(3, 'ucfirst'),
        'flink' => 'http://drupal.org/planet/rss.xml',
        'categories' => array((object)array('title' => $generator->words(3), 'cid' => 1)),
      ),
    ),
    'group' => t('Aggregator'),
  );

  $items['aggregator_summary_item'] = array(
    'title' => t('Aggregator summary item'),
    'theme' => 'aggregator_summary_item',
    'variables' => array(
      'item' => (object) array(
        'title' => $generator->words(10, 'ucfirst'),
        'link' => 'http://drupal.org/project/styleguide',
        'timestamp' => time() - 3600,
      ),
    ),
    'group' => t('Aggregator'),
  );

  return $items;
}
