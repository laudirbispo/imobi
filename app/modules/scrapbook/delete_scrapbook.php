<?PHP
session_name(SESSION_NAME);
session_start();

require_once $_SERVER['DOCUMENT_ROOT'].'/config/config.php';
require_once ($_SERVER['DOCUMENT_ROOT'].'/config/public_functions.php');

if($_SESSION['level'] !== 3 and 2)
{
  die (AlertsMensagens('warning', '<strong>'.$_SESSION['level'].' AÇÃO NÃO PERMITIDA!</strong> Você não tem autorização para completar esta ação.'));
}


if(empty($_POST['del']) or !isset($_POST['del']))
{
  die (AlertsMensagens('warning', '<strong>Campo Vazio!</strong> Marque uma opção para continuar.'));
}
else
{
  $ids = filter($_POST['del']);
 
}

function filter( $dados )
{
  $ids = Array();
	foreach( $dados AS $dado ) $ids[] = (int)$dado;
	return $ids;
}
  
require_once ($_SERVER['DOCUMENT_ROOT'].'/controles/class/connect.class.php');
$con_db = new DB();
$con = $con_db->connect();
  
$delete = $con->query(' DELETE FROM `recados` WHERE `id` IN('.implode( ',', $ids ).') ');
  
