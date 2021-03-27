<?php
session_name(SESSION_NAME);
session_start();
require_once ($_SERVER['DOCUMENT_ROOT'].'/config/autoload.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/config/config.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/config/public_functions.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/app/controls/adminFunctions.php');

use config\connect_db;

if( empty($_GET['action']) or !isset($_GET['action']) )
{
    die('<OPTION VALUE="">Error ao obter lista, nenhuma ação reconhecida</OPTION>');
}
else
{
    if( empty($_GET['option']) or !isset($_GET['option']) )
    {
         die('<OPTION VALUE="">Error ao obter lista, escolha uma opção</OPTION>');
    }
    else
    {
        if( $_GET['action'] === 'select_brand' )
        {
            $category = filterString($_GET['option'], 'CHAR');
            die(returnBrandsVehicles($category));
        }
        else if( $_GET['action'] === 'select_model' )
        {   
            $brand = filterString($_GET['option'], 'CHAR');
            
            if( empty($_GET['category']) or !isset($_GET['category']) )
            {
                die('<OPTION VALUE="">Selecione uma categoria</OPTION>');
            }
            else
            {   
                $categoria = filterString($_GET['category'], 'CHAR');
                die(returnModelsVehicles($brand, $categoria));
            }
        }
        else
        {
            die('<OPTION VALUE="">Error ao obter lista</OPTION>');
        }
    }
}

//-----------------------------------------------------------------------------------



function returnBrandsVehicles($category)
{
    $con_db = new config\connect_db();
    $con = $con_db->connect();
    $vehicles_brands = $con->query(" SELECT * FROM `vehicles_brands` WHERE `categoria` = '$category' ORDER BY `marca` ASC ");
    $rows = $vehicles_brands->num_rows;
    
    $b = '';
    while( $reg = $vehicles_brands->fetch_assoc() )
    {
       $b .= '<OPTION VALUE="'.$reg['marca'].'">'.$reg['marca'].'</OPTION>'; 
    }
    
    if( $vehicles_brands and $rows > 0 )
    {   
        $vehicles_brands->close();
        return $b; 
    }
    else
    {
        $vehicles_brands->close();
        return('<OPTION VALUE="">Error ao obter lista</OPTION>');
    }
}

function returnModelsVehicles($brand, $categoria)
{
    $con_db = new config\connect_db();
    $con = $con_db->connect();
    $vehicles_models = $con->query(" SELECT * FROM `vehicles_models` WHERE `categoria` = '$categoria' AND `marca` = '$brand' ORDER BY `modelo` ASC ");
    $rows = $vehicles_models->num_rows;
    
    $m = '';
    while( $reg = $vehicles_models->fetch_assoc() )
    {
       $m .= '<OPTION VALUE="'.$reg['modelo'].'">'.$reg['modelo'].'</OPTION>'; 
    }
    
    if( $vehicles_models and $rows > 0 )
    {   
        $vehicles_models->close();
        return $m; 
    }
    else if( $vehicles_models and $rows <= 0 )
    {   
        $vehicles_models->close();
        return('<OPTION VALUE="">Nenhum ítem cadastrado para essa marca.</OPTION>'); 
    }
    else
    {
        $vehicles_models->close();
        return('<OPTION VALUE="">Falha ao obter lista</OPTION>');
    }
}



