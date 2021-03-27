<?php
require_once ($_SERVER['DOCUMENT_ROOT'].'/config/config.php');
session_name(SESSION_NAME);
session_start();
header('Content-Type: application/json');
require_once ($_SERVER['DOCUMENT_ROOT'].'/config/autoload.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/config/public_functions.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/app/controls/adminFunctions.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/libs/WideImage/WideImage.php');

use config\connect_db;
use app\controls\blowfish_crypt;

if( $_SESSION['user_type'] != 'administrador' and $_SESSION['user_type'] != 'suporte' )
{
    $response = array(
       'status'  => 'error',
       'message' => 'Você não tem permissão para realizar está ação.',
       'link'    => '',
    );
    die(json_encode($response));   
}

if( $_POST['form-token'] != md5(SECRET_FORM_TOKEN.$_SESSION['user_id'].$_SESSION['user']) )
{
     $response = array(
        'status'  => 'error',
        'message' => 'A origem de alguns dados nos parece duvidosa! Por isso bloqueamos está ação.',
        'link'    => '',
      );
     die(json_encode($response));
}

if( !isset($_POST['user_id']) or (int)$_POST['user_id'] !== (int)$_SESSION['user_id'] )
{
    $response = array(
        'status'  => 'warning',
        'message' => 'Você não pode fazer isso!',
        'link'    => '',
     );
     die(json_encode($response));
}
 
//--------------------------------------------------------------------------

$error_message = NULL;

if( empty($_FILES['logo']) or !isset($_FILES['logo']) )
{
    die('<div class="ajax-file-upload-error alert alert-danger" role="alert">Arquivo não reconhecido ou inexistente!</div>');
}

// Cria os diretórios necessários

// diretório temporário para upload
$dir_upload = $_SERVER['DOCUMENT_ROOT'].'/tmp';
if( !file_exists($dir_upload) ) mkdir($dir_upload, 0777, true);

//diretório do imóvel
$patch_company = $_SERVER['DOCUMENT_ROOT'].'/docs/company/logomarca';
if( !file_exists($patch_company) ) mkdir($patch_company, 0777, true);

// imagem lg
if( !file_exists($patch_company.'/lg') ) mkdir($patch_company.'/lg', 0777, true);
// imagem md
if( !file_exists($patch_company.'/md') ) mkdir($patch_company.'/md', 0777, true);
// imagem sm
if( !file_exists($patch_company.'/sm') ) mkdir($patch_company.'/sm', 0777, true);
// imagem xs
if( !file_exists($patch_company.'/xs') ) mkdir($patch_company.'/xs', 0777, true);

$images = $_FILES['logo']; 
$count  = sizeof($_FILES['logo']['tmp_name']);
$allowed_types = array('jpg', 'png', 'gif', 'jpeg', 'JPG', 'PNG', 'GIF', 'JPEG');

$images_name    = $images['name'];
$images_type    = $images['type'];
$images_tmpName = $images['tmp_name'];
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
    die('<div class="ajax-file-upload-error alert alert-danger" role="alert">'.$images_name.' não parece ser uma imagem!</div><br>');
}
else if ( !in_array($images_ext, $allowed_types) )
{
    die('<div class="ajax-file-upload-error alert alert-danger" role="alert"> A imagem '.$images_name.' possui o formato não permitido! </div><br>');
}
else
{
    $images_newName = date("Ymdhis").time().mt_rand(0, 999999).'.'.$images_ext;
    $file_tmp = $dir_upload.'/'.$images_newName;
    
    $image_lg = $patch_company.'/lg/'.$images_newName;
    $image_md = $patch_company.'/md/'.$images_newName;
    $image_sm = $patch_company.'/sm/'.$images_newName;
    $image_xs = $patch_company.'/xs/'.$images_newName;
    
    if( move_uploaded_file($images_tmpName, $file_tmp) ) 
    { 
        $con_db = new config\connect_db();
        $con = $con_db->connect();
        
        $system_id = SYSTEM_ID;
        
        $update_logo = $con->prepare("UPDATE settings_properties SET company_logo = ? WHERE unique_id = ?");
        $update_logo->bind_param('ss', $images_newName, $system_id);
        $update_logo->execute();
    
       if ($update_logo)
       {              
           $image_wide = new WideImage();
           $image_wide = WideImage::load($file_tmp); 
      
           if ($images_ext === 'jpg' or $images_ext === 'jpeg')
           {
               $image_wide = $image_wide->resize(400, 110, 'inside', 'down');
               $image_wide->saveToFile($image_lg, 70);  
               $image_wide = $image_wide->resize(273, 75, 'inside', 'down');
               $image_wide->saveToFile($image_md, 70);
               $image_wide = $image_wide->resize(204, 56, 'inside', 'down');
               $image_wide->saveToFile($image_sm, 70);
               $image_wide = $image_wide->resize(138, 38, 'inside', 'down');
               $image_wide->saveToFile($image_xs, 70);      
           }
           else if ($images_ext === 'png')
           {
               $image_wide = $image_wide->resize(400, 110, 'inside', 'down');
               $image_wide->saveToFile($image_lg, 9);
               $image_wide = $image_wide->resize(273, 75, 'inside', 'down');
               $image_wide->saveToFile($image_md, 9);
               $image_wide = $image_wide->resize(204, 56, 'inside', 'down');
               $image_wide->saveToFile($image_sm, 9);
               $image_wide = $image_wide->resize(138, 38, 'inside', 'down');
               $image_wide->saveToFile($image_xs, 9);
           }
           else
           {
               $image_wide = $image_wide->resize(400, 110, 'inside', 'down');
               $image_wide->saveToFile($image_lg);
               $image_wide = $image_wide->resize(273, 75, 'inside', 'down');
               $image_wide->saveToFile($image_md);
               $image_wide = $image_wide->resize(204, 56, 'inside', 'down');
               $image_wide->saveToFile($image_sm);
               $image_wide = $image_wide->resize(138, 38, 'inside', 'down');
               $image_wide->saveToFile($image_xs);
           }//. if tipo de salvamento
           
           if(file_exists($file_tmp)) unlink($file_tmp);
           
            $response = array(
                'status'  => 'success',
                'message' => 'Logomarca alterada',
                'link'    => '',
             );
             die(json_encode($response));
           
       }
       else
       {      
           if(file_exists($file_tmp)) unlink($file_tmp);

           $response = array(
                'status'  => 'warning',
                'message' => $images_name.' não pode ser salva!',
                'link'    => '',
             );
             die(json_encode($response));
      
       }//,if insert---
    
    }//. if upload---
    else
    {
        $response = array(
            'status'  => 'warning',
            'message' => 'Ocorreu um problema ao carregar a imagem '.$images_name.'. Tente novamente.',
            'link'    => '',
         );
         die(json_encode($response));
    }  
  
}//.if tamanho, name ou type não permitido
