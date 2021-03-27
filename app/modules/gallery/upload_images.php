<?php
session_name(SESSION_NAME);
session_start();
header("Access-Control-Allow-Origin: *");
require_once ($_SERVER['DOCUMENT_ROOT'].'/config/autoload.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/config/config.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/config/public_functions.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/libs/WideImage/WideImage.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/app/controls/adminFunctions.php');

use config\connect_db;

if( ($_SESSION['user_master_perms'] !== 'administrador') )
{
    if( $_SESSION['gallery_edit'] !== '1')
    {
        die('<div class="ajax-file-upload-error alert alert-danger"><i class="fa fa-ban"></i> Você nã otem permissões para concluir está ação.</div>');
    }
}

//****************************************************************************

if( empty($_FILES['images']) or !isset($_FILES['images']) )
{
    die('<div class="ajax-file-upload-error alert alert-danger" role="alert">Arquivo não reconhecido ou inexistente!</div>');
}

if( empty($_POST['id_album']) or !isset($_POST['id_album']) )
{
    die('<div class="ajax-file-upload-error alert alert-warning" role="alert"><i CLASS="fa fa-exclamation-triangle"></i> Álbum não identificado! </div><br>');
}
else
{
    $id_album = filterString($_POST['id_album'], 'INT');
}

if( empty($_POST['dir']) or !isset($_POST['dir']) )
{
    die('<div class="ajax-file-upload-error alert alert-warning" role="alert"><i CLASS="fa fa-exclamation-triangle"></i> Diretório não encontrado!</div><br>');
}
else
{
    $dir_album = filterString($_POST['dir'], 'CHAR');
}

$images = $_FILES['images']; 
$count  = sizeof($_FILES['images']['tmp_name']);
$allowed_types = array('jpg', 'png', 'gif', 'jpeg', 'JPG', 'PNG', 'GIF', 'JPEG');

$dir_upload = $_SERVER['DOCUMENT_ROOT'].'/docs/gallery/'.$dir_album.'/';

if( !file_exists($dir_upload) )
{
    mkdir($dir_upload, 0777, true);
}

$images_name    = $images['name'];
$images_type    = $images['type'];
$images_tmpName = $images['tmp_name'];
$images_size    = $images['size'];
$images_ext     = strtolower(pathinfo($images_name, PATHINFO_EXTENSION));

if( $images_size > 0 and mb_strlen($images_name, 'utf8') < 1 ) 
{
    die('<div class="ajax-file-upload-error alert alert-danger" role="alert">'.$images_name.' não parece ser uma imagem!</div><br>');
}
else if ( !in_array($images_ext, $allowed_types) )
{
    die('<div class="ajax-file-upload-error alert alert-danger" role="alert"> A imagem '.$images_name.' possui o formato não permitido! </div><br>');
}
else
{

    $con_db = new config\connect_db();
    $con = $con_db->connect();

    $images_newName = date("Ymdhis").time().mt_rand(0, 999999).'.'.$images_ext;
    $images_destiny = $dir_upload.$images_newName;
    $image = '/docs/gallery/'.$dir_album.'/'.$images_newName;
  
    if( move_uploaded_file($images_tmpName, $images_destiny) ) 
    {
      
        $insert_image = $con->prepare("INSERT INTO `images_gallery` (id_album, image, date_create, dir) VALUES (?, ?, ?, ?) ") ;
        $insert_image->bind_param('isss', $id_album, $image, $date_time, $dir_album);
        $insert_image->execute() ;
        $insert_image->store_result();
        $rows_insert_image = $insert_image->affected_rows;
        $insert_image->free_result();
        $insert_image->close();
    
       die(mysqli_error($con));

       if( $insert_image and $rows_insert_image > 0 )
       {              
      
           $image_wide = new WideImage();
           $image_wide = WideImage::load($images_destiny);
           $image_wide = $image_wide->resize(1920, 1080, 'inside', 'down');
      
           if( $images_ext == 'jpg' or $images_ext == 'jpeg' )
           {
               $image_wide->saveToFile($images_destiny, 70);
           }
           else if( $images_ext == 'png' )
           {
               $image_wide->saveToFile($images_destiny, 9);
           }
           else
           {
               $image_wide->saveToFile($images_destiny);
           }//. if tipo de salvamento
      
           die ('<div class="ajax-file-upload-error alert alert-success" role="alert"> '.$images_name.' upload completo! </div><br>');
      
       }
       else
       {      
           if(file_exists($images_destiny))
           {
              unlink($images_destiny); 
           }
      
         die ('<div class="ajax-file-upload-error alert alert-danger" role="alert">'.$images_name.' não pode ser salva! </div><br>');
      
       }//,if insert---
    
    }//. if upload---
    else
    {
        die('<div class="ajax-file-upload-error alert alert-danger" role="alert"> A imagem '.$images_name.' não pode ser salva no banco de dados ! </div><br>');
    }  
  
}//.if tamanho, name ou type não permitido
