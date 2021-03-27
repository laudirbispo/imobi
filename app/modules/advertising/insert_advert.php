<?php
session_name(SESSION_NAME);
session_start();
require_once ($_SERVER['DOCUMENT_ROOT'].'/config/config.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/config/public_functions.php');

if($_SESSION['level'] !== 3 and 2)
{
  die (AlertsMensagens('warning', '<strong>'.$_SESSION['level'].' AÇÃO NÃO PERMITIDA!</strong> Você não tem autorização para completar esta ação.'));
}

if(empty($_POST['publicidade-anunciante']) or !isset($_POST['publicidade-anunciante']))
{
  die (AlertsMensagens('warning', '<strong>Campo Vazio!</strong> Campo Anunciante.'));
}
else
{
  $publicidade_anunciante = filter_var($_POST['publicidade-anunciante'], FILTER_SANITIZE_SPECIAL_CHARS);
}

if(empty($_POST['publicidade-link']) or !isset($_POST['publicidade-link']))
{
  $publicidade_link = DOMINIO ;
}
else
{
  $publicidade_link = filter_var($_POST['publicidade-link'], FILTER_SANITIZE_URL);
}

if(empty($_POST['publicidade-local']) or !isset($_POST['publicidade-local']))
{
  die (AlertsMensagens('warning', '<strong>Campo Vazio!</strong> Campo local do anuncio.'));
}
else
{
  $publicidade_local = filter_var($_POST['publicidade-local'], FILTER_SANITIZE_SPECIAL_CHARS);
}

if(empty($_FILES['publicidade-image']) or !isset($_FILES['publicidade-image']))
{
  die (AlertsMensagens('warning', '<strong>Campo Vazio!</strong> Uma imagem é nesessária.'));
}
else
{
  $publicidade_image = $_FILES['publicidade-image'];
}
//************************************************************************************************

$images_name    = $publicidade_image['name'];
$images_type    = $publicidade_image['type'];
$images_tmpName = $publicidade_image['tmp_name'];
$images_size    = $publicidade_image['size'];
$images_ext     = strtolower(pathinfo($images_name, PATHINFO_EXTENSION));

$dir_upload = $_SERVER['DOCUMENT_ROOT'].'/docs/publicidade/';
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
  
  $img_patch = '/docs/publidicade/'.$images_newName;
  
  if(move_uploaded_file($images_tmpName, $images_destiny)) 
  {
    require_once $_SERVER['DOCUMENT_ROOT'].'/controles/class/connect.class.php';
    
    $connection = new DB();
    $con = $connection->connect();
  
    $views = '0';
  
    $update = $con->prepare("UPDATE `publicidade` SET `link` = ?, `anuncio_img` = ?, `date` = ?, `anunciante` = ?, `views` = ? WHERE `local` = ? ") or die (mysqli_error($con));
    $update->bind_param('ssssss', $publicidade_link, $img_patch, $date_time, $publicidade_anunciante, $views, $publicidade_local);
    $update->execute();
    $update_affected = $update->affected_rows;
    $update->close();

    if($update and $update_affected > 0)
    {    
        
      require_once $_SERVER['DOCUMENT_ROOT'].'/libs/WideImage/WideImage.php';
      $image_wide = new WideImage();
      $image_wide = WideImage::loadFromFile($images_destiny);
      $image_wide->resize(1200, 400);
      
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
      
      
      die ('1');
      
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

