<?php
require_once ($_SERVER['DOCUMENT_ROOT'].'/config/autoload.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/config/config.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/config/public_functions.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/app/controls/adminFunctions.php');

use app\controls\session;
use app\models\users\user_profile_info;
use app\controls\perms;
use app\controls\errors;

$session      = new session();
$user_profile = new user_profile_info();
$user_perms   = new perms();
$errors       = new errors();

// mas tarde passar está sessão para outra página
$_SESSION['notifications_system'] = array();

$user_profile->loadProfile($_SESSION['user_id']) ;
if( empty($user_profile->user_profile_photo))
{
  $_SESSION['notifications_system'][] = '<LI><a href="admin.php?page=my_account" class="text-darkgray"> <i class="fa fa-picture-o"></i> <small> Você ainda não escolheu uma imagem de perfil! Clique aqui para escolher um imagem.</small></a></LI>';
}
if( empty($user_profile->user_name))
{
  $_SESSION['notifications_system'][] = '<LI><a href="admin.php?page=my_account" class="text-darkgray"> <small> Algumas informações extras sobre você são necessárias, que tal nos dizer o seu nome? .</small></a></LI>';
}
if( empty($user_profile->user_profile_email))
{
  $_SESSION['notifications_system'][] = '<LI><a href="admin.php?page=my_account" class="text-darkgray"> <i class="fa fa-envelope"></i> <small> Atenção!<br>Você ainda não informou seu endereço de e-mail.<br> Ele é necessátio caso esqueça sua senha! Clique aqui para adicioar um e-mail.</small></a></LI>';
}
?>