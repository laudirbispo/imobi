<?php
session_name(SESSION_NAME);
session_start();
header('Content-Type: application/json');
require_once ($_SERVER['DOCUMENT_ROOT'].'/config/autoload.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/config/config.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/config/public_functions.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/app/controls/adminFunctions.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/libs/WideImage/WideImage.php');

use config\connect_db;

if( ($_SESSION['user_master_perms'] !== 'administrador') )
{
  if( $_SESSION['slide_create'] !== '1')
  {
      $response = array(
        'status'  => 'error',
        'message' => 'Você não tem permissão para realizar está ação.',
        'link'    => '',
      );
     die(json_encode($response));
  }
}

if( empty($_POST['form-token']) or !isset($_POST['form-token']) )
{
     $response = array(
        'status'  => 'error',
        'message' => 'A origem de alguns dados nos parece duvidosa! Por isso bloqueamos está ação.',
        'link'    => '',
      );
     die(json_encode($response));
}

if( empty($_POST['user_id']) or !isset($_POST['user_id']) or (int)$_POST['user_id'] !== (int)$_SESSION['user_id'] )
{
    $response = array(
        'status'  => 'error',
        'message' => 'É necessário estar logado para completar está ação.',
        'link'    => '',
     );
     die(json_encode($response));
}
else
{
  $user_id = filterString($_POST['user_id'], 'INT');
}
//****************************************************************************

$error_message = '';

if( !isset($_SESSION['config']) )
{
    $response = array(
        'status'  => 'error',
        'message' => 'Algumas configurações não foram carregadas adequadamente. Tente novamente, se o erro persistir entre em contato com o suporte técnico.',
        'link'    => '',
     );
     die(json_encode($response));  
}

if( empty($_FILES['images']) or !isset($_FILES['images']) )
{
    $response = array(
        'status'  => 'warning',
        'message' => 'Uma imagem é obrigatória.',
        'link'    => '',
     );
     die(json_encode($response));
}

if( empty($_POST['slide-identificacao']) or !isset($_POST['slide-identificacao']) )
{
    $error_message .= '<p>Forneça uma identificação para o banner.</p>';
}
else
{
    $slide_identificacao = filterString($_POST['slide-identificacao'], 'CHAR');
}

if( empty($_POST['slide-link']) or !isset($_POST['slide-link']) )
{
    $slide_link = '';
}
else
{
    $slide_link = filter_var($_POST['slide-link'], FILTER_SANITIZE_URL);
    if(valideuRl($slide_link) === false)
    {
        $error_message .= '<p>Forneça um endereço de url válido para o campo link.</p>';
    }
}

if( empty($_POST['slide-texto']) or !isset($_POST['slide-texto']) )
{
    $slide_texto = '';
}
else
{
    $slide_texto = $_POST['slide-texto'];
}

if( !empty($error_message) )
{
    $response = array(
        'status'  => 'warning',
        'message' => $error_message,
        'link'    => '',
     );
     die(json_encode($response));
}

$count  = sizeof($_FILES['images']['tmp_name']);
$allowed_types = array('jpg', 'png', 'gif', 'jpeg', 'JPG', 'PNG', 'GIF', 'JPEG');

$image_name    = $_FILES['images']['name'];
$image_type    = $_FILES['images']['type'];
$image_tmpName = $_FILES['images']['tmp_name'];
$image_size    = $_FILES['images']['size'];
$image_ext     = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));

if( $image_size > 5242880)
{
    $response = array(
        'status'  => 'warning',
        'message' => 'A imagem não pode ser maior que 5MB.'.$image_size,
        'link'    => '',
     );
     die(json_encode($response));
}

if( $image_size >= 0 and mb_strlen($image_name, 'utf8') < 1 ) 
{
    $response = array(
        'status'  => 'warning',
        'message' => 'Uma imagem é obrigatória.',
        'link'    => '',
     );
     die(json_encode($response));
}

if ( !in_array($image_ext, $allowed_types) )
{
    $response = array(
        'status'  => 'warning',
        'message' => 'A imagem '.$image_name.' possui um formato não permitido! ',
        'link'    => '',
     );
     die(json_encode($response));
}

$image_new_name = date("Ymdhis").time().mt_rand(0, 999999).'.'.$image_ext;
$type = 'image';

$con_db = new config\connect_db();
$con = $con_db->connect();

$insert_image = $con->prepare("INSERT INTO `slides` (type, name, image, link, text, autor_post, date_post) VALUES (?, ?, ?, ?, ?, ?, ?) ") ;
$insert_image->bind_param('sssssis', $type, $slide_identificacao, $image_new_name, $slide_link, $slide_texto, $user_id, $date_time);
$insert_image->execute() ;
$rows_insert_image = $insert_image->affected_rows;
$last_id = $insert_image->insert_id;
$insert_image->close();

if( $insert_image and $rows_insert_image > 0 )
{   
    $patch = $_SERVER['DOCUMENT_ROOT'].'/docs/slides/';
    
    $image_lg = $patch.'lg/'.$image_new_name;
    $image_md = $patch.'md/'.$image_new_name;
    $image_sm = $patch.'sm/'.$image_new_name;
    $image_xs = $patch.'xs/'.$image_new_name;
    
    $tmp = $patch.'tmp/'.$image_new_name;
   
    if( !file_exists($patch.'tmp') )
    {
        mkdir($tmp, 0777, true);
    }

    if( move_uploaded_file($image_tmpName, $tmp) )
    {
        $lg = explode('x', $_SESSION['config']['slide-lg']);
        $md = explode('x', $_SESSION['config']['slide-md']);
        $sm = explode('x', $_SESSION['config']['slide-sm']);
        $xs = explode('x', $_SESSION['config']['slide-xs']);
        
        $image_wide = new WideImage();
        $image_wide = WideImage::load($tmp);
         
        if( $image_ext === 'jpg' or $image_ext === 'jpeg' )
        {
            $image_wide = $image_wide->resize($lg[0], $lg[1], 'inside', 'down');
            $image_wide->saveToFile($image_lg, 70);
            
            $image_wide = $image_wide->resize($md[0], $md[1], 'inside', 'down');
            $image_wide->saveToFile($image_md, 70);
            
            $image_wide = $image_wide->resize($sm[0], $sm[1], 'inside', 'down');
            $image_wide->saveToFile($image_sm, 70);
            
            $image_wide = $image_wide->resize($xs[0], $xs[1], 'inside', 'down');
            $image_wide->saveToFile($image_xs, 70);
        }
        else if( $image_ext == 'png' )
        {   
            $image_wide = $image_wide->resize($lg[0], $lg[1], 'inside', 'down');
            $image_wide->saveToFile($image_lg, 9);
            
            $image_wide = $image_wide->resize($md[0], $md[1], 'inside', 'down');
            $image_wide->saveToFile($image_md, 9);
            
            $image_wide = $image_wide->resize($sm[0], $sm[1], 'inside', 'down');
            $image_wide->saveToFile($image_sm, 9);
            
            $image_wide = $image_wide->resize($xs[0], $xs[1], 'inside', 'down');
            $image_wide->saveToFile($image_xs, 9);
        }
        else
        {
            $image_wide->saveToFile($image_lg);
            $image_wide->saveToFile($image_md);
            $image_wide->saveToFile($image_sm);
            $image_wide->saveToFile($image_xs);
        }//. if tipo de salvamento
        
        $image_wide->destroy();
        
        //elimina a temporária
         if(file_exists($tmp))
         {
            unlink($tmp); 
         }
         
         $response = array(
            'status'  => 'success',
            'message' => 'Slide adicionado',
            'link'    => '',
         );
         die(json_encode($response));
         
    }  
    else
    {
        $del = $con->query(" DELETE FROM `slides` WHERE `id` = '$last_id' ");
        $response = array(
            'status'  => 'error',
            'message' => 'Upload não pode ser concluído. Tente novamente se o erro persistir entre em contato com suporte técnico.',
            'link'    => '',
         );
         die(json_encode($response));
         
    }// if move_uploaded
    
}
else
{
    
    $response = array(
        'status'  => 'error',
        'message' => 'Ocorreu um problema ao salva o slide. Tente novamente se o erro persistir entre em contato com suporte técnico.',
        'link'    => '',
     );
     die(json_encode($response));
     
}//if insert slide