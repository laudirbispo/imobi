<?php
require_once ($_SERVER['DOCUMENT_ROOT'].'/config/config.php');
session_name(SESSION_NAME);
session_start();
header('Content-Type: application/json');
require_once ($_SERVER['DOCUMENT_ROOT'].'/config/autoload.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/config/public_functions.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/app/controls/adminFunctions.php');

use config\connect_db;
use app\controls\activityRecord;

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
//--------------------------------------------------

if(!isset($_POST['actionid']))
{
    die(json_encode($response = array(
        'status'  => 'warning',
        'message' => 'Escolha um usuário para alterar as permissões.',
        'link'    => '',
     )));
}
else
{
    $actionid = filterString($_POST['actionid'], 'INT');
}

if(!isset($_POST['user_confirm']))
{
    die(json_encode($response = array(
        'status'  => 'warning',
        'message' => 'Por favor, confirme o usuário para continuar!',
        'link'    => '',
     )));
}

$con_db = new config\connect_db();
$con = $con_db->connect();

$is_adm = $con->prepare("SELECT type FROM sec_users WHERE id = ? ");
$is_adm->bind_param('i', $actionid);
$is_adm->execute();
$is_adm->store_result();
$is_adm->bind_result($type);
$is_adm->fetch();
$rows_is_adm = $is_adm->affected_rows;
$is_adm->free_result();
$is_adm->close();

if( $is_adm or $rows_is_adm > 0 )
{
    if( $type == 'administrador' or $type == 'suporte' )
    {
        die(json_encode($response = array(
            'status'  => 'warning',
            'message' => 'Você não pode alterar as permissões deste usuário.',
            'link'    => '',
         )));
    }
    else
    {
        $perms = (!empty($_POST['perms']) and isset($_POST['perms'])) ? implode(',', $_POST['perms']) : '' ;
        
        $update = $con->prepare("INSERT INTO user_perms (user_id, perms) VALUES (?, ?) ON DUPLICATE KEY UPDATE perms = VALUES(perms)") or die(mysqli_error($con));
        $update->bind_param('is', $actionid, $perms);
        $update->execute();
        $rows_up = $update->affected_rows;
        $update->close();
        
        if($update)
        {
            $register_activity = new activityRecord();
            $register_activity->record ($actionid, 'private', 'login', 'teve as permissões de acesso alterada.', null);
            die(json_encode($response = array(
                'status'  => 'success',
                'message' => 'As permissões foram alteradas',
                'link'    => '',
             )));
        }
        else
        {
            die(json_encode($response = array(
                'status'  => 'error',
                'message' => 'O servidor não está respondendo. Tente novamente, se o erro percistir, entre em contato com o admistrador!',
                'link'    => '',
             )));
        }

    }
}
else
{
    die(json_encode($response = array(
        'status'  => 'warning',
        'message' => 'Por favor, confirme o usuário para continuar!',
        'link'    => '',
     )));
}


?>