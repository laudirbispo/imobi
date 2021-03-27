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

//--------------------------------------------------------------------------

$error_message = '';

if (empty($_POST['user-login']) or !isset($_POST['user-login']) )
{
    $error_message  .= '<p>Por favor, informe um login para o usuário.</p>';
}
else
{
    
  
    if( strlen($_POST['user-login']) < 6 or strlen($_POST['user-login']) > 16)
    {
        $error_message  .= '<p>O login deve ter entre 6 e 16 caracteres.</p>';
    }
    if( preg_match('/\s/', $_POST['user-login']))
    {
        $error_message  .= '<p>Não use espaços no campo Login.</p>';
    }
    
    $pattern_login = '/([^\.a-z,0-9,A-Z,@_-]+)/';

    if(preg_match($pattern_login, $_POST['user-login']))
    {
        $error_message  .= ' O login pode conter entre 6 e 16 caracteres, letras maiúsculas, letras minúsculas, e os seguintes caracteres especiais "@.-_" ';
    }
    
    $user_login = filterString($_POST['user-login'], 'CHAR');
}

//--------------------------------------------------------------------------

$possible_typers = array('convidado', 'auxiliar', 'administrador');

if( !isset($_POST['user-type']) )
{
    $error_message  .= '<p>Por favor, informe o tipo de usuário.</p>';
}
else
{
    if( in_array($_POST['user-type'], $possible_typers))
    {
        $user_type = $_POST['user-type'];
    }
    else
    {
       $error_message  .= '<p>Este tipo de usuário não é permitido.</p>'; 
    }
}

//--------------------------------------------------------------------------

if( empty($_POST['user-password']) or !isset($_POST['user-password']) )
{
    $error_message  .= '<p>Por favor, uma senha temporária é necessária.</p>';
}
else
{
    $user_password = filterString($_POST['user-password'], 'CHAR');
}

//--------------------------------------------------------------------------

if( empty($_POST['user-confirm-password']) or !isset($_POST['user-confirm-password']) )
{
    $error_message  .= '<p>Por favor confirme a senha.</p>';
}
else
{
    $user_confirm_password = filterString($_POST['user-confirm-password'], 'CHAR');
}

//--------------------------------------------------------------------------

if( $_POST['user-password'] !== $_POST['user-confirm-password'] )
{
    $error_message  .= '<p>As senhas digitadas não são iguais!</p>';
}

//--------------------------------------------------------------------------

if( !empty($error_message) )
{
    $response = array(
        'status'  => 'warning',
        'message' => $error_message,
        'link'    => '',
     );
     die(json_encode($response));
}

if( isset($_POST['user-active']) )
{
    $user_active = 'N';
}
else
{
    $user_active = 'Y';
}

//*********************************************************************************************

$con_db = new config\connect_db();
$con = $con_db->connect();

//vereficamos se já existe um usuário cadastrado com esse nome
$exist_user = $con->query("SELECT * FROM sec_users WHERE login = '$user_login' ");
$user_rows = $exist_user->num_rows;
$exist_user->close();

if($exist_user and $user_rows > 0)
{ 
  $response = array(
        'status'  => 'warning',
        'message' => 'Já existe um usuário cadastrado com esse login!',
        'link'    => '',
     );
     die(json_encode($response));
}

//*********************************************************************************************

$pattern_password = '/(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*[^a-zA-Z0-9]).{8,16}/';

if( !preg_match($pattern_password, $user_password) )
{
    $response = array(
        'status'  => 'warning',
        'message' => 'A senha não satisfaz os requisitos necessários!',
        'link'    => '',
     );
     die(json_encode($response));
}

$crypt = new blowfish_crypt;
$password = $crypt->generateHash($user_password);

$insert_user = $con->prepare(" INSERT INTO sec_users (login, password, type, active, date_register) VALUES (?, ?, ?, ?, ?) ");
$insert_user->bind_param('sssss', $user_login, $password, $user_type, $user_active, $date_time);
$insert_user->execute();
$affected_rows = $insert_user->affected_rows;
$insert_id = $insert_user->insert_id;
$insert_user->close();

if( !$insert_user or $affected_rows <= 0 )
{
    
    $response = array(
        'status'  => 'error',
        'message' => 'O servidor não está respondendo.',
        'link'    => '',
     );
     die(json_encode($response));
}
else if( $insert_user and $affected_rows > 0 )
{

    $create_user_profile = $con->prepare(" INSERT INTO user_profile (user_id) VALUES (?) ");
    $create_user_profile->bind_param('i', $insert_id);
    $create_user_profile->execute();
    $create_user_profile->close();
  
    $create_user_perms = $con->prepare(" INSERT INTO user_perms (user_id) VALUES (?) ");
    $create_user_perms->bind_param('i', $insert_id);
    $create_user_perms->execute();
    $create_user_perms->close();
    
    $status = '3';
    
    $create_user_status = $con->prepare(" INSERT INTO online_users (user_id, status) VALUES (?, ? ) ");
    $create_user_status->bind_param('ii', $insert_id, $status);
    $create_user_status->execute();
    $create_user_status->close();
    
    $user_dir = '/docs/users/'.$insert_id;
    $user_dir_profile_images = '/docs/users/'.$insert_id.'/profile-images';
    $user_dir_cover_images = '/docs/users/'.$insert_id.'/cover-images';
    
    createDirFTP($user_dir, $action = 'create');
    createDirFTP($user_dir_profile_images, $action = 'create');
    createDirFTP($user_dir_cover_images, $action = 'create');
  
    $response = array(
        'status'  => 'success',
        'message' => 'Usuário cadastrado',
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