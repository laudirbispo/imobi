<?php
session_name(SESSION_NAME);
session_start();
require_once ($_SERVER['DOCUMENT_ROOT'].'/config/autoload.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/config/config.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/config/public_functions.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/libs/WideImage/WideImage.php');

use config\connect_db;
use app\controls\errors;

$errors = new errors();

if( ($_SESSION['user_master_perms'] !== 'administrador') )
{
    if( $_SESSION['gallery_edit'] !== '1')
    {
        die($errors->userNotAuthorized());
    }
}

/*
*
* @POST ['form-token'] = STRING = Formulário seguro
*
* @POST ['user_id'] = INT = Id para query
*
*/

if( empty($_GET['img']) or !isset($_GET['img']) )
{
    die ($errors->notObejectImage);
}
else
{
    $img = filterString($_GET['img'], 'CHAR');
}

if( empty($_GET['id']) or !isset($_GET['id']) )
{
    die ($errors->notReferenceId());
}
else
{
    $id = filterString($_GET['id'], 'INT');
}

$con_db = new config\connect_db();
$con = $con_db->connect();

$img_capa = $con->prepare("UPDATE `albuns` SET `image_capa` = ? WHERE `id` = ? ");
$img_capa->bind_param('si',$img, $id);
$img_capa->execute();
$img_capa->store_result();
$rows = $img_capa->affected_rows;
$img_capa->free_result();
$img_capa->close();

if( !$img_capa )
{
    die($con_db->serverFailure());
}
else if ( $rows <= 0 )
{
    die($con_db->serverFailure());
}
else if( $img_capa and $rows  > 0 )
{
    die ('<div class="alert alert-success alert-dismissible">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
          <h4><i class="icon fa fa-check"></i> Imagen de capa alterada!</h4>
        </div>');
}
else
{
    die ($errors->defaultQuery());
}