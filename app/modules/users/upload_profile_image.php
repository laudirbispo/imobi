<?php
require_once ($_SERVER['DOCUMENT_ROOT'].'/config/config.php');
session_name(SESSION_NAME);
session_start();
header('Content-Type: application/json');
require_once ($_SERVER['DOCUMENT_ROOT'].'/config/autoload.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/config/public_functions.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/app/controls/adminFunctions.php');

use config\connect_db;
use app\controls\errors;
use app\controls\activityRecord;

if( empty($_POST['data_img']) or !isset($_POST['data_img']) )
{
    $response = array(
        'status'  => 'error',
        'message' => 'Escolha uma imagem para continuar',
        'link'    => '',
        'ico'     => '',
      );
     die(json_encode($response));
}
else
{
    $data_bin = $_POST['data_img'];
}

$dir_user = '/docs/users/'.$_SESSION['user_id'];
$dir_upload = '/docs/users/'.$_SESSION['user_id'].'/profile-images';
$image_newName = date("Ymdhis").time().mt_rand(0, 999999).'.jpg';

$imageDataEncoded = base64_encode(file_get_contents($data_bin)); 
$imageData = base64_decode($imageDataEncoded); 
$source = imagecreatefromstring($imageData); 
$angle = 0;
$rotate = imagerotate($source, $angle, 0);
 // if want to rotate the image 
$image_tmp = $_SERVER['DOCUMENT_ROOT'].'/tmp/'.$image_newName;
$imageSave = imagejpeg($rotate,$image_tmp,70); 
imagedestroy($source);

$img_save_data = $dir_upload.'/'.$image_newName;

$ftp_connect = ftp_connect(SUBDOMAIN_FTP) or die(json_encode($response = array('status' => 'error', 'message' => 'O servidor não está respondendo', 'link' => '', 'ico' => '', )));

//save arquivo
ftp_login($ftp_connect, USER_FTP, PASS_FTP);

if( !file_exists('ftp://'.USER_FTP.':'.PASS_FTP.'@'.SUBDOMAIN_FTP.$dir_user) )
{
    @ftp_mkdir($ftp_connect, $dir_user);
}

if( !file_exists('ftp://'.USER_FTP.':'.PASS_FTP.'@'.SUBDOMAIN_FTP.$dir_upload) )
{
    @ftp_mkdir($ftp_connect, $dir_upload);
}

ftp_put( $ftp_connect, $img_save_data, $image_tmp, FTP_BINARY );
ftp_close($ftp_connect);

$con_db = new config\connect_db();
$con = $con_db->connect();

$update_img_profile = $con->prepare("UPDATE `user_profile` SET `user_profile_photo` = ? WHERE `user_id` = ? ");
$update_img_profile->bind_param('si', $img_save_data, $_SESSION['user_id']);
$update_img_profile->execute();
$update_img_profile->close();

if($update_img_profile)
{   
    $activity_link = '/app/admin/profile/'.base64_encode($_SESSION['user_id']);
    
    $register_activity = new activityRecord();
    $register_activity->record ($_SESSION['user_id'], 'public', 'update-profile', 'alterou sua imagem de perfil', $activity_link);
    
    $_SESSION['user_photo'] = SUBDOMAIN_IMGS.$img_save_data;
    $response = array(
        'status'  => 'success',
        'message' => 'Imagem alterada',
        'link'    => '',
        'ico'     => '',
      );
     die(json_encode($response));
}
else
{
    $response = array(
        'status'  => 'error',
        'message' => 'O servidor não está respondendo',
        'link'    => '',
        'ico'     => '',
      );
     die(json_encode($response));
}


?>
