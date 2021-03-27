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
    if(!isset($_SESSION['properties_edit']) or $_SESSION['properties_edit'] !== 'Y' )
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

if( empty($_POST['delete-imgs']) or !isset($_POST['delete-imgs']) )
{
    $response = array(
        'status'  => 'warning',
        'message' => 'Nenhuma imagem selecionada.',
        'link'    => '',
     );
     die(json_encode($response));
}
else
{
    $ids = $_POST['delete-imgs']; 
}

$con_db = new config\connect_db();
$con = $con_db->connect();

$count_array = count($_POST['delete-imgs']);

for ($i=0; $i < $count_array; $i++ )
{
    $id = $ids[$i];
    $img = $con->query("SELECT image, id_properties FROM images_properties WHERE id = '$id' ");
  
    while($reg = $img->fetch_array())
    {
        if($img and $img->num_rows > 0)
        {
            $file_delete = $reg['image'];
            $dir_properties = $reg['id_properties'];
        }
        else 
        {
            continue;
        }
    }
    
    $image_big = '/docs/properties/'.$dir_properties.'/big/'.$file_delete;
    $image_small = '/docs/properties/'.$dir_properties.'/small/'.$file_delete;

    $delete = $con->prepare(" DELETE FROM images_properties WHERE id = ? ");
    $delete->bind_param('i', $id);
    $delete->execute();
    $lines_affecteds = $delete->affected_rows;
  
    if ($delete and  $lines_affecteds > 0)
    { 
        
        $ftp_connect = ftp_connect(SUBDOMAIN_FTP) or die('Não foi possível se conectar ao servidor.');
        //faz login FTP
        ftp_login($ftp_connect, USER_FTP, PASS_FTP);
        
        if (!file_exists('ftp://'.USER_FTP.':'.PASS_FTP.'@'.SUBDOMAIN_FTP.'/properties/'.$dir_properties)) continue;
        
        if (file_exists('ftp://'.USER_FTP.':'.PASS_FTP.'@'.SUBDOMAIN_FTP.$image_big))
        {
            ftp_delete($ftp_connect, $image_big);
        }
        
        if (file_exists('ftp://'.USER_FTP.':'.PASS_FTP.'@'.SUBDOMAIN_FTP.$image_small))
        {
            ftp_delete($ftp_connect, $image_small);
        }
            
    }
    else 
    {
        continue;
        $error_message = '<p>Algumas imagens não foram deletadas!</p>';
    }
  
}

ftp_close($ftp_connect);
$con->close(); 

if (!isset($error_message))
{
    $response = array(
        'status'  => 'success',
        'message' => 'As imagens selecionadas foram excluidas',
        'link'    => '',
     );
     die(json_encode($response));
}
else
{
    $response = array(
        'status'  => 'warning',
        'message' => $error_message,
        'link'    => '',
     );
     die(json_encode($response));
}

