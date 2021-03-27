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
    if(!isset($_SESSION['clients_edit']) or $_SESSION['clients_edit'] !== 'Y' )
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

if(!isset($_POST['actionid']) or empty($_POST['actionid']))
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
	$actionid = filterString(base64_decode($_POST['actionid']), 'INT');
}
//--------------------------------------------------------------------------

$error_message = NULL;

if( !isset($_POST['client-type']) )
{
    $error_message .= '<p>Escolha o tipo de cliente, pessoa fisica, jurídica ou ambos;</p>';
}
else
{
    $client_type = filterString($_POST['client-type'], 'CHAR');
}

//-----------------------------------------------------------------------------------

if( empty($_POST['client-fantasy-name']) or !isset($_POST['client-fantasy-name']) and $_POST['cliente-tipo'] !== 'juridical' )
{
    $client_fantasy_name = '';
}
elseif( empty($_POST['client-fantasy-name']) or !isset($_POST['client-fantasy-name']) and $_POST['client-type'] === 'juridical' )
{
    $error_message .= '<p>O campo razão social é obrigatório.</p>';
}
else
{
    $client_fantasy_name = filterString($_POST['client-fantasy-name'], 'CHAR');
}

if( empty($_POST['client-cnpj']) or !isset($_POST['client-cnpj']) )
{
    $client_cnpj = '';
}
else
{
    $client_cnpj = filterString($_POST['client-cnpj'], 'CHAR');
    $client_cnpj = cleanCpfCnpj($client_cnpj);
}

if( empty($_POST['client-social-name']) or !isset($_POST['client-social-name']) )
{
    $client_social_name = '';
}
else
{
    $client_social_name = filterString($_POST['client-social-name'], 'CHAR');
}

if( empty($_POST['client-responsible']) or !isset($_POST['client-responsible']) )
{
    $client_responsible = '';
}
else
{
    $client_responsible = filterString($_POST['client-responsible'], 'CHAR');
}

//-----------------------------------------------------------------------------------

if( ($_POST['client-type'] === 'physical') and (empty($_POST['client-name']) or !isset($_POST['client-name'])) )
{
    $error_message .= '<p>Um nome de cliente é necessário.d</p>';
}
elseif( (empty($_POST['client-name']) or !isset($_POST['client-name'])) and ($_POST['client-type'] === 'juridical' or $_POST['client-type'] === 'other') )
{
    $client_name = '';
}
else
{
    $client_name = filterString($_POST['client-name'], 'CHAR');
}

if( (empty($_POST['client-last-name']) or !isset($_POST['client-last-name'])) and ($_POST['client-type'] === 'physical') )
{
    $error_message .= '<p>o sobrenome do cliente é necessário.</p>';
}
elseif( empty($_POST['client-last-name']) or !isset($_POST['client-last-name']) and $_POST['client-type'] === 'juridical' or $_POST['client-type'] === 'other' )
{
    $client_last_name = '';
}
else
{
    $client_last_name = filterString($_POST['client-last-name'], 'CHAR');
}

if( (empty($_POST['client-genre']) or !isset($_POST['client-genre'])) and ($_POST['client-type'] === 'physical') )
{
    $error_message .= '<p>Informe o sexo do cliente.</p>';
}
elseif( ( empty($_POST['client-genre']) or !isset($_POST['client-genre']) ) and ( $_POST['client-type'] === 'juridical' or $_POST['client-type'] === 'other') )
{
    $client_genre = '';
}
else
{
    $client_genre = filterString($_POST['client-genre'], 'CHAR');
}

if( ( empty($_POST['client-marital-status']) or !isset($_POST['client-marital-status']) ) and ($_POST['client-type'] === 'physical') )
{
    $client_marital_status = '';
}
elseif( ( empty($_POST['client-marital-status']) or !isset($_POST['client-marital-status']) ) and ($_POST['client-type'] === 'juridical' or $_POST['client-type'] === 'other') )
{
    $client_marital_status = '';
}
else
{
    $client_marital_status = filterString($_POST['client-marital-status'], 'CHAR');
}

if( ( empty($_POST['client-nationality']) or !isset($_POST['client-nationality']) ) and ($_POST['client-type'] === 'physical') )
{
    $nationality = '';
}
elseif( ( empty($_POST['client-nationality']) or !isset($_POST['client-nationality']) ) and ($_POST['client-type'] === 'juridical' or $_POST['client-type'] === 'other') )
{
    $nationality = '';
}
else
{
    $nationality = filterString($_POST['client-nationality'], 'CHAR');
}

if( ( empty($_POST['client-is-employed']) or !isset($_POST['client-is-employed']) ) and ($_POST['client-type'] === 'physical') )
{
    $error_message .= '<p>Informe se o cliente esta empregado no momento.</p>';
}
elseif (( empty($_POST['client-is-employed']) or !isset($_POST['client-is-employed']) ) and ($_POST['client-type'] === 'juridical' or $_POST['client-type'] === 'other'))
{
    $client_is_employed = 'N';
}
else
{
    $client_is_employed = 'Y';
}

//-----------------------------------------------------------------------------------

if( empty($_POST['client-birth-date']) or !isset($_POST['client-birth-date']) )
{
    $client_birth_date = null;
}
else
{
    $client_birth_date = filterString($_POST['client-birth-date'], 'CHAR');
    $client_birth_date = inverteData($client_birth_date);
}


if( empty($_POST['client-rg']) or !isset($_POST['client-rg']) )
{
    $client_rg = '';
}
else
{
	
    $client_rg = filterString($_POST['client-rg'], 'CHAR');
    $client_rg = cleanCpfCnpj($client_rg);
}

if( empty($_POST['client-cpf']) or !isset($_POST['client-cpf']) )
{
    $client_cpf = '';
}
else
{
    $client_cpf = filterString($_POST['client-cpf'], 'CHAR');
    $client_cpf = cleanCpfCnpj($client_cpf);
}

//-----------------------------------------------------------------------------------

if( empty($_POST['client-company-name']) or !isset($_POST['client-company-name']) )
{
    $client_company_name = '';
}
else
{
    $client_company_name = filterString($_POST['client-company-name'], 'CHAR');
}

if( empty($_POST['client-company-position']) or !isset($_POST['client-company-position']) )
{
    $client_company_position = '';
}
else
{
    $client_company_position = filterString($_POST['client-company-position'], 'CHAR');
}

if( empty($_POST['client-company-start-date']) or !isset($_POST['client-company-start-date']) )
{
    $client_company_start_date = null;
}
else
{
    $client_company_start_date = filterString($_POST['client-company-start-date'], 'CHAR');
    $client_company_start_date = inverteData($client_company_start_date);
}

if( empty($_POST['client-company-contact']) or !isset($_POST['client-company-contact']) )
{
    $client_company_contact = '';
}
else
{
    $client_company_contact = filterString($_POST['client-company-contact'], 'CHAR'); 
}

//-----------------------------------------------------------------------------------

if( empty($_POST['client-address-street']) or !isset($_POST['client-address-street']) )
{
    $client_address_street = '';
}
else
{
    $client_address_street = filterString($_POST['client-address-street'], 'CHAR');
}

if( empty($_POST['client-address-street-number']) or !isset($_POST['client-address-street-number']) )
{
    $client_address_street_number = 's/n°';
}
else
{
    $client_address_street_number = filterString($_POST['client-address-street-number'], 'CHAR');
}

if( empty($_POST['client-address-neighborhood']) or !isset($_POST['client-address-neighborhood']) )
{
    $client_address_neighborhood = '';
}
else
{
    $client_address_neighborhood = filterString($_POST['client-address-neighborhood'], 'CHAR');
}

if( empty($_POST['client-address-state']) or !isset($_POST['client-address-state']) )
{
    $client_address_state = '';
}
else
{
    $client_address_state = filterString($_POST['client-address-state'], 'CHAR');
}

if( empty($_POST['client-address-city']) or !isset($_POST['client-address-city']) )
{
    $client_address_city = '';
}
else
{
    $client_address_city = filterString($_POST['client-address-city'], 'CHAR');
}

if( empty($_POST['client-postal-code']) or !isset($_POST['client-postal-code']) )
{
    $client_postal_code = '';
}
else
{
    $client_postal_code = filterString($_POST['client-postal-code'], 'CHAR');
}

if( empty($_POST['client-address-complement']) or !isset($_POST['client-address-complement']) )
{
    $client_address_complement = '';
}
else
{
    $client_address_complement = filterString($_POST['client-address-complement'], 'CHAR');
}

if( empty($_POST['client-address-reference']) or !isset($_POST['client-address-reference']) )
{
    $client_address_reference = '';
}
else
{
    $client_address_reference = filterString($_POST['client-address-reference'], 'CHAR');
}

if( empty($_POST['client-contact-phone-1']) or !isset($_POST['client-contact-phone-1']) )
{
    $client_contact_phone_1 = '';
}
else
{
    $client_contact_phone_1 = filterString($_POST['client-contact-phone-1'], 'CHAR');
}

if( empty($_POST['client-contact-phone-2']) or !isset($_POST['client-contact-phone-2']) )
{
    $client_contact_phone_2 = '';
}
else
{
    $client_contact_phone_2 = filterString($_POST['client-contact-phone-2'], 'CHAR');
}

if( empty($_POST['client-contact-email']) or !isset($_POST['client-contact-email']) )
{
    $client_contact_email = '';
}
else
{
    $client_contact_email = filterString($_POST['client-contact-email'], 'CHAR');
}

if( empty($_POST['client-observations']) or !isset($_POST['client-observations']) )
{
    $client_observations = '';
}
else
{
   $client_observations = filterString($_POST['client-observations'], 'CHAR');
}

//-----------------------------------------------------------------------------------

if( !empty($error_message) )
{
    $response = array(
        'status'  => 'error',
        'message' => $error_message,
        'link'    => '',
    );
    die(json_encode($response));            
}

$con_db = new config\connect_db();
$con = $con_db->connect();

$update_clients = $con->prepare('UPDATE clients SET client_type = ?, client_social_name = ?, client_cnpj = ?, client_fantasy_name = ?, client_responsible = ?, client_name = ?, client_last_name = ?, client_birth_date = ?, client_nationality = ?, client_genre = ?, client_cpf = ?, client_rg = ?, client_marital_status = ?, client_is_employed = ?, client_company_name = ?, client_company_position = ?, client_company_start_date = ?, client_company_contact = ?, client_address_street = ?, client_address_street_number = ?, client_address_neighborhood = ?, client_address_city = ?, client_address_state = ?, client_postal_code = ?, client_address_complement = ?, client_address_reference = ?, client_contact_phone_1 = ?, client_contact_phone_2 = ?, client_contact_email = ?, client_observations = ?, date_update = ?, user_update = ? WHERE id = ?');
$update_clients->bind_param('sssssssssssssssssssssssssssssssii', $client_type, $client_social_name, $client_cnpj, $client_fantasy_name, $client_responsible, $client_name, $client_last_name, $client_birth_date, $nationality, $client_genre, $client_cpf, $client_rg, $client_marital_status, $client_is_employed, $client_company_name, $client_company_position, $client_company_start_date, $client_company_contact, $client_address_street, $client_address_street_number, $client_address_neighborhood, $client_address_city, $client_address_state, $client_postal_code, $client_address_complement, $client_address_reference, $client_contact_phone_1, $client_contact_phone_2, $client_contact_email, $client_observations, $date_time, $_SESSION['user_id'], $actionid);
$update_clients->execute();
$update_clients->close();

if( $update_clients )
{   
    $response = array(
        'status'  => 'success',
        'message' => 'Informações atualizadas.',
        'link'    => '',
    );
    die(json_encode($response));
}
else
{
    $response = array(
        'status'  => 'error',
        'message' => 'Não foi possível atualizar as informações',
        'link'    => '',
    );
    die(json_encode($response));
}



