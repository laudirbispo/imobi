<?php
session_name(SESSION_NAME);
session_start();
require_once $_SERVER['DOCUMENT_ROOT'].'/config/config.php';
require_once ($_SERVER['DOCUMENT_ROOT'].'/config/public_functions.php');

if($_SESSION['level'] !== 3 and 2)
{
  die (AlertsMensagens('warning', '<strong>'.$_SESSION['level'].' AÇÃO NÃO PERMITIDA!</strong> Você não tem autorização para completar esta ação.'));
}

if(empty($_POST['noticia-titulo']) or !isset($_POST['noticia-titulo']))
{
  die (AlertsMensagens('warning', '<strong>Campo Vazio!</strong> Campo Título da postagem.'));
}
else
{
  $noticia_titulo = filter_var($_POST['noticia-titulo'], FILTER_SANITIZE_SPECIAL_CHARS);
}

//---------
if(empty($_POST['noticia-subtitulo']) or !isset($_POST['noticia-subtitulo']))
{
  die (AlertsMensagens('warning', '<strong>Campo Vazio!</strong> Campo Subtitulo da postagem gg.'));
}
else
{
  $noticia_subtitulo = filter_var($_POST['noticia-subtitulo'], FILTER_SANITIZE_SPECIAL_CHARS);
}

//---------
if(empty($_POST['noticia-capa']) or !isset($_POST['noticia-capa']))
{
  
  $noticia_capa = '';
}
else
{
  if(filter_var($_POST['noticia-capa'], FILTER_VALIDATE_URL))
  {
    $noticia_capa = filter_var($_POST['noticia-capa'], FILTER_SANITIZE_URL);
  }
  else
  {
    die (AlertsMensagens('warning', '<strong>URL Inválida</strong> O endereço de imagem da capa não é válido.'));
  }
  
}

//---------
if(empty($_POST['noticia-categoria']) or !isset($_POST['noticia-categoria']))
{
  die (AlertsMensagens('warning', '<strong>Campo Vazio!</strong> Escolha uma categoria para a postagem.'));
}
else
{
  $noticia_categoria = filter_var($_POST['noticia-categoria'], FILTER_SANITIZE_SPECIAL_CHARS);
}

//---------
if(empty($_POST['noticia-subcategoria']) or !isset($_POST['noticia-subcategoria']))
{
  die (AlertsMensagens('warning', '<strong>Campo Vazio!</strong> Uma subcategoria é necessário.'));
}
else
{
  $noticia_subcategoria = filter_var($_POST['noticia-subcategoria'], FILTER_SANITIZE_SPECIAL_CHARS);
}

//---------
if(empty($_POST['noticia-destaque']) or !isset($_POST['noticia-destaque']))
{
 $noticia_destaque = 0;
}
else
{
  $noticia_destaque = filter_var($_POST['noticia-categoria'], FILTER_SANITIZE_NUMBER_INT);
}

//---------
if(empty($_POST['noticia-facebook']) or !isset($_POST['noticia-facebook']))
{
 $noticia_facebook = 0;
}
else
{
  $noticia_facebook = filter_var($_POST['noticia-facebook'], FILTER_SANITIZE_NUMBER_INT);
}

//---------
if(empty($_POST['noticia-hidden']) or !isset($_POST['noticia-hidden']))
{
 $noticia_hidden = 0;
}
else
{
  $noticia_hidden = filter_var($_POST['noticia-hidden'], FILTER_SANITIZE_NUMBER_INT);
}

//---------
if(empty($_POST['noticia-tags']) or !isset($_POST['noticia-tags']))
{
  $noticia_tags = '';
}
else
{
  $noticia_tags = filter_var($_POST['noticia-tags'], FILTER_SANITIZE_SPECIAL_CHARS);
}

//---------
if(empty($_POST['noticia-text']) or !isset($_POST['noticia-text']))
{
  die (AlertsMensagens('warning', '<strong>Campo Vazio!</strong> Escreva algo para postar.'));
}
else
{
  $noticia_text = $_POST['noticia-text'];
}


$autor_post = $_SESSION['user'];
$id_autor_post = $_SESSION['id_user'];

require_once ($_SERVER['DOCUMENT_ROOT'].'/controles/class/connect.class.php');
$con_db = new DB();
$con = $con_db->connect();

$insert = $con->prepare(" INSERT INTO `noticias` (titulo, subtitulo, categoria, subcategoria, tags, destaque, facebook, ativa, capa, autor_post, text_html, date_post) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ") or die(mysqli_error($con));
$insert->bind_param('sssssiiissss', $noticia_titulo, $noticia_subtitulo, $noticia_categoria, $noticia_subcategoria, $noticia_tags, $noticia_destaque, $noticia_facebook, $noticia_hidden, $noticia_capa, $autor_post, $noticia_text, $date_time);
$insert->execute();


if($insert and $insert->affected_rows > 0 )
{
  die ("OK");
}
else
{
  die (AlertsMensagens('warning', '<strong>Error!</strong> Ocorreu uma falha ao publicar a postagem.'));
}

