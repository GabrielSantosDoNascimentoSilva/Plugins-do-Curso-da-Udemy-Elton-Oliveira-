<?php
/*
Plugin Name: Altera Rodapé
Plugin URI: https://github.com
Description: Este plugin altera o rodapé do Blog
Version: 1.0
Author: Gabriel Santos do N.
Author URI: http://gabrielsantosdon.com.br
Text Domain: altera_rodape
Domain Path: /languages
*/

function meu_plugin_altera_rodape(){
	echo "Meu Primeiro Plugin - Gabriel Santos";
}
add_action('wp_footer','meu_plugin_altera_rodape'); 

add_action('init', 'my_user_check');
function my_user_check(){
	if ( is_user_logged_in() ){
		/*echo "<script> alert(1) </script>";*/ 
	}
}

add_filter( 'the_title', 'my_filteres_title', 10, 2 );
function my_filteres_title( $value, $id ){
		$value = '[' . $value . ']';
		return $value;
}