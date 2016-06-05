<?php

namespace Drupal\styleguide\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Render\BareHtmlPageRendererInterface;
use Drupal\styleguide\GeneratorInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * The Styleguide maintainers page controller.
 */
class StyleguideMaintenancePageController extends ControllerBase {

  /**
   * The bare HTML page renderer.
   *
   * @var \Drupal\Core\Render\BareHtmlPageRendererInterface
   */
  protected $bareHtmlPageRenderer;

  /**
   * The styleguide generator service.
   *
   * @var \Drupal\styleguide\Generator
   */
  protected $generator;

  /**
   * Constructs a new StyleguideMaintenancePageController.
   *
   * @param BareHtmlPageRendererInterface $bare_html_page_renderer
   * @param GeneratorInterface $styleguide_generator
   */
  public function __construct(BareHtmlPageRendererInterface $bare_html_page_renderer, GeneratorInterface $styleguide_generator) {
    $this->bareHtmlPageRenderer = $bare_html_page_renderer;
    $this->generator = $styleguide_generator;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('bare_html_page_renderer'),
      $container->get('styleguide.generator')
    );
  }

  /**
   * Builds Drupal maintenance page.
   *
   * @return array
   *   The rendered HTML response.
   */
  public function page() {
    $content = $this->generator->paragraphs();
    $title = $this->generator->sentence();

    return $this->bareHtmlPageRenderer->renderBarePage($content, $title, 'maintenance_page');
  }

}
