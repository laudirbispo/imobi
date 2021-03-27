<?php
session_name(SESSION_NAME);
session_start();
header('Content-Type: application/json');
require_once ($_SERVER['DOCUMENT_ROOT'].'/config/autoload.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/config/config.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/config/public_functions.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/app/controls/adminFunctions.php');

use config\connect_db;

$default_response = array(
    'status'  => 'warning',
    'message' => 'O servidor não respondeu ao nosso chamado. Tente novamente se o erro persistir entre em contato com suporte técnico.[DBx0001]',
    'link'    => '',
);

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

if(empty($_GET['id_car']) or !isset($_GET['id_car']))
{
    $response = array(
        'status'  => 'error',
        'message' => 'Selecione um veículo para executar está ação.',
        'link'    => '',
      );
     die(json_encode($response));
}
else
{
  $id_car = filterString($_GET['id_car'], 'INT');
}

if(empty($_GET['categoria']) or !isset($_GET['categoria']))
{
    $response = array(
        'status'  => 'error',
        'message' => 'Selecione um veículo para executar está ação.',
        'link'    => '',
      );
     die(json_encode($response));
}
else
{
    $categoria = filterString($_GET['categoria'], 'CHAR');
}

if(empty($_GET['action']) or !isset($_GET['action']))
{
    $response = array(
        'status'  => 'error',
        'message' => 'Não entendemos seu pedido.',
        'link'    => '',
      );
     die(json_encode($response));
}
else
{
  if($_GET['action'] === 'featured')
  {
    $featured = 1;
  }
  else if($_GET['action'] === 'unfeatured')
  {
    $featured = 0;
  }
  else
  {
      $response = array(
            'status'  => 'error',
            'message' => 'Não entendemos seu pedido.',
            'link'    => '',
       );
       die(json_encode($response));
  }
}

$con_db = new config\connect_db();
$con = $con_db->connect();

// reponse para error na query
$response = array(
    'status'  => 'error',
    'message' => 'Algumas informações para executar está ação estão incompletas.',
    'link'    => '',
);      
die(json_encode($response));     

$featured_car = $con->prepare("UPDATE $categoria SET `featured` = ? WHERE `id` = ? ") or die(json_encode($default_response)) ;
$featured_car->bind_param('ii', $featured, $id_car);
$featured_car->execute();
$featured_car->store_result();
$rows = $featured_car->affected_rows;
$featured_car->free_result();
$featured_car->close();

if(!$featured_car)
{
    $response = array(
        'status'  => 'error',
        'message' => 'O servidor não está respondendo.',
        'link'    => '',
      );
     die(json_encode($response));
}
else if($featured_car)
{
    $response = array(
        'status'  => 'success',
        'message' => 'Alterado',
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
