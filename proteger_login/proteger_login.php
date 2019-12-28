<?php 
/*
Plugin Name: Proteger Login
Plugin URI: http://exemplo.com
Description: Plugin desenvolvido para proteger telade login do administrador
Version: 1.0
Author: Gabriel Santos
Author URI: https://gabrielsantosdon.com.br
Text Domain: proteger-login
License: GPL2
*/
if(!defined('ABSPATH'))exit;
class Proteger_login {
  private static $instance;

  public static function getInstance() {
    if (self::$instance == NULL) {
      self::$instance = new self();
    }

    return self::$instance;
  }

  private function __construct() {
	add_action('login_form_login', array($this, 'pt_login'));
  }
  
  public function pt_login(){
	if($_SERVER['SCRIPT_NAME'] == '/wp-login.php'){
		// echo "<script> alert(1); </script>"; 
		
		$minuto = Date('i');
		if(!isset($_GET['empresa'.$minuto])){
			header('Location:http://gabrielsantosdon.com.br/');
		}
	}
  }
}

Proteger_login::getInstance();