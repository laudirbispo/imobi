<?php
session_name(SESSION_NAME);
session_start();
header('Content-Type: application/json');
require_once ($_SERVER['DOCUMENT_ROOT'].'/config/autoload.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/config/public_functions.php');

$default_response = array(
    'status'  => 'warning',
    'message' => 'O servidor não respondeu ao nosso chamado. Tente novamente se o erro persistir entre em contato com suporte técnico.[DBx0001]',
    'link'    => '',
);


if( ($_SESSION['user_master_perms'] !== 'administrador') )
{
  if( $_SESSION['slide_edit'] !== '1')
  {
      $response = array(
        'status'  => 'error',
        'message' => 'Você não tem permissão para realizar está ação.',
        'link'    => '',
      );
     die(json_encode($response));
  }
}

if( empty($_GET['id']) or !isset($_GET['id']) )
{
    $response = array(
        'status'  => 'warning',
        'message' => 'Ordem dos slides não identificada.',
        'link'    => '',
     );
     die(json_encode($response));
}
else
{
    $con_db = new config\connect_db();
    $con = $con_db->connect();

    foreach($_GET['id'] as $key => $id)
    {
        $id = filterString($id, 'INT');
        $key = filterString($key, 'INT');
        
        $order = $con->query(" UPDATE `slides` SET `ordered` = '$key' WHERE 3`id` = '$id' ") or die(json_encode($default_response));;
        if(!$order)
        {
            $response = array(
                'status'  => 'error',
                'message' => 'Não foi possível atualizar a ordem de alguns itens. Tente novamente se o erro persistir entre em contato com suporte técnico.',
                'link'    => '',
             );
             die(json_encode($response));
        }
        
    }// foreach
    
    $response = array(
        'status'  => 'success',
        'message' => '',
        'link'    => '',
     );
     die(json_encode($response));
    
}


