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

if (!isset($_POST['receipt-id']) or empty($_POST['receipt-id']))
{
    $response = array(
        'status'  => 'warning',
        'message' => 'Recibo não identificado.',
        'link'    => '',
     );
     die(json_encode($response));
}
else
{
    $receipt_id = filterString($_POST['receipt-id'], 'INT');
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

if(empty($_POST['low-situation']) or !isset($_POST['low-situation']))
{
    die(json_encode($response = array(
        'status'  => 'warning',
        'message' => 'É necessário informar sua senha para continuar!',
        'link'    => '',
     )));
}
else
{
    $low_situation = filterString($_POST['low-situation'], 'CHAR');
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
        
        if ($low_situation === 'del')
        {
            
            $del_receipt = $con->prepare("DELETE FROM receipts WHERE contract_id = ? AND id = ?");
            $del_receipt->bind_param('si', $contract_id, $receipt_id);
            $del_receipt->execute();
            $del_rows = $del_receipt->affected_rows;
            $del_receipt->close();
            
            $activity_link = '/app/admin/contracts_details/'.$contract_id;               
            $register_activity->record ($_SESSION['user_id'], 'private', 'update-receipts', 'deletou o recibo número: '.$receipt_id, $activity_link);
            
            die(json_encode($response = array(
                'status'  => 'success',
                'message' => 'Recibo apagado com sucesso',
                'link'    => '',
             )));
            
        }
        else if ($low_situation === 'billed')
        {
            $situation = 'billed';
            $update_receipt = $con->prepare("UPDATE receipts SET situation = ? WHERE contract_id = ? AND id = ?");
            $update_receipt->bind_param('ssi', $situation, $contract_id, $receipt_id);
            $update_receipt->execute();
            $rows_update = $update_receipt->affected_rows;
            $update_receipt->close();
            
            if ($update_receipt)
            {
                $activity_link = '/app/admin/contracts_details/'.$contract_id;               
                $register_activity->record ($_SESSION['user_id'], 'private', 'update-receipts', 'marcou como pago o recibo: '.$receipt_id, $activity_link);
                
                die(json_encode($response = array(
                    'status'  => 'success',
                    'message' => 'Recibo atualizado com sucesso',
                    'link'    => '',
                 )));
            }
            else
            {
                die(json_encode($response = array(
                    'status'  => 'warning',
                    'message' => 'Não foi possível completar a ação solicitada.',
                    'link'    => '',
                 )));
            }  
        }
        else
        {
            die(json_encode($response = array(
                'status'  => 'warning',
                'message' => 'Não conseguimos entender seu pedido',
                'link'    => '',
             )));
        }
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
