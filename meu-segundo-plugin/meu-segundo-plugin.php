<?php
/*
Plugin Name: Personalizar Painel
Plugin URI: https://github.com
Description: Plugin desenvolvido para personalizar painel
Version: 1.0
Author: Gabriel Santos do N.
Author URI: http://gabrielsantosdon.com.br
Text Domain: meu-segundo-plugin
Domain Path: /languages
*/
class Segundo_Plugin{
	
	private static $instance;
	const TEXT_DOMAIN = "meu-segundo-plugin";
	
	public static function getInstance(){
		
		if(self::$instance == NULL){
				self::$instance = new self();
		}
	}
	private function __construct(){
		// Desativar a action welcome_panel
		remove_action('welcome_panel', 'wp_welcome_panel');
		add_action('welcome_panel', array($this,'welcome_panel'));
		add_action('admin_enqueue_scripts', array($this, 'add_css'));
		add_action('init', array($this,'meusegundoplugin_load_textdomain')); 
	}	
	function meusegundoplugin_load_textdomain(){
		load_plugin_textdomain(self::TEXT_DOMAIN, false,dirname(plugin_basename(__FILE__)));
	}
	function welcome_panel(){
		?>
			<div class="welcome-panel-content">
				<h3><? __('Seja Bem Vindo ao Painel Administrativo', 'meu-segundo-plugin'); ?> </h3>
				<p><? __('Siga-nos nas Redes Sociais',  'meu-segundo-plugin'); ?></p>
				<p><? __('Olá', 'meu-segundo-plugin') ?></p>
				<div id="icons">
					<a href="#" target="_blank">
						<img src="http://gabrielsantosdon.com.br/wp-content/uploads/2019/11/1474968161-youtube-circle-color.png" />
					</a>
					<a href="#" target="_blank">
						<img src="http://gabrielsantosdon.com.br/wp-content/uploads/2019/11/1474968150-facebook-circle-color.png" />
					</a>
				</div>
			</div>
		<?php
	}
	function add_css(){
		wp_register_style('meu-segundo-plugin', plugin_dir_url(__FILE__). 'css/meu-segundo-plugin.css');
		wp_enqueue_style('meu-segundo-plugin');
	}
}

Segundo_Plugin::getInstance();


