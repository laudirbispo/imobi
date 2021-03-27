<?php 
header("Content-type: text/html");
require_once ($_SERVER['DOCUMENT_ROOT'].'/config/autoload.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/config/config.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/config/public_functions.php');
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title><?php echo SITE_NAME ?></title>
<!-- Padrão -->
<link href="/favicon.png" rel="shortcut icon">
<meta name="author" content="Easy Mobi - Tecnologia da Informação">
<meta name="keywords" content="">
<meta http-equiv="content-language" content="pt-br">
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta name="robots" content="index, follow"> 
<meta name="RATING" content="General">
<!-- Schema.org markup para Google+ -->
<meta ITEMPROP="name" content="">
<meta ITEMPROP="description" content="">
<meta ITEMPROP="image" content="<?php echo DOMINIO ?>/assets/images/meta-image.jpg">
<!-- Twitter Card data -->
<meta name="twitter:card" content="<?php echo DOMINIO ?>/assets/images/meta-image.jpg">
<meta name="twitter:site" content="<?php echo DOMINIO ?>">
<meta name="twitter:title" content="">
<meta name="twitter:description" content="">
<meta name="twitter:creator" content="">
<!-- Twitter Summary Card com imagem larga, tem que ter pelo menos 280x150px -->
<meta name="twitter:image:src" content="http://www.example.com/image.html">
<!-- Open Graph data -->
<meta PROPERTY="og:title" content="" />
<meta PROPERTY="og:url" content="<?php echo DOMINIO ?>" />
<meta PROPERTY="og:image" content="<?php echo DOMINIO ?>/assets/images/meta-image.jpg" />
<meta PROPERTY="og:description" content="" />
<meta PROPERTY="og:site_name" content="" />

<!-- others -->
<link href="/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="/assets/font-awesome-4.7.0/css/font-awesome.min.css">

<link href="/assets/css/AdminLTE.min.css" rel="stylesheet" type="text/css">
<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<meta name="HandheldFriendly" content="true">
<!-- FOROTAMA  -->
<link href="/plugins/fotorama-4.6.4/fotorama.css" rel="stylesheet" type="text/css">
<!-- carouseller -->
<link href="/plugins/carouseller/css/carouseller.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="/assets/css/bootstrap-social.css">
<link rel="stylesheet" type="text/css" href="/assets/css/magnific-popup.css">
<link href="/assets/css/geral.css" rel="stylesheet" type="text/css">
<noscript>
  <meta http-equiv="refresh" content="0; url=noscript.html">
</noscript>
</head>
<!-- ***************************************************** -->
<body id="page-top">
<!-- Retirar após atualizar o bootstrap usar o de baixo-->
<script src="/assets/javascript/jquery-2.2.3.js"  CROSSORIGIN="anonymous"></script> 
<!-- jQuery 3.0.0
<script src="/plugins/jQuery/jQuery-3.0.0.min.js"></script> -->
<!-- EagerImageLoader  -->
<script src="/plugins/EagerImageLoader/eager-image-loader.min.js"></script>
<script> new EagerImageLoader();</script>


<header>
   	<div class="nav-top hidden-xs">
	<div class="container">
		<div class="row">
			<div class="col-lg-6 col-md-6 col-sm-6 hidden-xs">
				<p class="t24 no-margin pull-left text-white"><b><i class="fa fa-whatsapp"></i> (46) 9-9901-7566 </b></p>
		   </div>
		   <div class="col-lg-6 col-md-6 col-sm-6 hidden-xs">
				<p class="t24 no-margin pull-right text-white"><b><i class="fa fa-phone"></i> (46) 3543-4405</b></p>
		   </div>
		</div>
	</div>

   </div>
   <nav class="navbar navbar-default" id="mainNav">
  <div class="container">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a href="/home"><img id="logoa" src="/assets/images/logo bohn.png" class="img-responsive" style="height: 140px; padding: 10px;"></a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav navbar-right">
        <li>
							<a href="/home">Início</a>
						</li>
						<li>
							<a href="/sobre">Quem somos</a>
						</li>
						<li>
							<a href="/imoveis?limit=25&order=date-desc&pagination=0&select=venda">Venda</a>
						</li>
						<li>
							<a href="/imoveis?limit=25&order=date-desc&pagination=0&select=aluguel">Locação</a>
						</li>
						<li>
							<a href="/anuncie">ANUNCIE</a>
						</li>
						<li>
							<a href="/contato">Contato</a>
						</li>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
</header>
