<?php
require_once ($_SERVER['DOCUMENT_ROOT'].'/config/config.php');
session_name(SESSION_NAME);
session_start();
header('Content-Type: application/json');
require_once ($_SERVER['DOCUMENT_ROOT'].'/config/autoload.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/config/public_functions.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/app/controls/adminFunctions.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/libs/HTMLPurifier/HTMLPurifier.auto.php');
$HTMLPurifier_config = HTMLPurifier_Config::createDefault();

use config\connect_db;
use app\controls\blowfish_crypt;

if( $_SESSION['user_type'] != 'administrador' and $_SESSION['user_type'] != 'suporte' )
{
    if(!isset($_SESSION['contracts_create']) or $_SESSION['contracts_create'] !== 'Y' )
    {
        $response = array(
           'status'  => 'error',
           'message' => 'Você não tem permissão para realizar está ação.',
           'link'    => '',
        );
        die(json_encode($response));
    }
    
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

$error_message = NULL;

if (empty($_POST['contracts-model-name']) or !isset($_POST['contracts-model-name']))
{
    $error_message .= '<p>Por favor, informe o nome do modelo.</p>';
}
else
{
    $modelo_name = filterString($_POST['contracts-model-name'], 'CHAR');
}

if (empty($_POST['contracts-text']) or !isset($_POST['contracts-text']))
{
    $error_message .= '<p>Você precisa digitalizar o contrato, para prosseguir.</p>';
}
else
{  
    $purifier = new HTMLPurifier($HTMLPurifier_config);
    $text = $purifier->purify($_POST['contracts-text']);
}

if (empty($_POST['contracts-description']) or !isset($_POST['contracts-description']))
{
    $description = '';
}
else
{  
    $purifier = new HTMLPurifier($HTMLPurifier_config);
    $description = $purifier->purify($_POST['contracts-description']);
}
   
if( !empty($error_message) )
{
    $response = array(
        'status'  => 'warning',
        'message' => $error_message,
        'link'    => '',
    );
    die(json_encode($response));
}


$con_db = new config\connect_db();
$con = $con_db->connect();

$insert = $con->prepare('INSERT INTO tempantes_contracts (model, description, text) VALUES (?, ?, ?)');
$insert->bind_param('sss', $modelo_name, $description, $text);
$insert->execute();
$rows = $insert->affected_rows;
$insert->close();

if ($insert and $rows > 0)
{
    $activity_link = '/app/admin/add_contracts';
        
    $register_activity = new app\controls\activityRecord();
    $register_activity->record ($_SESSION['user_id'], 'private', 'insert-contract-model', 'adicionou um novo modelo de contrato.', $activity_link);
    
    $response = array(
        'status'  => 'success',
        'message' => 'Modelo de contrato adicionado!',
        'link'    => '',
    );
    die(json_encode($response));
}
else
{
    $response = array(
        'status'  => 'warning',
        'message' => 'Ocorreu um problema ao adicionar o novo modelo, tente novamente mais tarde!',
        'link'    => '',
    );
    die(json_encode($response));
}

