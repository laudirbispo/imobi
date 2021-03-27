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
    $response = array(
       'status'  => 'error',
       'message' => 'Você não tem permissão para realizar está ação.',
       'link'    => '',
    );
    die(json_encode($response));
}

if( $_POST['form-token'] != md5(SECRET_FORM_TOKEN.$_SESSION['user_id'].$_SESSION['user']) )
{
     $response = array(
        'status'  => 'error',
        'message' => 'A origem de alguns dados nos parece duvidosa! Por isso bloqueamos está ação.',
        'link'    => '',
      );
     die(json_encode($response));
}

if( !isset($_POST['user_id']) or (int)$_POST['user_id'] !== (int)$_SESSION['user_id'] )
{
    $response = array(
        'status'  => 'warning',
        'message' => 'Você não pode fazer isso!',
        'link'    => '',
     );
     die(json_encode($response));
}

if(!isset($_POST['actionid']) or empty($_POST['actionid']))
{
    die(json_encode($response = array(
        'status'  => 'warning',
        'message' => 'Escolha um usuário para alterar as permissões.',
        'link'    => '',
     )));
}
else
{
    $actionid = filterString($_POST['actionid'], 'INT');
    if($actionid == $_SESSION['user_id'])
    {
        die(json_encode($response = array(
            'status'  => 'error',
            'message' => 'Você não pode alterar sua senha por aqui!<br>Caso queirá alterar sua senha <a href="/app/admin/my_account#box-password">clique aqui</a>.',
            'link'    => '',
         )));
    }
}

if(!isset($_POST['user_confirm']))
{
    die(json_encode($response = array(
        'status'  => 'warning',
        'message' => 'Por favor, confirme o usuário para continuar!',
        'link'    => '',
     )));
}

//****************************************************************************

if( empty($_POST['user-password']) or !isset($_POST['user-password']) )
{
    $response = array(
        'status'  => 'warning',
        'message' => 'Digite uma nova senha ou clique no botão gerar senha.',
        'link'    => '',
     );
     die(json_encode($response));
}
else
{
    $user_password = filterString($_POST['user-password'], 'CHAR');
}

//--------------------------------------------------------------------------

if( empty($_POST['user-confirm-password']) or !isset($_POST['user-confirm-password']) )
{
    $response = array(
        'status'  => 'warning',
        'message' => 'É necessário confirmar a nova senha.',
        'link'    => '',
     );
     die(json_encode($response));
}
else
{
    $user_confirm_password = filterString($_POST['user-confirm-password'], 'CHAR');
}

//--------------------------------------------------------------------------

if( $_POST['user-password'] !== $_POST['user-confirm-password'] )
{
    $response = array(
        'status'  => 'error',
        'message' => 'As senhas não conferem.',
        'link'    => '',
     );
     die(json_encode($response));
}

//*********************************************************************************************

$pattern_password = '/(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*[^a-zA-Z0-9]).{8,16}/';

if( !preg_match($pattern_password, $user_password) )
{
    $response = array(
        'status'  => 'error',
        'message' => 'A senha não satisfaz os requisitos necessários.',
        'link'    => '',
     );
     die(json_encode($response));
}

$crypt = new blowfish_crypt;
$password = $crypt->generateHash($user_password);

$con_db = new connect_db();
$con = $con_db->connect();

$update_password = $con->prepare(" UPDATE sec_users SET password = ?, last_change_password = ? WHERE id = ?");
$update_password->bind_param('ssi', $password, $date_time, $actionid);
$update_password->execute();
$update_password->store_result();
$affected_rows = $update_password->affected_rows;
$update_password->free_result();
$update_password->close();

if( $update_password )
{
    $response = array(
        'status'  => 'success',
        'message' => 'Senha alterada',
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

?>

