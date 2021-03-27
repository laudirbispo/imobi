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
    if(!isset($_SESSION['properties_create']) or $_SESSION['properties_create'] !== 'Y' )
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


if( empty($_POST['properties-situation']) or !isset($_POST['properties-situation']) )
{
    $error_message .= '<p>Informe a situação do imóvel.</p>';

}
else
{
    $situation = filterString($_POST['properties-situation'], 'CHAR');
}
//--------------------------------------------------------------------------

if( empty($_POST['properties-finality']) or !isset($_POST['properties-finality']) )
{
    $error_message .= '<p>Informe a finalidade do imóvel.</p>';

}
else
{
    $finality = filterString($_POST['properties-finality'], 'CHAR');
}

//--------------------------------------------------------------------------

if( empty($_POST['properties-segment']) or !isset($_POST['properties-segment']) )
{
    $error_message .= '<p>Informe o segmento do imóvel.</p>';

}
else
{
    $segment = filterString($_POST['properties-segment'], 'CHAR');
}
//--------------------------------------------------------------------------

if( empty($_POST['properties-type']) or !isset($_POST['properties-type']) )
{
    $error_message .= '<p>Informe o tipo do imóvel.</p>';

}
else
{
    $type = filterString($_POST['properties-type'], 'CHAR');
}
//--------------------------------------------------------------------------

if( empty($_POST['properties-owner']) or !isset($_POST['properties-owner']) )
{
    $error_message .= '<p>Por favor, nos informe o proprietário do imóvel.</p>';

}
else
{
    $owner = base64_decode($_POST['properties-owner']);
    $owner = filterString($owner, 'INT');
}
//--------------------------------------------------------------------------

if( !empty($_POST['properties-value-total']) or isset($_POST['properties-value-total']) )
{
    $value_total = filterString($_POST['properties-value-total'], 'CHAR');
    $value_total = moedaDecimal($value_total);
}
else
{
    $value_total = 0;
}
//--------------------------------------------------------------------------

$hidden_value_total = (isset($_POST['properties-hidden-value-total'])) ? 'Y' : 'N' ;

//--------------------------------------------------------------------------

if( empty($_POST['properties-value-monthly']) or !isset($_POST['properties-value-monthly']) )
{
    $value_monthly = 0;
}
else
{
    $value_monthly = filterString($_POST['properties-value-monthly'], 'CHAR');
    $value_monthly = moedaDecimal($value_monthly);
}
//--------------------------------------------------------------------------

$hidden_value_monthly = (isset($_POST['properties-hidden-value-monthly'])) ? 'Y' : 'N' ;


//--------------------------------------------------------------------------

if( ($_POST['properties-finality'] == 'temporada') or ($_POST['properties-finality'] == 'aluguel') or ($_POST['properties-finality'] == 'arrendamento') )
{
    if( empty($_POST['properties-value-monthly']) and !isset($_POST['properties-hidden-value-monthly']) )
    {
        $error_message .= '<p>Informe o valor do aluguel ou marque a caixa sob consulta.</p>';
    }
	if( empty($_POST['properties-value-refers']) or !isset($_POST['properties-value-refers']) )
    {
        $error_message .= '<p>Informe a quantidade de tempo que se refere o valor do aluguel.</p>';
    }
    else
    {
        $value_refers = filterString($_POST['properties-value-refers'], 'CHAR');
    }
}

//--------------------------------------------------------------------------

if( empty($_POST['properties-material']) or !isset($_POST['properties-material']) )
{
    $material = '';
}
else
{
    $material = filterString($_POST['properties-material'], 'CHAR');
}
//--------------------------------------------------------------------------

if( empty($_POST['properties-total-area']) or !isset($_POST['properties-total-area']) )
{
    $total_area = '';
}
else
{
        $total_area = filterString($_POST['properties-total-area'], 'CHAR');
    
}
//--------------------------------------------------------------------------

if( empty($_POST['properties-construct-area']) or !isset($_POST['properties-construct-area']) )
{
    $construct_area = '';
}
else
{
        $construct_area = filterString($_POST['properties-construct-area'], 'CHAR');   
    
}
//--------------------------------------------------------------------------

if( empty($_POST['properties-number-rooms']) or !isset($_POST['properties-number-rooms']) )
{
    $number_rooms = 0;
}
else
{
    if ( is_numeric($_POST['properties-number-rooms']) )
    {
        $number_rooms = filterString($_POST['properties-number-rooms'], 'INT');
    }
    else
    {
        $error_message .= '<p>O campo Quartos deve conter somente números </p>';
    }
    
}
//--------------------------------------------------------------------------

if( empty($_POST['properties-number-bathrooms']) or !isset($_POST['properties-number-bathrooms']) )
{
    $number_bathrooms = 0;
}
else
{
    if ( is_numeric($_POST['properties-number-bathrooms']) )
    {
        $number_bathrooms = filterString($_POST['properties-number-bathrooms'], 'INT');
    }
    else
    {
        $error_message .= '<p>O campo Banheiros deve conter somente números </p>';
    }
    
}
//--------------------------------------------------------------------------

if( empty($_POST['properties-number-garage']) or !isset($_POST['properties-number-garage']) )
{
    $number_garage = 0;
}
else
{
    if ( is_numeric($_POST['properties-number-garage']) )
    {
        $number_garage = filterString($_POST['properties-number-garage'], 'INT');
    }
    else
    {
        $error_message .= '<p>O campo Garagem deve conter somente números </p>';
    }
    
}
//--------------------------------------------------------------------------

if( empty($_POST['properties-number-suites']) or !isset($_POST['properties-number-suites']) )
{
    $number_suites = 0;
}
else
{
    if ( is_numeric($_POST['properties-number-suites']) )
    {
        $number_suites = filterString($_POST['properties-number-suites'], 'INT');
    }
    else
    {
        $error_message .= '<p>O campo Suites deve conter somente números </p>';
    }
    
}
//--------------------------------------------------------------------------

if( !empty($_POST['properties-condominium-value']) or isset($_POST['properties-condominium-value']) )
{
    $condominium_value = filterString($_POST['properties-condominium-value'], 'CHAR');
    $condominium_value = moedaDecimal($condominium_value);
}
else
{
    $condominium_value = 0;
}

//--------------------------------------------------------------------------

$hidden_condominium_value = (isset($_POST['properties-hidden-condominium-value'])) ? 'Y' : 'N' ;

if( empty($_POST['properties-finalization-date']) or !isset($_POST['properties-finalization-date']) )
{
    $finalization_date = null;
}
else
{
    $finalization_date = filterString($_POST['properties-finalization-date'], 'CHAR');
    $finalization_date = date("Y-m-d", strtotime($finalization_date));
}

//--------------------------------------------------------------------------

    
if( empty($_POST['properties-address-state']) or !isset($_POST['properties-address-state']))
{
    $error_message .= '<p>Informe o Estado na caixa de endereço.</p>';
}
else
{
    $state = filterString($_POST['properties-address-state'], 'CHAR');
}
//---------------------------------------------------------------------------------------

if( empty($_POST['properties-address-city']) or !isset($_POST['properties-address-city']))
{
    $error_message .= '<p>Informe a cidade.</p>';
}
else
{
    $city = filterString($_POST['properties-address-city'], 'CHAR');
}
//---------------------------------------------------------------------------------------

if( empty($_POST['properties-address-neighborhood']) or !isset($_POST['properties-address-neighborhood']))
{
    $neighborhood = null;
}
else
{
    $neighborhood = filterString($_POST['properties-address-neighborhood'], 'CHAR');
}
//---------------------------------------------------------------------------------------

if( empty($_POST['properties-address-street']) or !isset($_POST['properties-address-street']))
{
    $street = null;
}
else
{
    $street = filterString($_POST['properties-address-street'], 'CHAR');
}
//---------------------------------------------------------------------------------------

if( empty($_POST['properties-address-postal-code']) or !isset($_POST['properties-address-postal-code']))
{
    $postal_code = '';
}
else
{
    $postal_code = filterString($_POST['properties-address-postal-code'], 'CHAR');
}
//---------------------------------------------------------------------------------------

if( empty($_POST['properties-address-number']) or !isset($_POST['properties-address-number']))
{
    $number = null;
}
else
{
    $number = filterString($_POST['properties-address-number'], 'CHAR');
}

//---------------------------------------------------------------------------------------

//locais próximos
$near_hospital = (isset($_POST['near-hospital'])) ? 'Y' : 'N' ;
$near_restaurant = (isset($_POST['near-restaurant'])) ? 'Y' : 'N' ;
$near_bakery = (isset($_POST['near-bakery'])) ? 'Y' : 'N' ;
$near_road = (isset($_POST['near-road'])) ? 'Y' : 'N' ;
$near_fuel = (isset($_POST['near-fuel'])) ? 'Y' : 'N' ;
$near_fast_food = (isset($_POST['near-fast-food'])) ? 'Y' : 'N' ;
$near_beach = (isset($_POST['near-beach'])) ? 'Y' : 'N' ;
$near_summer_club = (isset($_POST['near-summer-club'])) ? 'Y' : 'N' ;
$near_airport = (isset($_POST['near-airport'])) ? 'Y' : 'N' ;
$near_school = (isset($_POST['near-school'])) ? 'Y' : 'N' ;
$near_university = (isset($_POST['near-university'])) ? 'Y' : 'N' ;
$near_shopping = (isset($_POST['near-shopping'])) ? 'Y' : 'N' ;
$near_supermarket = (isset($_POST['near-supermarket'])) ? 'Y' : 'N' ;
$near_park = (isset($_POST['near-park'])) ? 'Y' : 'N' ;
$near_drogstore = (isset($_POST['near-drogstore'])) ? 'Y' : 'N' ;
$near_academy = (isset($_POST['near-academy'])) ? 'Y' : 'N' ;
$near_bank = (isset($_POST['near-bank'])) ? 'Y' : 'N' ;
$near_police = (isset($_POST['near-police'])) ? 'Y' : 'N' ;
$near_firefighter = (isset($_POST['near-firefighter'])) ? 'Y' : 'N' ;

//--------------------------------------------------------------------------

if (isset($_POST['properties-save-coords']))
{
    $coor_lat =  filterString($_POST['txtLatitude'], 'CHAR'); 
    $coor_lon =  filterString($_POST['txtLongitude'], 'CHAR'); 
}
else
{
    $coor_lat = null; 
    $coor_lon = null; 
}

if( empty($_POST['properties-details']) or !isset($_POST['properties-details']) )
{
    $details = null;
}
else
{
    require_once ($_SERVER['DOCUMENT_ROOT'].'/libs/HTMLPurifier/HTMLPurifier.auto.php');
    $HTMLPurifier_config = HTMLPurifier_Config::createDefault();
    $purifier = new HTMLPurifier($HTMLPurifier_config);
    $details = $purifier->purify($_POST['properties-details']);
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

$ref = uniqueAlfa($length=8);

$con_db = new config\connect_db();
$con = $con_db->connect();

$insert_properties = $con->prepare(" INSERT INTO properties (owner_id, situation, finality, segment, type, ref, value_total, hidden_value_total, value_monthly, hidden_value_monthly, value_refers, construction_material, total_area, construct_area, number_rooms, number_bathrooms, number_garage, number_suites, condominium_value, hidden_condominium_value, finalization_date, coor_lat, coor_lon, address_state, address_city, address_street, address_number, address_neighborhood, address_postal_code, confirm_address, details, date_post, user_post) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ") or die(mysqli_error($con));
$insert_properties->bind_param('isssssisisssssiiiiisssssssssssssi',$owner, $situation, $finality, $segment, $type, $ref, $value_total, $hidden_value_total, $value_monthly, $hidden_value_monthly, $value_refers, $material,  $total_area, $construct_area, $number_rooms, $number_bathrooms, $number_garage, $number_suites, $condominium_value, $hidden_condominium_value, $finalization_date, $coor_lat, $coor_lon, $state, $city, $street, $number, $neighborhood, $postal_code, $confirm_address, $details, $date_time, $_SESSION['user_id']);
$insert_properties->execute();
$affected_rows = $insert_properties->affected_rows;
$insert_id = $insert_properties->insert_id;
$insert_properties->close();

if($insert_properties and $affected_rows > 0)
{
    $places = $con->prepare("INSERT INTO properties_places (properties_id, hospital, restaurant, bakery, road, fuel, fast_food, beach, summer_club, airport, school, university, shopping, supermarket, park, drogstore, academy, bank, police, firefighter) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ");
    $places->bind_param('isssssssssssssssssss', $insert_id, $near_hospital, $near_restaurant, $near_bakery, $near_road, $near_fuel, $near_fast_food, $near_beach, $near_summer_club, $near_airport, $near_school, $near_university, $near_shopping, $near_supermarket, $near_park, $near_drogstore, $near_academy, $near_bank, $near_police, $near_firefighter);
    $places->execute();
    $rows = $places->affected_rows;
    $places->close();
    
    $dir = $_SERVER['DOCUMENT_ROOT'].'/docs/properites/'.$insert_id;

    if(!file_exists($dir))
    {
        if(mkdir($dir, 0777, true))
        {
            mkdir($dir.'/small', 0777, true);
            mkdir($dir.'/big', 0777, true);
        }
    }

    if($places and $rows > 0)
    {
        $activity_link = '/app/admin/view_property/'.base64_encode($insert_id);
        
        $register_activity = new app\controls\activityRecord();
        $register_activity->record ($_SESSION['user_id'], 'public', 'post-properties', 'adicionou um novo imóvel', $activity_link);
        
        $response = array(
            'status'  => 'success',
            'message' => 'Imóvel cadastrado',
            'link'    => base64_encode($insert_id),
        );
        die(json_encode($response));
    }
    else
    {
        $response = array(
            'status'  => 'info',
            'message' => 'Imóvel cadastrado, mas com algumas falhas.<br> Os locais próximos não puderam ser configurados.',
            'link'    => base64_encode($insert_id),
        );
        die(json_encode($response));
    }
    
}
else
{
    $response = array(
        'status'  => 'error',
        'message' => 'Não .',
        'link'    => '',
    );
    die(json_encode($response));
}
