<?php
/*
Plugin Name: Media Unzip
Plugin URI: http://exemplo.com
Description: Plugin desenvolvido para exibir as minhas redes sociais
Version: 1.0
Author: Gabriel Santos
Author URI: http://meusite.com.br
Text Domain: media-unzip
License: GPL2
*/

class Media_unzip {
  private static $instance;

  public static function getInstance() {
    if (self::$instance == NULL) {
      self::$instance = new self();
    }

    return self::$instance;
  }

  private function __construct() {
   add_action('admin_menu', array($this, 'start_media_file_unzip'));
  }
  public function start_media_file_unzip(){
	  add_menu_page('Upload Media Zip', 'Upload Media Zip', 'manage_options', 'upload_media_zips', 'Media_unzip::upload_media_zips', 'dashicons-media-archive',10);
  }
  
  /* Tipos de imagens permitias */
  public function allowed_file_types($filetype){
	  $allowed_file_types = array('image/png', 'image/jpeg', 'image/gif', );
	  
	  if(in_array($filetype, $allowed_file_types)){
		  return true;  
	  }else{
		  return false;
	  }
  }
  public function upload_media_zips(){
	  _e('<h3>Upload de Arquivos ZIP</h3>', 'media-unzip');
	  
	  if(isset($_FILES['fileToUpload'])){
		  // Preparar os arquivos para serem enviados para o servidor 
		  
		  //Obter o diretório de upload atual
		  
		  $dir = "../wp-content/uploads".wp_upload_dir()['subdir'];
		  
		  //Usar o PHP para carregar o arquivo zip para o diretório de upload_media_zips
		  $target_file = $dir.'/'.basename($_FILES['fileToUpload']['name']);
		  move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $target_file);
		  $file_name - basename($_FILES['fileToUpload']['name']);
		  
		  // Criar a instancia de um objeto utilitário
		  
		  $zip = new ZipArchive();
		  
		  //Abrir o arquivo zip
		  
		  $res = $zip->open($target_file);
		  
		  if($res === TRUE){
			 $zip->extractTo($dir);
			 
			 echo "<h3 style='color:#090;'>O arquivo zip $file_name foi descompactado com êxito ".wp_upload_dir()['url']."</h3>";
			 
			 //Exibir uma mensagem com o número de arquivos de mídia no arquivo zip
			 echo "Tem ".$zip->numFiles." Arquivos neste arquivo zip <br />";
			 for( $i = 0; $i < $zip->numFiles; $i++){
				 //Obter URL do arquivo de mídia
				 $media_file_name = wp_upload_dir()['url'].'/'.$zip->getNameIndex($i);
				 
				 //Obter o tipo de arquivo de mídia
				 $filetype = wp_check_filetype(basename($media_file_name), null);
				 $allowed = Media_unzip::allowed_file_types($filetype['type']);
				 
				 if($allowed){
					//Exibir um link para o usuário ver o arquivo de upload..
					echo '<a href ="'. $media_file_name.'" target="_blank">'.$media_file_name.'</a>
						Tipos: '.$filetype['type'].'<br>';
						
					//Informações dos anexos que será utilizado pela biblioteca de mídia
					$attachment = array(
					  'guid' => $media_file_name,
					  'post_mime_type' => $filetype['type'],
					  'post_title' => preg_replace('/\.[^.]+$/','',$zip->getNameIndex($i)),
					  'post_content' => '',
					  'post_status' => 'inherit',
					);
					
					$attach_id = wp_insert_attachment($attachment, $dir.'/'.$zip->getNameIndex($i));
					
					//Metadados para o anexos
					$attach_data = wp_generate_attachment_metadata($attach_id, $dir.'/'.$zip->getNameIndex($i));
					wp_update_attachment_metadata($attach_id, $attach_data);
				 }else{
					 echo $zip->getNameIndex($i).'Não pode ser enviado, o tipo '.$filetype['type'].'não é permitido <br>';
				 }
			 }
		  }else{
			  echo "<h3 style='color:#f00;'>O arquivo não foi descompactado com êxito </h3>";
		  }
		  $zip->close();
	  }
	  
	  echo '
		<form style="margin-top:20px;" action="/wp-admin/admin.php?page=upload_media_zips" enctype="multipart/form-data" method="post">
		Selecione o Arquivo zip <input type="file" name="fileToUpload" id="fileToUpload">
		<br />
		<input type="submit" value="Upload de arquivo ZIP" name="submit">
		</form>
	  ';
  }
}

Media_unzip::getInstance();