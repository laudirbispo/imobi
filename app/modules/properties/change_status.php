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

if( empty($_GET['status']) or !isset($_GET['status']))
{
    $response = array(
       'status'  => 'warning',
       'message' => 'Forneça o estatus para o imóvel para continuar.',
       'link'    => '',
    );
    die(json_encode($response));
}
else
{
    $status_allowed = array('vendido', 'alugado', 'remove');
    
    if( !in_array($_GET['status'], $status_allowed) )
    {
        $response = array(
           'status'  => 'warning',
           'message' => $_GET['status'],
           'link'    => '',
        );
        die(json_encode($response));
    }
    else
    {
        if($_GET['status'] === 'remove')
        {
            $status = NULL;
        }
        else
        {
            $status = filterString($_GET['status'], 'CHAR');
        }
        
    }
}

//--------------------------------------------------------------------------

if( empty($_GET['tid']) or !isset($_GET['tid']))
{
    $response = array(
       'status'  => 'warning',
       'message' => 'Não conseguimos identificar qual imóvel você está tentando alterar o estatus.',
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

$update = $con->prepare("UPDATE properties SET status = ? WHERE id = ?");
$update->bind_param('si', $status, $tid);
$update->execute();
$update->close();

if($update)
{
    $response = array(
       'status'  => 'success',
       'message' => 'Estatus alterado',
       'link'    => '',
    );
    die(json_encode($response));
}
else
{
    $response = array(
       'status'  => 'error',
       'message' => 'Um erro inesperado ocorreu. Tente novamente, se o erro percistir entre em conato com o administrado.',
       'link'    => '',
    );
    die(json_encode($response));
}



