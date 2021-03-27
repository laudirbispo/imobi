<?php
session_name(SESSION_NAME);
session_start();

require_once $_SERVER['DOCUMENT_ROOT'].'/config/config.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/controles/class/connect.class.php';
require_once ($_SERVER['DOCUMENT_ROOT'].'/config/public_functions.php');
    
$connection = new DB();
$con = $connection->connect();

if(empty($_GET['action']) or !isset($_GET['action']))
{
  die (AlertsMensagens('warning', 'Não entendemos seu pedido!'));
}
else
{
  $action = filter_var($_GET['action'], FILTER_SANITIZE_SPECIAL_CHARS);
}

if(empty($_GET['id']) or !isset($_GET['id'])  )
{
  die (AlertsMensagens('danger', 'ID de registro inválido!'));
}
else
{
  $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
}


//******************************************************************
if($action == 'favorite')
{
  $is_favorite = $con->prepare(" SELECT `favorite` FROM `noticias` WHERE `id` = ? ");
  $is_favorite->bind_param('i', $id);
  $is_favorite->execute();
  $is_favorite->bind_result($favorite);
  $is_favorite->fetch();
  $is_favorite->close();
  
  if($favorite == '0' or $favorite == '')
  {
    $favoritar = $con->prepare("UPDATE `noticias` SET `favorite` = 1 WHERE `id` = ? ") ;
    $favoritar->bind_param('i', $id);
    $favoritar->execute();
    $favoritar->close();
  }
  if($favorite == '1')
  {
    $unfavoritar = $con->prepare("UPDATE `noticias` SET `favorite` = 0 WHERE `id` = ? ") ;
    $unfavoritar->bind_param('i', $id);
    $unfavoritar->execute();
    $unfavoritar->close();
  }
}

//******************************************************************

if($action == 'delete')
{
  $delete = $con->prepare("DELETE FROM `noticias` WHERE `id` = ?") or die (mysqli_error($con));
  $delete->bind_param('i', $id);
  $delete->execute();
  
  
  if($delete->affected_rows > 0)
  {
    die ('success');
  }
  else 
  {
    die (AlertsMensagens('danger', 'ID de registro inválido!'));
  }
  $delete->close();
}