<?php
header('Content-Type: application/json');
require_once ($_SERVER['DOCUMENT_ROOT'].'/config/autoload.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/config/config.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/app/controls/adminFunctions.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/config/public_functions.php');

use app\controls\authUser;
use app\controls\session;
use app\controls\reportBug;
use app\controls\activityRecord;

$server_failure = array(
    'status'  => 'error',
    'message' => 'O servidor não está respondendo.',
    'link'    => '',
);
    

if( empty($_POST['login']) or !isset($_POST['login']) or empty($_POST['password']) or !isset($_POST['password']) )
{
    $response = array(
        'status'  => 'error',
        'message' => 'Preencha todos os campos.',
        'link'    => '',
      );
    die(json_encode($response));
}
else
{
    $user = filterString($_POST['login'], 'CHAR');
    $password = filterString($_POST['password'], 'CHAR');
    
    $auth = new authUser();
    $auth->startAuth($user, $password);
    
    /* Verifica se o usuário existe */
    if($auth->existUser() === true )
    {
        /* Verifica se a conta está ativa */
        if($auth->isActive() === false)
        {
            $response = array(
                'status'  => 'error',
                'message' => 'Conta desativada',
                'link'    => '',
            );
            die(json_encode($response));
        }
        else
        {
            /* Verifica se a senha é a mesma do banco  */
            if($auth->checkPassword() === true and $auth->isExceededAttempts() === false)
            {
                if($auth->startSession() === true)
                {
                    $auth->resetAttempts();
                    $register_activity = new activityRecord();
                    $register_activity->record ($auth->user_id, 'private', 'login', 'fez login no sistema', null);
                    
                    $response = array(
                        'status'  => 'success',
                        'message' => 'Usuário autenticado com sucesso!',
                        'link'    => '',
                    );
                    die(json_encode($response));
                }
                else
                {
                    $response = array(
                        'status'  => 'error',
                        'message' => 'A sessão não pode ser iniciada.',// trocar mensagem após testes
                        'link'    => '',
                    );
                    die(json_encode($response));
                }
            }
            else if($auth->checkPassword() === true and $auth->isExceededAttempts() !== false)
            {
                $response = array(
                    'status'  => 'error',
                    'message' => 'Tente novamente dentro de '.$auth->isExceededAttempts().' minutos.',
                    'link'    => '',
                );
                die(json_encode($response));
            }
            else if($auth->checkPassword() === false and $auth->isExceededAttempts() !== false)
            {
                $auth->registerAttempts();
                $response = array(
                    'status'  => 'error',
                    'message' => 'Tente novamente dentro de '.$auth->isExceededAttempts().' minutos.',
                    'link'    => '',
                );
                die(json_encode($response));
            }
            else if($auth->checkPassword() === false and $auth->isExceededAttempts() === false)
            {
                $auth->registerAttempts();
                $response = array(
                    'status'  => 'error',
                    'message' => 'Senha incorreta',
                    'link'    => '',
                );
                die(json_encode($response));
            }
            else
            {   
                $auth->registerAttempts();
                $response = array(
                    'status'  => 'error',
                    'message' => 'Não entendemos seu pedido',
                    'link'    => '',
                );
                die(json_encode($response));

            }// if se a senha confere
            
            
        }// if se a conta está ativa
        
    }
    else
    {
         $response = array(
             'status'  => 'info',
             'message' => 'Usuário não cadastrado',
             'link'    => '',
          );
          die(json_encode($response));
    }
    // if se existe usuário --end--
}
