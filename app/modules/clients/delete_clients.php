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
    if(!isset($_SESSION['clients_delete']) or $_SESSION['clients_delete'] !== 'Y' )
    {
        $response = array(
           'status'  => 'error',
           'message' => 'Você não tem permissão para realizar está ação.',
           'link'    => '',
        );
        die(json_encode($response));
    }
}

if(empty($_GET['user_password']) or !isset($_GET['user_password']))
{
    die(json_encode($response = array(
        'status'  => 'warning',
        'message' => 'É necessário informar sua senha para continuar!',
        'link'    => '',
     )));
}
else
{
    $user_password = $_GET['user_password'];
}

if( empty($_GET['actionid']) or !isset($_GET['actionid']) )
{
    $response = array(
        'status'  => 'error',
        'message' => 'Usuário que você está tentando deletar não foi encontrado.',
        'link'    => '',
    );
    die(json_encode($response));
}
else
{
    $actionid = filterString($_GET['actionid'], 'INT');
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
    if($crypt->check($user_password, $password_hash) === false )
    {
        die(json_encode($response = array(
            'status'  => 'warning',
            'message' => 'A senha informada está incorreta!',
            'link'    => '',
         )));
    }
    
    
    $is_suporte = $con->query(" SELECT * FROM sec_users WHERE id = '$actionid' AND type = 'suporte' ");
    $rows = $is_suporte->num_rows;
    $is_suporte->close();
    
    if($is_suporte and $rows > 0)
    {
        die(json_encode($response = array(
            'status'  => 'warning',
            'message' => 'Você não tem autorização para concluir está ação!!!',
            'link'    => '',
         )));
    }
    else
    {
        $delete_client = $con->prepare("DELETE FROM clients WHERE id = ? ");
        $delete_client->bind_param('i', $actionid);
        $delete_client->execute();
        $delete_client->store_result();
        $rows_affecteds = $delete_client->affected_rows;
        $delete_client->free_result();
        $delete_client->close();
        
        if(!$delete_client)
        {
            $response = array(
                'status'  => 'error',
                'message' => 'O cliente não pode ser deletado. Tente novamente, se o erro percistir entre em contato com o administrador!',
                'link'    => '',
            );
            die(json_encode($response));
        }
        else if( $rows_affecteds <= 0 )
        {
            $response = array(
                'status'  => 'error',
                'message' => 'Dados sobre o cliente não encontrado. Não foi possível completar a ação solicitada.',
                'link'    => '',
            );
            die(json_encode($response));
        }
        else if( $rows_affecteds > 0 )
        {
            $response = array(
                'status'  => 'success',
                'message' => 'Cliente deletado com sucesso.',
                'link'    => '',
            );
            die(json_encode($response));  
        }
        else
        {
            $response = array(
                'status'  => 'error',
                'message' => 'Não entendemos seu pedido. Tente novamente mais tarde.',
                'link'    => '',
            );
            die(json_encode($response));
        }
        
    }//, usuário que está sendo deletado não é suporte
    
    
}
else
{
    die(json_encode($response = array(
        'status'  => 'warning',
        'message' => 'Algo de errado aconteceu! Tente novamente mais tarde.',
        'link'    => '',
    )));
}


