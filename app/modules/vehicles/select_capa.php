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
  if( $_SESSION['vehicles_edit'] !== '1')
  {
      $response = array(
        'status'  => 'error',
        'message' => 'Você não tem permissão para realizar está ação.',
        'link'    => '',
      );
     die(json_encode($response));
  }
}

if(empty($_GET['categoria']) or !isset($_GET['categoria']))
{
    $response = array(
        'status'  => 'error',
        'message' => 'Não foi possível completar está ação.',
        'link'    => '',
      );
    die(json_encode($response));
}
else
{
  $categoria = filterString($_GET['categoria'], 'CHAR');
}

if(empty($_GET['img']) or !isset($_GET['img']))
{
    $response = array(
        'status'  => 'error',
        'message' => 'Nenhuma imagem selecionada.',
        'link'    => '',
      );
    die(json_encode($response));
}
else
{
  $img = filterString($_GET['img'], 'CHAR');
}

if(empty($_GET['id']) or !isset($_GET['id']))
{
  $response = array(
        'status'  => 'error',
        'message' => 'Não reconhecemos qual veículo você está tentando editar.',
        'link'    => '',
      );
    die(json_encode($response));
}
else
{
  $id = filterString($_GET['id'], 'INT');
}

$con_db = new config\connect_db();
$con = $con_db->connect();

$img_capa = $con->prepare("UPDATE $categoria SET `image_capa` = ? WHERE `id` = ? ") ;
$img_capa->bind_param('si',$img, $id);
$img_capa->execute();
$img_capa->store_result();
$rows = $img_capa->affected_rows;
$img_capa->free_result();
$img_capa->close();

if(!$img_capa)
{
    $response = array(
        'status'  => 'error',
        'message' => 'A conexão com a base de dados falhou.',
        'link'    => '',
      );
    die(json_encode($response));
}
else if($img_capa)
{
  $response = array(
        'status'  => 'success',
        'message' => 'A imagem foi alterada.',
        'link'    => '',
      );
    die(json_encode($response));
}
else
{
  $response = array(
        'status'  => 'error',
        'message' => 'A conexão com a base de dados falhou.',
        'link'    => '',
      );
    die(json_encode($response));
}