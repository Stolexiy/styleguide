<?php

/**
 * @file
 * Style Guide plugin file for the Image module.
 */

/**
 * Implements hook_styleguide().
 */
function image_styleguide() {
  $items = array();

  // Get the sample file provided by the module.
  if (file_destination('public://styleguide-preview.jpg', FILE_EXISTS_ERROR) !== FALSE) {
    // Move the image so that styles may be applied.
    file_unmanaged_copy(styleguide_image('vertical'), 'public://styleguide-preview.jpg', FILE_EXISTS_ERROR);
  }

  // Iterate through the image styles on the site.
  module_load_include('admin.inc', 'image');
  foreach (image_styles() as $stylename => $style) {
    $details = array();
    foreach ($style['effects'] as $effect) {
      if (isset($effect['summary theme'])) {
        $details[] = format_string('%label: !summary', array(
          '%label' => $effect['label'],
          '!summary' => theme($effect['summary theme'], array('data' => $effect['data']))
        ));
      }
      else {
        $details[] = format_string('%label', array(
          '%label' => $effect['label'],
        ));
      }
    }

    $items['image_' . $stylename] = array(
      'title' => t('Image style, @stylename', array('@stylename' => $stylename)),
      'description' => theme('item_list', array('items' => $details)),
      'content' => theme('image_style', array('path' => 'public://styleguide-preview.jpg', 'style_name' => $stylename)),
      'group' => t('Media')
    );
  }

  return $items;
}
