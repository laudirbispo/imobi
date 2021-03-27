<?php
session_name(SESSION_NAME);
session_start();
require_once ($_SERVER['DOCUMENT_ROOT'].'/config/autoload.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/config/config.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/config/public_functions.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/libs/WideImage/WideImage.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/app/controls/adminFunctions.php');

use config\connect_db;
use app\controls\errors;

$errors = new errors();

if( ($_SESSION['user_master_perms'] !== 'administrador') )
{
  if( $_SESSION['vehicles_edit'] !== '1')
  {
    die('<div class="ajax-file-upload-error alert alert-danger"><i class="fa fa-ban"></i> Você nã otem permissões para concluir está ação.</div>');
  }
}

//****************************************************************************

if(empty($_FILES['images']) or !isset($_FILES['images']))
{
  die('<div class="ajax-file-upload-error alert alert-danger" role="alert">Arquivo não reconhecido ou inexistente!</div>');
}

if(empty($_POST['id-motorcycle']) or !isset($_POST['id-motorcycle']))
{
  die($_POST['id-motorcycle']);
}
else
{
  $id_motorcycle = filterString($_POST['id-motorcycle'], 'INT');
}

$images = $_FILES['images']; 
$dir_upload = $_SERVER['DOCUMENT_ROOT'].'/docs/vehicles/motorcycles/'.$id_motorcycle.'/';
$allowed_types = array('jpg', 'png', 'gif', 'jpeg', 'JPG', 'PNG', 'GIF', 'JPEG');
$max_size = 1024*1024*5;

if(!file_exists($dir_upload))
{
  mkdir($dir_upload, 0777, true);
}

$images_name    = $images['name'];
$images_type    = $images['type'];
$images_tmpName = $images['tmp_name'];
$images_size    = $images['size'];
$images_ext     = strtolower(pathinfo($images_name, PATHINFO_EXTENSION));

if($images_size > 0 and mb_strlen($images_name, 'utf8') < 1) 
{
  die('<div class="ajax-file-upload-error alert alert-danger" role="alert">'.$images_name.' não é uma imagem válida!</div>');
}
else if($images_size > $max_size)
{
  die('<div class="ajax-file-upload-error alert alert-danger" role="alert">'.$images_name.' é maior que o tamanho máximo permitido de 5Mb:</div>');
}
else if (!in_array($images_ext, $allowed_types))
{
  die('<div class="ajax-file-upload-error alert alert-danger" role="alert"> A imagem '.$images_name.' possui o formato não permitido! </div><br>');
}
else
{

  $con_db = new config\connect_db();
  $con = $con_db->connect();

  $images_newName = date("Ymdhis").'_'.time().'_'.mt_rand(0, 999999).'.'.$images_ext;
  $images_destiny = $dir_upload.$images_newName;
  $image = '/docs/vehicles/motorcycles/'.$id_motorcycle.'/'.$images_newName;
  
  if(move_uploaded_file($images_tmpName, $images_destiny)) 
  {
      
    $insert_image = $con->prepare("INSERT INTO `images_motorcycles` (image, id_motorcycle, date_create) VALUES (?, ?, ?) ");
    $insert_image->bind_param('sis', $image, $id_motorcycle, $date_time);
    $insert_image->store_result();
    $insert_image->execute() ;
    $rows = $insert_image->affected_rows;
    $insert_image->free_result();
    $insert_image->close();

    if($insert_image and $rows > 0)
    {              
      
        $image_wide = new WideImage();
        $image_wide = WideImage::load($images_destiny);
        $image_wide = $image_wide->resize(800, 600, 'inside', 'down');
        
        if($images_ext === 'jpg' or $images_ext === 'jpeg')
        {
        $image_wide->saveToFile($images_destiny, 70);
        }
        else if($images_ext === 'png')
        {
        $image_wide->saveToFile($images_destiny, 9);
        }
        else
        {
        $image_wide->saveToFile($images_destiny);
        }//. if tipo de salvamento
  
      
    }
    else
    {
      
      if(file_exists($images_destiny))
      {
        unlink($images_destiny); 
      }
      
      die('<div class="ajax-file-upload-error alert alert-danger" role="alert">'.$images_name.' não pode ser salva! </div>');
      
    }//,if insert---
    
  }//. if upload---
  else
  {
     die('<div class="ajax-file-upload-error alert alert-danger" role="alert"> A imagem '.$images_name.' não pode ser salva no banco de dados ! </div>');
  }
    
  
}//.if tamanho, name ou type não permitido
