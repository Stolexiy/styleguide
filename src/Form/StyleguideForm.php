<?php

namespace Drupal\styleguide\Form;

use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\styleguide\GeneratorInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Defines a form builder to display form controls in style guide.
 */
class StyleguideForm extends FormBase {

  /**
   * The styleguide generator.
   *
   * @var \Drupal\styleguide\GeneratorInterface
   */
  protected $generator;

  /**
   * The module handler.
   *
   * @var ModuleHandlerInterface
   */
  protected $moduleHandler;

  /**
   * Constructs a new StyleguideForm object.
   *
   * @param \Drupal\styleguide\GeneratorInterface $styleguide_generator
   *   The styleguide generator.
   * @param ModuleHandlerInterface $module_handler
   *   The module handler.
   */
  public function __construct(GeneratorInterface $styleguide_generator, ModuleHandlerInterface $module_handler) {
    $this->generator = $styleguide_generator;
    $this->moduleHandler = $module_handler;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('styleguide.generator'),
      $container->get('module_handler')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'styleguide_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $form_keys = []) {
    // @todo Use element names and defaults from element_info service.
    $form = [];
    $options = [];
    $list = $this->generator->wordList();
    foreach ($list as $item) {
      $options[$item] = $item;
    }
    $form['select'] = [
      '#type' => 'select',
      '#title' => $this->t('Select'),
      '#options' => $options,
      '#description' => $this->generator->sentence(),
    ];
    $form['checkbox'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Checkbox'),
      '#value' => 1,
      '#default_value' => 1,
      '#description' => $this->generator->sentence(),
    ];
    $form['checkboxes'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t('Checkboxes'),
      '#options' => $options,
      '#description' => $this->generator->sentence(),
    ];
    $form['radios'] = [
      '#type' => 'radios',
      '#title' => $this->t('Radios'),
      '#options' => $options,
      '#description' => $this->generator->sentence(),
    ];
    $form['textfield'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Textfield'),
      '#default_value' => $this->generator->words(3, 'ucfirst'),
      '#description' => $this->generator->sentence(),
    ];
    $form['autocomplete'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Autocomplete textfield'),
      '#default_value' => $this->generator->words(),
      '#description' => $this->generator->sentence(),
      '#autocomplete_path' => 'user/autocomplete',
    ];
    $form['textfield-machine'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Textfield, with machine name'),
      '#default_value' => $this->generator->words(3, 'ucfirst'),
      '#description' => $this->generator->sentence(),
    ];
    $form['machine_name'] = [
      '#type' => 'machine_name',
      '#title' => $this->t('Machine name'),
      '#machine_name' => [
        'exists' => 'styleguide_machine_name_exists',
        'source' => ['textfield-machine'],
      ],
      '#description' => $this->generator->sentence(),
    ];
    $form['textarea'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Textarea'),
      '#default_value' => $this->generator->paragraphs(5, TRUE),
      '#description' => $this->generator->sentence(),
    ];
    $form['date'] = [
      '#type' => 'date',
      '#title' => $this->t('Date'),
      '#description' => $this->generator->sentence(),
    ];
    $form['file'] = [
      '#type' => 'file',
      '#title' => $this->t('File'),
      '#description' => $this->generator->sentence(),
    ];
    $form['managed_file'] = [
      '#type' => 'managed_file',
      '#title' => $this->t('Managed file'),
      '#description' => $this->generator->sentence(),
    ];
    $form['password'] = [
      '#type' => 'password',
      '#title' => $this->t('Password'),
      '#default_value' => $this->generator->words(),
      '#description' => $this->generator->sentence(),
    ];
    $form['password_confirm'] = [
      '#type' => 'password_confirm',
      '#title' => $this->t('Password confirm'),
    ];
    $form['weight'] = [
      '#type' => 'weight',
      '#title' => $this->t('Weight'),
      '#delta' => 10,
      '#description' => $this->generator->sentence(),
    ];
    $form['details-close'] = [
      '#type' => 'details',
      '#title' => $this->t('Details closed'),
      '#open' => FALSE,
      '#description' => $this->generator->sentence(),
    ];
    $form['details-open'] = [
      '#type' => 'details',
      '#title' => $this->t('Details open'),
      '#open' => TRUE,
      '#description' => $this->generator->sentence(),
    ];
    $form['fieldset'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Fieldset'),
      '#description' => $this->generator->sentence(),
    ];
    $elements = ['fieldset', 'details-close', 'details-open'];
    $count = 0;
    foreach ($form as $key => $value) {
      if ($value['#type'] != 'fieldset' && $value['#type'] != 'checkbox' && $count < 2) {
        $count++;
        foreach ($elements as $item) {
          $form[$item][$key . '-' . $item] = $value;
        }
      }
    }
    $form['vertical_tabs']['elements'] = [
      '#type' => 'vertical_tabs',
      '#default_tab' => 'fieldset',
    ];
    foreach ($elements as $element) {
      $form['vertical_tabs'][$element] = $form[$element];
      $form['vertical_tabs'][$element]['#type'] = 'details';
      $form['vertical_tabs'][$element]['#group'] = 'elements';
    }
    $form['markup'] = [
      '#markup' => $this->t('<p><em>Markup</em>: Note that markup does not allow titles or descriptions. Use "item" for those options.</p>') . $this->generator->paragraphs(1, TRUE),
    ];
    $form['item'] = [
      '#type' => 'item',
      '#title' => $this->t('Item'),
      '#markup' => $this->generator->paragraphs(1, TRUE),
      '#description' => $this->generator->sentence(),
    ];
    $form['image_button'] = [
      '#type' => 'image_button',
      '#src' => 'core/misc/druplicon.png',
      '#attributes' => ['height' => 40],
      '#name' => $this->t('Image button'),
    ];
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    ];
    $form['button'] = [
      '#type' => 'button',
      '#value' => $this->t('Button'),
    ];

    if ($this->moduleHandler->moduleExists('filter')) {
      $form['text_format'] = [
        '#title' => $this->generator->sentence(),
        '#type' => 'text_format',
        '#default_value' => $this->generator->paragraphs(5, TRUE),
        '#format' => filter_default_format(),
      ];
    }

    if (!empty($form_keys)) {
      $items = [];
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
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // TODO: Implement submitForm() method.
  }

}
