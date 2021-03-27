<?php
session_name(SESSION_NAME);
session_start();
header('Content-Type: application/json');
require_once ($_SERVER['DOCUMENT_ROOT'].'/config/autoload.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/config/public_functions.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/app/controls/adminFunctions.php');

use config\connect_db;

if( ($_SESSION['user_master_perms'] !== 'administrador') )
{
  if( $_SESSION['slide_delete'] !== '1')
  {
      $response = array(
        'status'  => 'error',
        'message' => 'Você não tem permissão para realizar está ação.',
        'link'    => '',
      );
     die(json_encode($response));
  }
}

if( empty($_GET['id']) or !isset($_GET['id']) )
{
    $response = array(
        'status'  => 'warning',
        'message' => 'Não identificamos qual slide você deseja excluir.',
        'link'    => '',
     );
     die(json_encode($response));
}

//----------------------------------------------

$id = filterString($_GET['id'], 'INT');

$con_db = new config\connect_db();
$con = $con_db->connect();

$query = $con->prepare("SELECT `image` FROM `slides` WHERE `id` = ? ");
$query->bind_param('i', $id);
$query->execute();
$query->store_result();
$query->bind_result($image);
$query->fetch();
$rows = $query->num_rows;
$query->free_result();
$query->close();

if( $query and $rows > 0 )
{
    $del = $con->prepare(" DELETE FROM `slides` WHERE `id` = ? ") or die(mysqli_error($con));
    $del->bind_param('i', $id);
    $del->execute();
    $rows_deleted = $del->affected_rows;
    $del->close();
    
    if($del and $rows_deleted > 0)
    {
        $dir = $_SERVER['DOCUMENT_ROOT'].'/docs/slides/';
        $img_lg = $dir.'lg/'.$image;
        $img_md = $dir.'md/'.$image;
        $img_sm = $dir.'sm/'.$image;
        $img_xs = $dir.'xs/'.$image;
        
        if( file_exists($img_lg) ){unlink($img_lg);}
        if( file_exists($img_md) ){unlink($img_md);}
        if( file_exists($img_sm) ){unlink($img_sm);}
        if( file_exists($img_xs) ){unlink($img_xs);}
        
        $response = array(
            'status'  => 'success',
            'message' => 'Slide deletado',
            'link'    => '',
         );
         die(json_encode($response));
        
    }
    else
    {
        $response = array(
            'status'  => 'error',
            'message' => 'Houve um problema ao tentar excluir o slide. Tente novamente se o erro persistir entre em contato com suporte técnico.',
            'link'    => '',
         );
         die(json_encode($response));
    }
    
}
else
{
    $response = array(
        'status'  => 'warning',
        'message' => 'Não encontramos a imagem que você deseja excluir.',
        'link'    => '',
     );
     die(json_encode($response));
}
    

