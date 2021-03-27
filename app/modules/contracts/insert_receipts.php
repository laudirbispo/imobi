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

if (!isset($_POST['contract-id']) or empty($_POST['contract-id']))
{
    $response = array(
        'status'  => 'warning',
        'message' => 'É preciso identificar o contrato.',
        'link'    => '',
     );
     die(json_encode($response));
}
else
{
    $contract_id = filterString($_POST['contract-id'], 'CHAR');
}

//--------------------------------------------------------------------------

if (
     !isset($_POST['receipts-gross']) or 
     !isset($_POST['receipts-due']) or
     !isset($_POST['receipts-discount']) or
     !isset($_POST['discount-cause']) or
     !isset($_POST['receipts-addition']) or
     !isset($_POST['additions-cause']) 
   ){
    
    die(json_encode($response = array('status'  => 'warning', 'message' => 'Preencha todos os campos.', 'link' => '')));
}

$n = count($_POST['receipts-gross']);

$con_db = new config\connect_db();
$con = $con_db->connect();

$insert_receipts = $con->prepare("INSERT IGNORE INTO receipts (contract_id, situation, value_gross, discount, discount_cause, addition, addition_cause, due_date, date_post, user_post) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

for ($i = 0; $i < $n; $i++)
{
    if (!empty($_POST['receipts-gross'][$i]))
    {
        $value_gross = filterString($_POST['receipts-gross'][$i], 'CHAR');
        $value_gross = moedaDecimal($value_gross);
    }
    else
    {
        die(json_encode($response = array('status'  => 'warning', 'message' => $_POST['receipts-gross'][$i].' Ação interrompida! Preencha todos os campos marcados como obrigatório.', 'link' => '')));
    }  
    
    if (!empty($_POST['receipts-due'][$i]))
    {
        $due_date = filterString($_POST['receipts-due'][$i], 'CHAR');
        $due_date = inverteData($due_date);
        
        $today = strtotime(date('Y-m-d'));
        $situation = (strtotime($due_date) < $today) ? 'expired' : 'open' ;    
    }
    else
    {
        die(json_encode($response = array('status'  => 'warning', 'message' => 'Ação interrompida! Preencha todos os campos marcados como obrigatório.', 'link' => '')));
    }
    
    if (!empty($_POST['receipts-discount'][$i]))
    {
        $discount = filterString($_POST['receipts-discount'][$i], 'CHAR');
        $discount = moedaDecimal($discount);
    }
    else
    {
        $discount = 0;
    } 
    
    if (!empty($_POST['discount-cause'][$i]))
    {
        $discount_cause = filterString($_POST['discount-cause'][$i], 'CHAR');
    }
    else
    {
        $discount_cause = NULL;
    }
    
    if (!empty($_POST['receipts-addition'][$i]))
    {
        $addition = filterString($_POST['receipts-addition'][$i], 'CHAR');
        $addition = moedaDecimal($addition);
    }
    else
    {
        $addition = 0;
    } 
    
    if (!empty($_POST['additions-cause'][$i]))
    {
        $additions_cause = filterString($_POST['additions-cause'][$i], 'CHAR');
    }
    else
    {
        $additions_cause = NULL;
    }
    
    $insert_receipts->bind_param('ssiisisssi', $contract_id, $situation, $value_gross, $discount, $discount_cause, $addition, $additions_cause, $due_date, $date_time, $_SESSION['user_id']);
    $insert_receipts->execute();
    
    if ($insert_receipts->affected_rows <= 0) die(json_encode($response = array('status'  => 'warning', 'message' => 'Ação interrompida! Certifique-se que não há mais de um recibo com a mesma data de vencimento.', 'link' => '')));
    
}// laço for

$insert_receipts->close();                    

$activity_link = '/app/admin/contracts_details/'.$contract_id;
        
$register_activity = new app\controls\activityRecord();
$register_activity->record ($_SESSION['user_id'], 'private', 'insert-receipts', 'adicionou alguns recibos.', $activity_link);
                    
die(json_encode($response = array('status'  => 'success', 'message' => 'Recibos adicionados', 'link' => '')));                    