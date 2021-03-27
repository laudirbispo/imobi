<?php
require_once ($_SERVER['DOCUMENT_ROOT'].'/config/config.php');
session_name(SESSION_NAME);
session_start();
require_once ($_SERVER['DOCUMENT_ROOT'].'/config/autoload.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/config/public_functions.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/app/controls/adminFunctions.php');

use config\connect_db;

$con_db = new config\connect_db();
$con = $con_db->connect();

$activity = $con->query("SELECT su.login, su.id, su.type, up.user_name, up.user_profile_photo,ar.user_id, ar.datetime, ar.privacy, ar.activity, ar.activity_body, ar.activity_link FROM activity_record ar LEFT JOIN user_profile up ON (up.user_id = ar.user_id) LEFT JOIN sec_users su ON (su.id = ar.user_id) ORDER BY ar.id DESC LIMIT 15 ");
$rows = $activity->num_rows;

$ac = '';
while($reg = $activity->fetch_assoc())
{
    
    if($reg['privacy'] === 'public')
    {
        $privacy_icon = '<i class="fa fa-globe privacy-icon" title="Público"></i>';
    }
    else if ($reg['privacy'] === 'private')
    {
        if($reg['id'] != $_SESSION['user_id'] and $_SESSION['user_type'] != 'suporte')
        {
            continue;
        }
        else
        {
            $privacy_icon = '<i class="fa fa-lock privacy-icon" title="Privado"></i>';
        } 
       
    }
    else
    {
        $privacy_icon = '<i class="fa fa-low-vision privacy-icon" title="Somente eu"></i>'; 
    }
    
    $user_name = (empty($reg['user_name'])) ? $reg['login'] : $reg['user_name'] ;
        
    $user_img = (fileRemoteExist(SUBDOMAIN_IMGS.$reg['user_profile_photo']) === true and !empty($reg['user_profile_photo'])) ? SUBDOMAIN_IMGS.$reg['user_profile_photo'] : SUBDOMAIN_IMGS.'/defaults/default-user.png'; 
    
    if($reg['id'] == $_SESSION['user_id'])
    {
       $user_name = 'Você ';
    }
    
    if(!empty($reg['activity_link']))
    {
        $body = '<a href="'.$reg['activity_link'].'"><span class="text-white">'.$reg['activity_body'].'</span></a><br>';
    }
    else
    {
        $body = '<a href="javascript:;"><span class="text-white">'.$reg['activity_body'].'</span></a><br>';
    }
    
    $ac .= '<div class="media user-status">';
    $ac .= $privacy_icon;
    $ac .= '<div class="media-left media-middle">';
    $ac .= '<a href="/app/admin/profile/'.base64_encode($reg['user_id']).'">';
    $ac .= '<img class="media-object img-circle" width="48" height="48" src="'.$user_img.'" alt="Imagem perfil">';
    $ac .= '</a>';
    $ac .= '</div>';
    $ac .= '<div class="media-body text-white">';
    $ac .= '<a href="/app/admin/profile/'.base64_encode($reg['user_id']).'"><span class="small text-white"><strong>'.$user_name.' </strong></span></a>';
    $ac .= $body;
    $ac .= '<span class="small"><i class="fa fa-clock-o"></i> '.tempo_corrido($reg["datetime"]).'</span>';
    $ac .= '  </div>';
    $ac .= '</div>';
    
}

if($activity and $rows > 0)
{
    echo $ac;
}
else
{
    echo 'A conexão com o servidor falhou';
}