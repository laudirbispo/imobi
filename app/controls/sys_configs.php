<?php
require_once ($_SERVER['DOCUMENT_ROOT'].'/config/autoload.php');

use config\connect_db;

$con_db = new connect_db();
$con = $con_db->connect();

$configs = $con->query(" SELECT * FROM `system_configs`");

while( $reg = $configs->fetch_array() )
{
    $_SESSION['config']['slide-lg'] = $reg['slide-lg'];
    $_SESSION['config']['slide-md'] = $reg['slide-md'];
    $_SESSION['config']['slide-sm'] = $reg['slide-sm'];
    $_SESSION['config']['slide-xs'] = $reg['slide-xs'];
    $_SESSION['config']['slide_image_quality'] = $reg['slide_image_quality'];
    $_SESSION['config']['vehicles_image_quality'] = $reg['vehicles_image_quality'];
    $_SESSION['config']['gallery_image_quality'] = $reg['gallery_image_quality'];
    
}

$configs->close();