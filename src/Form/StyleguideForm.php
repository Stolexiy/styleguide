<?php
/**
 * @file
 * Contains \Drupal\styleguide\Form\StyleguideForm.
 */

namespace Drupal\styleguide\Form;


use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class StyleguideForm extends FormBase {

  /**
   *
   * Returns a unique string identifying the form.
   *
   * @return string
   *   The unique string identifying the form.
   */
  public function getFormId() {
    return 'styleguide_form';
  }

  /**
   * Sample form, showing all elements.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   * @param array $form_keys
   *  An array containing the type of elements to return.
   *
   * @return array
   *   The form structure.
   */
  public function buildForm(array $form, FormStateInterface $form_state, $form_keys = array()) {
    $form = array();
    $options = array();
    $list = styleguide_list();
    foreach ($list as $item) {
      $options[$item] = $item;
    }
    $form['select'] = array(
      '#type' => 'select',
      '#title' => t('Select'),
      '#options' => $options,
      '#description' => styleguide_sentence(),
    );
    $form['checkbox'] = array(
      '#type' => 'checkbox',
      '#title' => t('Checkbox'),
      '#value' => 1,
      '#default_value' => 1,
      '#description' => styleguide_sentence(),
    );
    $form['checkboxes'] = array(
      '#type' => 'checkboxes',
      '#title' => t('Checkboxes'),
      '#options' => $options,
      '#description' => styleguide_sentence(),
    );
    $form['radios'] = array(
      '#type' => 'radios',
      '#title' => t('Radios'),
      '#options' => $options,
      '#description' => styleguide_sentence(),
    );
    $form['textfield'] = array(
      '#type' => 'textfield',
      '#title' => t('Textfield'),
      '#default_value' => styleguide_word(3, 'ucfirst'),
      '#description' => styleguide_sentence(),
    );
    $form['autocomplete'] = array(
      '#type' => 'textfield',
      '#title' => t('Autocomplete textfield'),
      '#default_value' => styleguide_word(),
      '#description' => styleguide_sentence(),
      '#autocomplete_path' => 'user/autocomplete',
    );
    $form['textfield-machine'] = array(
      '#type' => 'textfield',
      '#title' => t('Textfield, with machine name'),
      '#default_value' => styleguide_word(3, 'ucfirst'),
      '#description' => styleguide_sentence(),
    );
    $form['machine_name'] = array(
      '#type' => 'machine_name',
      '#title' => t('Machine name'),
      '#machine_name' => array(
        'exists' => 'styleguide_machine_name_exists',
        'source' => array('textfield-machine'),
      ),
      '#description' => styleguide_sentence(),
    );
    $form['textarea'] = array(
      '#type' => 'textarea',
      '#title' => t('Textarea'),
      '#default_value' => styleguide_paragraph(),
      '#description' => styleguide_sentence(),
    );
    $form['date'] = array(
      '#type' => 'date',
      '#title' => t('Date'),
      '#description' => styleguide_sentence(),
    );
    $form['file'] = array(
      '#type' => 'file',
      '#title' => t('File'),
      '#description' => styleguide_sentence(),
    );
    $form['managed_file'] = array(
      '#type' => 'managed_file',
      '#title' => t('Managed file'),
      '#description' => styleguide_sentence(),
    );
    $form['password'] = array(
      '#type' => 'password',
      '#title' => t('Password'),
      '#default_value' => styleguide_word(),
      '#description' => styleguide_sentence(),
    );
    $form['password_confirm'] = array(
      '#type' => 'password_confirm',
      '#title' => t('Password confirm'),
    );
    $form['weight'] = array(
      '#type' => 'weight',
      '#title' => t('Weight'),
      '#delta' => 10,
      '#description' => styleguide_sentence(),
    );
    $form['fieldset-collapsed'] = array(
      '#type' => 'fieldset',
      '#title' => t('Fieldset collapsed'),
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,
      '#description' => styleguide_sentence(),
    );
    $form['fieldset-collapsible'] = array(
      '#type' => 'fieldset',
      '#title' => t('Fieldset collapsible'),
      '#collapsible' => TRUE,
      '#description' => styleguide_sentence(),
    );
    $form['fieldset'] = array(
      '#type' => 'fieldset',
      '#title' => t('Fieldset'),
      '#collapsible' => FALSE,
      '#description' => styleguide_sentence(),
    );
    $fieldsets = array('fieldset', 'fieldset-collapsed', 'fieldset-collapsible');
    $count = 0;
    foreach ($form as $key => $value) {
      if ($value['#type'] != 'fieldset' && $value['#type'] != 'checkbox' && $count < 2) {
        $count++;
        foreach ($fieldsets as $item) {
          $form[$item][$key . '-' .  $item] = $value;
        }
      }
    }
    $form['vertical_tabs'] = array(
      '#type' => 'vertical_tabs',
    );
    foreach ($fieldsets as $fieldset) {
      $form['vertical_tabs'][$fieldset] = $form[$fieldset];
    }
    $form['markup'] = array(
      '#markup' => t('<p><em>Markup</em>: Note that markup does not allow titles or descriptions. Use "item" for those options.</p>') . styleguide_paragraph(1),
    );
    $form['item'] = array(
      '#type' => 'item',
      '#title' => t('Item'),
      '#markup' => styleguide_paragraph(1),
      '#description' => styleguide_sentence(),
    );
    $form['image_button'] = array(
      '#type' => 'image_button',
      '#src' => 'core/misc/druplicon.png',
      '#attributes' => array('height' => 40),
      '#name' => t('Image button'),
    );
    $form['submit'] = array(
      '#type' => 'submit',
      '#value' => t('Submit'),
    );
    $form['button'] = array(
      '#type' => 'button',
      '#value' => t('Button'),
    );
    if (!empty($form_keys)) {
      $items = array();
      foreach ($form_keys as $key) {
        if (isset($form[$key])) {
          $items[$key] = $form[$key];
        }
      }
      return $items;
    }

    return $form;
  }

  /**
   * Form submission handler.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // TODO: Implement submitForm() method.
  }
}