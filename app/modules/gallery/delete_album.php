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
  if( $_SESSION['gallery_delete'] !== '1')
  {
      $response = array(
        'status'  => 'error',
        'message' => 'Você não tem permissão para realizar está ação.',
        'link'    => '',
      );
     die(json_encode($response));
  }
}

//****************************************************************************

if(empty($_GET['id']) or !isset($_GET['id']))
{
    $response = array(
        'status'  => 'warning',
        'message' => 'Não identificamos qual álbum você deseja excluir.',
        'link'    => '',
    );
    die(json_encode($response));
}
else
{
    $id = filterString($_GET['id'], 'INT'); 
}

$con_db = new connect_db();
$con = $con_db->connect(); 

$get_dir = $con->query("SELECT `dir` FROM `albuns` WHERE `id` = '$id' ");
$get_dir_rows = $get_dir->num_rows;

while($reg = $get_dir->fetch_assoc())
{
    $album_dir = $reg['dir'];
}

$get_dir->close();

if( $get_dir and $get_dir_rows > 0)
{
    $patch = $_SERVER['DOCUMENT_ROOT'].'/docs/gallery/'.$album_dir;
}
else
{
    $response = array(
        'status'  => 'warning',
        'message' => 'Diretório do álbum não encontrado.',
        'link'    => '',
      );
     die(json_encode($response));
}


$delete_album = $con->prepare(" DELETE FROM `albuns` WHERE `id` = ? ");
$delete_album->bind_param('i', $id);
$delete_album->execute();
$rows_delete = $delete_album->affected_rows;
$delete_album->close();

if( $delete_album and $rows_delete > 0 )
{
    $delete_imgs = $con->prepare(" DELETE FROM `images_gallery` WHERE `id_album` = ? ");
    $delete_imgs->bind_param('i', $id);
    $delete_imgs->execute();
    $lines_affecteds = $delete_imgs->affected_rows;
    $delete_imgs->close();
    
    if(file_exists($patch))
    {
        unlinkRecursive($patch, true); 
    }
  
    $response = array(
        'status'  => 'success',
        'message' => 'Álbum deletado',
        'link'    => '',
      );
     die(json_encode($response));
  
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
