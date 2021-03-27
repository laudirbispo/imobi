<?php
require_once ($_SERVER['DOCUMENT_ROOT'].'/config/config.php');
session_name(SESSION_NAME);
session_start();
header('Content-Type: application/json');
require_once ($_SERVER['DOCUMENT_ROOT'].'/config/autoload.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/config/public_functions.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/app/controls/adminFunctions.php');

use config\connect_db;
use app\controls\blowfish_crypt;

if( $_SESSION['user_type'] != 'administrador' and $_SESSION['user_type'] != 'suporte' )
{
    if(!isset($_SESSION['contracts_delete']) or $_SESSION['contracts_delete'] !== 'Y' )
    {
        $response = array(
           'status'  => 'error',
           'message' => 'Você não tem permissão para realizar está ação.',
           'link'    => '',
        );
        die(json_encode($response));
    }    
}

if (!isset($_POST['contract-id']) or empty($_POST['contract-id']))
{
    $response = array(
        'status'  => 'warning',
        'message' => 'Contrato não identificado.',
        'link'    => '',
     );
     die(json_encode($response));
}
else
{
    $contract_id = filterString($_POST['contract-id'], 'CHAR');
}

if(empty($_POST['user-password']) or !isset($_POST['user-password']))
{
    die(json_encode($response = array(
        'status'  => 'warning',
        'message' => 'É necessário informar sua senha para continuar!',
        'link'    => '',
     )));
}
else
{
    $user_password = $_POST['user-password'];
}

$con_db = new config\connect_db();
$con = $con_db->connect();

$confirm_pass = $con->prepare(" SELECT password, type FROM sec_users WHERE id = ? ");
$confirm_pass->bind_param('i', $_SESSION['user_id']);
$confirm_pass->execute();
$confirm_pass->store_result();
$confirm_pass->bind_result($password_hash, $type);
$confirm_pass->fetch();
$rows = $confirm_pass->affected_rows;
$confirm_pass->free_result();
$confirm_pass->close();

if($confirm_pass and $rows > 0)
{    
    $crypt = new blowfish_crypt();
    if($crypt->check($user_password, $password_hash) != true )
    {
        die(json_encode($response = array(
            'status'  => 'warning',
            'message' => 'A senha informada está incorreta!',
            'link'    => '',
         )));
    }
    else
    {
        $register_activity = new app\controls\activityRecord();
        
        $del_contract = $con->prepare("DELETE FROM contracts WHERE contract_id = ?");
        $del_contract->bind_param('s', $contract_id);
        $del_contract->execute();
        $rows_del_contract = $del_contract->affected_rows;
        $del_contract->close();
        
        if ($del_contract and $rows_del_contract > 0)
        {
            $del_receipts = $con->prepare("DELETE FROM receipts WHERE contract_id = ?");
            $del_receipts->bind_param('s', $contract_id);
            $del_receipts->execute();
            $del_rows_receipts = $del_receipts->affected_rows;
            $del_receipts->close();
            
            recursiveDeleteFTP('/contracts/'.$contract_id);
            
            die(json_encode($response = array(
                'status'  => 'success',
                'message' => 'Contrato deletado',
                'link'    => '',
             )));
            
        }
        else
        {
            die(json_encode($response = array(
                'status'  => 'warning',
                'message' => 'Não foi possível excluir o contrato selecionado. Tente novamente mais tarde.',
                'link'    => '',
             )));
        }// se deletetou o contrato
        
    }// verificação de password
}
else
{
    die(json_encode($response = array(
        'status'  => 'warning',
        'message' => 'Não foi possível autentica-lo. Tente novamente mais tarde.',
        'link'    => '',
     )));
}// consulta para validar password
