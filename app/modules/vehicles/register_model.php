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

$error_message = '';

if( empty($_POST['veiculo-categoria-2']) or !isset($_POST['veiculo-categoria-2']) )
{
    $error_message .= '<p>Selecione uma categoria.</p>';
}
else
{
    $veiculo_categoria = filterString($_POST['veiculo-categoria-2'], 'CHAR');
}

if( empty($_POST['veiculo-marca-2']) or !isset($_POST['veiculo-marca-2']) )
{
    $error_message .= '<p>Informe o nome da nova marca.</p>';
}
else
{
    $veiculo_marca = filterString($_POST['veiculo-marca-2'], 'CHAR');
    $veiculo_marca = ucwords($veiculo_marca);
}

if( empty($_POST['veiculo-modelo']) or !isset($_POST['veiculo-modelo']) )
{
    $error_message .= '<p>Informe o nome da novo modelo.</p>';
}
else
{
    $veiculo_modelo = filterString($_POST['veiculo-modelo'], 'CHAR');
    $veiculo_modelo = ucwords($veiculo_modelo);
}

//-----------------------------------------------------------------------------------

if( !empty($error_message) )
{
    $response = array(
        'status'  => 'error',
        'message' => $error_message,
        'link'    => '',
    );
    die(json_encode($response));            
}

$con_db = new config\connect_db();
$con = $con_db->connect();

$is_registered = $con->prepare(" SELECT * FROM `vehicles_models` WHERE `marca` = ? AND `modelo` = ? LIMIT 1 ");
$is_registered->bind_param('ss', $veiculo_marca, $veiculo_modelo);
$is_registered->execute();
$is_registered->store_result();
$rows = $is_registered->affected_rows;
$is_registered->free_result();
$is_registered->close();

if( $is_registered and $rows > 0 )
{
    $response = array(
        'status'  => 'warning',
        'message' => 'Modelo já cadastrada.',
        'link'    => '',
    );
    die(json_encode($response)); 
}


$insert_modelo = $con->prepare(" INSERT INTO `vehicles_models` (`categoria`, `marca`, `modelo`) VALUES (?, ?, ?) ");
$insert_modelo->bind_param('sss', $veiculo_categoria, $veiculo_marca, $veiculo_modelo);
$insert_modelo->execute();
$rows_insert = $insert_modelo->affected_rows;
$insert_modelo->close();

if( $insert_modelo and $rows_insert > 0 )
{   
    $response = array(
        'status'  => 'success',
        'message' => $veiculo_modelo.' cadastrado(a).',
        'link'    => '',
    );
    die(json_encode($response));
}
else
{
    $response = array(
        'status'  => 'error',
        'message' => 'Não foi possível cadastrar a nova marca no banco de dados.',
        'link'    => '',
    );
    die(json_encode($response));
}



