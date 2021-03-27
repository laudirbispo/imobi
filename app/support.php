<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/config/config.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/config/autoload.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/config/public_functions.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/app/controls/adminFunctions.php');
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>loires:</title>
<link href="/app/images/logo-loires-ico.png" favicon.png"" rel="shortcut icon">
<meta name="author" content="Laudir Bispo">
<meta http-equiv="content-language" content="pt-br">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport"/>
<link rel="stylesheet" href="/app/css/bootstrap.min.css">
<link rel="stylesheet" href="/app/css/font-awesome.min.css">
<link rel="stylesheet" href="/app/css/admin.css">
<link rel="stylesheet" href="/app/css/AdminLTE.min.css">
<link rel="stylesheet" href="/app/css/_all-skins.min.css"> 
<link rel="stylesheet" href="/libs/jQueryUi-1.12.1/jquery-ui.min.css"> 
<link rel="stylesheet" href="/plugins/jQueryConfirm/jquery-confirm.min.css">
<link rel="stylesheet" href="/plugins/Pnotify/pnotify.custom.min.css">
<link rel="stylesheet" href="/plugins/animations/css/animations.min.css">
<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,400i,600,600i,700,700i,800,800i" rel="stylesheet"> 
<META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">
<NOSCRIPT>
    <meta http-equiv="refresh" content="0; url=noscript.html">
</NOSCRIPT>
<script src="/libs/jQuery/jquery-2.2.3.js"></script>
<script src="/app/javascript/bootstrap.min.js"></script>
<!-- jQuery UI 1.12.1 -->
<script src="/libs/jQueryUi-1.12.1/jquery-ui.min.js"></script>
<script src="/plugins/bootstrap-validator-master/dist/validator.min.js"></script>
<script src="/app/javascript/global.js"></script>
</head>

<body data-spy="scroll" style=" background-color: #FFF"/>
<!-- Content Wrapper. Contains page content -->
<DIV CLASS="content">
    <!-- Main content -->
    <SECTION CLASS="conteiner">


        <?php 
            if( empty($_GET['pageHelp']) or !isset($_GET['pageHelp'])  )
            {
                include ($_SERVER['DOCUMENT_ROOT'].'/app/support/pages/starting.php');
            } 
            else 
            {
                require_once ($_SERVER['DOCUMENT_ROOT'].'/libs/HTMLPurifier/HTMLPurifier.auto.php');
                $HTMLPurifier_config = HTMLPurifier_Config::createDefault();
                $purifier = new HTMLPurifier($HTMLPurifier_config);
                $getPage = $purifier->purify($_GET['pageHelp']);

                $base_path = $_SERVER['DOCUMENT_ROOT'].'/app/support/pages/';
                if (!file_exists($base_path.$getPage.'.php'))
                {
                    $page = $_SERVER['DOCUMENT_ROOT'].'/app/views/404.php' ;
                }
                else
                {
                    $page = $base_path.$getPage.'.php' ;  
                }

                require_once($page);
            }               
        ?>
      
    </SECTION>
    <!-- /.content -->
</DIV>
<!-- /.content-wrapper -->


<!-- Ativa ações de mouse das widgets jQuery Ui no celular -->
<script src="/libs/jQueryUi-1.12.1/jQueryUiTouch.js"></script>
<!-- AdminLTE App -->
<script src="/app/javascript/app.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="/app/javascript/demo.js"></script>
<script src="/app/javascript/functions.js"></script>
</body>
</html>
