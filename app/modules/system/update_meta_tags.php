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

if ($_SESSION['user_type'] !== 'administrador' and $_SESSION['user_type'] !== 'suporte')
{
    die(json_encode($response = array(
       'status'  => 'error',
       'message' => 'Você não tem permissão para realizar está ação.',
       'link'    => '',
    )));
}

if ($_POST['form-token'] != md5(SECRET_FORM_TOKEN.$_SESSION['user_id'].$_SESSION['user']))
{
     die(json_encode($response = array(
        'status'  => 'error',
        'message' => 'A origem de alguns dados nos parece duvidosa! Por isso bloqueamos está ação.',
        'link'    => '',
      )));
}

//--------------------------------------------------------------------------

$error_message = '';

if (!isset($_POST['settings-site-title']))
{
    $error_message .= '';
}
else if (empty($_POST['settings-site-title']))
{
    $meta_name = null;
}
else
{
    $meta_name = filterString()
}
