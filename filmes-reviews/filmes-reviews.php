<?php
/*
Plugin Name: Filme Reviews
Plugin URI: https://github.com
Description: Plugin para reviews de filmes
Version: 1.0
Author: Gabriel Santos do N.
Author URI: http://gabrielsantosdon.com.br
Text Domain: filme_reviews
Domain Path: /languages
*/

require dirname(__FILE__).'/lib/class-tgm-plugin-activation.php';

class Filmes_reviews{
	private static $instance;
	const TEXT_DOMAIN = "filmes-review";
	const FIELD_PREFIX = 'fr_';
	
	public static function getInstace(){
		if (self::$instance == NULL){
				self::$instance = new self();
		}	
		return self::$instance;
	}
	private function __construct(){
		add_action('init', 'Filmes_reviews::register_post_type');
		add_action('init', 'Filmes_reviews::register_taxonomies');
		add_action('tgmpa_register', array($this, 'check_required_plugins'));
		add_filter( 'rwmb_meta_boxes', array($this,'metabox_custom_fields' ));
		
		/* Template Customizado */
		add_action('template_include', array($this, 'add_cpt_template'));
		add_action('wp_enqueue_scripts', array($this, 'add_style_scripts'));
	}
	/* Registrar Post Type Filmes Reviews */
	public static function register_post_type(){
		register_post_type('filmes_reviews', array(
			'labels' => array(
				'name'	 => 'Filmes Reviews',
				'singular_name' => 'Filmes Review',
			),
			'description' => 'Post para cadastro de reviews',
			'supports' => array(
				'title', 'editor', 'excertpt', 'author', 'revisions', 'thumbnail', 'custom-fields',
			),
			'public' => TRUE,
			'menu_icon' => 'dashicons-format-video',
			'menu_position' => 3,
		));
	}
	
	/* Registrar Taxinomia Filmes Tipos */
	public static function register_taxonomies(){
		register_taxonomy('tipos_filmes', array('filmes_reviews'),
			array(
				'labels' => array(
					'name' => __('Filmes Tipos'),
					'singular_name' => __('Filme Tipo'),
				),
				'public' => TRUE,
				'hierarchical' => TRUE,
				'rewrite' => array('slug' => 'tipos-filmes'),
			),
		);
	}
	
	/* Checar plugins requeridos */
	function check_required_plugins(){
		$plugins = array(
			array(
				'name' => 'Meta Box',
				'slug' => 'meta-box',
				'required' => true,
				'force_activation' => false,
				'force_deactivation' => false,
			),
		);
		
		/*Config*/
      $config  = array(
      'domain'           => 'filmes-reviews',
      'default_path'     => '',
      'parent_slug'      => 'plugins.php',
      'capability'       => 'update_plugins',
      'menu'             => 'install-required-plugins',
      'has_notices'      => true,
      'is_automatic'     => false,
      'message'          => '',
      'strings'          => array(
        'page_title'                      => __( 'Instalar plugins requeridos', 'filmes-reviews' ),
        'menu_title'                      => __( 'Instalar Plugins', 'filmes-reviews'),
        'installing'                      => __( 'Instalando Plugin: %s', 'filmes-reviews'),
        'oops'                            => __( 'Algo deu errado com a API do plug-in.', 'filmes-reviews' ),
        'notice_can_install_required'     => _n_noop( 'O Comentário do plugin Filmes Reviews depende do seguinte plugin:%1$s.', 'Os Comentários do plugin Filmes Reviews depende dos seguintes plugins:%1$s.' ),
        'notice_can_install_recommended'  => _n_noop( 'O plugin Filmes review recomenda o seguinte plugin: %1$s.', 'O plugin Filmes review recomenda os seguintes plugins: %1$s.' ),
        'notice_cannot_install'           => _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.' ),
        'notice_can_activate_required'    => _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.' ),
        'notice_can_activate_recommended' => _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.' ),
        'notice_cannot_activate'          => _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.' ),
        'notice_ask_to_update'            => _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.' ),
        'notice_cannot_update'            => _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.' ),
        'install_link'                    => _n_noop( 'Comece a instalação de plug-in', 'Comece a instalação dos plugins' ),
        'activate_link'                   => _n_noop( 'Ativar o plugin instalado', 'Ativar os plugins instalados' ),
        'return'                          => __( 'Voltar parapara os plugins requeridos instalados', 'filmes-reviews' ),
        'plugin_activated'                => __( 'Plugin ativado com sucesso.', 'filmes-reviews' ),
        'complete'                        => __( 'Todos os plugins instalados e ativados com sucesso. %s', 'filmes-reviews' ),
        'nag_type'                        => 'updated',
      )
    );
    tgmpa( $plugins, $config );
    /*Fim Config*/  
	}
	
	/* METABOX */
	public function metabox_custom_fields(){
		$meta_boxes[] = array(
			'id' 			=> 'data_filme',
			'title'      	=> __('Informações Adicionais', 'filmes-reviews'),
			'pages' 		=> array('filmes_reviews','post'),
			'context' 		=> 'normal',
			'priority' 		=> 'high',
			'fields' 		=> array(
				array(
					'name'  => __('Ano de Lançamento','filmes-reviews'),
					'desc'  => __('Ano que o filme foi lançado','filmes-reviews'),
					'id'    => self::FIELD_PREFIX.'filme_ano',
					'type'  => 'number',
					'std' 	=> date('Y'),
					'min'	=> '1880',
				),
				array(
					'name'  => __('Diretor','filmes-reviews'),
					'desc'  => __('Quem dirigiu o filme','filmes-reviews'),
					'id'    => self::FIELD_PREFIX.'filme_diretor',
					'type'  => 'text',
					'std' 	=> '',
				),
				array(
					'name'  => __('Site','filmes-reviews'),
					'desc'  => __('Link do site do filme','filmes-reviews'),
					'id'    => self::FIELD_PREFIX.'filme_site',
					'type'  => 'url',
					'std' 	=> '',
				),
			),
		);
		$meta_boxes[] = array(
			'id' 			=> 'review_data',
			'title'      	=> __('Filme Review', 'filmes-reviews'),
			'pages' 		=> array('filmes_reviews'),
			'context' 		=> 'side',
			'priority' 		=> 'high',
			'fields' 		=> array(
				array(
					'name'  => __('Rating','filmes-reviews'),
					'desc'  => __('Em Uma escala de 1 - 10, sendo que 10 é a melhor nota','filmes-reviews'),
					'id'    => self::FIELD_PREFIX.'review_rating',
					'type'  => 'select',
					'options' => array(
						'' => __('Avalie Aqui', 'filmes-review'),
						1 => __('1 - Gostei um pouco', 'filmes-review'),
						2 => __('2 - Eu gostei mais ou menos', 'filmes-review'),
						3 => __('3 - Não Recomendo', 'filmes-review'),
						4 => __('4 - Deu pra assistir tudo', 'filmes-review'),
						5 => __('5 - Filme decente', 'filmes-review'),
						6 => __('6 - Filme legal', 'filmes-review'),
						7 => __('7 - Legal, recomendo', 'filmes-review'),
						8 => __('8 - O meu favorito', 'filmes-review'),
						9 => __('9 - Amei um dos melhores filmes', 'filmes-review'),
						10 => __('10 - O melhor filme de todos os tempos, recomendo !!!', 'filmes-review'),
					),
					'std' => '',
				),
			),
		);
		return $meta_boxes;
	}
	
	function add_cpt_template($template){
		if(is_singular('filmes_reviews')){
			if(file_exists(get_stylesheet_directory().'single-filme-review.php')){
				return get_stylesheet_directory().'single-filme-review.php';
			}
			return plugin_dir_path(__FILE__).'single-filme-review.php';
		}
		return $template;
	}
	
	function add_style_scripts(){
		wp_enqueue_style('filme-review-style', plugin_dir_url(__FILE__).'filme-review.css');
	}
	
	/* Função que ativa a classe Filmes_reviews */
	public static function activate(){
		self::register_post_type();
		self::register_taxonomies();
		flush_rewrite_rules();
	}
}
Filmes_reviews::getInstace();

register_deactivation_hook( __FILE__, 'flush_rewrite_rules' );
register_activation_hook( __FILE__, 'Filmes_reviews::activate' );
