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

if( empty($_GET['img']) or !isset($_GET['img']) )
{
    $response = array(
       'status'  => 'warning',
       'message' => 'Escolha uma imagem para continuar.',
       'link'    => '',
    );
    die(json_encode($response));
}
else
{
    $img = filterString($_GET['img'], 'CHAR');
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

$img_capa = $con->prepare("UPDATE properties SET cover_image = ? WHERE id = ? ");
$img_capa->bind_param('si',$img, $tid);
$img_capa->execute();
$rows = $img_capa->affected_rows;
$img_capa->close();

if( !$img_capa )
{
    $response = array(
       'status'  => 'warning',
       'message' => 'Não foi possível definir está imagem como capa! Tente novamente.',
       'link'    => '',
    );
    die(json_encode($response));
}
else if( $img_capa )
{
    $response = array(
       'status'  => 'success',
       'message' => 'Imagem de capa definida',
       'link'    => '',
    );
    die(json_encode($response));
}
else
{
    $response = array(
       'status'  => 'error',
       'message' => 'O servidor não está respondendo',
       'link'    => '',
    );
    die(json_encode($response));
}