<?php
/*
Plugin Name: Meu Twitter
Plugin URI: http://exemplo.com
Description: Plugin desenvolvido para cadastro do twitter
Version: 1.0
Author: Elton Oliveira
Author URI: http://meusite.com.br
Text Domain: meu-twitter
License: GPL2
*/

if(!defined('ABSPATH')) header("Location:http://gabrielsantosdon.com.br");

class Meu_twitter {
  private static $instance;

  public static function getInstance() {
    if (self::$instance == NULL) {
      self::$instance = new self();
    }

    return self::$instance;
  }

  private function __construct() {
   
	add_action('admin_menu',array($this,set_custom_fields));
   add_shortcode('twitter', array($this, 'twitter'));
  }
  public function set_custom_fields(){
	  add_menu_page('Meu Twitter','Meu Twitter','manage_options','meu_twitter','Meu_twitter::save_custom_fields', 'dashicons-twitter','10');
  }
  public function save_custom_fields(){
	echo '<h3>'.__("Cadastro do Twitter", "meu-twitter").'</h3>';
	echo "<form method='post'>";
	
	$campos = array('twitter');
	
	foreach($campos as $campo):
		
		if(isset($_POST[$campo]))
		  update_option($campo, $_POST[$campo]);
		
		$valor = stripcslashes(get_option($campo));
		$label = ucwords(strtolower($campo));
		
		echo "
			<p>
				<label $label </label><br>
				<textarea cols='100' rows='10' name='$campo'> $valor </textarea>
			</p>
		";
		
		
	endforeach;
	$nomeBotao = (get_option('twitter') !== '') ? "Editar" : "Cadastrar";
	echo "<input type='submit' value='".$nomeBotao."'/>";
	echo "</form>";
  }
  public function twitter( $params = null ){
	  return stripslashes(get_option('twitter'));
  }

}

Meu_twitter::getInstance();