<?php
require_once ($_SERVER['DOCUMENT_ROOT'].'/config/config.php');
session_name(SESSION_NAME);
session_start();
require_once ($_SERVER['DOCUMENT_ROOT'].'/config/autoload.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/config/public_functions.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/app/controls/adminFunctions.php');

$con_db = new config\connect_db();
$con = $con_db->connect();

if(!isset($_REQUEST['status']))
{
    $sts = 'undefined';
}
else
{
    $status_perms = array('1', '2', '3');
    $sts = filterString($_REQUEST['status'], 'CHAR');
    if( in_array($sts, $status_perms) )
    {
        $sts = $_REQUEST['status'];
    }
    else
    {
        $sts = 'undefined';
    }
    
}

$update_status = $con->prepare("INSERT INTO online_users (user_id, status, last_access) VALUES (?, ?, NOW()) ON DUPLICATE KEY UPDATE status = VALUES(status), last_access = NOW() ");
$update_status->bind_param('ii', $_SESSION['user_id'], $sts);
$update_status->execute();

$user_status = $con->query("SELECT su.id, su.login, su.type, up.user_name, up.user_profile_photo, ou.status, ou.last_access FROM online_users ou LEFT JOIN user_profile up ON (up.user_id = ou.user_id) LEFT JOIN sec_users su ON (su.id = ou.user_id) WHERE su.id = ou.user_id ORDER BY ou.status ASC, ou.last_access DESC");
$rows = $user_status->num_rows;

$users = '';
while( $reg = $user_status->fetch_assoc() )
{
    if($reg['type'] == 'suporte' or $reg['id'] == $_SESSION['user_id'])
    {
        continue;
    }
    
    $img = (fileRemoteExist(SUBDOMAIN_IMGS.$reg['user_profile_photo']) === true) ? SUBDOMAIN_IMGS.$reg['user_profile_photo'] : SUBDOMAIN_IMGS.'/defaults/default-user.png'; 
    
    $name = (empty($reg['user_name'])) ? $reg['login'] : $reg['user_name'] ;
    $dif =  time() - strtotime($reg['last_access']) ;
    $dif = $dif / 60;
    
    if($reg['status'] == '1' and $dif <= 0.5)
    {
        $border = 'user-status-on';
        $status = 'Online';
    }
    else if ($reg['status'] == '2' and $dif <= 0.5 )
    {
        $border = 'user-status-absent';
        $status = 'Ausente';
    }
    else if ($reg['status'] == '3' or $dif > 0.5 )
    {
        $border = 'user-status-off';
        $status = 'Offline';
    }
    else
    {
        $border = 'user-status-off';
        $status = 'Offline';
    }
    
    $users .= '<div class="media user-status">';
    $users .= '  <div class="media-left media-middle">';
    $users .= '    <a href="#">';
    $users .= '      <img class="media-object img-circle '.$border.'" width="35" height="35" src="'.$img.'" alt="Imagem perfil">';
    $users .= '    </a>';
    $users .= '  </div>';
    $users .= '  <div class="media-body">';
    $users .= '    <span>'.$name.'</span><br>';
    $users .= '    <span class="small">'.$status.'</span>';
    $users .= '  </div>';
    $users .= '</div>';
    
}
$user_status->close();

if($user_status)
{
    echo $users;
}
else
{
    echo 'Tentando carregar informações';
}
