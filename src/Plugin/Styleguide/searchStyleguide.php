<?php

namespace Drupal\styleguide\Plugin\Styleguide;

use Drupal\styleguide\GeneratorInterface;
use Drupal\styleguide\Plugin\StyleguidePluginBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Theme\ThemeManagerInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Form\FormBuilder;
use Drupal\Core\Session\AccountInterface;
use Drupal\node\Entity\Node;
use Drupal\Core\Entity\EntityManagerInterface;
use Drupal\Core\Render\RendererInterface;

/**
 * Search Styleguide items implementation.
 *
 * @Plugin(
 *   id = "search_styleguide",
 *   label = @Translation("Node search Styleguide elements")
 * )
 */
class SearchStyleguide extends StyleguidePluginBase {

  /**
   * The styleguide generator service.
   *
   * @var \Drupal\styleguide\Generator
   */
  protected $generator;

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
   * The form builder.
   *
   * @var \Drupal\Core\Form\FormBuilder
   */
  protected $formBuilder;

  /**
   * The current user.
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $currentUser;

  /**
   * An entity manager object.
   *
   * @var \Drupal\Core\Entity\EntityManagerInterface
   */
  protected $entityManager;

  /**
   * The Renderer service to format the username and node.
   *
   * @var \Drupal\Core\Render\RendererInterface
   */
  protected $renderer;

  /**
   * Constructs a new imageStyleguide.
   *
   * @param array $configuration
   * @param string $plugin_id
   * @param mixed $plugin_definition
   * @param \Drupal\styleguide\GeneratorInterface $styleguide_generator
   * @param ThemeManagerInterface $theme_manager
   * @param \Drupal\Core\Form\FormBuilder $form_builder
   * @param ModuleHandlerInterface $module_handler
   * @param \Drupal\Core\Session\AccountInterface $current_user
   * @param \Drupal\Core\Entity\EntityManagerInterface $entity_manager
   * @param \Drupal\Core\Render\RendererInterface $renderer
   *
   * @internal param \Drupal\Core\Breadcrumb\ChainBreadcrumbBuilderInterface $breadcrumb
   * @internal param \Drupal\styleguide\GeneratorInterface $generator
   */

  public function __construct(array $configuration, $plugin_id, $plugin_definition, GeneratorInterface $styleguide_generator, ThemeManagerInterface $theme_manager, ModuleHandlerInterface $module_handler, FormBuilder $form_builder, AccountInterface $current_user, EntityManagerInterface $entity_manager, RendererInterface $renderer) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->generator = $styleguide_generator;
    $this->themeManager = $theme_manager;
    $this->moduleHandler = $module_handler;
    $this->formBuilder = $form_builder;
    $this->currentUser = $current_user;
    $this->entityManager = $entity_manager;
    $this->renderer = $renderer;
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
      $container->get('theme.manager'),
      $container->get('module_handler'),
      $container->get('form_builder'),
      $container->get('current_user'),
      $container->get('entity.manager'),
      $container->get('renderer')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function items() {
    $items = [];
    if ($this->moduleHandler->moduleExists('search')) {
      $items['search_block_form'] = [
        'title' => $this->t('Search block form'),
        'content' => $this->formBuilder->getForm('Drupal\search\Form\SearchBlockForm'),
        'group' => $this->t('Search'),
      ];

      $items['search_no_results'] = [
        'title' => $this->t('Search, no results'),
        'content' => [
          '#theme' => 'item_list__search_results',
          '#items' => [],
          '#empty' => [
            '#markup' => '<h3>' . $this->t('Your search yielded no results.') . '</h3>',
          ],
        ],
        'group' => $this->t('Search')
      ];

      // Generate sample results.
      $this->searchResults($items);
    }

    return $items;
  }

  /**
   * Generate fake search results.
   *
   * @param $items
   */
  private function searchResults(&$items) {
    $results = [];
    if (\Drupal::hasService('plugin.manager.search')) {
      $definitions = \Drupal::service('plugin.manager.search')->getDefinitions();
      // If definitions has "user_search" provider, we should show user for admin users.
      if (in_array('user_search', array_keys($definitions))) {
        $definitions['user_search_admin'] = [
          'id' => 'user_search_admin',
          'provider' => 'user_admin',
        ];
      }
      foreach ($definitions as $definition) {
        $search_provider = $definition['provider'];
        // Generate fake search results.
        for ($i = 0; $i < 5; $i++) {
          if ($search_provider == 'node') {
            $title = $this->t('Node Search, results');
            $result = $this->searchNodePrepare($i);
          }
          else {
            if ($search_provider == 'user_admin') {
              $title = $this->t('User Search, results (Has permission "administer users")');
              $result = [
                'title' => $this->generator->words(1) . " (user_{$i}@email.com)",
              ];
            }
            else {
              $title = $this->t('@type Search, results', ['@type' => ucfirst($search_provider)]);
              $result = [
                'title' => $this->generator->words(1),
              ];
            }
          }

          $results[$i] = [
            '#theme' => 'search_result',
            '#result' => [
              'link' => '#',
            ],
            '#plugin_id' => $definition['id'],
          ];
          $results[$i]['#result'] = array_merge($results[$i]['#result'], $result);
        }

        // Attach fake search results to the items.
        $items["{$definition['id']}_search_results"] = [
          'title' => $title,
          'content' => [
            '#theme' => 'item_list__search_results',
            '#items' => $results,
            '#empty' => [
              '#markup' => '<h3>' . $this->t('Your search yielded no results.') . '</h3>',
            ],
            '#list_type' => 'ol',
            '#context' => [
              'plugin' => $definition['id'],
            ],
          ],
          'group' => $this->t('Search')
        ];
      }
    }
  }

  /**
   * Helper method to prepare a fake node for the search.
   *
   * @param $i
   * @return array
   */
  private function searchNodePrepare($i) {
    // Render a fake node.
    $node = Node::create([
      'type' => 'article',
      'title' => "Title {$i}",
      'body' => $this->generator->words(20),
      'in_preview' => TRUE,
    ]);
    $node_render = $this->entityManager->getViewBuilder('node');
    $build = $node_render->view($node, 'search_result');
    unset($build['#theme']);
    $rendered = $this->renderer->renderPlain($build);
    return [
      'title' => $node->label(),
      'node' => $node,
      'extra' => $this->moduleHandler->invokeAll('node_search_result', [$node]),
      'snippet' => search_excerpt('title', $rendered),
      'date' => time(),
      'user' => [
        '#theme' => 'username',
        '#account' => $this->currentUser,
      ],
    ];
  }

}
