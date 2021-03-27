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
    if(!isset($_SESSION['clients_view']) or $_SESSION['clients_view'] !== 'Y' )
    {
        $response = array(
           'status'  => 'error',
           'message' => 'Você não tem permissão para visualizar informações sobre clients.',
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

$get_info = $con->prepare("SELECT client_type, client_social_name, client_cnpj, client_fantasy_name, client_name, client_last_name, client_cpf, client_rg, client_nationality, client_marital_status, client_company_name, client_address_street, client_address_street_number, client_address_neighborhood, client_address_city, client_address_state, client_postal_code, client_address_complement, client_contact_phone_1, client_contact_phone_2, client_contact_email, client_observations FROM clients WHERE id = ?");
$get_info->bind_param('i', $actionid);
$get_info->execute();
$get_info->store_result();
$get_info->bind_result($type, $social_name, $cnpj, $fantasy, $name, $last_name, $cpf, $rg, $nationality, $marital, $company_name, $street, $street_number, $neighborhood, $city, $state, $postal_code, $complement, $phone_1, $phone_2, $email, $obs);
$get_info->fetch();
$rows = $get_info->num_rows;
$get_info->free_result();
$get_info->close();

if ($get_info and $rows > 0)
{
    $address = array();
    $address[] = ($street != null) ? $street : 'rua não informada';
    $address[] = ($street_number != null) ? $street_number : 'nº não informado';
    $address[] = ($neighborhood != null) ? $neighborhood : 'bairro não informado';
    $address[] = ($city != null) ? $city : 'cidade não informada';
    $address[] = ($state != null) ? $state : 'estado não informado';
    $address[] = ($postal_code != null) ? $postal_code : 'CEP não informado';
    
    $address = implode(', ', $address);
    
    $name = ($name != null) ? $name : 'não informado';
    $last_name = ($last_name != null) ? $last_name : '';
    $name = $name.' '.$last_name;  
    if($type !== null)
    {
        if($type === 'physical') $type = 'Pessoa Física';
        if($type === 'juridical') $type = 'Pessoa Jurídica';
        if($type === 'other') $type = 'Pessoa Física/Jurídica';
    }
    else
    {
        $type = 'não informado';
    } 
    $social_name = ($social_name != null) ? $social_name : 'não informado';
    $cnpj = ($cnpj != null) ? valToMask($cnpj,'##.###.###/####-##') : 'não informado';
    $fantasy = ($fantasy != null) ? $fantasy : 'não informado';
    $cpf = ($cpf != null) ? valToMask($cpf,'###.###.###-##') : 'não informado';
    $rg = ($rg != null) ? valToMask($rg,'##.###.###.#') : 'não informado';
    $nationality = ($nationality != null) ? $nationality : 'não informado';
    $marital = ($marital != null) ? $marital : 'não informado';
    $company_name = ($company_name != null) ? $company_name : 'não informado';
    $complement = ($complement != null) ? $complement : 'não informado';
    $phone_1 = ($phone_1 != null) ? $phone_1 : 'não informado';
    $phone_2 = ($phone_2 != null) ? $phone_2 : 'não informado';
    $phones = $phone_1. ' / '.$phone_2;
    $email = ($email != null) ? $email : 'não informado';
    $obs = ($obs != null) ? $obs : 'não informado';
    
    if ($marital != null)
    {
        if ($marital == 'married')
        {
            $marital = 'Casado(a)';
        }
        else if ($marital == 'separate')
        {
            $marital = 'Separado(a)';
        }
        else if ($marital == 'divorced')
        {
            $marital = 'Divorciado(a)';
        }
        else if ($marital == 'widower')
        {
            $marital = 'Viúvo(a)';
        }
        else
        {
            $marital = 'não declarado';
        }
    }
    else
    {
        $marital = 'não informado';
    }
    
    $response = array(
        'client_id' => base64_encode($actionid),
        'status'  => 'success',
        'message' => 'Não encontramos nenhum registro com os dados fornecidos.',
        'name'    => $name,
        'rg'      => $rg,
        'nationality' => $nationality,
        'type'    => $type,
        'cnpj'    => $cnpj,
        'fantasy' => $fantasy,
        'cpf'     => $cpf,
        'marital' => $marital,
        'company' => $company_name,
        'social'  => $social_name,
        'address' => $address,
        'complement' => $complement,
        'phones'    => $phones,
        'email'    => $email,
        'obs'    => $obs,
     );
     die(json_encode($response));
}
else
{
    $response = array(
        'status'  => 'warning',
        'message' => 'Não encontramos nenhum registro com os dados fornecidos.',
        'link'    => '',
     );
     die(json_encode($response));
}
