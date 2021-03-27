<?php
require_once ($_SERVER['DOCUMENT_ROOT'].'/config/config.php');
session_name(SESSION_NAME);
session_start();
header('Content-Type: application/json');
require_once ($_SERVER['DOCUMENT_ROOT'].'/config/autoload.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/config/public_functions.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/app/controls/adminFunctions.php');


if( $_SESSION['user_type'] != 'administrador' and $_SESSION['user_type'] != 'suporte' )
{
    if(!isset($_SESSION['properties_delete']) or $_SESSION['properties_delete'] !== 'Y' )
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

if( empty($_GET['tid']) or !isset($_GET['tid']) )
{
    $response = array(
       'status'  => 'warning',
       'message' => 'Selecione um imóvel para completar esta ação.',
       'link'    => '',
    );
    die(json_encode($response));
}
else
{
    $tid = filterString($_GET['tid'], 'INT');
}

$del_patch = '/properties/'.$tid;

$con_db = new config\connect_db();
$con = $con_db->connect();  

$del_properties = $con->prepare("DELETE FROM properties WHERE id = ?");
$del_properties->bind_param('i', $tid);
$del_properties->execute();
$rows_affected = $del_properties->affected_rows;
$del_properties->close();

if($del_properties and $rows_affected > 0)
{
    $del_images = $con->prepare("DELETE FROM images_properties WHERE id_properties = ?");
    $del_images->bind_param('i', $tid);
    $del_images->execute();
    $rows_del = $del_images->affected_rows;
    $del_images->close();
    
    if($del_images and $rows_del > 0)
    {
        
        //recursiveDeleteFTP($del_patch);
        
        $response = array(
           'status'  => 'success',
           'message' => 'Imóvel deletado.',
           'link'    => '',
        );
        die(json_encode($response)); 
    }
    else
    {
        $response = array(
           'status'  => 'success',
           'message' => 'Imóvel deletado, mais alguns arquivos podem ter ficados',
           'link'    => '',
        );
        die(json_encode($response)); 
    }
}
else
{
    $response = array(
       'status'  => 'error',
       'message' => 'O servidor não foi capaz de entender seu pedido.',
       'link'    => '',
    );
    die(json_encode($response));
}

