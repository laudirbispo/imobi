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
    if(!isset($_SESSION['contracts_delete']) or $_SESSION['contracts_delete'] !== 'Y' )
    {
        $response = array(
           'status'  => 'error',
           'message' => 'Você não tem permissão para realizar está ação.',
           'link'    => '',
        );
        die(json_encode($response));
    }
    
}
if(!isset($_GET['actionid']) or empty($_GET['actionid']))
{
	$response = array(
        'status'  => 'warning',
        'message' => 'Selecione um modelo de contrato para executar esta ação.',
        'link'    => '',
     );
     die(json_encode($response));
}
else
{
	$actionid = filterString(base64_decode($_GET['actionid']), 'INT');
}
//--------------------------------------------------------------------------

$con_db = new config\connect_db();
$con = $con_db->connect();

$delete_templante_contracts = $con->prepare("DELETE FROM tempantes_contracts WHERE id = ?");
$delete_templante_contracts->bind_param('i', $actionid);
$delete_templante_contracts->execute();
$rows = $delete_templante_contracts->affected_rows;
$delete_templante_contracts->close();

if ($delete_templante_contracts and $rows > 0)
{
    $response = array(
        'status'  => 'success',
        'message' => 'Modelo de contrato deletado',
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
