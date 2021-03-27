<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/config/config.php';

if(empty($_FILES['images']) or !isset($_FILES['images']))
{
  die("Arquivo não especificado!");
}

$images = $_FILES['images']; 

$images_name    = $images['name'];
$images_type    = $images['type'];
$images_tmpName = $images['tmp_name'];
$images_size    = $images['size'];
$images_ext     = strtolower(pathinfo($images_name, PATHINFO_EXTENSION));

$dir_upload = $_SERVER['DOCUMENT_ROOT'].'/docs/noticias/';
$allowed_types = array('jpg', 'png', 'gif', 'jpeg');
$date = date('Ymdhis');

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
    
    $insert_image = $con->prepare("INSERT INTO `repository` (image_name, date_upload) VALUES (?, ?) ") or die(mysqli_error($con));
    $insert_image->bind_param('ss', $images_newName, $date);
    $insert_image->execute();
    $insert_image->close();

    if($insert_image)
    {    
        
      require_once $_SERVER['DOCUMENT_ROOT'].'/libs/WideImage/WideImage.php';
      $image_wide = new WideImage();
      $image_wide = WideImage::loadFromFile($images_destiny);
      $image_wide->resize(800, 1422, 'inside', 'any');
      
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
      
    }
    else
    {
      
      die('<div class="alert alert-warning alert-dismissible" role="alert">
  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <i class="fa fa-exclamation-triangle text-yellow" aria-hidden="true"></i> <strong>Falha no upload!</strong> Ocorreu um erro ao gravar o arquivo '.$images_name.' no banco de dados. </div>');
      unlink($images_destiny);
      
    }//,if insert---
    
  }//. if upload---
    
  
}//.if tamanho, name ou type não permitido