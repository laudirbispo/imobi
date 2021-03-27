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
  if( $_SESSION['gallery_edit'] !== '1')
  {
      $response = array(
        'status'  => 'error',
        'message' => 'Você não tem permissão para realizar está ação.',
        'link'    => '',
      );
     die(json_encode($response));
  }
}
//*********************************************************************

if( empty($_POST['id-image']) or !isset($_POST['id-image']) )
{
    $response = array(
        'status'  => 'error',
        'message' => 'Não conseguimos identificar a imagem para completar está ação.',
        'link'    => '',
      );
     die(json_encode($response));
}
else
{
    $id_image = filterString($_POST['id-image'], 'INT');
}

if( !isset($_POST['legenda']) )
{
    $response = array(
        'status'  => 'warning',
        'message' => 'Diga algo sobre está imagem.',
        'link'    => '',
      );
     die(json_encode($response));
}
else
{
    $legenda = filterString($_POST['legenda'], 'CHAR');
}

$con_db = new config\connect_db();
$con = $con_db->connect();

$update_legenda = $con->prepare("UPDATE `images_gallery` SET `legenda` = ? WHERE `id` = ? ");
$update_legenda->bind_param('si',$legenda, $id_image);
$update_legenda->execute();
$update_legenda->store_result();
$rows = $update_legenda->affected_rows;
$update_legenda->free_result();
$update_legenda->close();

if( !$update_legenda )
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
    $response = array(
        'status'  => 'success',
        'message' => 'Atualizada',
        'link'    => '',
      );
     die(json_encode($response));
}