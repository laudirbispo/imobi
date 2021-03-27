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

if( !isset($_POST['veiculo-signature']) )
{
    $response = array(
        'status'  => 'error',
        'message' => 'Não identificamos a origem do seu pedido.',
        'link'    => '',
     );
     die(json_encode($response));
}
else
{
    $signature = filterString($_POST['veiculo-signature'], 'CHAR');
    
    $con_db = new config\connect_db();
    $con = $con_db->connect();
    
    $up_signature = $con->prepare(" UPDATE `settings_vehicles` SET `obs_signature` = ? ");
    $up_signature->bind_param('s', $signature);
    $up_signature->execute();
    $rows = $up_signature->affected_rows;
    $up_signature->close();
    
    if( $up_signature )
    {
        $response = array(
        'status'  => 'success',
        'message' => 'Assinatura alterada.',
        'link'    => '',
        );
        die(json_encode($response));
    } 
    else
    {
        $response = array(
        'status'  => 'error',
        'message' => 'A assinatura não pode ser alterada.',
        'link'    => '',
        );
        die(json_encode($response));
    }
}