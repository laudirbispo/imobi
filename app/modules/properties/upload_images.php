<?php
require_once ($_SERVER['DOCUMENT_ROOT'].'/config/config.php');
session_name(SESSION_NAME);
session_start();
header("Access-Control-Allow-Origin: *");
require_once ($_SERVER['DOCUMENT_ROOT'].'/config/autoload.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/config/public_functions.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/libs/WideImage/WideImage.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/app/controls/adminFunctions.php');

if( $_SESSION['user_type'] != 'administrador' and $_SESSION['user_type'] != 'suporte' )
{
    if(!isset($_SESSION['properties_edit']) or $_SESSION['properties_edit'] !== 'Y' )
    {
        die('<div class="ajax-file-upload-error alert alert-warning" role="alert">Você não tem permissão para realizar está ação!</div>');
    }   
}

if( $_POST['form-token'] != md5(SECRET_FORM_TOKEN.$_SESSION['user_id'].$_SESSION['user']) )
{
     die('<div class="ajax-file-upload-error alert alert-warning" role="alert">A origem de alguns dados nos parece duvidosa! Por isso bloqueamos está ação!</div>');
}

if( !isset($_POST['user_id']) or (int)$_POST['user_id'] !== (int)$_SESSION['user_id'] )
{
    die('<div class="ajax-file-upload-error alert alert-warning" role="alert">Uploadinterrompido! Não foi possível comprocar sua identidade.</div>');
}

//--------------------------------------------------------------------------

if( empty($_FILES['images']) or !isset($_FILES['images']) )
{
    die('<div class="ajax-file-upload-error alert alert-warning" role="alert">Arquivo não reconhecido ou inexistente!</div>');
}

if( empty($_POST['id_properties']) or !isset($_POST['id_properties']) )
{
    die('<div class="ajax-file-upload-error alert alert-warning" role="alert"><i CLASS="fa fa-exclamation-triangle"></i> Álbum não identificado! </div><br>');
}
else
{
    $id_properties = filterString($_POST['id_properties'], 'INT');
}

$images = $_FILES['images']; 
$allowed_types = array('jpg', 'png', 'gif', 'jpeg', 'JPG', 'PNG', 'GIF', 'JPEG');

$images_name    = $images['name'];
$images_type    = $images['type'];
$images_tmp_name = $images['tmp_name'];
$images_size    = $images['size'];
$images_error   = $images["error"];
$images_ext     = strtolower(pathinfo($images_name, PATHINFO_EXTENSION));

switch ($images_error) {
    case UPLOAD_ERR_INI_SIZE:
        $upload_error = "<p>O arquivo enviado excede o tamanho máximo permitido pelo servidor.</p>";
        break;
    case UPLOAD_ERR_FORM_SIZE:
        $upload_error = "<p>O arquivo enviado excede o tamanho máximo permitido pelo sistema.</p>";
        break;
    case UPLOAD_ERR_PARTIAL:
        $upload_error = "<p>O arquivo enviado não foi carregado completamente. Tente novamente.</p>";
        break;
    case UPLOAD_ERR_NO_FILE:
        $upload_error = "<p>Nenhum arquivo foi enviado.</p>";
        break;
    case UPLOAD_ERR_NO_TMP_DIR:
        $upload_error = "Não é possível salvar na pasta temporária.";
        break;
    case UPLOAD_ERR_CANT_WRITE:
        $upload_error = "<p>Falha ao gravar o arquivo no disco. Verefique se você tem permissão para escrita no disco.</p>";
        break;
    case UPLOAD_ERR_EXTENSION:
        $upload_error = "<p>Algo de errado aconteceu. Tente novamente.</p>";
        break;

    default:
        $upload_error = '';
        break;
} 

if(!empty($upload_error)) die($upload_error);
    
    
if( $images_size > 0 and mb_strlen($images_name, 'utf8') < 1 ) 
{
    die('<div class="ajax-file-upload-error alert alert-warning" role="alert">'.$images_name.' não parece ser uma imagem!</div><br>');
}
if ( !in_array($images_ext, $allowed_types) )
{
    die('<div class="ajax-file-upload-error alert alert-warning" role="alert"> A imagem '.$images_name.' possui o formato não permitido! </div><br>');
}

//diretório do imóvel
$dir_properties = '/docs/properties/'.$id_properties;

$dir_big_images = $dir_properties.'/big';
$dir_small_images = $dir_properties.'/small';
$images_new_name = time().mt_rand(111111, 999999).'.'.$images_ext;

$tmp_file = '/tmp/'.$images_new_name;

$image_big = $dir_big_images.'/'.$images_new_name;
$image_small = $dir_small_images.'/'.$images_new_name;

/**
 * Upload images by FTP connection
 * Configure data in "/config/config.php"
 */

$ftp_connect = ftp_connect(SUBDOMAIN_FTP) or die('<div class="ajax-file-upload-error alert alert-danger" role="alert"> Não foi possível se conectar ao servidor.</div><br>');
//faz login FTP
ftp_login($ftp_connect, USER_FTP, PASS_FTP);


if(!file_exists('ftp://'.USER_FTP.':'.PASS_FTP.'@'.SUBDOMAIN_FTP.$dir_properties) )
{
	//echo ('ftp://'.USER_FTP.':'.PASS_FTP.'@'.SUBDOMAIN_FTP.$dir_properties);
    @ftp_mkdir($ftp_connect, $dir_properties);
    @ftp_chmod($ftp_connect, 0777, $dir_properties);
}

if( !file_exists('ftp://'.USER_FTP.':'.PASS_FTP.'@'.SUBDOMAIN_FTP.$dir_big_images) )
{
    @ftp_mkdir($ftp_connect, $dir_big_images);
    @ftp_chmod($ftp_connect, 0777, $dir_big_images);
}

if( !file_exists('ftp://'.USER_FTP.':'.PASS_FTP.'@'.SUBDOMAIN_FTP.$dir_small_images) )
{
    @ftp_mkdir($ftp_connect, $dir_small_images);
    @ftp_chmod($ftp_connect, 0777, $dir_small_images);
}

ftp_put($ftp_connect, $tmp_file, $images_tmp_name, FTP_BINARY );

$image_wide = new WideImage();
$image_wide = WideImage::load($_SERVER['DOCUMENT_ROOT'].$tmp_file);

if( $images_ext == 'jpg' or $images_ext == 'jpeg' )
{
   $image_wide = $image_wide->resize(1920, 1080, 'inside', 'down');
   $image_wide->saveToFile($_SERVER['DOCUMENT_ROOT'].$image_big, 90);

   $image_wide = $image_wide->resize(480, 360, 'inside', 'down');
   $image_wide->saveToFile($_SERVER['DOCUMENT_ROOT'].$image_small, 90);
}
else if( $images_ext == 'png' )
{
   $image_wide->saveToFile($_SERVER['DOCUMENT_ROOT'].$image_big, 9);
   $image_wide->saveToFile($_SERVER['DOCUMENT_ROOT'].$image_small, 9);
}
else
{
   $image_wide->saveToFile($_SERVER['DOCUMENT_ROOT'].$image_big);
   $image_wide->saveToFile($_SERVER['DOCUMENT_ROOT'].$image_small);
}

if (file_exists($_SERVER['DOCUMENT_ROOT'].$tmp_file))
{
    unlink($_SERVER['DOCUMENT_ROOT'].$tmp_file);
}

ftp_close($ftp_connect);

$con_db = new config\connect_db();
$con = $con_db->connect();

$insert_image = $con->prepare("INSERT INTO images_properties (id_properties, image, date_post) VALUES (?, ?, ?) ") or die(mysqli_error($con));
$insert_image->bind_param('iss', $id_properties, $images_new_name, $date_time);
$insert_image->execute() ;
$insert_image->store_result();
$rows_insert_image = $insert_image->affected_rows;
$insert_image->free_result();
$insert_image->close();

if( $insert_image and $rows_insert_image > 0 )
{
    //die ('<div class="ajax-file-upload-error alert alert-success" role="alert"> '.$images_name.' upload completo! </div><br>');
}
else
{
    die ('<div class="ajax-file-upload-error alert alert-danger" role="alert">'.$images_name.' não pode ser salva! </div><br>');
}
