<?php
session_name(SESSION_NAME);
session_start();
header('Content-Type: application/json');
require_once ($_SERVER['DOCUMENT_ROOT'].'/config/autoload.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/config/config.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/config/public_functions.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/app/controls/adminFunctions.php');

use config\connect_db;

if( ($_SESSION['user_master_perms'] !== 'administrador') )
{
  if( $_SESSION['gallery_create'] !== '1')
  {
      $response = array(
        'status'  => 'error',
        'message' => 'Você não tem permissão para realizar está ação.',
        'link'    => '',
      );
     die(json_encode($response));
  }
}

if( empty($_POST['form-token']) or !isset($_POST['form-token']) )
{
     $response = array(
        'status'  => 'error',
        'message' => 'A origem de alguns dados nos parece duvidosa! Por isso bloqueamos está ação.',
        'link'    => '',
      );
     die(json_encode($response));
}

if( empty($_POST['user_id']) or !isset($_POST['user_id']) or (int)$_POST['user_id'] !== (int)$_SESSION['user_id'] )
{
    $response = array(
        'status'  => 'error',
        'message' => 'É necessário estar logado para completar está ação.',
        'link'    => '',
     );
     die(json_encode($response));
}
else
{
  $user_id = filterString($_POST['user_id'], 'INT');
}
//****************************************************************************

if( empty($_POST['album-nome']) or !isset($_POST['album-nome']) )
{
    $response = array(
        'status'  => 'warning',
        'message' => 'Um nome para o álbum é requerido!',
        'link'    => '',
     );
     die(json_encode($response));
}
else
{
    $album_name = filterString($_POST['album-nome'], 'CHAR');
}

$con_db = new connect_db();
$con = $con_db->connect(); 

$exists_album = $con->query("SELECT `name` FROM `albuns` WHERE `name` = '$album_name' ");
$rows_albuns = $exists_album->num_rows;
$exists_album->close();

if( $exists_album and $rows_albuns > 0 )
{
      $response = array(
        'status'  => 'warning',
        'message' => 'Já existe um álbum com esse nome! Tente outro.',
        'link'    => '',
     );
     die(json_encode($response));
      
}
else if( $exists_album and $rows_albuns <= 0 )
{
    $user_post = $_SESSION['user_id'] ;
    $dir_name = utf8_decode($album_name);
    $dir_name = str_replace(" ", "-", $album_name);    
    $dir_album = $_SERVER['DOCUMENT_ROOT'].'/docs/gallery/'.$dir_name.'/';
  
    $new_album = $con->prepare(" INSERT INTO `albuns` (`name`, `dir`, `date_create`, `user_post`) VALUES (?, ?, ?, ?) ");
    $new_album->bind_param('sssi', $album_name, $dir_name, $date_time, $user_post); 
    $new_album->execute();
    $affected_rows = $new_album->affected_rows;
    $new_album->close();
  
    if( !$new_album or $affected_rows <= 0 )
    {
        $response = array(
            'status'  => 'error',
            'message' => 'O servidor não está respondendo.',
            'link'    => '',
         );
         die(json_encode($response));
    }
    else 
    {
        if(!file_exists($dir_album))
        {
            mkdir($dir_album, 0777, true);
        }
      
        $response = array(
            'status'  => 'success',
            'message' => 'O álbum foi criado',
            'link'    => '',
        );
        die(json_encode($response));
    }

}
else
{
      $response = array(
          'status'  => 'error',
          'message' => 'O servidor não está respondendo.',
          'link'    => '',
       );
       die(json_encode($response));
}