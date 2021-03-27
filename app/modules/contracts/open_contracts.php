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
    if(!isset($_SESSION['contracts_view']) or $_SESSION['contracts_view'] !== 'Y' )
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
        'message' => 'Selecione um contrato para executar esta ação.',
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

$open_contract = $con->prepare("SELECT model, description, text FROM tempantes_contracts WHERE id = ? LIMIT 1");
$open_contract->bind_param('i', $actionid);
$open_contract->execute();
$open_contract->store_result();
$open_contract->bind_result($model, $description, $text);
$open_contract->fetch();
$rows = $open_contract->num_rows;
$open_contract->free_result();
$open_contract->close();

if ($open_contract and $rows > 0)
{
    require_once ($_SERVER['DOCUMENT_ROOT'].'/libs/HTMLPurifier/HTMLPurifier.auto.php');
    $HTMLPurifier_config = HTMLPurifier_Config::createDefault();
    $purifier = new HTMLPurifier($HTMLPurifier_config);
    $html = $purifier->purify($text);

    $response = array(
        'status'  => 'success',
        'message' => 'Modelo carregado',
        'model'   => $model,
        'description' => $description,
        'html'    => $html,
        
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

