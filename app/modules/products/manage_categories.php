<?php
require_once ($_SERVER['DOCUMENT_ROOT'].'/config/config.php');
session_name(SESSION_NAME);
session_start();
header('Content-Type: application/json');
require_once ($_SERVER['DOCUMENT_ROOT'].'/config/autoload.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/config/public_functions.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/app/controls/adminFunctions.php');

use config\connect_db;
use app\controls\blowfish_crypt;

if( $_SESSION['user_type'] !== 'administrador' and $_SESSION['user_type'] !== 'suporte' )
{
    if(!isset($_SESSION['products_configure']) or $_SESSION['products_configure'] !== 'Y' )
    {
        die(json_encode($response = array(
           'status'  => 'error',
           'message' => 'Você não tem permissão para realizar está ação.',
           'link'    => '',
        )));
    }
    
}

if (empty($_GET['action']) or !isset($_GET['action']))
{
    die(json_encode($response = array(
       'status'  => 'error',
       'message' => 'Você não tem permissão para realizar está ação.',
       'link'    => '',
    )));
}
else
{
    $action = filterString($_GET['action'], 'CHAR');
}


$con_db = new config\connect_db();
$con = $con_db->connect();

switch ($action) 
{
    case 'new-category':
        
        if (empty($_GET['categoryName']) or !isset($_GET['categoryName']))
        {
            die(json_encode($response = array(
               'status'  => 'warning',
               'message' => 'Você esqueceu de informar o nome da nova categoria.',
               'link'    => '',
            )));
        }
        else
        {
            $categoryName = filterString($_GET['categoryName'], 'CHAR');
        }
        
        if (empty($_GET['categorySelected']) or !isset($_GET['categorySelected']))
        {
            $categorySelected = 0;
        }
        else
        {
            $categorySelected = filterString($_GET['categorySelected'], 'INT');
        }
        
        $insertCategory = $con->prepare("INSERT INTO `products_categories` ( `category_name`, `category_parent`) VALUES (?, ?)");
        $insertCategory->bind_param('si',$categoryName, $categorySelected );
        $insertCategory->execute();
        $ic_rows = $insertCategory->affected_rows;
        $insertCategory->close();
        
        if ($insertCategory and $ic_rows > 0)
        {
            die(json_encode($response = array(
               'status'  => 'success',
               'message' => 'Categoria adicionada',
               'link'    => '',
            )));
        }
        else
        {
            die(json_encode($response = array(
               'status'  => 'error',
               'message' => 'Não foi possível adicionar a categoria',
               'link'    => '',
            )));
        }
        
    break;
        
    case 'edit-category':
        
        if (empty($_GET['categoryName']) or !isset($_GET['categoryName']))
        {
            die(json_encode($response = array(
               'status'  => 'warning',
               'message' => 'Você esqueceu de informar o nome da nova categoria.',
               'link'    => '',
            )));
        }
        else
        {
            $categoryName = filterString($_GET['categoryName'], 'CHAR');
        }
        
        if (empty($_GET['categorySelected']) or !isset($_GET['categorySelected']))
        {
            die(json_encode($response = array(
               'status'  => 'warning',
               'message' => 'Você esqueceu de uma categoria para editar o nome.',
               'link'    => '',
            )));
        }
        else
        {
            $categorySelected = filterString($_GET['categorySelected'], 'INT');
        }
        
        $updateCategory = $con->pepare("UPDATE `products_categories` SET `category_name` = ? WHERE `category_id` = ?");
        
    break;
        
    case 'delete-category':
        
    break;
        
}
    
    
    
    
    
    