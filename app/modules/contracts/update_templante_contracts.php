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

if(!isset($_POST['actionid']) or empty($_POST['actionid']))
{
	$response = array(
        'status'  => 'warning',
        'message' => 'Imóvel não identificado.',
        'link'    => '',
     );
     die(json_encode($response));
}
else
{
	$actionid = filterString(base64_decode($_POST['actionid']), 'INT');
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

$update_templante = $con->prepare("UPDATE tempantes_contracts SET model = ?, description = ?, text = ? WHERE id = ?");
$update_templante->bind_param('sssi', $modelo_name, $description, $text, $actionid);
$update_templante->execute();

if ($update_templante)
{
    $response = array(
        'status'  => 'success',
        'message' => 'Modelo de contrato atualizado',
        'link'    => '',
    );
    die(json_encode($response));
}
else
{
    $response = array(
        'status'  => 'error',
        'message' => 'Um erro inesperado aconteceu! Tente novamente mais tarde.',
        'link'    => '',
     );
     die(json_encode($response));
}

