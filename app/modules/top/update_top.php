<?php
session_name(SESSION_NAME);
session_start();
require_once $_SERVER['DOCUMENT_ROOT'].'/config/config.php';
require_once ($_SERVER['DOCUMENT_ROOT'].'/app/controles/functions/functions.php');

if($_SESSION['level'] !== 3 and 2)
{
  die (AlertsMensagens('warning', '<strong> AÇÃO NÃO PERMITIDA!</strong> Você não tem autorização para completar esta ação.'));
}

if(empty($_POST['top-cantor']) or !isset($_POST['top-cantor']))
{
  die (AlertsMensagens('warning', '<strong>Campo Vazio!</strong> Campo (Mome do Cantor).'));
}
else
{
  $top_cantor = filter_var($_POST['top-cantor'], FILTER_SANITIZE_SPECIAL_CHARS);
}

if(empty($_POST['top-musica']) or !isset($_POST['top-musica']))
{
  die (AlertsMensagens('warning', '<strong>Campo Vazio!</strong> Campo (Mome da Música).'));
}
else
{
  $top_musica = filter_var($_POST['top-musica'], FILTER_SANITIZE_SPECIAL_CHARS);
}

if(empty($_POST['top-link']) or !isset($_POST['top-link']))
{
  die (AlertsMensagens('warning', '<strong>Campo Vazio!</strong> Campo (Link Externo).'));
}
else
{
  $top_link = filter_var($_POST['top-link'], FILTER_SANITIZE_SPECIAL_CHARS);
}

if(empty($_FILES['top-img']) or !isset($_FILES['top-img']))
{
  die (AlertsMensagens('warning', '<strong>Campo Vazio!</strong> Uma imagem é necessária.'));
}
else
{
  $top_img = $_FILES['top-img'];
}

if(empty($_POST['top-position']) or !isset($_POST['top-position']) or $_POST['top-position'] == '0' )
{
  die (AlertsMensagens('warning', '<strong>Campo Vazio!</strong> Escolha a posição que deseja alterar.'));
}
else
{
  $top_position = filter_var($_POST['top-position'], FILTER_SANITIZE_SPECIAL_CHARS);
}
//********************************************************************************************888


$images_name    = $top_img['name'];
$images_type    = $top_img['type'];
$images_tmpName = $top_img['tmp_name'];
$images_size    = $top_img['size'];
$images_ext     = strtolower(pathinfo($images_name, PATHINFO_EXTENSION));

$dir_upload = $_SERVER['DOCUMENT_ROOT'].'/docs/top/';
$allowed_types = array('jpg', 'png', 'gif', 'jpeg');

if($images_size > 0 and mb_strlen($images_name, 'utf8') < 1) 
{
  die('<div class="alert alert-warning alert-dismissible" role="alert">
  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <i class="fa fa-exclamation-triangle text-yellow" aria-hidden="true"></i> <strong>Falha no upload!</strong> Arquivo não reconhecido. </div>');
}
else if (!in_array($images_ext, $allowed_types))
{
  die('<div class="alert alert-warning alert-dismissible" role="alert">
  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <i class="fa fa-exclamation-triangle text-yellow" aria-hidden="true"></i> <strong>Falha no upload!</strong> Formato de arquivo <strong>não permitido!</strong> Os formatos permitidos são: *.jpg, *.png, *.gif . </div>');
}
else
{
  $images_newName = date("Ymdhis").time().mt_rand(0, 999999).'.'.$images_ext;
  $images_destiny = $dir_upload.$images_newName;
  
  if(move_uploaded_file($images_tmpName, $images_destiny)) 
  {
    require_once $_SERVER['DOCUMENT_ROOT'].'/controles/class/connect.class.php';
    
    $connection = new DB();
    $con = $connection->connect();
    
    //primeiramente deletamos a imagem atual
    $del_img = $con->query("SELECT `img` FROM `top_music` WHERE `id` = '$top_position' ");
    
    while($del_reg = $del_img->fetch_array())
    {
      $arq_del = $del_reg['img'];
    }
    

    
    
    
    $update_top = $con->prepare("UPDATE `top_music` SET `cantor` = ?, `musica` = ?, `link` = ?, `img` = ? WHERE `id` = ? ") or die (mysqli_error($con));
    $update_top->bind_param('ssssi', $top_cantor, $top_musica, $top_link, $images_newName, $top_position );
    $update_top->execute();
    $update_top_affected = $update_top->affected_rows;
    $update_top->close();

    if($update_top and $update_top_affected > 0)
    {    
        
      require_once $_SERVER['DOCUMENT_ROOT'].'/libs/WideImage/WideImage.php';
      $image_wide = new WideImage();
      $image_wide = WideImage::loadFromFile($images_destiny);
      $image_wide->resize(310, 180, 'inside', 'any');
      
      if($images_ext == 'jpg')
      {
        $image_wide->saveToFile($images_destiny, 70);
      }
      else if($images_ext == 'png')
      {
        $image_wide->saveToFile($images_destiny, 9);
      }
      else
      {
        $image_wide->saveToFile($images_destiny);
      }//. if tipo de salvamento
      
      // se occoreu tudo certo deleta a imagem anterior
     if($del_img and $del_img->num_rows > 0)
     {
       $arq_delete =  $_SERVER['DOCUMENT_ROOT'].'/docs/top/'.$arq_del;
       DeleteFiles($arq_delete );
     }
      
    }
    else
    {
      
      die('<div class="alert alert-warning alert-dismissible" role="alert">
  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <i class="fa fa-exclamation-triangle text-yellow" aria-hidden="true"></i> <strong>Falha no upload!</strong> Ocorreu um erro ao gravar o arquivo '.$images_name.' no banco de dados. </div>');
      DeleteFiles($images_destiny);
      
    }//,if insert---
    
  }//. if upload---
    
  
}//.if tamanho, name ou type não permitido

