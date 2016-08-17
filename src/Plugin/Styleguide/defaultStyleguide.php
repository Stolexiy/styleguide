<?php

namespace Drupal\styleguide\Plugin\Styleguide;

use Drupal\Core\Block\BlockManager;
use Drupal\Core\Breadcrumb\ChainBreadcrumbBuilderInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Form\FormBuilder;
use Drupal\Core\Form\FormState;
use Drupal\Core\Menu\MenuLinkTreeInterface;
use Drupal\Core\Menu\MenuTreeParameters;
use Drupal\Core\Render\Element;
use Drupal\Core\Routing\CurrentRouteMatch;
use Drupal\Core\Theme\ThemeManagerInterface;
use Drupal\Core\Url;
use Drupal\styleguide\GeneratorInterface;
use Drupal\styleguide\Plugin\StyleguidePluginBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Default Styleguide items implementation.
 *
 * @Plugin(
 *   id = "default_styleguide",
 *   label = @Translation("Default Styleguide elements")
 * )
 */
class DefaultStyleguide extends StyleguidePluginBase {

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
   * The theme manager service.
   *
   * @var \Drupal\Core\Theme\ThemeManagerInterface
   */
  protected $themeManager;

  /**
   * The module handler service.
   *
   * @var ModuleHandlerInterface
   */
  protected $moduleHandler;

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
   * @param BlockManager $block_manager
   * @param ThemeManagerInterface $theme_manager
   * @param ModuleHandlerInterface $module_handler
   *
   * @internal param \Drupal\Core\Breadcrumb\ChainBreadcrumbBuilderInterface $breadcrumb
   * @internal param \Drupal\styleguide\GeneratorInterface $generator
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, GeneratorInterface $styleguide_generator, RequestStack $request_stack, MenuLinkTreeInterface $link_tree, FormBuilder $form_builder, ChainBreadcrumbBuilderInterface $breadcrumb_manager, CurrentRouteMatch $current_route_match, BlockManager $block_manager, ThemeManagerInterface $theme_manager, ModuleHandlerInterface $module_handler) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);

    $this->generator = $styleguide_generator;
    $this->requestStack = $request_stack;
    $this->linkTree = $link_tree;
    $this->formBuilder = $form_builder;
    $this->breadcrumbManager = $breadcrumb_manager;
    $this->currentRouteMatch = $current_route_match;
    $this->blockManager = $block_manager;
    $this->themeManager = $theme_manager;
    $this->moduleHandler = $module_handler;
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
      $container->get('plugin.manager.block'),
      $container->get('theme.manager'),
      $container->get('module_handler')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function items() {
    $current_url = $this->requestStack->getCurrentRequest()->getRequestUri();
    $items['a'] = array(
      'title' => $this->t('Link'),
      'content' => [
        '#type' => 'inline_template',
        '#template' => '{{ pre }} {{ link }} {{ post }}.',
        '#context' => [
          'pre' => $this->generator->words(3, 'ucfirst'),
          'link' => $this->buildLink($this->generator->words(3), '/node'),
          'post' => $this->generator->words(4),
        ],
      ],
    );
    $items['b'] = array(
      'title' => $this->t('Bold'),
      'content' => [
        '#type' => 'inline_template',
        '#template' => '{{ pre }} <b>{{ bold }}</b> {{ post }}.',
        '#context' => [
          'pre' => $this->generator->words(3, 'ucfirst'),
          'bold' => $this->generator->words(3),
          'post' => $this->generator->words(4),
        ],
      ],
    );
    $items['del'] = array(
      'title' => $this->t('Delete'),
      'content' => [
        '#type' => 'inline_template',
        '#template' => '{{ pre }} <del>{{ del }}</del> {{ post }}.',
        '#context' => [
          'pre' => $this->generator->words(3, 'ucfirst'),
          'del' => $this->generator->words(3),
          'post' => $this->generator->words(4),
        ],
      ],
    );
    $items['em'] = array(
      'title' => $this->t('Emphasis'),
      'content' => [
        '#type' => 'inline_template',
        '#template' => '{{ pre }} <em>{{ em }}</em> {{ post }}.',
        '#context' => [
          'pre' => $this->generator->words(3, 'ucfirst'),
          'em' => $this->generator->words(3),
          'post' => $this->generator->words(4),
        ],
      ],
    );
    $items['figcaption'] = array(
      'title' => $this->t('Figcaption'),
      'content' => [
        '#type' => 'inline_template',
        '#template' => '{{ pre }} <figcaption>{{ figcaption }}</figcaption> {{ post }}.',
        '#context' => [
          'pre' => $this->generator->words(3, 'ucfirst'),
          'figcaption' => $this->generator->words(3),
          'post' => $this->generator->words(4),
        ],
      ],
    );
    $items['figure'] = array(
      'title' => $this->t('Figure'),
      'content' => [
        '#type' => 'inline_template',
        '#template' => '{{ pre }} <figure>{{ figure }}</figure> {{ post }}.',
        '#context' => [
          'pre' => $this->generator->words(3, 'ucfirst'),
          'figure' => $this->generator->words(3),
          'post' => $this->generator->words(4),
        ],
      ],
    );
    $items['hr'] = array(
      'title' => $this->t('Horizontal rule'),
      'content' => [
        '#type' => 'inline_template',
        '#template' => '{{ pre }} <hr>{{ hr }}</hr> {{ post }}.',
        '#context' => [
          'pre' => $this->generator->words(3, 'ucfirst'),
          'hr' => $this->generator->words(3),
          'post' => $this->generator->words(4),
        ],
      ],
    );
    $items['i'] = array(
      'title' => $this->t('Italic'),
      'content' => [
        '#type' => 'inline_template',
        '#template' => '{{ pre }} <i>{{ i }}</i> {{ post }}.',
        '#context' => [
          'pre' => $this->generator->words(3, 'ucfirst'),
          'i' => $this->generator->words(3),
          'post' => $this->generator->words(4),
        ],
      ],
    );
    $items['q'] = array(
      'title' => $this->t('Quote'),
      'content' => [
        '#type' => 'inline_template',
        '#template' => '{{ pre }} <q>{{ q }}</q> {{ post }}.',
        '#context' => [
          'pre' => $this->generator->words(3, 'ucfirst'),
          'q' => $this->generator->words(3),
          'post' => $this->generator->words(4),
        ],
      ],
    );
    $items['s'] = array(
      'title' => $this->t('Strikethrough'),
      'content' => [
        '#type' => 'inline_template',
        '#template' => '{{ pre }} <s>{{ s }}</s> {{ post }}.',
        '#context' => [
          'pre' => $this->generator->words(3, 'ucfirst'),
          's' => $this->generator->words(3),
          'post' => $this->generator->words(4),
        ],
      ],
    );
    $items['small'] = array(
      'title' => $this->t('Small'),
      'content' => [
        '#type' => 'inline_template',
        '#template' => '{{ pre }} <small>{{ small }}</small> {{ post }}.',
        '#context' => [
          'pre' => $this->generator->words(3, 'ucfirst'),
          'small' => $this->generator->words(3),
          'post' => $this->generator->words(4),
        ],
      ],
    );
    $items['strong'] = array(
      'title' => $this->t('Strong'),
      'content' => [
        '#type' => 'inline_template',
        '#template' => '{{ pre }} <strong>{{ strong }}</strong> {{ post }}.',
        '#context' => [
          'pre' => $this->generator->words(3, 'ucfirst'),
          'strong' => $this->generator->words(3),
          'post' => $this->generator->words(4),
        ],
      ],
    );
    $items['sub'] = array(
      'title' => $this->t('Subscript'),
      'content' => [
        '#type' => 'inline_template',
        '#template' => '{{ pre }} <sub>{{ sub }}</sub> {{ post }}.',
        '#context' => [
          'pre' => $this->generator->words(3, 'ucfirst'),
          'sub' => $this->generator->words(1),
          'post' => $this->generator->words(4),
        ],
      ],
    );
    $items['sup'] = array(
      'title' => $this->t('Superscript'),
      'content' => [
        '#type' => 'inline_template',
        '#template' => '{{ pre }} <sup>{{ sup }}</sup> {{ post }}.',
        '#context' => [
          'pre' => $this->generator->words(3, 'ucfirst'),
          'sup' => $this->generator->words(1),
          'post' => $this->generator->words(4),
        ],
      ],
    );
    $items['u'] = array(
      'title' => $this->t('Underline'),
      'content' => [
        '#type' => 'inline_template',
        '#template' => '{{ pre }} <u>{{ u }}</u> {{ post }}.',
        '#context' => [
          'pre' => $this->generator->words(3, 'ucfirst'),
          'u' => $this->generator->words(3),
          'post' => $this->generator->words(4),
        ],
      ],
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
        '#title' => $this->generator->words(3, 'ucfirst'),
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

    $menu = $this->linkTree->load('admin', new MenuTreeParameters());
    $items['menu_tree'] = array(
      'title' => $this->t('Menu tree'),
      'content' => $this->linkTree->build($menu),
      'group' => $this->t('Menus'),
    );
    $items['menu_link'] = array(
      'title' => $this->t('Menu link'),
      'content' => $this->generator->menuItem($current_url),
      'group' => $this->t('Menus'),
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
      'content' => [
        '#type' => 'inline_template',
        '#template' => '{{ pre }}<blockquote>{{ blockquote }}</blockquote>{{ post }}',
        '#context' => [
          'pre' => $this->generator->paragraphs(1),
          'blockquote' => $this->generator->paragraphs(1),
          'post' => $this->generator->paragraphs(1),
        ],
      ],
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
        [$this->generator->paragraphs(1)],
        [
          '#theme' => 'image',
          '#uri' => $this->generator->image('horizontal'),
          '#alt' => $this->t('My image'),
          '#title' => $this->t('My image'),
        ],
        [$this->generator->paragraphs(2)],
      ],
      'group' => $this->t('Media'),
    );
    $items['image-inset-vertical'] = array(
      'title' => $this->t('Image, vertical, within text'),
      'content' => [
        [$this->generator->paragraphs(1)],
        [
          '#theme' => 'image',
          '#uri' => $this->generator->image('vertical'),
          '#alt' => $this->t('My image'),
          '#title' => $this->t('My image'),
        ],
        [$this->generator->paragraphs(2)],
      ],
      'group' => $this->t('Media'),
    );
    $content = array();
    for ($i = 1; $i <= 6; $i++) {
      $content[] = [
        '#type' => 'inline_template',
        '#template' => '<h{{ i }}>h{{ i }}: {{ wordList }}</h{{ i }}>',
        '#context' => [
          'i' => $i,
          'wordList' => implode(' ', $this->generator->wordList()),
        ],
      ];
    }
    $items['headings'] = array(
      'title' => "Headings",
      'content' => $content,
      'group' => $this->t('Text'),
    );
    $content = array();
    for ($i = 1; $i <= 6; $i++) {
      $content[] = [
        '#type' => 'inline_template',
        '#template' => '<h{{ i }}>h{{ i }}: {{ wordList }}</h{{ i }}>{{ paragraph }}',
        '#context' => [
          'i' => $i,
          'wordList' => implode(' ', $this->generator->wordList()),
          'paragraph' => $this->generator->paragraphs(1),
        ],
      ];
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
    $form_state = new FormState();
    $elements = $this->formBuilder->buildForm('Drupal\styleguide\Form\StyleguideForm', $form_state);
    $basic = array();
    $details = array();
    $tabs = array();
    $markup = array();
    foreach (Element::children($elements) as $key) {
      if ($key == 'vertical_tabs' && !in_array($key, $tabs)) {
        $tabs[] = $key;
      }
      elseif (!isset($elements[$key]['#type']) || $elements[$key]['#type'] == 'item') {
        $markup[] = $key;
      }
      elseif ($elements[$key]['#type'] == 'details') {
        $details[] = $key;
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
    $items['form-details'] = array(
      'title' => $this->t('Forms, details'),
      'content' => $this->formBuilder->getForm('Drupal\styleguide\Form\StyleguideForm', $details),
      'group' => $this->t('Forms'),
    );
    $items['form-fieldset'] = array(
      'title' => $this->t('Forms, fieldset'),
      'content' => $this->formBuilder->getForm('Drupal\styleguide\Form\StyleguideForm', array('fieldset')),
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
      'group' => $this->t('System'),
    );
    // Builds a link to the Styleguide maintenance page.
    $route_name = 'styleguide.maintenance_page.' . $this->themeManager->getActiveTheme()->getName();
    $items['maintenance_page'] = array(
      'title' => $this->t('Maintenance page'),
      'content' => $this->buildLinkFromRoute($this->t('Open the maintenance page'), $route_name, array(), array(
        'attributes' => array(
          'target' => array('_blank'),
        ),
      )),
      'group' => $this->t('System'),
    );
    $plugin = $this->blockManager->createInstance('system_powered_by_block');
    $items['system_powered_by'] = array(
      'title' => $this->t('System powered by'),
      'content' => $plugin->build(),
      'group' => $this->t('System'),
    );
    $items['confirm_form'] = array(
      'title' => $this->t('Confirm form'),
      'content' => $this->formBuilder->getForm('Drupal\styleguide\Form\StyleguideConfirmForm'),
      'group' => $this->t('System'),
    );

    if ($this->moduleHandler->moduleExists('filter')) {
      $items['text_format'] = array(
        'title' => t('Text format'),
        'content' => $this->formBuilder->getForm('Drupal\styleguide\Form\StyleguideForm', array('text_format')),
        'group' => t('System'),
      );
      $items['filter_tips'] = array(
        'title' => t('Filter tips, short'),
        'content' => array(
          '#theme' => 'filter_tips',
          '#tips' => _filter_tips(-1, FALSE),
          '#long' => FALSE,
        ),
        'group' => t('System'),
      );
      $items['filter_tips_long'] = array(
        'title' => t('Filter tips, long'),
        'content' => array(
          '#theme' => 'filter_tips',
          '#tips' => _filter_tips(-1, TRUE),
          '#long' => TRUE,
        ),
        'group' => t('System'),
      );
    }

    $items['pager'] = array(
      'title' => $this->t('Pager'),
      'content' => $this->generator->pager(),
      'group' => $this->t('User interface'),
    );
    $items['progress_bar'] = array(
      'title' => $this->t('Progress bar'),
      'content' => [
        '#theme' => 'progress_bar',
        '#percent' => 57,
        '#message' => $this->generator->sentence(2),
      ],
      'group' => $this->t('User interface'),
    );
    // Use alternative item name to avoid conflict with main breadcrumb.
    $breadcrumb = $this->breadcrumbManager->build($this->currentRouteMatch);
    $items['styleguide_breadcrumb'] = array(
      'title' => $this->t('Breadcrumb'),
      'content' => $breadcrumb->toRenderable(),
      'group' => $this->t('User interface'),
    );
    $items['link'] = array(
      'title' => $this->t('Link'),
      'content' => $this->buildLink($this->generator->words(2), $current_url),
      'group' => $this->t('Link'),
    );
    $items['links'] = array(
      'title' => $this->t('Links'),
      'content' => [
        '#theme' => 'links',
        '#links' => $this->generator->links($current_url),
      ],
      'group' => $this->t('Link'),
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
      'group' => $this->t('Link'),
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
      'group' => $this->t('Link'),
    );
    $items['more_help_link'] = array(
      'title' => $this->t('More help link'),
      'content' => [
        [$this->generator->paragraphs(1)],
        [
          '#type' => 'link',
          '#url' => Url::fromUserInput($current_url),
          '#title' => t('More help'),
          '#attributes' => array(
            'class' => array('icon-help'),
          ),
        ],
      ],
      'group' => $this->t('Link'),
    );
    $items['more_link'] = array(
      'title' => $this->t('More link'),
      'content' => [
        [$this->generator->paragraphs(1)],
        [
          '#type' => 'more_link',
          '#url' => Url::fromUserInput($current_url),
        ],
      ],
      'group' => $this->t('Link'),
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
