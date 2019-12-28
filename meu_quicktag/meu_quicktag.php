<?
/*
Plugin Name: Meu QuickTag
Plugin URI: http://exemplo.com
Description: Plugin desenvolvido para exibir as minhas redes sociais
Version: 1.0
Author: Gabriel Santos
Author URI: http://meusite.com.br
Text Domain: meu-quicktag
License: GPL2
*/

class Meu_quicktag {
  private static $instance;

  public static function getInstance() {
    if (self::$instance == NULL) {
      self::$instance = new self();
    }

    return self::$instance;
  }

  private function __construct() {
	add_action('admin_print_footer_scripts', array($this,'my_quicktag'));
  }
  public function my_quicktag(){
	  if(wp_script_is('quicktags')){
		  ?>
			<script type="text/javascript">
				//função para recuperar o texto selecionado
				function getSel(){
						var txtarea = document.getElementById("content");
						var start = txtarea.selectionStart;
						var finish = txtarea.selectionEnd;
						return txtarea.value.substring(start,finish);
				}
				QTags.addButton('btn_personalizado', 'Short Code Twitter', get_t);
				function get_t(){
					var selected_text = getSel();
					QTags.insertContent('[twitter]');
				}
			</script>
		  <?php
	  }
  }
}

Meu_quicktag::getInstance();