<?php
require_once ($_SERVER['DOCUMENT_ROOT'].'/config/config.php');
session_name(SESSION_NAME);
session_start();
require_once ($_SERVER['DOCUMENT_ROOT'].'/config/autoload.php');
require_once( $_SERVER[ 'DOCUMENT_ROOT' ] . '/config/public_functions.php' );
require_once( $_SERVER[ 'DOCUMENT_ROOT' ] . '/app/controls/adminFunctions.php' );

if( !isset($_POST['status'])  )
{
    die('gggg');
}
else
{
    if( $_POST['status'] != 'ON' or $_POST['status'] != 'OFF' or $_POST['status'] != 'ABSENT' )
    {
        die($_POST['status']);
    }
    else
    {
        $con_db = new config\connect_db();
        $con = $con_db->connect();
        
        $status = filterString($_POST['status'], 'CHAR');
        
        $update_status = $con->prepare("INSERT INTO online_users (user_id, status, last_access) VALUES (?, ?, NOW()) ON DUPLICATE KEY UPDATE status = VALUES(status), last_access = NOW() ) ") or die(mysqli_error($con));
        $update_status->bind_param('is', $_SESSION['user_id'], $status);
        $update_status->execute();
        $rows = $update_status->affected_rows;
        $update_status->close();


        if($update_status and $rows <= 0)
        {
            die('error');
        }
        else
        {
            die('success');
        }
            
    }
}
?>