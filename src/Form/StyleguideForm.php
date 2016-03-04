<?php
/**
 * @file
 * Contains \Drupal\styleguide\Form\StyleguideForm.
 */

namespace Drupal\styleguide\Form;


use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\styleguide\GeneratorInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class StyleguideForm extends FormBase implements ContainerInjectionInterface {

  /**
   * The styleguide generator service.
   *
   * @var \Drupal\styleguide\Generator
   */
  protected $generator;

  /**
   * Constructs a new StyleguideForm.
   *
   * @param \Drupal\styleguide\Generator $styleguide_generator
   *   The styleguide generator service.
   */
  public function __construct(GeneratorInterface $styleguide_generator) {
    $this->generator = $styleguide_generator;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static($container->get('styleguide.generator'));
  }

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
    $list = $this->generator->wordList();
    foreach ($list as $item) {
      $options[$item] = $item;
    }
    $form['select'] = array(
      '#type' => 'select',
      '#title' => $this->t('Select'),
      '#options' => $options,
      '#description' => $this->generator->sentence(),
    );
    $form['checkbox'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('Checkbox'),
      '#value' => 1,
      '#default_value' => 1,
      '#description' => $this->generator->sentence(),
    );
    $form['checkboxes'] = array(
      '#type' => 'checkboxes',
      '#title' => $this->t('Checkboxes'),
      '#options' => $options,
      '#description' => $this->generator->sentence(),
    );
    $form['radios'] = array(
      '#type' => 'radios',
      '#title' => $this->t('Radios'),
      '#options' => $options,
      '#description' => $this->generator->sentence(),
    );
    $form['textfield'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Textfield'),
      '#default_value' => $this->generator->words(3, 'ucfirst'),
      '#description' => $this->generator->sentence(),
    );
    $form['autocomplete'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Autocomplete textfield'),
      '#default_value' => $this->generator->words(),
      '#description' => $this->generator->sentence(),
      '#autocomplete_path' => 'user/autocomplete',
    );
    $form['textfield-machine'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Textfield, with machine name'),
      '#default_value' => $this->generator->words(3, 'ucfirst'),
      '#description' => $this->generator->sentence(),
    );
    $form['machine_name'] = array(
      '#type' => 'machine_name',
      '#title' => $this->t('Machine name'),
      '#machine_name' => array(
        'exists' => 'styleguide_machine_name_exists',
        'source' => array('textfield-machine'),
      ),
      '#description' => $this->generator->sentence(),
    );
    $form['textarea'] = array(
      '#type' => 'textarea',
      '#title' => $this->t('Textarea'),
      '#default_value' => $this->generator->paragraphs(5, TRUE),
      '#description' => $this->generator->sentence(),
    );
    $form['date'] = array(
      '#type' => 'date',
      '#title' => $this->t('Date'),
      '#description' => $this->generator->sentence(),
    );
    $form['file'] = array(
      '#type' => 'file',
      '#title' => $this->t('File'),
      '#description' => $this->generator->sentence(),
    );
    $form['managed_file'] = array(
      '#type' => 'managed_file',
      '#title' => $this->t('Managed file'),
      '#description' => $this->generator->sentence(),
    );
    $form['password'] = array(
      '#type' => 'password',
      '#title' => $this->t('Password'),
      '#default_value' => $this->generator->words(),
      '#description' => $this->generator->sentence(),
    );
    $form['password_confirm'] = array(
      '#type' => 'password_confirm',
      '#title' => $this->t('Password confirm'),
    );
    $form['weight'] = array(
      '#type' => 'weight',
      '#title' => $this->t('Weight'),
      '#delta' => 10,
      '#description' => $this->generator->sentence(),
    );
    $form['fieldset-collapsed'] = array(
      '#type' => 'fieldset',
      '#title' => $this->t('Fieldset collapsed'),
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,
      '#description' => $this->generator->sentence(),
    );
    $form['fieldset-collapsible'] = array(
      '#type' => 'fieldset',
      '#title' => $this->t('Fieldset collapsible'),
      '#collapsible' => TRUE,
      '#description' => $this->generator->sentence(),
    );
    $form['fieldset'] = array(
      '#type' => 'fieldset',
      '#title' => $this->t('Fieldset'),
      '#collapsible' => FALSE,
      '#description' => $this->generator->sentence(),
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
      '#markup' => $this->t('<p><em>Markup</em>: Note that markup does not allow titles or descriptions. Use "item" for those options.</p>') . $this->generator->paragraphs(1, TRUE),
    );
    $form['item'] = array(
      '#type' => 'item',
      '#title' => $this->t('Item'),
      '#markup' => $this->generator->paragraphs(1, TRUE),
      '#description' => $this->generator->sentence(),
    );
    $form['image_button'] = array(
      '#type' => 'image_button',
      '#src' => 'core/misc/druplicon.png',
      '#attributes' => array('height' => 40),
      '#name' => $this->t('Image button'),
    );
    $form['submit'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    );
    $form['button'] = array(
      '#type' => 'button',
      '#value' => $this->t('Button'),
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