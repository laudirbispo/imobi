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
    if(!isset($_SESSION['contracts_edit']) or $_SESSION['contracts_edit'] !== 'Y' )
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

if (!isset($_POST['contract-id']) or empty($_POST['contract-id']))
{
    $response = array(
        'status'  => 'warning',
        'message' => 'Contrato não identificado.',
        'link'    => '',
     );
     die(json_encode($response));
}
else
{
    $contract_id = filterString($_POST['contract-id'], 'CHAR');
}

if (!isset($_POST['receipt-id']) or empty($_POST['receipt-id']))
{
    $response = array(
        'status'  => 'warning',
        'message' => 'Recibo não identificado.',
        'link'    => '',
     );
     die(json_encode($response));
}
else
{
    $receipt_id = filterString($_POST['receipt-id'], 'INT');
}

if (!isset($_POST['receipt-discount']) or empty($_POST['receipt-discount']))
{
    $discount = 0;
}
else
{
    $discount = filterString($_POST['receipt-discount'], 'CHAR');
    $discount = moedaDecimal($discount);
}

if (!isset($_POST['receipt-discount-cause']) or empty($_POST['receipt-discount-cause']))
{
    $discount_cause = NULL;
}
else
{
    $discount_cause = filterString($_POST['receipt-discount-cause'], 'CHAR');
}

if (!isset($_POST['receipt-addition']) or empty($_POST['receipt-addition']))
{
    $addition = 0;
}
else
{
    $addition = filterString($_POST['receipt-addition'], 'CHAR');
    $addition = moedaDecimal($addition);
}

if (!isset($_POST['receipt-addition-cause']) or empty($_POST['receipt-addition-cause']))
{
    $addition_cause = NULL;
}
else
{
    $addition_cause = filterString($_POST['receipt-addition-cause'], 'CHAR');
}

if (!isset($_POST['receipt-observations']) or empty($_POST['receipt-observations']))
{
    $observations = null;
}
else
{
    $observations = filterString($_POST['receipt-observations'], 'CHAR');
}

$con_db = new config\connect_db();
$con = $con_db->connect();

$update = $con->prepare("UPDATE receipts SET discount = ?, discount_cause = ?, addition = ?, addition_cause = ?, observations = ? WHERE contract_id = ? AND id = ?");
$update->bind_param('isisssi', $discount, $discount_cause, $addition, $addition_cause, $observations, $contract_id, $receipt_id);
$update->execute();
$rows = $update->affected_rows;
$update->close();

if ($update)
{
    $activity_link = '/app/admin/contracts_details/'.$contract_id;       
    $register_activity = new app\controls\activityRecord();
    $register_activity->record ($_SESSION['user_id'], 'private', 'update-receipts', 'alterou dados do recibo número: '.$receipt_id, $activity_link);
                    
    $response = array(
       'status'  => 'success',
       'message' => 'Dados atualizados.',
       'link'    => '',
    );
    die(json_encode($response));
}
else
{
    $response = array(
       'status'  => 'warning',
       'message' => 'Não foi possível completar está ação.',
       'link'    => '',
    );
    die(json_encode($response));
}                   

                   