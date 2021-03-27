<?php
require_once ($_SERVER['DOCUMENT_ROOT'].'/config/config.php');
session_name(SESSION_NAME);
session_start();
header('Content-Type: application/json');
require_once ($_SERVER['DOCUMENT_ROOT'].'/config/autoload.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/config/public_functions.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/app/controls/adminFunctions.php');

use config\connect_db;
use app\controls\activityRecord;

if( !isset($_POST['form-token']) or $_POST['form-token'] != $_SESSION['secret_form_token'] )
{
     $response = array(
        'status'  => 'error',
        'message' => 'A origem de alguns dados nos parece duvidosa! Por isso bloqueamos está ação.',
        'link'    => '',
      );
     die(json_encode($response));
}

if( empty($_POST['user_id']) or !isset($_SESSION['user_id']) or $_POST['user_id'] != $_SESSION['user_id'] )
{
    $response = array(
        'status'  => 'error',
        'message' => 'É necessário estar logado para completar está ação.',
        'link'    => '',
     );
     die(json_encode($response));
}

//***************************************************************************

$error_message = '';

if( empty($_POST['profile-name']) or !isset($_POST['profile-name']) )
{
    $error_message .= '<p>Precisamos saber seu nome.</p>';
}
else
{
    $profile_name = filterString($_POST['profile-name'], 'CHAR');
}

if( empty($_POST['profile-facebook']) )
{
    $profile_facebook = '';
}
else
{
    $profile_facebook = filter_var($_POST['profile-facebook'], FILTER_SANITIZE_URL);
}

if( empty($_POST['profile-google']) )
{
    $profile_google = '';
}
else
{
    $profile_google = filter_var($_POST['profile-google'], FILTER_SANITIZE_URL);
    if(valideuRl($profile_google) === false)
    {
        $error_message .= '<p>O endereço do seu perfil no Google+ não é vãlido.</p>';
    }
}

if( empty($_POST['profile-twitter']) )
{
    $profile_twitter = '';
}
else
{
    $profile_twitter = filter_var($_POST['profile-twitter'], FILTER_SANITIZE_URL);
    if(valideuRl($profile_twitter) === false)
    {
        $error_message .= '<p>O endereço do seu perfil no Twitter não é vãlido.</p>';
    }
}

if( empty($_POST['profile-linkedin']) )
{
    $profile_linkedin = '';
}
else
{
    $profile_linkedin = filter_var($_POST['profile-linkedin'], FILTER_SANITIZE_URL);
    if(valideuRl($profile_linkedin) === false)
    {
        $error_message .= '<p>O endereço do seu perfil no LinkedIn não é vãlido.</p>';
    }
}

if( empty($_POST['profile-about']) )
{
    $profile_about = '';
}
else
{
    $profile_about = filterString($_POST['profile-about'], 'CHAR');
}
//*********************************************************************************

if( !empty($error_message) )
{
    $response = array(
        'status'  => 'warning',
        'message' => $error_message,
        'link'    => '',
     );
     die(json_encode($response));
}

$con_db = new config\connect_db();
$con = $con_db->connect();

$update_profile = $con->prepare("UPDATE `user_profile` SET `user_name` = ?, `user_profile_about` = ?, `user_profile_facebook` = ?, `user_profile_google` = ?, `user_profile_twitter` = ?, `user_profile_linkedin` = ? WHERE `user_id` = ? ");
$update_profile->bind_param('ssssssi', $profile_name, $profile_about, $profile_facebook, $profile_google, $profile_twitter, $profile_linkedin, $_SESSION['user_id']);
$update_profile->execute();
$update_profile_rows = $update_profile->affected_rows;
$update_profile->close();

if( !$update_profile )
{
    $response = array(
        'status'  => 'error',
        'message' => 'Ocorreu um erro não identificado durante o processamento do pedido.',
        'link'    => '',
    );
    die(json_encode($response));
}
else if( $update_profile )
{
    $register_activity = new activityRecord();
    
    $activity_link = '/app/admin/profile/'.base64_encode($_SESSION['user_id']);
    
    if( $_SESSION['user_name'] != $profile_name )
    { 
        $register_activity->record ($_SESSION['user_id'], 'public', 'update-profile', 'alterou seu nome de '.$_SESSION['user_name'].' para '.$profile_name, $activity_link);
        $_SESSION['user_name'] = $profile_name;
    }
    else
    {
        $register_activity->record ($_SESSION['user_id'], 'public', 'update-profile', 'atualizou seu perfil', $activity_link);
    }
    
    $response = array(
            'status'  => 'success',
            'message' => 'Informações atualizadas',
            'link'    => '',
        );
        die(json_encode($response));
}
else
{
    $response = array(
        'status'  => 'error',
        'message' => 'Ocorreu um erro não identificado durante o processamento do pedido.',
        'link'    => '',
    );
    die(json_encode($response));
}