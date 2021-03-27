<?php
session_name(SESSION_NAME);
session_start();
require_once ($_SERVER['DOCUMENT_ROOT'].'/controles/class/connect.class.php');
$con_db = new DB();
$con = $con_db->connect();

if($_SESSION['level'] !== 3 and 2)
{
  die (AlertsMensagens('warning', '<strong>'.$_SESSION['level'].' AÇÃO NÃO PERMITIDA!</strong> Você não tem autorização para completar esta ação.'));
}

if(empty($_GET['id']) or !isset($_GET['id']))
{
  die('Não há uma identificação de registro para completar esta ação.');
}
else
{
  $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
}
  
if($_GET['action'] == 'aprovar')
{
  $val = '1';
  $aprova = $con->prepare("UPDATE `recados` SET `publicado` = ? WHERE `id` = ?");
  $aprova->bind_param('si', $val, $id);
  $aprova->execute();
  if($aprova and $aprova->affected_rows > 0)
  {
    $aprova->close();
    echo '1';
  }  
}
else if ($_GET['action'] == 'desaprovar')
{
  $not_aprova = $con->prepare("DELETE FROM `recados` WHERE `id` = ? ");
  $not_aprova->bind_param('i', $id);
  $not_aprova->execute();
  if($not_aprova and $not_aprova->affected_rows > 0)
  {
    $not_aprova->close();
    die('1');
  }
}
else
{
  die('A ação não pode ser concluida! ');
}