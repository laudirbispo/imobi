<?php
require_once ($_SERVER['DOCUMENT_ROOT'].'/config/config.php');
session_name(SESSION_NAME);
session_start();
header('Content-Type: application/json');
require_once ($_SERVER['DOCUMENT_ROOT'].'/config/autoload.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/config/public_functions.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/app/controls/adminFunctions.php');

if( $_SESSION['user_type'] != 'administrador' and $_SESSION['user_type'] != 'suporte')
{
    $response = array(
       'status'  => 'error',
       'message' => 'Você não tem permissão para realizar está ação.',
       'link'    => '',
    );
    die(json_encode($response));
}

if( empty($_GET['user_id']) or !isset($_GET['user_id']) )
{
    $response = array(
       'status'  => 'error',
       'message' => 'Usuário não identificado.',
       'link'    => '',
    );
    die(json_encode($response));
}
else 
{
    $user_id = filterString($_GET['user_id'], 'INT');
}

if( empty($_GET['action']) or !isset($_GET['action']) )
{
    $response = array(
       'status'  => 'error',
       'message' => 'Ação não reconhecida.',
       'link'    => '',
    );
    die(json_encode($response));
}
else
{
    if( $_GET['action'] === 'user-unlock' )
    {
        $user_active = 'Y';
    }
    else if ($_GET['action'] === 'user-lock' )
    {
        $user_active = 'N';
    }
    else
    {
        $response = array(
            'status'  => 'error',
            'message' => 'Ação não reconhecida.',
            'link'    => '',
       );
       die(json_encode($response));
    }
  
}

$con_db = new config\connect_db();
$con = $con_db->connect();

$bloq_update = $con->prepare(" UPDATE sec_users SET active = ? WHERE id = ? ");
$bloq_update->bind_param('si', $user_active, $user_id);
$bloq_update->execute();
$bloq_update->store_result();
$rows_affecteds = $bloq_update->affected_rows;
$bloq_update->free_result();
$bloq_update->close();

if($bloq_update and $rows_affecteds > 0 )
{
    $response = array(
       'status'  => 'success',
       'message' => 'Alterado',
       'link'    => '',
    );
    die(json_encode($response));
}
else
{
    $response = array(
       'status'  => 'error',
       'message' => 'Não entendemos seu pedido.',
       'link'    => '',
    );
    die(json_encode($response));
}

?>