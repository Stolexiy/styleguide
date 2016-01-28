<?php

/**
 * @file
 * Contains \Drupal\styleguide\Generator.
 */

namespace Drupal\styleguide;

use Drupal\Core\Url;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class Generator.
 *
 * @package Drupal\styleguide
 */
class Generator implements GeneratorInterface {

  /**
   * The current request.
   *
   * @var \Symfony\Component\HttpFoundation\Request
   */
  protected $currentRequest;

  /**
   * Constructor.
   */
  public function __construct(RequestStack $request_stack) {
    $this->currentRequest = $request_stack->getCurrentRequest();
  }

  /**
   * {@inheritdoc}
   */
  public function wordList($size = 5, $words = 3) {
    $items = array();
    for ($i = 0; $i < $size; $i++) {
      $items[] = $this->words($words, 'ucfirst');
    }
    return $items;
  }

  /**
   * {@inheritdoc}
   */
  public function words($size = 1, $case = 'strtolower') {
    $words = $this->lorem(1, $size, 'lower', FALSE, FALSE);
    $functions = array('ucfirst', 'ucwords', 'strtoupper', 'strtolower');
    if (!is_null($case) && function_exists($case) && in_array($case, $functions)) {
      $words = $case($words);
    }
    return $words;
  }

  /**
   * {@inheritdoc}
   */
  public function tableHeader($size = 5) {
    $header = $this->wordList($size);
    return $header;
  }

  /**
   * {@inheritdoc}
   */
  public function tableRows($size = 5) {
    $rows = array();
    for ($i = 0; $i < $size; $i++) {
      $rows[] = $this->wordList($size);
    }
    return $rows;
  }

  /**
   * {@inheritdoc}
   */
  public function lorem($size = 5, $words = 0, $case = 'mixed', $returns = TRUE, $punctuation = TRUE, $array = FALSE) {
    $text = <<<EOT
Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam iaculis, velit gravida convallis tincidunt, felis enim venenatis lorem, nec lobortis nisl urna et mi. Pellentesque ac dictum ante. Fusce dignissim tempor elementum. Ut dignissim convallis eros, viverra luctus lacus consequat ac. Sed feugiat velit sed magna aliquam accumsan. Nam vitae porta tortor. Nam auctor dui a neque iaculis in aliquam erat viverra. Duis orci nunc, lacinia in malesuada et, euismod id turpis. Cras mattis vulputate erat, eget tempor magna egestas eu. Vestibulum sit amet massa est.

Vivamus pretium placerat lorem, in tempor massa convallis sit amet. Aliquam sed quam eget ligula luctus aliquam sed vitae nulla. Aliquam dui dolor, ullamcorper eget rutrum ut, hendrerit ac lorem. Donec magna est, sollicitudin vel ultrices vel, mattis ut odio. Integer vel felis laoreet purus sollicitudin varius sed id ipsum. Suspendisse potenti. Praesent ut justo vitae metus luctus vehicula a et purus. Suspendisse potenti. Sed viverra, quam non hendrerit laoreet, massa odio blandit arcu, ac molestie metus diam eu tortor. Donec erat arcu, ultrices sit amet placerat non, feugiat in arcu. Mauris eros quam, varius eget volutpat vel, tristique sed est. In faucibus feugiat urna sit amet elementum. Integer consequat rhoncus libero, in molestie augue posuere et. Phasellus ac eleifend magna. Proin vulputate dui ac justo pharetra consequat. In vel iaculis ligula.

Cras vestibulum lacus sit amet sem commodo ullamcorper aliquet eros vestibulum. Sed fermentum nulla quis risus suscipit dapibus. Sed vitae velit ut dolor varius semper at id lectus. Aenean quis leo sit amet tellus tempus cursus. Vivamus semper vehicula ante eget semper. In ac ipsum erat. Suspendisse lectus erat, commodo nec fringilla quis, interdum non leo. Vivamus et lectus vitae risus porta sollicitudin luctus eget est. Etiam quis elit vel est suscipit tristique. Nullam fringilla purus ac velit gravida ullamcorper. Praesent porttitor ante non lacus suscipit porta. Nunc fermentum sem et metus aliquam ultricies non sollicitudin nibh. Vestibulum ut ligula dolor, in placerat tortor. Sed nec lacus sed nibh iaculis luctus. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Curabitur rutrum, diam vel tempor commodo, augue nunc viverra risus, in pellentesque neque justo eget dolor. Maecenas quis odio leo, a auctor lorem.

Curabitur dapibus odio quis enim hendrerit eu placerat lorem accumsan. Phasellus sagittis, orci vel laoreet molestie, urna orci imperdiet elit, quis ultricies orci mauris vel ante. Cras pharetra, nisl a sagittis feugiat, turpis magna placerat sem, sed euismod erat elit in magna. Phasellus blandit ullamcorper diam vel porta. Vivamus mollis, metus nec tincidunt venenatis, risus odio sodales risus, vitae ultrices est nisi eget ante. Aenean eget nisi mi. Nulla non nulla nec metus rhoncus congue. Curabitur quis nunc nibh. Cras metus lorem, euismod ornare mattis sagittis, ultrices eget turpis. Integer quis dui tellus. Morbi vel dolor sit amet metus eleifend fringilla. Fusce nunc neque, ultricies et commodo semper, dignissim vitae tortor. Phasellus et ipsum quis sapien accumsan auctor. Morbi congue nulla vel tortor aliquet imperdiet. Morbi eget odio elit, et cursus odio. Quisque a velit diam. Duis urna libero, tempus non mattis a, convallis ac erat. Etiam vel dui posuere lectus auctor viverra vitae id eros. Maecenas mollis eros non elit sollicitudin quis fermentum diam lacinia. Quisque at ante nibh, a molestie ligula.

Sed et enim nunc, nec vehicula sem. Sed risus orci, auctor et dictum at, hendrerit eu augue. Curabitur sed ante non quam fermentum vehicula. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam tincidunt dictum molestie. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Phasellus nec urna ut lorem tempus aliquet eget nec lectus. Phasellus quis venenatis tortor. Integer elementum, sapien at feugiat cursus, tortor sapien adipiscing massa, non molestie elit lacus vel velit. Suspendisse sit amet sem id libero auctor pharetra sit amet ut dui. Aenean sit amet tellus sit amet ante congue faucibus. Nullam hendrerit, justo et iaculis tristique, ligula risus pretium erat, sed tempus lacus felis ut nulla.
EOT;
    if (!$punctuation) {
      $text = str_replace(array(',', '.'), '', $text);
    }
    switch ($case) {
      case 'mixed':
        break;
      case 'upper':
        $text = strtoupper($text);
        break;
      case 'lower':
        $text = strtolower($text);
        break;
    }
    $graphs = explode("\n\n", $text);
    $text = array_slice($graphs, 0, $size);
    $spacer = ' ';
    if ($returns) {
      $spacer = "\n\n";
    }
    if ($words > 0) {
      $elements = explode(' ', implode(' ', $text));
      $output = array();
      for ($i = 0; $i < $words; $i++) {
        $val = array_rand($elements);
        $output[] = $elements[$val];
      }
      return implode(' ', $output);
    }
    if (!$array) {
      return implode($spacer, $text);
    }
    return $text;
  }

  /**
   * {@inheritdoc}
   */
  public function paragraphs($size = 5) {
    $text = $this->lorem($size, 0, 'mixed', TRUE, TRUE, TRUE);
    $output = '';
    foreach ($text as $item) {
      $output .= '<p>' . trim($item) . '</p>';
    }
    return $output;
  }

  /**
   * {@inheritdoc}
   */
  public function image($image = 'vertical', $type = 'jpg') {
    $path = drupal_get_path('module', 'styleguide');
    $filepath = $path . '/assets/image-' . $image . '.' . $type;
    if (file_exists($filepath)) {
      return $filepath;
    }
  }

  /**
   * {@inheritdoc}
   */
  public function sentence($link = FALSE) {
    $graph = strip_tags($this->paragraphs());
    $explode = explode('.', $graph);
    $rand = array_rand($explode);
    $sentence = trim($explode[$rand]);
    if ($link) {
      $explode = explode(' ', $sentence);
      $link = array(
        '#theme' => 'link',
        '#text' => $explode[0],
        '#path' => $link,
        '#options' => array(
          'attributes' => array(),
          'html' => FALSE,
        ),
      );
      $explode[0] = render($link);
      $sentence = implode(' ', $explode);
    }
    return  $sentence . '.';
  }

  /**
   * {@inheritdoc}
   */
  public function pager($size = 8, $total = 20) {
    pager_default_initialize($total, $size);
    $pager = ['#type' => 'pager'];
    return render($pager);
  }

  /**
   * {@inheritdoc}
   */
  public function links($url, $size = 4) {
    $links = array();
    for ($i = 0; $i < 5; $i++) {
      $links[] = array(
        'title' => $this->words(3),
        'href' => $url,
      );
    }
    return $links;
  }

  /**
   * {@inheritdoc}
   */
  public function menuItem($url) {
    $menu_item = array(
      '#type' => 'link',
      '#title' => $this->sentence(),
      '#url' => Url::fromUserInput($url),
    );
    return $menu_item;
  }

  /**
   * {@inheritdoc}
   */
  public function ulLinks() {
    $links = array();

    for ($i = 0; $i <= 10; $i++) {
      $word = $this->words();
      $links[$word] = array(
        'title' => $word,
        'url' => Url::fromUserInput($this->currentRequest->getRequestUri()),
        'fragment' => 'ul_links',
      );
    }

    return $links;
  }

}
