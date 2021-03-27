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
    $response = array(
       'status'  => 'error',
       'message' => 'Você não tem permissão para realizar está ação.',
       'link'    => '',
    );
    die(json_encode($response));   
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

if( empty($_POST['settings-properties-social-name']) or !isset($_POST['settings-properties-social-name']) )
{
    $error_message .= '<p>Razão Social é um campo de preenchimento obrigatório.</p>';
}
else
{
    $social_name = filterString($_POST['settings-properties-social-name'], 'CHAR');
}

if( empty($_POST['settings-properties-creci']) or !isset($_POST['settings-properties-creci']) )
{
    $error_message .= '<p>CRECI é um campo de preenchimento obrigatório.</p>';
}
else
{
    $creci = filterString($_POST['settings-properties-creci'], 'CHAR');
}

if( empty($_POST['settings-properties-cnpj']) or !isset($_POST['settings-properties-cnpj']) )
{
    $error_message .= '<p>CNPJ é um campo de preenchimento obrigatório.</p>';
}
else
{
    $cnpj = filterString($_POST['settings-properties-cnpj'], 'CHAR');
}

if( empty($_POST['settings-properties-address-street']) or !isset($_POST['settings-properties-address-street']) )
{
    $error_message .= '<p>Rua é um campo de preenchimento obrigatório.</p>';
}
else
{
    $street = filterString($_POST['settings-properties-address-street'], 'CHAR');
}

if( empty($_POST['settings-properties-address-street-number']) or !isset($_POST['settings-properties-address-street-number']) )
{
    $street_number = '';
}
else
{
    $street_number = filterString($_POST['settings-properties-address-street-number'], 'CHAR');
}

if( empty($_POST['settings-properties-address-neighborhood']) or !isset($_POST['settings-properties-address-neighborhood']) )
{
    $error_message .= '<p>Bairro é um campo de preenchimento obrigatório.</p>';
}
else
{
    $neighborhood = filterString($_POST['settings-properties-address-neighborhood'], 'CHAR');
}

if( empty($_POST['settings-properties-address-state']) or !isset($_POST['settings-properties-address-state']) )
{
    $error_message .= '<p>Estado é um campo de preenchimento obrigatório.</p>';
}
else
{
    $state = filterString($_POST['settings-properties-address-state'], 'CHAR');
}

if( empty($_POST['settings-properties-address-city']) or !isset($_POST['settings-properties-address-city']) )
{
    $error_message .= '<p>Cidade é um campo de preenchimento obrigatório.</p>';
}
else
{
    $city = filterString($_POST['settings-properties-address-city'], 'CHAR');
}

if( empty($_POST['settings-properties-postal-code']) or !isset($_POST['settings-properties-postal-code']) )
{
    $error_message .= '<p>CEP é um campo de preenchimento obrigatório.</p>';
}
else
{
    $postal_code = filterString($_POST['settings-properties-postal-code'], 'CHAR');
}
//--------------------------------------------------------------------------

if( !empty($error_message) )
{
    $response = array(
        'status'  => 'warning',
        'message' => $error_message,
        'link'    => '',
    );
    die(json_encode($response));
}

$system_id = SYSTEM_ID;

$con_db = new config\connect_db();
$con = $con_db->connect();

$update_settings = $con->prepare("UPDATE settings_properties SET company_social_name = ?, company_creci = ?, company_cnpj = ?, company_street = ?, company_street_number = ?, company_neighborhood = ?, company_state = ?, company_city = ?, company_postal_code = ?, user_update = ?, date_update = ? WHERE unique_id = ?");
$update_settings->bind_param('sssssssssiss', $social_name, $creci, $cnpj, $street, $street_number, $neighborhood, $state, $city, $postal_code, $_SESSION['user_id'], $date_time, $system_id);
$rows = $update_settings->affected_rows;
$update_settings->close();

if($update_settings)
{
    $activity_link = null;
        
    $register_activity = new app\controls\activityRecord();
    $register_activity->record ($_SESSION['user_id'], 'public', 'update-system-configurations', 'alterou os dados da empresa', $activity_link);
    
    $response = array(
        'status'  => 'success',
        'message' => 'Dados atualizados',
        'link'    => '',
    );
    die(json_encode($response));
}
else
{
    $response = array(
        'status'  => 'warning',
        'message' => 'Não foi possível atualizar os dados.',
        'link'    => '',
    );
    die(json_encode($response));
}
