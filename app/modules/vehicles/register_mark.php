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

if( empty($_POST['veiculo-categoria']) or !isset($_POST['veiculo-categoria']) )
{
    $error_message .= '<p>Selecione uma categoria.</p>';
}
else
{
    $veiculo_categoria = filterString($_POST['veiculo-categoria'], 'CHAR');
}

if( empty($_POST['veiculo-nova-marca']) or !isset($_POST['veiculo-nova-marca']) )
{
    $error_message .= '<p>Informe o nome da nova marca.</p>';
}
else
{
    $veiculo_nova_marca = filterString($_POST['veiculo-nova-marca'], 'CHAR');
    $veiculo_nova_marca = ucwords($veiculo_nova_marca);
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

$is_registered = $con->prepare(" SELECT * FROM `vehicles_brands` WHERE `categoria` = ? AND `marca` = ? LIMIT 1 ");
$is_registered->bind_param('ss',$veiculo_categoria, $veiculo_nova_marca);
$is_registered->execute();
$is_registered->store_result();
$rows = $is_registered->affected_rows;
$is_registered->free_result();
$is_registered->close();

if( $is_registered and $rows > 0 )
{
    $response = array(
        'status'  => 'warning',
        'message' => 'Categoria já cadastrada.',
        'link'    => '',
    );
    die(json_encode($response)); 
}


$insert_marca = $con->prepare(" INSERT INTO `vehicles_brands` (`categoria`, `marca`) VALUES (?, ?) ");
$insert_marca->bind_param('ss', $veiculo_categoria, $veiculo_nova_marca);
$insert_marca->execute();
$rows_insert = $insert_marca->affected_rows;
$insert_marca->close();

if( $insert_marca and $rows_insert > 0 )
{   
    $response = array(
        'status'  => 'success',
        'message' => $veiculo_nova_marca.' cadastrado(a).',
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



