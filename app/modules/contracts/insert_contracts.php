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

//--------------------------------------------------------------------------

$error_message = NULL;

if (empty($_POST['contract-type']) or !isset($_POST['contract-type']))
{
    $error_message .= '<p>Por favor, informe o tipo de contrato.</p>';
}
else
{
    $contract_type = filterString($_POST['contract-type'], 'CHAR');
}

if (empty($_POST['client-owner']) or !isset($_POST['client-owner']))
{
    $error_message .= '<p>Por favor, informe o proprietário do imóvel.</p>';
}
else
{
    $client_owner = filterString($_POST['client-owner'], 'CHAR');
    $client_owner = base64_decode($client_owner);
}

if (empty($_POST['client-tenant']) or !isset($_POST['client-tenant']))
{
    $client_tenant = NULL;
}
else
{
    $client_tenant = filterString($_POST['client-tenant'], 'CHAR');
    $client_tenant = base64_decode($client_tenant);
}

if (empty($_POST['contract-property']) or !isset($_POST['contract-property']))
{
    $contract_property = NULL;
}
else
{
    $contract_property = filterString($_POST['contract-property'], 'CHAR');
}

if (empty($_POST['contract-start-date']) or !isset($_POST['contract-start-date']))
{
    $error_message .= '<p>Por favor, informe a data de início da vigência do contrato.</p>';
}
else
{
    $contract_start_date = filterString($_POST['contract-start-date'], 'CHAR');
    $contract_start_date = inverteData($contract_start_date);
}

if (empty($_POST['contract-end-date']) or !isset($_POST['contract-end-date']))
{
    $contract_end_date = NULL;
}
else
{
    $contract_end_date = filterString($_POST['contract-end-date'], 'CHAR');
    $contract_end_date = inverteData($contract_end_date);
}

if (empty($_POST['contract-text']) or !isset($_POST['contract-text']))
{
    $error_message .= '<p>Por favor, digitalize o contrato no campo indicado.</p>';
}
else
{
    // purificamos os texto do contrato
    require_once ($_SERVER['DOCUMENT_ROOT'].'/libs/HTMLPurifier/HTMLPurifier.auto.php');
    $HTMLPurifier_config = HTMLPurifier_Config::createDefault();
    $purifier = new HTMLPurifier($HTMLPurifier_config);
    $contract_text = $purifier->purify($_POST['contract-text']);
 
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

$contract_id = uniqueAlfa($length=8);
$pdf_name = time().mt_rand(11111, 99999).'.pdf';

$dir_contracts = '/contracts';
$dir_new_contract = $dir_contracts.'/'.$contract_id;

$file_temp = $_SERVER['DOCUMENT_ROOT'].'/tmp/'.$pdf_name;

$ftp_connect = ftp_connect(SUBDOMAIN_FTP) or die(json_encode($response = array(
    'status'  => 'error',
    'message' => 'Não foi possível se conectar ao servidor! Tente novamente mais tarde.',
    'link'    => '',
)));
ftp_login($ftp_connect, USER_FTP, PASS_FTP);

createDirFTP($dir_contracts, $action = 'create');
createDirFTP($dir_new_contract, $action = 'create');

// criamos o PDF
require_once($_SERVER['DOCUMENT_ROOT'].'/libs/mPDF/mpdf.php');
@$mpdf = new mPDF();
@$mpdf->SetCreator('Gerenciador imobiliário - EASY MOBI');
@$mpdf->WriteHTML($contract_text);
@$mpdf->Output($file_temp, 'F');

$file_path = $dir_new_contract.'/'.$pdf_name;

ftp_put( $ftp_connect, $file_path, $file_temp, FTP_BINARY );

DeleteFiles($file_temp);

$con_db = new config\connect_db();
$con = $con_db->connect();

$insert_contract = $con->prepare("INSERT INTO contracts (`contract_id`, `owner_id`, `tenant_id`, `property_ref`, `valid_start`,  `type`, `date_post`, `user_post`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$insert_contract->bind_param('siissssi', $contract_id, $client_owner, $client_tenant, $contract_property, $contract_start_date, $contract_type, $date_time, $_SESSION['user_id']);
$insert_contract->execute();
$last_id = $insert_contract->insert_id;
$rows_insert_contract = $insert_contract->affected_rows;
$insert_contract->close();

if ($insert_contract and $rows_insert_contract > 0)
{
    $insert_pdf = $con->prepare("INSERT INTO contracts_docs (`contract_id`, `file_path`, `date_end`, `date_post`, `user_post`) VALUES (?, ?, ?, ?, ?)");
    $insert_pdf->bind_param('ssssi', $contract_id, $file_path, $contract_end_date, $date_time, $_SESSION['user_id']);
    $insert_pdf->execute();
    $rows_insert_pdf = $insert_pdf->affected_rows;
    $insert_pdf->close();
    
    if ($insert_pdf and $rows_insert_pdf > 0)
    {
        ftp_close($ftp_connect);
        
        $activity_link = '/app/admin/contracts_details/'.$contract_id;
        
        $register_activity = new app\controls\activityRecord();
        $register_activity->record ($_SESSION['user_id'], 'private', 'insert-contracts', 'adicionou um novo contrato.', $activity_link);
        
        $response = array(
            'status'  => 'success',
            'message' => 'Contrado adicionado com sucesso',
            'link'    => '',
        );
        die(json_encode($response));
    }
    else
    {
        $delete = $con->prepare("DELETE FROM contracts WHERE id = ?");
        $delete->bind_param('i', $last_id);
        $delete->execute();
        
        if (file_exists('ftp://'.USER_FTP.':'.PASS_FTP.'@'.SUBDOMAIN_FTP.$file_path))
        {
            ftp_delete($ftp_connect, $file_path);
        }
        
        ftp_close($ftp_connect);
        $response = array(
            'status'  => 'error',
            'message' => 'Não foi possível adicionar o contrato! Tente novamente mais tarde.',
            'link'    => '',
        );
        die(json_encode($response));
    }
}
else
{
    if (file_exists('ftp://'.USER_FTP.':'.PASS_FTP.'@'.SUBDOMAIN_FTP.$file_path))
    {
        ftp_delete($ftp_connect, $file_path);
    }

    ftp_close($ftp_connect);
    $response = array(
        'status'  => 'error',
        'message' => 'Não foi possível adicionar o contrato! Tente novamente mais tarde.',
        'link'    => '',
    );
    die(json_encode($response));   
}