<?php

/**
 * @file
 * Contains \Drupal\styleguide\Plugin\Styleguide\defaultStyleguide.
 */

namespace Drupal\styleguide\Plugin\Styleguide;

use Drupal\Core\Form\FormState;
use Drupal\Core\Link;
use Drupal\Core\Menu\MenuTreeParameters;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Plugin\PluginBase;
use Drupal\Core\Render\Element;
use Drupal\styleguide\GeneratorInterface;
use Drupal\styleguide\StyleguideInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Url;

/**
 * @Plugin(
 *   id = "default_styleguide",
 * )
 */
class defaultStyleguide extends PluginBase implements StyleguideInterface, ContainerFactoryPluginInterface {

  /**
   * The styleguide generator service.
   *
   * @var \Drupal\styleguide\Generator
   */
  protected $generator;

  /**
   * @param array $configuration
   * @param string $plugin_id
   * @param mixed $plugin_definition
   * @param \Drupal\styleguide\GeneratorInterface $styleguide_generator
   * @internal param \Drupal\styleguide\GeneratorInterface $generator
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, GeneratorInterface $styleguide_generator) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);

    $this->generator = $styleguide_generator;
  }

  /**
   * @return array
   */
  public function items() {
    $current_url = \Drupal::request()->getRequestUri();
    $items['a'] = array(
      'title' => t('Link'),
      'content' => $this->generator->words(3, 'ucfirst') . ' ' . $this->createLink($this->generator->words(3), '/node') . ' ' . $this->generator->words(4) . '.',
    );
    $items['b'] = array(
      'title' => t('B'),
      'content' => $this->generator->words(3, 'ucfirst') . ' <b>' . $this->generator->words(3) . '</b> ' . $this->generator->words(4) . '.',
    );
    $items['del'] = array(
      'title' => t('Delete'),
      'content' => $this->generator->words(3, 'ucfirst') . ' <del>' . $this->generator->words(3) . '</del> ' . $this->generator->words(4) . '.',
    );
    $items['em'] = array(
      'title' => t('Emphasis'),
      'content' => $this->generator->words(3, 'ucfirst') . ' <em>' . $this->generator->words(3) . '</em> ' . $this->generator->words(4) . '.',
    );
    $items['figcaption'] = array(
      'title' => t('Figcaption'),
      'content' => $this->generator->words(3, 'ucfirst') . ' <figcaption>' . $this->generator->words(3) . '</figcaption> ' . $this->generator->words(4) . '.',
    );
    $items['figure'] = array(
      'title' => t('Figure'),
      'content' => $this->generator->words(3, 'ucfirst') . ' <figure>' . $this->generator->words(3) . '</figure> ' . $this->generator->words(4) . '.',
    );
    $items['hr'] = array(
      'title' => t('hr'),
      'content' => $this->generator->words(3, 'ucfirst') . ' <hr>' . $this->generator->words(3) . '</hr> ' . $this->generator->words(4) . '.',
    );
    $items['i'] = array(
      'title' => t('Italic'),
      'content' => $this->generator->words(3, 'ucfirst') . ' <i>' . $this->generator->words(3) . '</i> ' . $this->generator->words(4) . '.',
    );
    $items['q'] = array(
      'title' => t('Quote'),
      'content' => $this->generator->words(3, 'ucfirst') . ' <q>' . $this->generator->words(3) . '</q> ' . $this->generator->words(4) . '.',
    );
    $items['s'] = array(
      'title' => t('Strikethrough'),
      'content' => $this->generator->words(3, 'ucfirst') . ' <s>' . $this->generator->words(3) . '</s> ' . $this->generator->words(4) . '.',
    );
    $items['small'] = array(
      'title' => t('Small'),
      'content' => $this->generator->words(3, 'ucfirst') . ' <small>' . $this->generator->words(3) . '</small> ' . $this->generator->words(4) . '.',
    );
    $items['strong'] = array(
      'title' => t('Strong'),
      'content' => $this->generator->words(3, 'ucfirst') . ' <strong>' . $this->generator->words(3) . '</strong> ' . $this->generator->words(4) . '.',
    );
    $items['sub'] = array(
      'title' => t('Subscript'),
      'content' => $this->generator->words(3, 'ucfirst') . ' <sub>' . $this->generator->words(1) . '</sub> ' . $this->generator->words(4) . '.',
    );
    $items['sup'] = array(
      'title' => t('Superscript'),
      'content' => $this->generator->words(3, 'ucfirst') . ' <sup>' . $this->generator->words(1) . '</sup> ' . $this->generator->words(4) . '.',
    );
    $items['u'] = array(
      'title' => t('Underline'),
      'content' => $this->generator->words(3, 'ucfirst') . ' <u>' . $this->generator->words(3) . '</u> ' . $this->generator->words(4) . '.',
    );
    $items['ul'] = array(
      'title' => t('Unordered list'),
      'theme' => 'item_list',
      'variables' => array('items' => $this->generator->wordList(), 'list_type' => 'ul'),
      'group' => t('Lists'),
    );
    $items['ol'] = array(
      'title' => t('Ordered list'),
      'theme' => 'item_list',
      'variables' => array('items' => $this->generator->wordList(), 'list_type' => 'ol'),
      'group' => t('Lists'),
    );
    $items['ul_title'] = array(
      'title' => t('Unordered list, with title'),
      'theme' => 'item_list',
      'variables' => array('items' => $this->generator->wordList(), 'list_type' => 'ul', 'title' => $this->generator->words(3, 'ucfirst')),
      'group' => t('Lists'),
    );
    $items['ol_title'] = array(
      'title' => t('Ordered list, with title'),
      'theme' => 'item_list',
      'variables' => array('items' => $this->generator->wordList(), 'list_type' => 'ol', 'title' => $this->generator->words(3, 'ucfirst')),
      'group' => t('Lists'),
    );
    $items['ul_long'] = array(
      'title' => t('Unordered list with wrapped list items'),
      'theme' => 'item_list',
      'variables' => array('items' => $this->generator->wordList(3, 120), 'list_type' => 'ul'),
      'group' => t('Lists'),
    );
    $items['ol_long'] = array(
      'title' => t('Ordered list with wrapped list items'),
      'theme' => 'item_list',
      'variables' => array('items' => $this->generator->wordList(3, 120), 'list_type' => 'ol'),
      'group' => t('Lists'),
    );
    $items['ul_links'] = array(
      'title' => t('Unordered list with links'),
      'theme' => 'links',
      'variables' => array(
        'links' => $this->generator->ulLinks(),
      ),
      'group' => t('Lists'),
    );
    $items['ul_links_inline'] = array(
      'title' => t('Unordered inline list with links'),
      'theme' => 'links',
      'variables' => array(
        'links' => $this->generator->ulLinks(),
        'attributes' => array(
          'class' => array(
            'inline',
          ),
        ),
      ),
      'group' => t('Lists'),
    );

    $menu = \Drupal::menuTree()->load('admin', new MenuTreeParameters());
    $items['menu_tree'] = array(
      'title' => t('Menu tree'),
      'content' => \Drupal::menuTree()->build($menu),
      'group' => t('Menus'),
    );
    $items['menu_link'] = array(
      'title' => t('Menu link'),
      'content' => $this->generator->menuItem($current_url),
      'group' => t('Menus')
    );
    $items['table'] = array(
      'title' => t('Table'),
      'theme' => 'table',
      'variables' => array('caption' => $this->generator->words(3), 'header' => $this->generator->tableHeader(), 'rows' => $this->generator->tableRows()),
      'group' => t('Tables'),
    );
    $items['text'] = array(
      'title' => t('Text block'),
      'content' => $this->generator->paragraphs(3),
      'group' => t('Text'),
    );
    $items['blockquote'] = array(
      'title' => t('Blockquote'),
      'content' => $this->generator->paragraphs(1) . '<blockquote>' . $this->generator->paragraphs(1) . '</blockquote>' . $this->generator->paragraphs(1),
      'group' => t('Text'),
    );
    $items['image-horizontal'] = array(
      'title' => t('Image, horizontal'),
      'theme' => 'image',
      'variables' => array('uri' => $this->generator->image('horizontal'), 'alt' => t('My image'), 'title' => t('My image')),
      'group' => t('Media'),
    );
    $items['image-vertical'] = array(
      'title' => t('Image, vertical'),
      'theme' => 'image',
      'variables' => array('uri' => $this->generator->image('vertical'), 'alt' => t('My image'), 'title' => t('My image')),
      'group' => t('Media'),
    );
    $items['image-inset-horizontal'] = array(
      'title' => t('Image, horizontal, within text'),
      'content' => $this->generator->paragraphs(1) . $this->themeElement('image', array('uri' => $this->generator->image('horizontal'), 'alt' => t('My image'), 'title' => t('My image'))) . $this->generator->paragraphs(2),
      'group' => t('Media'),
    );
    $items['image-inset-vertical'] = array(
      'title' => t('Image, vertical, within text'),
      'content' => $this->generator->paragraphs(1) . $this->themeElement('image', array('uri' => $this->generator->image('vertical'), 'alt' => t('My image'), 'title' => t('My image'))) . $this->generator->paragraphs(2),
      'group' => t('Media'),
    );
    $content = '';
    for ($i = 1; $i <=6; $i++) {
      $content .= "<h$i>" . "h$i: " . implode(' ', $this->generator->wordList()) . "</h$i>";
    }
    $items['headings'] = array(
      'title' => "Headings",
      'content' => $content,
      'group' => t('Text'),
    );
    $content = '';
    for ($i = 1; $i <=6; $i++) {
      $content .= "<h$i>" . "h$i: " . implode(' ', $this->generator->wordList()) . "</h$i>" . $this->generator->paragraphs(1);
    }
    $items['headings_text'] = array(
      'title' => "Headings with text",
      'content' => $content,
      'group' => t('Text'),
    );

    // Store all of the current messages, do not display them here.
    $message_queue = drupal_get_messages();
    $messages = array('status', 'warning', 'error');
    foreach ($messages as $message) {
      // Set a new message with a link.
      drupal_set_message($this->generator->sentence('http://www.example.com'), $message);
      $items[$message . '-message'] = array(
        'title' => ucwords($message) . ' message',
        'content' => array(
          '#theme' => 'status_messages',
          '#message_list' => drupal_get_messages($message),
        ),
      );
    }
    // Loop through the original messages, resetting them.
    foreach ($message_queue as $message_type => $messages) {
      foreach ($messages as $message) {
        drupal_set_message($message, $message_type);
      }
    }

    // Form elements.
    $elements = \Drupal::formBuilder()->buildForm('Drupal\styleguide\Form\StyleguideForm', new FormState());
    $basic = array();
    $fieldsets = array();
    $tabs = array();
    $markup = array();
    foreach (Element::children($elements) as $key) {
      if (!isset($elements[$key]['#type']) || $elements[$key]['#type'] == 'item') {
        $markup[] = $key;
      }
      elseif ($elements[$key]['#type'] == 'fieldset') {
        $fieldsets[] = $key;
      }
      elseif ($key == 'vertical_tabs') {
        $tabs[] = $key;
      }
      // We skip these.
      elseif (in_array($elements[$key]['#type'], array('button', 'submit', 'image_button'))) {
        $buttons[] = $key;
      }
      else {
        $basic[] = $key;
      }
    }
    $items['form'] = array(
      'title' => t('Forms, basic'),
      'content' => \Drupal::formBuilder()->getForm('Drupal\styleguide\Form\StyleguideForm', $basic),
      'group' => t('Forms'),
    );
    $items['form-submit'] = array(
      'title' => t('Forms, submit'),
      'content' => \Drupal::formBuilder()->getForm('Drupal\styleguide\Form\StyleguideForm', array('submit')),
      'group' => t('Forms'),
    );
    $items['form-button'] = array(
      'title' => t('Forms, button'),
      'content' => \Drupal::formBuilder()->getForm('Drupal\styleguide\Form\StyleguideForm', array('button')),
      'group' => t('Forms'),
    );
    $items['form-image-button'] = array(
      'title' => t('Forms, image button'),
      'content' => \Drupal::formBuilder()->getForm('Drupal\styleguide\Form\StyleguideForm', array('image_button')),
      'group' => t('Forms'),
    );
    $items['form-markup'] = array(
      'title' => t('Forms, markup'),
      'content' => \Drupal::formBuilder()->getForm('Drupal\styleguide\Form\StyleguideForm', $markup),
      'group' => t('Forms'),
    );
    $items['form-fieldsets'] = array(
      'title' => t('Forms, fieldsets'),
      'content' => \Drupal::formBuilder()->getForm('Drupal\styleguide\Form\StyleguideForm', $fieldsets),
      'group' => t('Forms'),
    );
    $items['form-vertical-tabs'] = array(
      'title' => t('Forms, vertical tabs'),
      'content' => \Drupal::formBuilder()->getForm('Drupal\styleguide\Form\StyleguideForm', $tabs),
      'group' => t('Forms'),
    );
    $items['feed_icon'] = array(
      'title' => t('Feed icon'),
      'content' => $this->themeElement('feed_icon', array('url' => 'rss.xml', 'title' => t('Syndicate'))),
      'group' => t('System')
    );
    // This item kills drupal_set_message. The messages are displayed here.
    $items['maintenance_page'] = array(
      'title' => t('Maintenance page'),
      'content' => $this->themeElement('maintenance_page', array('title' => $this->generator->sentence(1))),
      'group' => t('System')
    );
    $items['system_powered_by'] = array(
      'title' => t('System powered by'),
      'content' => $this->themeElement('system_powered_by'),
      'group' => t('System')
    );
    $items['confirm_form'] = array(
      'title' => t('Confirm form'),
      'content' => \Drupal::formBuilder()->getForm('Drupal\styleguide\Form\StyleguideConfirmForm'),
      'group' => t('System')
    );
    $items['pager'] = array(
      'title' => t('Pager'),
      'content' => $this->generator->pager(),
      'group' => t('User interface')
    );
    $items['progress_bar'] = array(
      'title' => t('Progress bar'),
      'content' => $this->themeElement('progress_bar', array('percent' => 57, 'message' => $this->generator->sentence(2))),
      'group' => t('User interface')
    );
    // Use alternative item name to avoid conflict with main breadcrumb.
    $breadcrumb_manager = \Drupal::service('breadcrumb');
    $current_route_match = \Drupal::service('current_route_match');
    $breadcrumb = $breadcrumb_manager->build($current_route_match);
    $items['styleguide_breadcrumb'] = array(
      'title' => t('Breadcrumb'),
      'content' => render($breadcrumb),
      'group' => t('User interface')
    );
    $items['link'] = array(
      'title' => t('Link'),
      'content' => $this->createLink($this->generator->words(2), $current_url),
      'group' => t('Link')
    );
    $items['links'] = array(
      'title' => t('Links'),
      'content' => $this->themeElement('links', array('links' => $this->generator->links($current_url))),
      'group' => t('Link')
    );
    $items['mark_new'] = array(
      'title' => t('Mark, new'),
      'content' => $this->createLink($this->generator->sentence(), $current_url) . $this->themeElement('mark', array('type' => MARK_NEW)),
      'group' => t('Link')
    );
    $items['mark_updated'] = array(
      'title' => t('Mark, updated'),
      'content' => $this->createLink($this->generator->sentence(), $current_url) . $this->themeElement('mark', array('type' => MARK_UPDATED)),
      'group' => t('Link')
    );
    $items['more_help_link'] = array(
      'title' => t('More help link'),
      'content' => $this->generator->paragraphs(1) . $this->themeElement('more_help_link', array('url' => $current_url)),
      'group' => t('Link')
    );
    $items['more_link'] = array(
      'title' => t('More link'),
      'content' => $this->generator->paragraphs(1) . $this->themeElement('more_link', array('url' => $current_url, 'title' => $this->generator->sentence())),
      'group' => t('Link')
    );
    $items['monospace'] = array(
      'title' => t('Monospace'),
      'content' => $this->generator->lorem(1, 0, 'mixed', FALSE),
      'group' => t('Text'),
      'tag' => 'code',
    );

    return $items;
  }

  /**
   * @param $text
   * @param $uri
   * @return null
   */
  public function createLink($text, $uri) {
    $url = Url::fromUserInput($uri);
    $link = Link::fromTextAndUrl($text, $url);
    $to_render = $link->toRenderable();
    return render($to_render);
  }

  /**
   * @param $name
   * @param array $variables
   * @return null
   */
  public function themeElement($name, $variables = array()) {
    $el = ['#theme' => $name];
    foreach ($variables as $key => $value) {
      $el['#' . $key] = $value;
    }
    return render($el);
  }

  /**
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
   * @param array $configuration
   * @param string $plugin_id
   * @param mixed $plugin_definition
   * @return static
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('styleguide.generator')
    );
  }
}
