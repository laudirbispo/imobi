<?php
session_name(SESSION_NAME);
session_start();
header('Content-Type: application/json');
require_once ($_SERVER['DOCUMENT_ROOT'].'/config/autoload.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/config/config.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/config/public_functions.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/app/controls/adminFunctions.php');

use config\connect_db;

if( ($_SESSION['user_master_perms'] !== 'administrador') )
{
  if( $_SESSION['gallery_delete'] !== '1')
  {
      $response = array(
        'status'  => 'error',
        'message' => 'Você não tem permissão para realizar está ação.',
        'link'    => '',
      );
     die(json_encode($response));
  }
}

if( empty($_POST['form-token']) or !isset($_POST['form-token']) )
{
     $response = array(
        'status'  => 'error',
        'message' => 'A origem de alguns dados nos parece duvidosa! Por isso bloqueamos está ação.',
        'link'    => '',
      );
     die(json_encode($response));
}

if( empty($_POST['user_id']) or !isset($_POST['user_id']) or (int)$_POST['user_id'] !== (int)$_SESSION['user_id'] )
{
    $response = array(
        'status'  => 'error',
        'message' => 'É necessário estar logado para completar está ação.',
        'link'    => '',
     );
     die(json_encode($response));
}
else
{
  $user_id = filterString($_POST['user_id'], 'INT');
}

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
    $img = $con->query("SELECT `image` FROM `images_gallery` WHERE `id` = '$id' ");
  
    while($reg = $img->fetch_array())
    {
        if($img and $img->num_rows > 0)
        {
            $file_delete = $reg['image'];
        }
        else 
        {
            continue;
        }
    }

    $delete = $con->prepare(" DELETE FROM `images_gallery` WHERE `id` = ? ");
    $delete->bind_param('i', $id);
    $delete->execute();
    $lines_affecteds = $delete->affected_rows;
  
    if( $delete and  $lines_affecteds > 0 )
    { 
        if( file_exists($_SERVER['DOCUMENT_ROOT'].$file_delete) )
        {
            unlink($_SERVER['DOCUMENT_ROOT'].$file_delete); 
        }       
    }
    else 
    {
        continue;
        $error_message = '<p>Algumas imagens não foram deletadas!</p>';
    }
  
}

$con->close(); 

if( !isset($error_message) )
{
    $response = array(
        'status'  => 'success',
        'message' => 'Todas as imagens foram excluidas.',
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

