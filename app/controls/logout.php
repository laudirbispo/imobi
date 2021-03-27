<?php
require_once ($_SERVER['DOCUMENT_ROOT'].'/config/config.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/config/autoload.php');
session_name(SESSION_NAME);
session_start();

$con_db = new config\connect_db();
$con = $con_db->connect();

$sts = '3';

$update_status = $con->prepare("INSERT INTO online_users (user_id, status) VALUES (?, ?) ON DUPLICATE KEY UPDATE status = '3' ");
$update_status->bind_param('ii', $_SESSION['user_id'], $sts);
$update_status->execute();
$update_status->close();

$_SESSION = array();
unset($_SESSION);
session_destroy();

header("Location: /app/login");

?>