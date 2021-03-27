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
use app\controls\activityRecord;

$crypt = new blowfish_crypt;

if( !isset($_POST['form-token']) or $_POST['form-token'] != $_SESSION['secret_form_token'] )
{
     $response = array(
        'status'  => 'error',
        'message' => 'A origem de alguns dados nos parece duvidosa! Por isso bloqueamos está ação.',
        'link'    => '',
      );
     die(json_encode($response));
}

//****************************************************************************
$con_db = new config\connect_db();
$con = $con_db->connect();

$pass_db = $con->prepare("SELECT password FROM sec_users WHERE id = ?");
$pass_db->bind_param('s', $_SESSION['user_id']);
$pass_db->execute();
$pass_db->store_result();
$pass_db->bind_result($old_pass);
$pass_db->fetch();
$rows = $pass_db->num_rows;
$pass_db->free_result();
$pass_db->close();

if( empty($_POST['user-password-current']) or !isset($_POST['user-password-current']) )
{
    $response = array(
        'status'  => 'warning',
        'message' => 'Por favor, informe sua senha atual para continuar.',
        'link'    => '',
     );
     die(json_encode($response));
}

if( $crypt->check($_POST['user-password-current'], $old_pass) === false )
{
    $response = array(
        'status'  => 'warning',
        'message' => 'A senha informada está incorreta.',
        'link'    => '',
     );
     die(json_encode($response)); 
}

if( $crypt->check($_POST['user-password'], $old_pass) === true )
{
    $response = array(
        'status'  => 'warning',
        'message' => 'A nova senha é a mesma que a atual! Escolha outra senha para continuar.',
        'link'    => '',
     );
     die(json_encode($response)); 
}

if( empty($_POST['user-password']) or !isset($_POST['user-password']) )
{
    $response = array(
        'status'  => 'warning',
        'message' => 'Por favor, digite uma nova senha ou clique em gerar senha.',
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
        'message' => 'Por favor, confirme a senha.',
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
        'message' => 'As senhas digitadas não são iguais.',
        'link'    => '',
     );
     die(json_encode($response));
}

//--------------------------------------------------------------------------

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


$password = $crypt->generateHash($user_password);

$update_password = $con->prepare(" UPDATE sec_users SET password = ?, last_change_password = ? WHERE id = ?");
$update_password->bind_param('ssi', $password, $date_time, $_SESSION['user_id']);
$update_password->execute();
$update_password->store_result();
$affected_rows = $update_password->affected_rows;
$update_password->free_result();
$update_password->close();

if($update_password )
{
    $register_activity = new activityRecord();
    $register_activity->record ($_SESSION['user_id'], 'private', 'change-password', 'alterou a senha de login', null);
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
        'message' => 'Não foi possível atualizar sua senha. Tente novamente mais tarde.',
        'link'    => '',
     );
     die(json_encode($response));
}

?>

