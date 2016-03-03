<?php

/**
 * @file
 * Contains \Drupal\styleguide\Plugin\Styleguide\defaultStyleguide.
 */

namespace Drupal\styleguide\Plugin\Styleguide;

use Drupal\Core\Block\BlockManager;
use Drupal\Core\Breadcrumb\ChainBreadcrumbBuilderInterface;
use Drupal\Core\Form\FormBuilder;
use Drupal\Core\Form\FormState;
use Drupal\Core\Menu\MenuLinkTreeInterface;
use Drupal\Core\Menu\MenuTreeParameters;
use Drupal\Core\Render\Element;
use Drupal\Core\Routing\CurrentRouteMatch;
use Drupal\styleguide\GeneratorInterface;
use Drupal\styleguide\Plugin\StyleguidePluginBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @Plugin(
 *   id = "default_styleguide",
 *   label = @Translation("Default Styleguide elements")
 * )
 */
class defaultStyleguide extends StyleguidePluginBase {

  /**
   * The styleguide generator service.
   *
   * @var \Drupal\styleguide\Generator
   */
  protected $generator;

  /**
   * The request stack.
   *
   * @var \Symfony\Component\HttpFoundation\RequestStack
   */
  protected $requestStack;

  /**
   * The menu link tree.
   *
   * @var \Drupal\Core\Menu\MenuLinkTreeInterface
   */
  protected $linkTree;

  /**
   * The form builder.
   *
   * @var \Drupal\Core\Form\FormBuilder
   */
  protected $formBuilder;

  /**
   * The breadcrumb manager.
   *
   * @var \Drupal\Core\Breadcrumb\ChainBreadcrumbBuilderInterface
   */
  protected $breadcrumbManager;

  /**
   * The current_route_match service.
   *
   * @var \Drupal\Core\Routing\CurrentRouteMatch
   */
  protected $currentRouteMatch;

  /**
   * The block plugin manager.
   *
   * @var \Drupal\Core\Block\BlockManager
   */
  protected $blockManager;

  /**
   * Constructs a new defaultStyleguide.
   *
   * @param array $configuration
   * @param string $plugin_id
   * @param mixed $plugin_definition
   * @param \Drupal\styleguide\GeneratorInterface $styleguide_generator
   * @param \Symfony\Component\HttpFoundation\RequestStack $request_stack
   * @param \Drupal\Core\Menu\MenuLinkTreeInterface $link_tree
   * @param \Drupal\Core\Form\FormBuilder $form_builder
   * @param \Drupal\Core\Breadcrumb\ChainBreadcrumbBuilderInterface $breadcrumb_manager
   * @param \Drupal\Core\Routing\CurrentRouteMatch $current_route_match
   * @internal param \Drupal\Core\Breadcrumb\ChainBreadcrumbBuilderInterface $breadcrumb
   * @internal param \Drupal\styleguide\GeneratorInterface $generator
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, GeneratorInterface $styleguide_generator, RequestStack $request_stack, MenuLinkTreeInterface $link_tree, FormBuilder $form_builder, ChainBreadcrumbBuilderInterface $breadcrumb_manager, CurrentRouteMatch $current_route_match, BlockManager $block_manager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);

    $this->generator = $styleguide_generator;
    $this->requestStack = $request_stack;
    $this->linkTree = $link_tree;
    $this->formBuilder = $form_builder;
    $this->breadcrumbManager = $breadcrumb_manager;
    $this->currentRouteMatch = $current_route_match;
    $this->blockManager = $block_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('styleguide.generator'),
      $container->get('request_stack'),
      $container->get('menu.link_tree'),
      $container->get('form_builder'),
      $container->get('breadcrumb'),
      $container->get('current_route_match'),
      $container->get('plugin.manager.block')
    );
  }

  /**
   * @return array
   */
  public function items() {
    $current_url = $this->requestStack->getCurrentRequest()->getRequestUri();
    $items['a'] = array(
      'title' => $this->t('Link'),
      'content' => [
        ['#markup' => $this->generator->words(3, 'ucfirst') . ' '],
        $this->buildLink($this->generator->words(3), '/node'),
        ['#markup' => ' ' . $this->generator->words(4) . '.'],
      ],
    );
    $items['b'] = array(
      'title' => $this->t('Bold'),
      'content' => $this->generator->words(3, 'ucfirst') . ' <b>' . $this->generator->words(3) . '</b> ' . $this->generator->words(4) . '.',
    );
    $items['del'] = array(
      'title' => $this->t('Delete'),
      'content' => $this->generator->words(3, 'ucfirst') . ' <del>' . $this->generator->words(3) . '</del> ' . $this->generator->words(4) . '.',
    );
    $items['em'] = array(
      'title' => $this->t('Emphasis'),
      'content' => $this->generator->words(3, 'ucfirst') . ' <em>' . $this->generator->words(3) . '</em> ' . $this->generator->words(4) . '.',
    );
    $items['figcaption'] = array(
      'title' => $this->t('Figcaption'),
      'content' => $this->generator->words(3, 'ucfirst') . ' <figcaption>' . $this->generator->words(3) . '</figcaption> ' . $this->generator->words(4) . '.',
    );
    $items['figure'] = array(
      'title' => $this->t('Figure'),
      'content' => $this->generator->words(3, 'ucfirst') . ' <figure>' . $this->generator->words(3) . '</figure> ' . $this->generator->words(4) . '.',
    );
    $items['hr'] = array(
      'title' => $this->t('Horizontal rule'),
      'content' => $this->generator->words(3, 'ucfirst') . ' <hr>' . $this->generator->words(3) . '</hr> ' . $this->generator->words(4) . '.',
    );
    $items['i'] = array(
      'title' => $this->t('Italic'),
      'content' => $this->generator->words(3, 'ucfirst') . ' <i>' . $this->generator->words(3) . '</i> ' . $this->generator->words(4) . '.',
    );
    $items['q'] = array(
      'title' => $this->t('Quote'),
      'content' => $this->generator->words(3, 'ucfirst') . ' <q>' . $this->generator->words(3) . '</q> ' . $this->generator->words(4) . '.',
    );
    $items['s'] = array(
      'title' => $this->t('Strikethrough'),
      'content' => $this->generator->words(3, 'ucfirst') . ' <s>' . $this->generator->words(3) . '</s> ' . $this->generator->words(4) . '.',
    );
    $items['small'] = array(
      'title' => $this->t('Small'),
      'content' => $this->generator->words(3, 'ucfirst') . ' <small>' . $this->generator->words(3) . '</small> ' . $this->generator->words(4) . '.',
    );
    $items['strong'] = array(
      'title' => $this->t('Strong'),
      'content' => $this->generator->words(3, 'ucfirst') . ' <strong>' . $this->generator->words(3) . '</strong> ' . $this->generator->words(4) . '.',
    );
    $items['sub'] = array(
      'title' => $this->t('Subscript'),
      'content' => $this->generator->words(3, 'ucfirst') . ' <sub>' . $this->generator->words(1) . '</sub> ' . $this->generator->words(4) . '.',
    );
    $items['sup'] = array(
      'title' => $this->t('Superscript'),
      'content' => $this->generator->words(3, 'ucfirst') . ' <sup>' . $this->generator->words(1) . '</sup> ' . $this->generator->words(4) . '.',
    );
    $items['u'] = array(
      'title' => $this->t('Underline'),
      'content' => $this->generator->words(3, 'ucfirst') . ' <u>' . $this->generator->words(3) . '</u> ' . $this->generator->words(4) . '.',
    );
    $items['ul'] = array(
      'title' => $this->t('Unordered list'),
      'content' => [
        '#theme' => 'item_list',
        '#items' => $this->generator->wordList(),
        '#list_type' => 'ul',
      ],
      'group' => $this->t('Lists'),
    );
    $items['ol'] = array(
      'title' => $this->t('Ordered list'),
      'content' => [
        '#theme' => 'item_list',
        '#items' => $this->generator->wordList(),
        '#list_type' => 'ol',
      ],
      'group' => $this->t('Lists'),
    );
    $items['ul_title'] = array(
      'title' => $this->t('Unordered list, with title'),
      'content' => [
        '#theme' => 'item_list',
        '#items' => $this->generator->wordList(),
        '#list_type' => 'ul',
        '#title' => $this->generator->words(3, 'ucfirst')
      ],
      'group' => $this->t('Lists'),
    );
    $items['ol_title'] = array(
      'title' => $this->t('Ordered list, with title'),
      'content' => [
        '#theme' => 'item_list',
        '#items' => $this->generator->wordList(),
        '#list_type' => 'ol',
        '#title' => $this->generator->words(3, 'ucfirst'),
      ],
      'group' => $this->t('Lists'),
    );
    $items['ul_long'] = array(
      'title' => $this->t('Unordered list with wrapped list items'),
      'content' => [
        '#theme' => 'item_list',
        '#items' => $this->generator->wordList(3, 120),
        '#list_type' => 'ul',
      ],
      'group' => $this->t('Lists'),
    );
    $items['ol_long'] = array(
      'title' => $this->t('Ordered list with wrapped list items'),
      'content' => [
        '#theme' => 'item_list',
        '#items' => $this->generator->wordList(3, 120),
        '#list_type' => 'ol',
      ],
      'group' => $this->t('Lists'),
    );
    $items['ul_links'] = array(
      'title' => $this->t('Unordered list with links'),
      'content' => [
        '#theme' => 'links',
        '#links' => $this->generator->ulLinks(),
      ],
      'group' => $this->t('Lists'),
    );
    $items['ul_links_inline'] = array(
      'title' => $this->t('Unordered inline list with links'),
      'content' => [
        '#theme' => 'links',
        '#links' => $this->generator->ulLinks(),
        '#attributes' => array('class' => array('inline')),
      ],
      'group' => $this->t('Lists'),
    );
//    dpm($items['ul_links_inline']['content']);

    $menu = $this->linkTree->load('admin', new MenuTreeParameters());
    $items['menu_tree'] = array(
      'title' => $this->t('Menu tree'),
      'content' => $this->linkTree->build($menu),
      'group' => $this->t('Menus'),
    );
    $items['menu_link'] = array(
      'title' => $this->t('Menu link'),
      'content' => $this->generator->menuItem($current_url),
      'group' => $this->t('Menus')
    );
    $items['table'] = array(
      'title' => $this->t('Table'),
      'content' => [
        '#theme' => 'table',
        '#caption' => $this->generator->words(3),
        '#header' => $this->generator->tableHeader(),
        '#rows' => $this->generator->tableRows(),
      ],
      'group' => $this->t('Tables'),
    );
    $items['text'] = array(
      'title' => $this->t('Text block'),
      'content' => $this->generator->paragraphs(3),
      'group' => $this->t('Text'),
    );
    $items['blockquote'] = array(
      'title' => $this->t('Blockquote'),
      'content' => $this->generator->paragraphs(1) . '<blockquote>' . $this->generator->paragraphs(1) . '</blockquote>' . $this->generator->paragraphs(1),
      'group' => $this->t('Text'),
    );
    $items['image-horizontal'] = array(
      'title' => $this->t('Image, horizontal'),
      'content' => [
        '#theme' => 'image',
        '#uri' => $this->generator->image('horizontal'),
        '#alt' => $this->t('My image'),
        '#title' => $this->t('My image'),
      ],
      'group' => $this->t('Media'),
    );
    $items['image-vertical'] = array(
      'title' => $this->t('Image, vertical'),
      'content' => [
        '#theme' => 'image',
        '#uri' => $this->generator->image('vertical'),
        '#alt' => $this->t('My image'),
        '#title' => $this->t('My image'),
      ],
      'group' => $this->t('Media'),
    );
    $items['image-inset-horizontal'] = array(
      'title' => $this->t('Image, horizontal, within text'),
      'content' => [
        ['#markup' => $this->generator->paragraphs(1)],
        [
          '#theme' => 'image',
          '#uri' => $this->generator->image('horizontal'),
          '#alt' => $this->t('My image'),
          '#title' => $this->t('My image'),
        ],
        ['#markup' => $this->generator->paragraphs(2)],
      ],
      'group' => $this->t('Media'),
    );
    $items['image-inset-vertical'] = array(
      'title' => $this->t('Image, vertical, within text'),
      'content' => [
        ['#markup' => $this->generator->paragraphs(1)],
        [
          '#theme' => 'image',
          '#uri' => $this->generator->image('vertical'),
          '#alt' => $this->t('My image'),
          '#title' => $this->t('My image'),
        ],
        ['#markup' => $this->generator->paragraphs(2)],
      ],
      'group' => $this->t('Media'),
    );
    $content = '';
    for ($i = 1; $i <=6; $i++) {
      $content .= "<h$i>" . "h$i: " . implode(' ', $this->generator->wordList()) . "</h$i>";
    }
    $items['headings'] = array(
      'title' => "Headings",
      'content' => $content,
      'group' => $this->t('Text'),
    );
    $content = '';
    for ($i = 1; $i <=6; $i++) {
      $content .= "<h$i>" . "h$i: " . implode(' ', $this->generator->wordList()) . "</h$i>" . $this->generator->paragraphs(1);
    }
    $items['headings_text'] = array(
      'title' => "Headings with text",
      'content' => $content,
      'group' => $this->t('Text'),
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
    $elements = $this->formBuilder->buildForm('Drupal\styleguide\Form\StyleguideForm', new FormState());
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
      'title' => $this->t('Forms, basic'),
      'content' => $this->formBuilder->getForm('Drupal\styleguide\Form\StyleguideForm', $basic),
      'group' => $this->t('Forms'),
    );
    $items['form-submit'] = array(
      'title' => $this->t('Forms, submit'),
      'content' => $this->formBuilder->getForm('Drupal\styleguide\Form\StyleguideForm', array('submit')),
      'group' => $this->t('Forms'),
    );
    $items['form-button'] = array(
      'title' => $this->t('Forms, button'),
      'content' => $this->formBuilder->getForm('Drupal\styleguide\Form\StyleguideForm', array('button')),
      'group' => $this->t('Forms'),
    );
    $items['form-image-button'] = array(
      'title' => $this->t('Forms, image button'),
      'content' => $this->formBuilder->getForm('Drupal\styleguide\Form\StyleguideForm', array('image_button')),
      'group' => $this->t('Forms'),
    );
    $items['form-markup'] = array(
      'title' => $this->t('Forms, markup'),
      'content' => $this->formBuilder->getForm('Drupal\styleguide\Form\StyleguideForm', $markup),
      'group' => $this->t('Forms'),
    );
    $items['form-fieldsets'] = array(
      'title' => $this->t('Forms, fieldsets'),
      'content' => $this->formBuilder->getForm('Drupal\styleguide\Form\StyleguideForm', $fieldsets),
      'group' => $this->t('Forms'),
    );
    $items['form-vertical-tabs'] = array(
      'title' => $this->t('Forms, vertical tabs'),
      'content' => $this->formBuilder->getForm('Drupal\styleguide\Form\StyleguideForm', $tabs),
      'group' => $this->t('Forms'),
    );
    $items['feed_icon'] = array(
      'title' => $this->t('Feed icon'),
      'content' => [
        '#theme' => 'feed_icon',
        '#url' => 'rss.xml',
        '#title' => $this->t('Syndicate'),
      ],
      'group' => $this->t('System')
    );
    // This item kills drupal_set_message. The messages are displayed here.
    $items['maintenance_page'] = array(
      'title' => $this->t('Maintenance page'),
      'content' => [
        '#theme' => 'maintenance_page',
        '#title' => $this->generator->sentence(1),
      ],
      'group' => $this->t('System')
    );
    $plugin = $this->blockManager->createInstance('system_powered_by_block');
    $items['system_powered_by'] = array(
      'title' => $this->t('System powered by'),
      'content' => $plugin->build(),
      'group' => $this->t('System')
    );
    $items['confirm_form'] = array(
      'title' => $this->t('Confirm form'),
      'content' => $this->formBuilder->getForm('Drupal\styleguide\Form\StyleguideConfirmForm'),
      'group' => $this->t('System')
    );
    $items['pager'] = array(
      'title' => $this->t('Pager'),
      'content' => $this->generator->pager(),
      'group' => $this->t('User interface')
    );
    $items['progress_bar'] = array(
      'title' => $this->t('Progress bar'),
      'content' => [
        '#theme' => 'progress_bar',
        '#percent' => 57,
        '#message' => $this->generator->sentence(2),
      ],
      'group' => $this->t('User interface')
    );
    // Use alternative item name to avoid conflict with main breadcrumb.
    $breadcrumb = $this->breadcrumbManager->build($this->currentRouteMatch);
    $items['styleguide_breadcrumb'] = array(
      'title' => $this->t('Breadcrumb'),
      'content' => $breadcrumb->toRenderable(),
      'group' => $this->t('User interface')
    );
    $items['link'] = array(
      'title' => $this->t('Link'),
      'content' => $this->buildLink($this->generator->words(2), $current_url),
      'group' => $this->t('Link')
    );
    $items['links'] = array(
      'title' => $this->t('Links'),
      'content' => [
        '#theme' => 'links',
        '#links' => $this->generator->links($current_url),
      ],
      'group' => $this->t('Link')
    );
    $items['mark_new'] = array(
      'title' => $this->t('Mark, new'),
      'content' => [
        [$this->buildLink($this->generator->sentence(), $current_url)],
        [
          '#theme' => 'mark',
          '#type' => MARK_NEW,
        ],
      ],
      'group' => $this->t('Link')
    );
    $items['mark_updated'] = array(
      'title' => $this->t('Mark, updated'),
      'content' => [
        [$this->buildLink($this->generator->sentence(), $current_url)],
        [
          '#theme' => 'mark',
          '#type' => MARK_UPDATED,
        ],
      ],
      'group' => $this->t('Link')
    );
    $items['more_help_link'] = array(
      'title' => $this->t('More help link'),
      'content' => [
        ['#markup' => $this->generator->paragraphs(1)],
        [
          '#theme' => 'more_help_link',
          '#url' => $current_url,
        ],
      ],
      'group' => $this->t('Link')
    );
    $items['more_link'] = array(
      'title' => $this->t('More link'),
      'content' => [
        ['#markup' => $this->generator->paragraphs(1)],
        [
          '#theme' => 'more_link',
          '#url' => $current_url,
          '#title' => $this->generator->sentence(),
        ],
      ],
      'group' => $this->t('Link')
    );
    $items['monospace'] = array(
      'title' => $this->t('Monospace'),
      'content' => $this->generator->lorem(1, 0, 'mixed', FALSE),
      'group' => $this->t('Text'),
      'tag' => 'code',
    );

    return $items;
  }

}
