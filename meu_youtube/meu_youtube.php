<?php

/*
Plugin Name: Meu Youtube
Plugin URI: http://exemplo.com
Description: Plugin desenvolvido para exibir botão de inscrição de canal
Version: 1.0
Author: Gabriel Santos
Author URI: http://meusite.com.br
Text Domain: minhas-youtube
License: GPL2
*/

class Meu_youtube {
  private static $instance;

  public static function getInstance() {
    if (self::$instance == NULL) {
      self::$instance = new self();
    }

    return self::$instance;
  }

  private function __construct() {
	add_shortcode('youtube', array($this, 'youtube'));
  }
  public function youtube($parametros){
	  $a = shortcode_atts(array('canal' => ''), $parametros);
	  $canal = $a['canal'];
	return '
	<script src="https://apis.google.com/js/platform.js"></script>

<div class="g-ytsubscribe" data-channel="'.$canal.'" data-layout="default" data-count="default"></div>';  
  }
}

Meu_youtube::getInstance();