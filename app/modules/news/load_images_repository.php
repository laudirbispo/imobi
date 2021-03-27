<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/config/config.php';

$error_msg = '<div class="alert alert-danger alert-dismissible clearfix"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><p><i class="icon fa fa-ban"></i> Error! Não foi possível carregar as imagens!</p></div>';

$end_msg = '<div class="alert alert-info alert-dismissible clearfix" STYLE="clear:both"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><p><i class="icon fa fa-ban"></i> Fim! Não há mais imagens para carregar!</p></div>';

$page = $_GET['page'];
if($_GET['page'] == '0') {$inicio = 0;}
$qntd = 20;
$inicio = $qntd * $page;


require_once $_SERVER['DOCUMENT_ROOT'].'/controles/class/connect.class.php';
$connection = new DB();
$con = $connection->connect();

$load_images = $con->query("SELECT * FROM `repository` ORDER BY `id` DESC LIMIT $inicio,$qntd") or die($error_msg);

$imgs = '';

while( $reg = $load_images->fetch_array() )
{
  $imgs .= '<div CLASS="col-lg-4 col-md-4 col-sm-6 col-xs-6" STYLE="height:90px;margin-bottom:20px;">';
  $imgs .= '  <img data-src="'.DOMINIO.'/docs/noticias/'.$reg['image_name'].'" SRC="/plugins/EagerImageLoader/ajax_loader.gif" class="img-responsive" STYLE="max-height:100% !important; margin:auto;">';
  $imgs .= '</div>';
}


$return = ($load_images->num_rows > 0 and $load_images) ? $imgs  : $end_msg ;
echo $return;