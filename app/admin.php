<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/config/config.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/config/autoload.php');
session_name(SESSION_NAME);
session_start();

if (!empty($_SESSION['user_id']) or isset($_SESSION['user_id'])) 
{
    if ( $_SESSION['user_auth'] != 'Y' ) header( "Location: /app/loockscreen" );
} 
else 
{
    unset($_SESSION);
    session_destroy();
    header( "Location: /app/login" );
}
require_once($_SERVER['DOCUMENT_ROOT'].'/config/public_functions.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/app/controls/adminFunctions.php');

$return = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>loires:</title>
<link href="favicon.png" rel="shortcut icon">
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
<META http-equiv="refresh" content="1500000; url=/app/loockscreen/?return=<?php echo base64_encode($return) ?>">
<META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">
<NOSCRIPT>
    <meta http-equiv="refresh" content="0; url=noscript.html">
</NOSCRIPT>
<script src="/libs/jQuery/jquery-2.2.3.js"></script>
<!-- jQuery UI 1.12.1 -->
<script src="/libs/jQueryUi-1.12.1/jquery-ui.min.js"></script>
<script src="/app/javascript/bootstrap.min.js"></script>
<script src="/plugins/bootstrap-validator-master/dist/validator.min.js"></script>
<script src="/app/javascript/global.js"></script>
</head>

<body data-spy="scroll" class="hold-transition skin-black-light sidebar-mini"/>
<DIV CLASS="wrapper">

    <HEADER CLASS="main-header">
        <a href="/app/admin/home" class="logo hidden-xs" style="position: fixed">
          <span class="logo-mini"><i class="fa fa-home"></i></span>
          <span class="logo-lg"><i class="fa fa-home"></i> HOME</span>
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <NAV CLASS="navbar navbar-fixed-top">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" DATA-TOGGLE="offcanvas" role="button">
                <SPAN CLASS="sr-only">Toggle navigation</SPAN>
            </a>
            <DIV CLASS="navbar-custom-menu">
                <UL CLASS="nav navbar-nav">
                    <!-- Messages: style can be found in dropdown.less-->
                    <LI CLASS="dropdown messages-menu hidden" ID="notification-recados">
                        <a href="#" class="dropdown-toggle" DATA-TOGGLE="dropdown">
                            <I CLASS="fa fa-commenting-o"></I>
                            <?php //echo AlertNewsRecados(); ?>
                        </a>
                        <?php //echo NotificationRecados(); ?>
                    </LI>
                    <!-- /. notificações de recados  -->

                    <!-- Notifications: style can be found in dropdown.less -->
                    <LI CLASS="dropdown notifications-menu" id="notifications-receipts">
                        <?php echo expiredReceiptsNotifications (); ?>
                    </LI>

                    <!-- Menssagens do Suporte-->
                    <LI CLASS="dropdown messages-menu hidden">
                        <a href="#" class="dropdown-toggle" DATA-TOGGLE="dropdown" aria-expanded="true">
                            <I CLASS="fa fa-globe"></I>
                            <SPAN CLASS="label label-success"></SPAN>
                        </a>
                        <UL CLASS="dropdown-menu">
                            <LI>
                                <!-- inner menu: contains the actual data -->
                                <UL CLASS="menu text-darkgray" STYLE="color:#333 !important">
                                </UL>
                            </LI>
                        </UL>
                    </LI>
                    <!-- Tasks: style can be found in dropdown.less -->
                    <LI CLASS="dropdown tasks-menu hidden">
                        <a href="#" class="dropdown-toggle" DATA-TOGGLE="dropdown">
                            <I CLASS="fa fa-flag-o"></I>
                            <SPAN CLASS="label label-danger">9</SPAN>
                        </a>
                        <UL CLASS="dropdown-menu">
                            <LI CLASS="header">You have 9 tasks</LI>
                            <LI>
                                <!-- inner menu: contains the actual data -->
                                <UL CLASS="menu">
                                    <LI><a href="#">
                                      <H3>Some task I need to do <SMALL CLASS="pull-right">60%</SMALL></H3>
                                      <DIV CLASS="progress xs">
                                        <DIV CLASS="progress-bar progress-bar-red" STYLE="width: 60%" ROLE="progressbar" ARIA-VALUENOW="20" ARIA-VALUEMIN="0" ARIA-VALUEMAX="100">
                                          <SPAN CLASS="sr-only">60% Complete</SPAN>
                                        </DIV>
                                      </DIV>
                                    </a>
                                    </LI>
                                    <!-- end task item -->
                                    <LI>
                                        <a href="#">
                                          <H3>Make beautiful transitions <SMALL CLASS="pull-right">80%</SMALL></H3>
                                          <DIV CLASS="progress xs">
                                            <DIV CLASS="progress-bar progress-bar-yellow" STYLE="width: 80%" ROLE="progressbar" ARIA-VALUENOW="20" ARIA-VALUEMIN="0" ARIA-VALUEMAX="100">
                                              <SPAN CLASS="sr-only">80% Complete</SPAN>
                                            </DIV>
                                          </DIV>
                                        </a>
                                    </LI>
                                    <!-- end task item -->
                                </UL>
                            </LI>
                            <LI CLASS="footer">
                                <a href="#">View all tasks</a>
                            </LI>
                        </UL>
                    </LI>
                    <!-- Control Sidebar Toggle Button -->
                    <LI>
                        <a href="#" DATA-TOGGLE="control-sidebar">
                            <I CLASS="fa fa-gears"></I>
                        </a>
                    </LI>
                    <!-- User Account: style can be found in dropdown.less -->
                    <LI CLASS="dropdown user user-menu">

                        <IMG SRC="<?php echo $_SESSION['user_photo'] ?>" HEIGHT="50" CLASS="dropdown-toggle" DATA-TOGGLE="dropdown" STYLE="cursor:pointer" alt="Foto de perfil">

                        <UL CLASS="dropdown-menu">
                            <!-- User image -->
                            <LI CLASS="user-header ">
                                <IMG SRC="<?php echo $_SESSION['user_photo'] ?>" CLASS="img-circle" alt="Foto de perfil">
                                    <P><?php echo $_SESSION['user_name'] ?><SMALL><?php echo $_SESSION['user_type'] ?></SMALL></P>
                            </LI>
                            <!-- Menu Footer-->
                            <LI CLASS="user-footer">
                                <DIV CLASS="pull-left">
                                    <a href="/app/admin/my_account" class="btn btn-default btn-flat" title="Minha conta"><i class="fa fa-user" aria-hidden="true"></i></a>
                                </DIV>
                                <DIV CLASS="pull-left">
                                    <a href="/app/loockscreen/?return=<?php echo base64_encode($return) ?>" class="btn btn-warning btn-flat" title="Bloquear tela"><i class="fa fa-lock" aria-hidden="true"></i></a>
                                </DIV>
                                <DIV CLASS="pull-left">
                                    <a href="/app/controls/logout.php" class="btn btn-danger btn-flat" title="Sair"><i class="fa fa-power-off" aria-hidden="true"></i></a>
                                </DIV>
                            </LI>
                        </UL>
                    </LI>
                </UL>
            </DIV>
        </NAV>
    </HEADER>

    <!-- Left side column. contains the logo and sidebar -->
    <ASIDE CLASS="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <SECTION CLASS="sidebar">

            <DIV CLASS="user-panel">
                <DIV CLASS="pull-left image">
                    <IMG SRC="<?php echo $_SESSION['user_photo'] ?>"  CLASS="img-circle" alt="Foto de perfil">
                </DIV>
                <DIV CLASS="pull-left info">
                    <P CLASS="text-capitalize">
                        <?php echo $_SESSION['user_name'] ?>
                    </P>
                    <SMALL CLASS="text-capitalize">
                        <?php echo ucwords($_SESSION['user_type']) ?>
                    </SMALL>
                </DIV>
            </DIV>

            <!-- sidebar menu: : style can be found in sidebar.less -->
            <UL CLASS="sidebar-menu">
                <li class="header">MENU</li>
                
                <?php
                if (( in_array('news', $active_modules)) and ($_SESSION['user_type'] === 'administrador' or $_SESSION['user_type'] === 'suporte' or isset($_SESSION['news_view'])) ) :
                ?>             
                    <LI CLASS="treeview">
                        <a href="javascript:;">
                            <div class="icons_sys" id="sprites-news"></div> 
                            <span>NOTÍCIAS</span>
                            <I CLASS="fa fa-angle-left pull-right"></I>
                        </a>
                        <UL CLASS="treeview-menu">
                            <LI CLASS="active"><a href="/app/admin/news"><SPAN>Editar Notícias</SPAN></a>
                            </LI>
                            <LI><a href="/app/admin/edit_news"><SPAN>Escrever Notícia</SPAN></a>
                            </LI>
                        </UL>
                    </LI>
                <?php endif; ?>
                
                <?php              
                if( ( in_array('products', $active_modules)) and ($_SESSION['user_type'] === 'administrador' or $_SESSION['user_type'] === 'suporte' or isset($_SESSION['products_view'])) ) :
                ?>
                    <LI CLASS="treeview">
                        <a href="javascript:;">
                            <div class="icons_sys" id="sprites-shop"></div> 
                            <span>SHOP</span> <I CLASS="fa fa-angle-left pull-right"></I>
                        </a>
                        <UL CLASS="treeview-menu">
                            <LI CLASS="active"><a href="/app/admin/products"><SPAN>Produtos</SPAN></a></LI>
                            <LI><a href="/app/admin/add_products"><SPAN>Adicionar Produtos</SPAN></a></LI>
                        </UL>
                    </LI>
                <?php endif; ?>
                
                <?php             
                if( ( in_array('vehicles', $active_modules)) and ($_SESSION['user_type'] === 'administrador' or $_SESSION['user_type'] === 'suporte' or isset($_SESSION['vehicles_view'])) ) :
                ?>
                    <LI CLASS="treeview">
                        <a href="#">
                            <div class="icons_sys" id="sprites-car"></div> 
                            <span>VEÍCULOS</span>
                            <I CLASS="fa fa-angle-left pull-right"></I>
                        </a>
                        <UL CLASS="treeview-menu">
                            <LI CLASS="active"><a href="/app/admin/vehicles"><SPAN>Veículos</SPAN></a>
                            </LI>
                            <LI><a href="/app/admin/add_cars"><SPAN>Adicionar Carros</SPAN></a>
                            </LI>
                            <LI><a href="/app/admin/add_motorcycles"><SPAN>Adicionar Motos</SPAN></a>
                            </LI>
                            <LI><a href="/app/admin/statistics_vehicles"><SPAN>Estatísticas</SPAN></a>
                            </LI>
                            <LI><a href="/app/admin/settings_vehicles"><SPAN>Configurações</SPAN></a>
                            </LI>
                        </UL>
                    </LI>
                <?php endif; ?>
                
                <?php             
                if( ( in_array('properties', $active_modules)) and ($_SESSION['user_type'] === 'administrador' or $_SESSION['user_type'] === 'suporte' or isset($_SESSION['properties_view'])) ) :
                ?>
                    <LI CLASS="treeview">
                        <a href="#">
                            <div class="icons_sys" id="sprites-properties"></div> 
                            <span>IMÓVEIS</span>
                            <I CLASS="fa fa-angle-left pull-right"></I>
                        </a>
                        <UL CLASS="treeview-menu">
                            <LI CLASS="active"><a href="/app/admin/properties"><SPAN>Imóveis Cadastrados</SPAN></a></LI>
                            <LI><a href="/app/admin/add_properties"><SPAN>Cadastrar Imóveis</SPAN></a></LI>
                            <LI class="hidden"><a href="/app/admin/settings_properties"><SPAN>Configurações</SPAN></a></LI>
                        </UL>
                    </LI>
                <?php endif; ?>
                
                <?php              
                if( ( in_array('clients', $active_modules)) and ($_SESSION['user_type'] === 'administrador' or $_SESSION['user_type'] === 'suporte' or isset($_SESSION['clients_view'])) ) :
                ?>
                    <LI CLASS="treeview">
                        <a href="javascript:;">
                            <div class="icons_sys" id="sprites-clients"></div> 
                            <span>CLIENTES</span> <I CLASS="fa fa-angle-left pull-right"></I>
                        </a>
                        <UL CLASS="treeview-menu">
                            <LI CLASS="active"><a href="/app/admin/clients"><SPAN>Clientes</SPAN></a></LI>
                            <LI><a href="/app/admin/add_clients"><SPAN>Cadastrar Cliente</SPAN></a></LI>
                        </UL>
                    </LI>
                <?php endif; ?>
                
                <?php              
                if( ( in_array('contracts', $active_modules)) and ($_SESSION['user_type'] === 'administrador' or $_SESSION['user_type'] === 'suporte' or isset($_SESSION['contracts_view'])) ) :
                ?>
                    <LI CLASS="treeview">
                        <a href="javascript:;">
                            <div class="icons_sys" id="sprites-contracts"></div> 
                            <span>CONTRATOS</span> <I CLASS="fa fa-angle-left pull-right"></I>
                        </a>
                        <UL CLASS="treeview-menu">
                            <LI CLASS="active"><a href="/app/admin/contracts"><SPAN>Contratos</SPAN></a></LI>
                            <LI><a href="/app/admin/add_contracts"><SPAN>Novo Contrato</SPAN></a></LI>
                            <LI><a href="/app/admin/new_template_contracts"><SPAN>Novo Modelo de Contrato</SPAN></a></LI>
                        </UL>
                    </LI>
                <?php endif; ?>
                
                <?php 
                if( ( in_array('scrapbook', $active_modules)) and ($_SESSION['user_type'] === 'administrador' or $_SESSION['user_type'] === 'suporte' or isset($_SESSION['scrapbook_view'])) ) :
                ?>
                    <LI>
                        <a href="/app/admin/scrapbook"><div class="icons_sys" id="sprites-scrapbook"></div><span>RECADOS</span></a>
                    </LI>
                <?php endif; ?>
                
                <?php           
                if( ( in_array('survey', $active_modules)) and ($_SESSION['user_type'] === 'administrador' or $_SESSION['user_type'] === 'suporte' or isset($_SESSION['survey_view'])) ) :
                ?>
                    <LI CLASS="">
                        <a href="/app/admin/survey"><div class="icons_sys" id="sprites-survey"></div><span>ENQUETE</SPAN></a>
                    </LI>
                <?php endif; ?>
                
                <?php  
                if( ( in_array('galerry', $active_modules)) and ($_SESSION['user_type'] === 'administrador' or $_SESSION['user_type'] === 'suporte' or isset($_SESSION['gallery_view'])) ) :
                ?>
                    <LI>
                        <a href="/app/admin/gallery"><div class="icons_sys" id="sprites-survey"></div><SPAN>GALERIA</SPAN></a>
                    </LI>
                <?php endif; ?>
                
                <?php             
                if( ( in_array('slides', $active_modules)) and ($_SESSION['user_type'] === 'administrador' or $_SESSION['user_type'] === 'suporte' or isset($_SESSION['slides_view'])) ) :
                ?>
                    <LI>
                        <a href="/app/admin/slides"><div class="icons_sys" id="sprites-slides"></div><SPAN>SLIDES</SPAN></a>
                    </LI>
                <?php endif; ?>
                
                <?php            
                if( ( in_array('top', $active_modules)) and ($_SESSION['user_type'] === 'administrador' or $_SESSION['user_type'] === 'suporte' or isset($_SESSION['top_view'])) ) :
                ?>
                    <LI>
                        <a href="/app/admin/top"><div class="icons_sys" id="sprites-top-list"></div><SPAN>TOP 10</SPAN></a>
                    </LI>   
                <?php endif; ?>
                
                <?php            
                if( ( in_array('advertising', $active_modules)) and ($_SESSION['user_type'] === 'administrador' or $_SESSION['user_type'] === 'suporte' or isset($_SESSION['advertising_view'])) ) :
                ?>
                    <LI>
                        <a href="/app/admin/advertising"><div class="icons_sys" id="sprites-advertising"></div><SPAN>PUBLICIDADE</SPAN></a>
                    </LI>
                <?php endif; ?>
                
                <?php             
                if( ( in_array('access_report', $active_modules)) and ($_SESSION['user_type'] === 'administrador' or $_SESSION['user_type'] === 'suporte' or isset($_SESSION['access_report_view'])) ) :
                ?>
                    <LI CLASS="">
                        <a href="/app/admin/statistics"><div class="icons_sys" id="sprites-statistics"></div><SPAN>RELATÓRIOS DE ACESSO</SPAN></a>
                    </LI>
                <?php endif; ?>
                
                <?php 
                if (($_SESSION['user_type'] === 'administrador' or $_SESSION['user_type'] === 'suporte' ) ) :
                ?>
                    <LI>
                        <a href="javascript:;">
                            <div class="icons_sys" id="sprites-users"></div>  
                            <span>USUÁRIOS</span> <I CLASS="fa fa-angle-left pull-right"></I>
                        </a>
                        <UL CLASS="treeview-menu">
                            <LI CLASS="active"><a href="/app/admin/users"><SPAN>Usuários</SPAN></a></LI>
                            <LI><a href="/app/admin/new_user"><SPAN>Novo Usuário</SPAN></a></LI>
                        </UL>
                    </LI>
                    <LI CLASS="treeview hidden">
                        <a href="/app/admin/settings">
                            <div class="icons_sys" id="sprites-configurations"></div>
                            <SPAN>CONFIGURAÇÕES GERAIS</SPAN><I CLASS="fa fa-angle-left pull-right"></I>
                        </a>
                        <UL CLASS="treeview-menu">
                            <LI><a href="/app/admin/site_settings"><SPAN>Configurações do Site</SPAN></a></LI>
                            <LI><a href="/app/admin/system_settings"><SPAN>Configurações do Sistema</SPAN></a></LI>
                        </UL>
                    </LI>
                <?php endif; ?>           
                
                <LI CLASS="">
                    <a href="http://ajudaesuporte.loires.com.br/" target="_blank"><div class="icons_sys" id="sprites-support"></div> <SPAN>AJUDA E SUPORTE</SPAN></a>
                </LI>

            </UL>
        </SECTION>
        <!-- /.sidebar -->
    </ASIDE>

    <!-- Content Wrapper. Contains page content -->
    <DIV CLASS="content-wrapper">


        <!-- Main content -->
        <SECTION CLASS="content">

        <?php 
            if(empty($_GET['page']) or !isset($_GET['page'])  )
            {
                include ($_SERVER['DOCUMENT_ROOT'].'/app/views/home.php') ;
                //echo 'aqui';
                // var_dump($_REQUEST);
            } 
            else 
            {
                require_once ($_SERVER['DOCUMENT_ROOT'].'/libs/HTMLPurifier/HTMLPurifier.auto.php');
                $HTMLPurifier_config = HTMLPurifier_Config::createDefault();
                $purifier = new HTMLPurifier($HTMLPurifier_config);
                $getPage = $purifier->purify($_GET['page']);
                $base_path = $_SERVER['DOCUMENT_ROOT'].'/app/views/';
                $includePage = new app\controls\securePage($base_path);
                $includePage->urlInclude($getPage);
                // var_dump($includePage);
            }               
        ?>

        </SECTION>
        <!-- /.content -->
    </DIV>
    <!-- /.content-wrapper -->

    <FOOTER CLASS="main-footer">
        <DIV CLASS="pull-right hidden-xs">
            <B>Version</B> 2.0.1
        </DIV>
        <STRONG>Copyright <?php auto_copyright($startYear = 2016); ?> <a href="http://loires.com.br"> - Loires - Soluções Integradas</a>.</STRONG> Todos os direitos reservados.
    </FOOTER>

    <!-- Control Sidebar -->
    <ASIDE CLASS="control-sidebar control-sidebar-dark">
        <!-- Create the tabs -->
        <UL CLASS="nav nav-tabs nav-justified control-sidebar-tabs">
            <LI>
                <a href="#control-sidebar-home-tab" DATA-TOGGLE="tab"><I CLASS="fa fa-pencil"></I></a>
            </LI>
            <LI>
                <a href="#control-sidebar-users-status-tab" DATA-TOGGLE="tab"><I CLASS="fa fa-users"></I></a>
            </LI>
            <LI>
                <a href="#control-sidebar-settings-tab" DATA-TOGGLE="tab"><I CLASS="fa fa-gears"></I></a>
            </LI>
        </UL>
        <!-- Tab panes -->
        <DIV CLASS="tab-content">
            <!-- Home tab content -->
            <DIV CLASS="tab-pane" ID="control-sidebar-home-tab">
                <H4 CLASS="control-sidebar-heading">Atividades recentes</H4>
                <div class="row scrollbar-custom" id="recent_activity">
                    <div class="overlay text-center">
                      <br>
                      <i class="fa fa-refresh fa-spin"></i> Carregando...
                    </div>
                </div>
                <!-- /.row -->
            </DIV>
            <!-- /.tab-pane -->
            <!-- Stats tab content -->
            <DIV CLASS="tab-pane" ID="control-sidebar-stats-tab">Stats Tab Content</DIV>
            <!-- /.tab-pane -->
            
            <!-- Users status tab-->
            <DIV CLASS="tab-pane" ID="control-sidebar-users-status-tab">
                <div class="row scrollbar-custom" id="users-status-load">
                    <div class="overlay text-center">
                        <br>
                      <i class="fa fa-refresh fa-spin"></i> Carregando...
                    </div>
                </div>
                <!-- /.row-->
            </DIV>
            <!-- /.tab-pane -->
            
            <!-- Settings tab content -->
            <DIV CLASS="tab-pane" ID="control-sidebar-settings-tab">
             <a href="javascript:;" class="link" data-control="active-desktop-notification"><i class="fa fa-bullhorn"></i> Ativar Desktop notificações.</a> 
            </DIV>
            <!-- /.tab-pane -->
        </DIV>
    </ASIDE>
    <!-- /.control-sidebar -->
    <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
    <DIV CLASS="control-sidebar-bg"></DIV>
</DIV>

<div class="droppable-trash animated" id="droppable-trash">
  <div class="lid"></div>
  <div class="lidcap"></div>
  <div class="bin"></div>
</div>

<script src="/plugins/bootstrap-validator-master/dist/validator.min.js"></script>
<!-- Ativa ações de mouse das widgets jQuery Ui no celular -->
<script src="/libs/jQueryUi-1.12.1/jQueryUiTouch.js"></script>
<!-- AdminLTE App -->
<script src="/app/javascript/app.min.js"></script>
<!-- Pnotify  -->
<script src="/plugins/Pnotify/pnotify.custom.min.js"></script>
<script>
$(document).ready(function(){
    PNotify.desktop.permission(); 
});
</script>

<script src="/app/javascript/loadHelp.js"></script>
<!-- EagerImageLoader -->
<script src="/plugins/EagerImageLoader/eager-image-loader.min.js"></script>
<script>
    new EagerImageLoader();
</script>
<!-- AdminLTE for demo purposes -->
<script src="/app/javascript/demo.js"></script>
<!--Jquery Confirm -->
<script type="text/javascript" src="/plugins/jQueryConfirm/jquery-confirm.min.js"></script>
<script async defer>
$(document).ready(function(){
    $('#users-status-load').load("/app/modules/users/users_status.php", {status: '1'});
    setInterval(function() {$('#users-status-load').load("/app/modules/users/users_status.php", {status: '1'});
    }, 5000);
    
    setInterval(function() {$('#recent_activity').load("/app/modules/users/users_activity.php");
    }, 5000);
    
});     
</script>
<script>
$(document).ready(function() {
    $('[title]').tooltip();
});
</script>
<script src="/app/javascript/functions.js"></script>
</body>
</html>