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
  if( $_SESSION['vehicles_delete'] !== '1')
  {
      $response = array(
        'status'  => 'error',
        'message' => 'Você não tem permissão para realizar está ação.',
        'link'    => '',
      );
     die(json_encode($response));
  }
}

$con_db = new connect_db();
$con = $con_db->connect();

if(isset($_GET['id']))
{
    if( empty($_GET['categoria']) or !isset($_GET['categoria']) )
    {
        $response = array(
            'status'  => 'error',
            'message' => 'Não foi possível completar a ação solicitada.',
            'link'    => '',
          );
         die(json_encode($response));
    }
    else
    {
        $categoria = filterString($_GET['categoria'], 'CHAR');
    }
  $id = filterString($_GET['id'], 'INT');
  $query = " DELETE FROM $categoria WHERE `id` = '$id' ";
  $delete = $con->query($query);
  $con->close();
  
  $dir = $_SERVER['DOCUMENT_ROOT'].'/docs/vehicles/'.$categoria.'/'.$id.'/';
  
  if($delete)
  { 
    if(file_exists($dir))
    {
      unlinkRecursive($dir, true);
    }    
    $response = array(
        'status'  => 'success',
        'message' => 'Veículo(s) deletado(s) com sucesso.',
        'link'    => '',
      );
     die(json_encode($response));
  }
  else
  {
    $response = array(
        'status'  => 'error',
        'message' => 'Não foi possível excluir excluir o(s) veículo(s);',
        'link'    => '',
      );
     die(json_encode($response));
  }
  
}
else if(isset($_POST['del']))
{
    if( empty($_POST['categoria']) or !isset($_POST['categoria']) )
    {
        $response = array(
            'status'  => 'error',
            'message' => 'Não foi possível completar a ação solicitada.155',
            'link'    => '',
          );
         die(json_encode($response));
    }
    else
    {
        $categoria = filterString($_POST['categoria'], 'CHAR');
    }
  
  $error_message = '';
  $count_array = count($_POST['del']);
  
  for($i = 0; $i < $count_array; $i++)
  {
    $id = $_POST['del'][$i];
    $query = " DELETE FROM $categoria WHERE `id` = '$id' ";
    $delete = $con->query($query);
    
    $dir = $_SERVER['DOCUMENT_ROOT'].'/docs/vehicles/'.$categoria.'/'.$id.'/';
    if($delete)
    { 
    
      if(file_exists($dir))
      {
        unlinkRecursive($dir, true);
      }    
    
    }
    else
    {
      $error_message .= '<p>Falha ao deletar o registro número '.$id.'</p><br>';
    }
    
  }
  
  if( empty($error_message) )
  {
    $response = array(
        'status'  => 'success',
        'message' => 'Veículo(s) deletado(s) com sucesso.',
        'link'    => '',
      );
     die(json_encode($response));
  }
  else
  {
    $response = array(
        'status'  => 'error',
        'message' => $error_message,
        'link'    => '',
      );
     die(json_encode($response));
  }
  
}
else
{
  $response = array(
        'status'  => 'error',
        'message' => 'Não foi possível completar a ação solicitada.',
        'link'    => '',
      );
     die(json_encode($response));
}


 
