<?php
require_once ($_SERVER['DOCUMENT_ROOT'].'/config/config.php');
session_name(SESSION_NAME);
session_start();
header('Content-Type: application/json');
require_once ($_SERVER['DOCUMENT_ROOT'].'/config/autoload.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/config/public_functions.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/app/controls/adminFunctions.php');

/**
 * set_cover -> arquivo do model properties
 *
 */

use config\connect_db;
use app\controls\blowfish_crypt;

if( $_SESSION['user_type'] != 'administrador' and $_SESSION['user_type'] != 'suporte' )
{
    if(!isset($_SESSION['properties_edit']) or $_SESSION['properties_edit'] !== 'Y' )
    {
        $response = array(
           'status'  => 'error',
           'message' => 'Você não tem permissão para realizar está ação.',
           'link'    => '',
        );
        die(json_encode($response));
    }
    
}

//--------------------------------------------------------------------------

$error_message = NULL;

if( empty($_GET['action']) or !isset($_GET['action']) )
{
    $response = array(
       'status'  => 'warning',
       'message' => 'Escolha uma ação para continuar.',
       'link'    => '',
    );
    die(json_encode($response));
}
else
{
    $action = filterString($_GET['action'], 'CHAR');
    if($action == 'show')
    {
        $action = 'Y';
    }
    else if($action == 'hidden')
    {
        $action = 'N';
    }
    else
    {
        $response = array(
           'status'  => 'warning',
           'message' => 'Ação inválida',
           'link'    => '',
        );
        die(json_encode($response));
    }
}

if( empty($_GET['tid']) or !isset($_GET['tid']) )
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
    $tid = filterString($_GET['tid'], 'INT');
}

$con_db = new config\connect_db();
$con = $con_db->connect();

$visibility = $con->prepare("UPDATE properties SET visibility = ? WHERE id = ? ");
$visibility->bind_param('si',$action, $tid);
$visibility->execute();
$rows = $visibility->affected_rows;
$visibility->close();

if( !$visibility )
{
    $response = array(
       'status'  => 'warning',
       'message' => 'Não foi possível alterar a visibilidade deste imóvel',
       'link'    => '',
    );
    die(json_encode($response));
}
else if( $visibility )
{
    $response = array(
       'status'  => 'success',
       'message' => 'Visibilidade alterada',
       'link'    => '',
    );
    die(json_encode($response));
}
else
{
    $response = array(
       'status'  => 'error',
       'message' => 'Um erro inesperado aconteceu',
       'link'    => '',
    );
    die(json_encode($response));
}