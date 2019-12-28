<?php
/*
Plugin Name: Newsletter Curso
Plugin URI: https://github.com
Version: 1.0
Author: Gabriel Santos do N.
Author URI: http://gabrielsantosdon.com.br
Text Domain: newsletter-curso
Domain Path: /languages
*/

if(!defined('ABSPATH')){
	exit;
}

//Load Scripts
require_once(plugin_dir_path(__FILE__).'includes/newsletter-scripts.php');

//Load Class
require_once(plugin_dir_path(__FILE__).'includes/newsletter-curso-class.php');

//Register Widget
function register_newsletter_curso(){
	register_widget('Newsletter_Curso_Widget');
}
add_action('widgets_init', 'register_newsletter_curso');