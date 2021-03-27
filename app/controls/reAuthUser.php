<?php
header('Content-Type: application/json');
require_once ($_SERVER['DOCUMENT_ROOT'].'/config/autoload.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/config/config.php');
session_name(SESSION_NAME);
session_start();

use app\controls\blowfish_crypt;
use config\connect_db;

if(!isset($_POST['secret-form-token']) or $_POST['secret-form-token'] !== md5(SECRET_FORM_TOKEN.$_SESSION['user_id'].$_SESSION['user']))
{
    $response = array(
        'status'  => 'error',
        'message' => 'A origem de alguns dados nos parece duvidosa! Por isso bloqueamos está ação.',
        'link'    => '',
    );
    die(json_encode($response));
}

if(empty($_POST['password']) or !isset($_POST['password']))
{
    $response = array(
        'status'  => 'warning',
        'message' => 'Informe sua senha.',
        'link'    => '',
    );
    die(json_encode($response));
}
else
{
    $con_db = new connect_db();
    $con = $con_db->connect();
    
    $pass = $_POST['password'];
    
    $reAuth = $con->prepare("SELECT password FROM sec_users WHERE id = ?");
    $reAuth->bind_param('i', $_SESSION['user_id']);
    $reAuth->execute();
    $reAuth->store_result();
    $reAuth->bind_result($password_db);
    $reAuth->fetch();
    $rows = $reAuth->num_rows;
    $reAuth->free_result();
    $reAuth->close();
    
    if($reAuth and $rows > 0)
    {
        $crypt = new blowfish_crypt();
        
        if($crypt->check($pass, $password_db) === true)
        {
            $_SESSION['user_auth'] = 'Y';
            $response = array(
                'status'  => 'success',
                'message' => 'Usuário autenticado.',
                'link'    => '',
            );
            die(json_encode($response));
        }
        else
        {
            $response = array(
                'status'  => 'error',
                'message' => 'Senha incorreta.',
                'link'    => '',
            );
            die(json_encode($response));
        }
        
    }
    else
    {
        $response = array(
            'status'  => 'error',
            'message' => 'Não autenticado.',
            'link'    => '',
        );
        die(json_encode($response));
    }
}



