<?php

namespace WpMustache;

class Renderer {

  protected static $_instance = null;

  private $engine;

  public static function getInstance() {
    if (self::$_instance == null) {
      self::$_instance = new self;
    }
    return self::$_instance;
  }

  public static function mapQuery($query, $mapAdditionalFields) {
    $result = array();

    while ( $query->have_posts() ) {
      $query->the_post();

      $data = array(
          'permalink' => get_permalink(),
          'title' => get_field('title'),
          'description' => get_field('description')
      );

      if ($mapAdditionalFields) {
        $mapAdditionalFields($data);
      }

      array_push($result, $data);
    }

    wp_reset_query();

    return $result;
  }

  protected function __construct() {
    $this->engine = new Mustache_Engine(array(
        'loader' => new Mustache_Loader_FilesystemLoader(get_template_directory())
    ));
  }

  public function render($template, $data) {
    echo $this->engine->render($template, $data);
  }
}
