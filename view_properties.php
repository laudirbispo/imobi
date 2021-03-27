<?php 
require_once ($_SERVER['DOCUMENT_ROOT'].'/config/autoload.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/config/config.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/config/public_functions.php');

$current_url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

    if(!isset($_GET['id']) or empty($_GET['id']))
    {
         echo '<div class="alert alert-warning">
              <h4><i class="icon fa fa-warning"></i> Atenção!</h4>
              Nenhum imóvel identificado com essas credenciais.<br>
              Verifique se o endereço está correto, se essa mensagem continuar aparecendo, procure por ajuda na página de <a href="/app/admin/support">Ajuda & Suporte</a> ou entre em contato com o administrador.
              </div>'; 
    }
    else
    {
        $actionid = filterString(base64_decode($_GET['id']), 'INT');
        

        $con_db = new config\connect_db();
        $con = $con_db->connect();
        
        $properties = $con->prepare("SELECT pr.status, pr.ref, pr.situation, pr.finality, pr.segment, pr.type, pr.value_total, pr.hidden_value_total, pr.value_monthly, pr.hidden_value_monthly, pr.value_refers, pr.construction_material, pr.total_area, pr.construct_area, pr.number_rooms, pr.number_bathrooms, pr.number_garage, pr.number_suites, pr.condominium_value, pr.hidden_condominium_value, pr.finalization_date, pr.coor_lat, pr.coor_lon, pr.address_state, pr.address_city, pr.address_street, pr.address_number, pr.address_neighborhood, pr.address_postal_code, pr.confirm_address, pr.details, pr.cover_image, pr.featured, pl.hospital, pl.restaurant, pl.bakery, pl.road, pl.fuel, pl.fast_food, pl.beach, pl.summer_club, pl.airport, pl.school, pl.university, pl.shopping, pl.supermarket, pl.park, pl.drogstore, pl.academy, pl.bank, pl.police, pl.firefighter FROM properties pr LEFT JOIN properties_places pl ON (pl.properties_id = pr.id) WHERE pr.id = ?");
        $properties->bind_param('i', $actionid);
        $properties->execute();
        $properties->store_result();
        $properties->bind_result($status, $ref, $situation, $finality, $segment, $type, $value_total, $hidden_value_total, $value_monthly, $hidden_value_monthly, $value_refers, $construction_material, $total_area, $construct_area, $number_rooms, $number_bathrooms, $number_garage, $number_suites, $condominium_value, $hidden_condominium_value, $finalization_date, $coor_lat, $coor_lon, $address_state, $address_city, $address_street, $address_number, $address_neighborhood, $address_postal_code, $confirm_address, $detalhes, $cover_image, $featured, $hospital, $restaurant, $bakery, $road, $fuel, $fast_food, $beach, $summer_club, $airport, $school, $university, $shopping, $supermarket, $park, $drogstore, $academy, $bank, $police, $firefighter);
        $properties->fetch();
        $rows = $properties->affected_rows;
        $properties->free_result();
        $properties->close();
       
        if($properties and $rows > 0)
        {    
            
            // Purifica saída html
            require_once($_SERVER['DOCUMENT_ROOT'].'/libs/HTMLPurifier/HTMLPurifier.auto.php');
            $config = HTMLPurifier_Config::createDefault();
            $purifier = new HTMLPurifier($config);
            $clean_detalhes = $purifier->purify($detalhes);
            
            if($hidden_value_monthly === 'Y')
            {
                $hidden_monthly = '<span class="text-mediumgray">- Valor exibido como Sob Consulta</span>';
            }
            else
            {
                $hidden_monthly = '';
            }
            
            if($hidden_value_total === 'Y')
            {
                $hidden_total = '<span class="text-mediumgray">- Valor exibido como Sob Consulta</span>';
            }
            else
            {
                $hidden_total = '';
            }
            
            if($value_refers === 'daily' )
            {
                $refers = '<p class="text-danger">* O valor do aluguel é referente à 1 dia;</p>';
            }
            else if($value_refers === 'weekly' )
            {
                $refers = '<p class="text-danger">* O valor do aluguel é referente à 1 semana;</p>';
            }
            else if($value_refers === 'biweekly' )
            {
                $refers = '<p class="text-danger">* O valor do aluguel é referente à 15 dias;</p>';
            }
            else if($value_refers === 'monthly' )
            {
                $refers = '<p class="text-danger">* O valor do aluguel é referente à 30 dias(1 mês);</p>';
            }
            else
            {
                $refers = '';    
            }
            
            $status = ( !empty($status) ) ? '<div class="sale-status text-capitalize pull-right "><p class="bg-orange label">'.$status .'</p></div>' : '';
            
            if( $situation === 'construção' or  $situation === 'planta' )
            {
                if( !empty($finalization_date))
                {
                    $finalization = '<br> <small class="text-iguacu">Data previsto para entrega '.inverteData($finalization_date).'</small>';
                }
                else
                {
                    $finalization = '<br> <small class="text-iguacu">Data de entrega não informada;</small>';
                }
            }
            else
            {
                $finalization = '';
            }

            if($finality === 'venda-aluguel')
            {
                $finality = 'venda ou aluguel';

                if($hidden_value_total === 'Y' and !empty($value_total))
                {
                    $venda = '<p>Venda:<strong class="text-iguacu"> R$ '.decimalMoeda($value_total).' </strong> - Sob consulta</p>';
                }
                else if($hidden_value_total === 'Y' and empty($value_total))
                {
                    $venda = '<p>Venda:<strong class="text-iguacu"> Não informado </strong> - Sob consulta</p>';
                }
                else if($hidden_value_total === 'N' and !empty($value_total))
                {
                    $venda = '<p>Venda:<strong class="text-iguacu"> R$ '.decimalMoeda($value_total).' </strong></p>';
                }
                else if($hidden_value_total === 'N' and empty($value_total))
                {
                    $venda = '<p>Venda:<strong class="text-iguacu"> Não informado </strong></p>';
                }
                else
                {
                    $venda = '<p>Venda:<strong class="text-iguacu"> Indefinido </strong></p>';
                }

                if($hidden_value_monthly === 'Y' and !empty($value_monthly))
                {
                    $aluguel = '<p>Aluguel:<strong class="text-iguacu"> R$ '.decimalMoeda($value_monthly).' </strong> - Sob consulta</p>';
                }
                else if($hidden_value_monthly === 'Y' and empty($value_monthly))
                {
                    $aluguel = '<p>Aluguel:<strong class="text-iguacu"> Não informado </strong> - Sob consulta</p>';
                }
                else if($hidden_value_monthly === 'N' and !empty($value_monthly))
                {
                    $aluguel = '<p>Aluguel:<strong class="text-iguacu"> R$ '.decimalMoeda($value_monthly).' </strong></p>';
                }
                else if($hidden_value_monthly === 'N' and empty($value_monthly))
                {
                    $aluguel = '<p>Aluguel:<strong class="text-iguacu"> Não informado </strong></p>';
                }
                else
                {
                    $aluguel = '<p>Aluguel:<strong class="text-iguacu"> Indefinido </strong></p>';
                }

            }
            else if( $finality === 'venda' )
            {
                $finality = 'venda';
                if($hidden_value_total == 'Y' and !empty($value_total))
                {
                    $venda = '<p>Venda:<strong class="text-iguacu"> R$ '.decimalMoeda($value_total).' </strong> - Sob consulta</p>';
                }
                else if($hidden_value_total == 'Y' and empty($value_total))
                {
                    $venda = '<p>Venda:<strong class="text-iguacu"> Não informado </strong> - Sob consulta</p>';
                }
                else if($hidden_value_total == 'N' and !empty($value_total))
                {
                    $venda = '<p>Venda:<strong class="text-iguacu"> R$ '.decimalMoeda($value_total).' </strong></p>';
                }
                else if($hidden_value_total == 'N' and empty($value_total))
                {
                    $venda = '<p>Venda:<strong class="text-iguacu"> Não informado </strong></p>';
                }
                else
                {
                    $venda = '<p>Venda:<strong class="text-iguacu"> Indefinido </strong></p>';
                }

                $aluguel = '';
            }
            else if( $finality === 'aluguel' or $finality === 'temporada' or $finality === 'arrendamento')
            {
                $finality = 'aluguel';
                if($hidden_value_monthly == 'Y' and !empty($value_monthly))
                {
                    $aluguel = '<p>Aluguel:<strong class="text-iguacu"> R$ '.decimalMoeda($value_monthly).' </strong> - Sob consulta</p>';
                }
                else if($hidden_value_monthly == 'Y' and empty($value_monthly))
                {
                    $aluguel = '<p>Aluguel:<strong class="text-iguacu"> Não informad </strong> - Sob consulta</p>';
                }
                else if($hidden_value_monthly == 'N' and !empty($value_monthly))
                {
                    $aluguel = '<p>Aluguel:<strong class="text-iguacu"> R$ '.decimalMoeda($value_monthly).' </strong></p>';
                }
                else if($hidden_value_monthly == 'N' and empty($value_monthly))
                {
                    $aluguel = '<p>Aluguel:<strong class="text-iguacu"> Não informado </strong></p>';
                }
                else
                {
                    $aluguel = '<p>Aluguel:<strong class="text-iguacu"> Indefinido </strong></p>';
                }

                $venda = '';

            }
            else
            {
                $venda = '<p>Indefinido</p>';
                $aluguel = '<p>Indefinido</p>';
            }

?>

<!doctype html>
<html><head>
<meta charset="utf-8">
<title><?php echo $type;?> para <?php echo $finality;?> - <?php echo SITE_NAME ?></title>
<!-- Padrão -->
<link href="/favicon.png" rel="shortcut icon">
<meta name="author" content="Easy Mobi">
<meta name="keywords" content="">
<meta http-equiv="content-language" content="pt-br">
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta name="robots" content="index, follow"> 
<meta name="RATING" content="General">
<!-- Schema.org markup para Google+ -->
<meta ITEMPROP="name" content="">
<meta ITEMPROP="description" content="">
<meta ITEMPROP="image" content="<?php echo(SUBDOMAIN_IMGS.'/docs/properties/'.$actionid.'/big/'.$cover_image); ?>">
<!-- Twitter Card data -->
<meta name="twitter:card" content="<?php echo(SUBDOMAIN_IMGS.'/docs/properties/'.$actionid.'/big/'.$cover_image); ?>">
<meta name="twitter:site" content="<?php echo DOMINIO.'/view_properties/'.base64_encode($actionid) ?>">
<meta name="twitter:title" content="<?php echo $type;?> para <?php echo $finality;?> - <?php echo SITE_NAME ?>">
<meta name="twitter:description" content="">
<meta name="twitter:creator" content="Easy Mobi">
<!-- Twitter Summary Card com imagem larga, tem que ter pelo menos 280x150px -->
<meta name="twitter:image:src" content="http://www.example.com/image.html">
<!-- Open Graph data -->
<meta PROPERTY="og:locale" content="pt_BR">
<meta PROPERTY="og:title" content="<?php echo $type;?> para <?php echo $finality;?> - <?php echo SITE_NAME ?>" />
<meta PROPERTY="og:url" content="<?php echo DOMINIO.'/view_properties/'.base64_encode($actionid) ?>" />
<meta PROPERTY="og:image" content="<?php echo(SUBDOMAIN_IMGS.'/docs/properties/'.$actionid.'/big/'.$cover_image); ?>" />
<meta PROPERTY="og:description" content="<?php echo($address_street.', '.$address_number.','.$address_neighborhood.', '.$address_city.'-'.$address_state); ?>" />
<meta PROPERTY="og:site_name" content="<?php echo SITE_NAME ?>" />
<meta PROPERTY="og:image:type" content="image/jpeg">
<meta PROPERTY="og:image:width" content="800">
<meta PROPERTY="og:image:height" content="600">
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



<link rel="stylesheet" href="/plugins/fotorama-4.6.4/fotorama.css">


<div class="space-40 clearfix"></div>

<div CLASS="row">
    <div CLASS="container">
        <div class="col-md-12 col-sm-12 col-xs-12 text-center">
        	<h2 class="text-capitalize"><b><?php echo $type;?> para <?php echo $finality;?></b></h2>
        </div>
        <div CLASS="clearfix space-30"></div>
   
        <div class="col-md-6 col-sm-6 col-xs-12">

           <div class="fotorama bg-white margin-bottom" data-nav="thumbs" data-allowfullscreen="true" data-autoplay="true" data-arrows="true" data-click="true" data-swipe="true" data-width="100%" data-height="335" data-loop="true">
              <?php

                 $images = $con->query("SELECT image FROM images_properties WHERE id_properties = '$actionid'");
                 $total = $images->num_rows;

                 if($images and $total > 0)
                 {
                     $img = '';
                     while($reg = $images->fetch_array())
                     {
                        $img .= '<a href="'.SUBDOMAIN_IMGS.'/docs/properties/'.$actionid.'/big/'.$reg['image'].'"><img src="'.SUBDOMAIN_IMGS.'/docs/properties/'.$actionid.'/small/'.$reg['image'].'"></a>';
                     }
                 }
                 else
                 {
                    $img = '<a href="/app/images/no-image.png"><img src="/app/images/no-image.png"></a>';
                 }

                 echo $img;
              ?>
           </div>

       </div><!-- //.col-left-->

       <div class="col-md-6 col-sm-6 col-xs-12">
			
           <div class="box box-solid" style="height: 400px;">
               <div class="box-body no-padding">
					
                   <div class="nav-tabs-custom no-margin">
                    <ul class="nav nav-tabs custom-tabs">
                      <li class="active"><a href="#tab-principal" data-toggle="tab" aria-expanded="true">Principal</a></li>
                        <li class=""><a href="#tab-construct" data-toggle="tab" aria-expanded="true"><span>Detalhes</span></a></li>
                      <li><a href="#tab-locais" data-toggle="tab"><span>Locais próximos</span></a></li>
                    </ul>
                    <div class="tab-content">
                      <div class="tab-pane active" id="tab-principal">
                          
                        <div class="space-20"></div>
                        <?php echo $status; ?>
                        <p class="text-capitalize"><strong>Situação:</strong> <?php echo $situation.$finalization;?></p>
                        <p class="text-capitalize"><strong>Finalidade:</strong> <?php echo $finality; ?></p>
                        <p class="text-capitalize"><strong>Segmento:</strong> <?php echo $segment; ?></p>
                        <p class="text-capitalize"><strong>Tipo:</strong> <?php echo $type; ?></p>
                        <p class="text-capitalize"><strong>Ref:</strong> <?php echo $ref; ?></p>
                        <h4 class="text-iguacu"><strong>Endereço:</strong></h4>
                        <?php echo "<i class='fa fa-map'></i> ".$address_street.", ".$address_number.",".$address_neighborhood.", ".$address_city."-".$address_state; ?>
                        <h3 class="text-iguacu"><strong>Valor:</strong></h3>
                        <?php echo  $venda; ?>
                        <?php echo $aluguel; ?>  
                      </div>
                       <!-- /.tab-pane -->
                      <div class="tab-pane" id="tab-construct">
                          
                          <div class="space-20"></div>
                          <p class="text-capitalize"><strong>Material da construção:</strong> <?php echo (!empty($construction_material)) ? $construction_material : 'Não informado' ; ?></p>
                          <p class="text-capitalize"><strong>Área total:</strong> <?php echo (!empty($total_area)) ? $total_area.'m²' : 'Não informado' ; ?></p>
                          <p class="text-capitalize"><strong>Área construida:</strong> <?php echo (!empty($construct_area)) ? $construct_area.'m²' : 'Não informado' ; ?></p>
                          <div class="clearfix space-30"></div>
                          <div class="row">
                              <div class="col-md-3 col-sm-3 col-xs-4 text-center">
                                  <p>
                                  <i class="fa fa-bed fa-2x" aria-hidden="true"></i><br>
                                  <span>Quartos</span><br>
                                  <span><?php echo (!empty($number_rooms)) ? $number_rooms : 'Não informado' ; ?></span>
                                  </p>
                              </div>
                              
                              <div class="col-md-3 col-sm-3 col-xs-4 text-center">
                                  <p>
                                  <i class="fa fa-star fa-2x" aria-hidden="true"></i><br>
                                  <span>Suites</span><br>
                                  <span><?php echo (!empty($number_suites)) ? $number_suites : 'Não informado' ; ?></span>
                                  </p>
                              </div>
                              
                              <div class="col-md-3 col-sm-3 col-xs-4 text-center">
                                  <p>
                                  <i class="fa fa-bath fa-2x" aria-hidden="true"></i><br>
                                  <span>Banheiros</span><br>
                                  <span><?php echo (!empty($number_bathrooms)) ? $number_bathrooms : 'Não informado' ; ?></span>
                                  </p>
                              </div>
                              
                              <div class="col-md-3 col-sm-3 col-xs-4 text-center">
                                  <p>
                                  <i class="fa fa-car fa-2x" aria-hidden="true"></i><br>
                                  <span>Garagem</span><br>
                                  <span><?php echo (!empty($number_garage)) ? $number_garage : 'Não informado' ; ?></span>
                                  </p>
                              </div>
                              
                          </div>
                      </div>
                      <!-- /.tab-pane -->
                      <div class="tab-pane" id="tab-locais">
                          
                          <div class="clearfix space-20"></div>
                          
                          <?php
                            if($hospital === 'Y')
                            {
                               echo '<div class="col-md-2 col-sm-3 col-xs-4">          
                                      <div class="mini-box-near" tabindex= "0" role= "button" DATA-CONTROL="popover-hover" DATA-PLACEMENT="auto" DATA-TOGGLE="popover" DATA-CONTAINER="body" DATA-HTML="true" DATA-TRIGGER="focus" data-title="false" DATA-CONTENT="Hospital">
                                          <img src="/app/images/icons/properties-icons/cardiogram.png" class="img-reponsive">
                                      </div>                            
                                  </div><!--/.col-3 item -->'; 
                            }
            
                            if($restaurant === 'Y')
                            {
                        
                                echo '<div class="col-md-2 col-sm-3 col-xs-4">
                                    <div class="mini-box-near" tabindex= "0" role= "button" DATA-CONTROL="popover-hover" DATA-PLACEMENT="auto" DATA-TOGGLE="popover" DATA-CONTAINER="body" DATA-HTML="true" DATA-TRIGGER="focus" data-title="false" DATA-CONTENT="Restaurante">
                                        <img src="/app/images/icons/properties-icons/dish.png" class="img-reponsive">
                                    </div>
                                </div><!--/.col-3 item -->';
                            }
            
                            if($bakery === 'Y')
                            {
                                echo '<div class="col-md-2 col-sm-3 col-xs-4">
                                    <div class="mini-box-near" tabindex= "0" role= "button" DATA-CONTROL="popover-hover" DATA-PLACEMENT="auto" DATA-TOGGLE="popover" DATA-CONTAINER="body" DATA-HTML="true" DATA-TRIGGER="hover" data-title="false" DATA-CONTENT="Cafeteria/Padaria">
                                        <img src="/app/images/icons/properties-icons/doughnut.png" class="img-reponsive">
                                    </div>
                                </div><!--/.col-3 item -->';
                            }
            
                            if($road === 'Y')
                            {
                                echo '<div class="col-md-2 col-sm-3 col-xs-4">
                                    <div class="mini-box-near" tabindex= "0" role= "button" DATA-CONTROL="popover-hover" DATA-PLACEMENT="auto" DATA-TOGGLE="popover" DATA-CONTAINER="body" DATA-HTML="true" DATA-TRIGGER="focus" data-title="false" DATA-CONTENT="Terminal Rodoviário">
                                        <img src="/app/images/icons/properties-icons/school-bus.png" class="img-reponsive">
                                    </div>
                                </div><!--/.col-3 item -->';
                            }
            
                            if($fuel === 'Y')
                            {
                                echo '<div class="col-md-2 col-sm-3 col-xs-4">
                                    <div class="mini-box-near" tabindex= "0" role= "button" DATA-CONTROL="popover-hover" DATA-PLACEMENT="auto" DATA-TOGGLE="popover" DATA-CONTAINER="body" DATA-HTML="true" DATA-TRIGGER="focus" data-title="false" DATA-CONTENT="Posto de Combustível">
                                        <img src="/app/images/icons/properties-icons/gas-station.png" class="img-reponsive">
                                    </div>
                                </div><!--/.col-3 item -->';
                            }
            
                            if($fast_food === 'Y')
                            {
                                echo '<div class="col-md-2 col-sm-3 col-xs-4">
                                    <div class="mini-box-near" tabindex= "0" role= "button" DATA-CONTROL="popover-hover" DATA-PLACEMENT="auto" DATA-TOGGLE="popover" DATA-CONTAINER="body" DATA-HTML="true" DATA-TRIGGER="focus" data-title="false" DATA-CONTENT="Fast Food">
                                        <img src="/app/images/icons/properties-icons/hamburguer.png" class="img-reponsive">
                                    </div>
                                </div><!--/.col-3 item -->';
                            }
            
                            if($beach === 'Y')
                            {
                                echo '<div class="col-md-2 col-sm-3 col-xs-4">
                                    <div class="mini-box-near" tabindex= "0" role= "button" DATA-CONTROL="popover-hover" DATA-PLACEMENT="auto" DATA-TOGGLE="popover" DATA-CONTAINER="body" DATA-HTML="true" DATA-TRIGGER="focus" data-title="false" DATA-CONTENT="Praia">
                                        <img src="/app/images/icons/properties-icons/sun-umbrella.png" class="img-reponsive">
                                    </div>
                                </div><!--/.col-3 item -->';
                            }
            
                            if($summer_club === 'Y')
                            { 
                                echo '<div class="col-md-2 col-sm-3 col-xs-4">
                                    <div class="mini-box-near" tabindex= "0" role= "button" DATA-CONTROL="popover-hover" DATA-PLACEMENT="auto" DATA-TOGGLE="popover" DATA-CONTAINER="body" DATA-HTML="true" DATA-TRIGGER="focus" data-title="false" DATA-CONTENT="Clube de Verão">
                                    <img src="/app/images/icons/properties-icons/swimming-pool.png" class="img-reponsive">
                                    </div>
                                </div><!--/.col-3 item -->';
                            }
            
                            if($airport === 'Y')
                            {
                                echo '<div class="col-md-2 col-sm-3 col-xs-4">
                                    <div class="mini-box-near" tabindex= "0" role= "button" DATA-CONTROL="popover-hover" DATA-PLACEMENT="auto" DATA-TOGGLE="popover" DATA-CONTAINER="body" DATA-HTML="true" DATA-TRIGGER="focus" data-title="false" DATA-CONTENT="Aeroporto">
                                    <img src="/app/images/icons/properties-icons/departure.png" class="img-reponsive">
                                    </div>
                                </div><!--/.col-3 item -->';
                            }
            
                            if($school === 'Y')
                            {
                                echo '<div class="col-md-2 col-sm-3 col-xs-4">
                                    <div class="mini-box-near" tabindex= "0" role= "button" DATA-CONTROL="popover-hover" DATA-PLACEMENT="auto" DATA-TOGGLE="popover" DATA-CONTAINER="body" DATA-HTML="true" DATA-TRIGGER="focus" data-title="false" DATA-CONTENT="Colégio">
                                    <img src="/app/images/icons/properties-icons/blackboard.png" class="img-reponsive">
                                    </div>
                                </div><!--/.col-3 item -->';
                            }
            
                            if($university === 'Y')
                            {
                                echo '<div class="col-md-2 col-sm-3 col-xs-4">
                                    <div class="mini-box-near" tabindex= "0" role= "button" DATA-CONTROL="popover-hover" DATA-PLACEMENT="auto" DATA-TOGGLE="popover" DATA-CONTAINER="body" DATA-HTML="true" DATA-TRIGGER="focus" data-title="false" DATA-CONTENT="Universidade">
                                    <img src="/app/images/icons/properties-icons/mortarboard.png" class="img-reponsive">
                                    </div>
                                </div><!--/.col-3 item -->';
                            }
                            
                            if($shopping === 'Y')
                            {
                                echo '<div class="col-md-2 col-sm-3 col-xs-4">
                                    <div class="mini-box-near" tabindex= "0" role= "button" DATA-CONTROL="popover-hover" DATA-PLACEMENT="auto" DATA-TOGGLE="popover" DATA-CONTAINER="body" DATA-HTML="true" DATA-TRIGGER="focus" data-title="false" DATA-CONTENT="Shopping">
                                    <img src="/app/images/icons/properties-icons/shopping-bag.png" class="img-reponsive">
                                    </div>
                                </div><!--/.col-3 item -->';
                            }
                        
                            if($supermarket === 'Y')
                            {
                                echo '<div class="col-md-2 col-sm-3 col-xs-4">
                                        <div class="mini-box-near" tabindex= "0" role= "button" DATA-CONTROL="popover-hover" DATA-PLACEMENT="auto" DATA-TOGGLE="popover" DATA-CONTAINER="body" DATA-HTML="true" DATA-TRIGGER="focus" data-title="false" DATA-CONTENT="Supermercado">
                                        <img src="/app/images/icons/properties-icons/cart.png" class="img-reponsive">
                                        </div>
                                </div><!--/.col-3 item -->';
                            }
                        
                            if($park === 'Y')
                            {
                                echo '<div class="col-md-2 col-sm-3 col-xs-4">
                                        <div class="mini-box-near" tabindex= "0" role= "button" DATA-CONTROL="popover-hover" DATA-PLACEMENT="auto" DATA-TOGGLE="popover" DATA-CONTAINER="body" DATA-HTML="true" DATA-TRIGGER="focus" data-title="false" DATA-CONTENT="Praças, área de lazer, etc">
                                        <img src="/app/images/icons/properties-icons/park.png" class="img-reponsive">
                                        </div>
                                </div><!--/.col-3 item -->';
                            }
            
                            if($drogstore === 'Y')
                            {            
                                echo '<div class="col-md-2 col-sm-3 col-xs-4">
                                        <div class="mini-box-near" tabindex= "0" role= "button" DATA-CONTROL="popover-hover" DATA-PLACEMENT="auto" DATA-TOGGLE="popover" DATA-CONTAINER="body" DATA-HTML="true" DATA-TRIGGER="focus" data-title="false" DATA-CONTENT="Farmácia, drogaria">
                                        <img src="/app/images/icons/properties-icons/drugs.png" class="img-reponsive">
                                        </div>
                                </div><!--/.col-3 item -->';
                            }
            
                            if($academy === 'Y')
                            {
                                echo '<div class="col-md-2 col-sm-3 col-xs-4">
                                        <div class="mini-box-near" tabindex= "0" role= "button" DATA-CONTROL="popover-hover" DATA-PLACEMENT="auto" DATA-TOGGLE="popover" DATA-CONTAINER="body" DATA-HTML="true" DATA-TRIGGER="focus" data-title="false" DATA-CONTENT="Academia">
                                        <img src="/app/images/icons/properties-icons/dumbbell.png" class="img-reponsive">
                                        </div>
                                </div><!--/.col-3 item -->';
                            }
            
                            if($bank === 'Y')
                            {
                                echo '<div class="col-md-2 col-sm-3 col-xs-4">
                                        <div class="mini-box-near" tabindex= "0" role= "button" DATA-CONTROL="popover-hover" DATA-PLACEMENT="auto" DATA-TOGGLE="popover" DATA-CONTAINER="body" DATA-HTML="true" DATA-TRIGGER="focus" data-title="false" DATA-CONTENT="Banco">
                                        <img src="/app/images/icons/properties-icons/bank.png" class="img-reponsive">
                                        </div>
                                </div><!--/.col-3 item -->';
                            }
                        
                            if($police === 'Y')
                            {
                                echo '<div class="col-md-2 col-sm-3 col-xs-4">
                                        <div class="mini-box-near" tabindex= "0" role= "button" DATA-CONTROL="popover-hover" DATA-PLACEMENT="auto" DATA-TOGGLE="popover" DATA-CONTAINER="body" DATA-HTML="true" DATA-TRIGGER="focus" data-title="false" DATA-CONTENT="Posto policial">
                                        <img src="/app/images/icons/properties-icons/police-car.png" class="img-reponsive">
                                        </div>
                                </div><!--/.col-3 item -->';
                            }
            
                            if($firefighter === 'Y')
                            { 
                                echo '<div class="col-md-2 col-sm-3 col-xs-4">
                                        <div class="mini-box-near" tabindex= "0" role= "button" DATA-CONTROL="popover-hover" DATA-PLACEMENT="auto" DATA-TOGGLE="popover" DATA-CONTAINER="body" DATA-HTML="true" DATA-TRIGGER="focus" data-title="false" DATA-CONTENT="Bombeiros">
                                        <img src="/app/images/icons/properties-icons/firefighter.png" class="img-reponsive">
                                        </div>
                                </div><!--/.col-3 item -->';
                            }
                        ?>
						<div class="clearfix space-20"></div>
                      </div>
                      
                    </div>
                    <!-- /.tab-content -->
                  </div><!-- /.tab-content -->

               </div>
           </div>

       </div><!-- //.col-right-->
       
       <div class="clearfix space-30"></div>
      
      <div class="col-md-6 col-xs-12">       
          <div class="box box-solid">
              <div class="box-body text-center">
                  <div class="space-10"></div>
			<h3 class="no-margin"><b>Compartilhe:</b></h3><br>
			<div class="">
			<div CLASS="feed-noticia-share">
            <i class="fa fa-2x fa-facebook social-share" title="Compartilhar no Facebook" data-url="http://www.facebook.com/sharer/sharer.php?u=<?php echo ($current_url) ?>"></i>
            <i class="fa fa-2x fa-google-plus social-share" title="Compartilhar no Google Plus" data-url="https://plus.google.com/share?url=<?php echo ($current_url) ?>"></i>	
			<i class="fa fa-2x fa-whatsapp social-share hidden-lg hidden-md hidden-sm" title="Compartilhar no WhatsApp" data-url="whatsapp://send?text=<?php echo SITE_NAME ?> - <?php echo ($current_url) ?>"></i>
            </div>
			</div>
			<div class="space-10"></div>
              </div>
          </div>  
      </div>
      <div class="col-md-6 col-xs-12">       
          <div class="box box-solid">
              <div class="box-body text-center">
                  <div class="space-10"></div>
			<h3 class="no-margin"><b>Solicitar mais informações</b></h3><br>
			<div class="">
				<button type="button" class="btn-proposta" data-toggle="modal" data-target="#myModal">
				  <b>CLIQUE AQUI</b>
				</button>
			</div>
			<div class="space-10"></div>
              </div>
          </div>  
      </div>
      
       <div class="clearfix space-30"></div>
      
      <div class="col-md-12">       
          <div class="box box-solid">
              <div class="box-header"><strong>Mais detalhes</strong></div>
              <div class="box-body">
                  <?php echo $clean_detalhes; ?>
              </div>
          </div>  
      </div>
      
  </div><!--container-->
  
  
</div><!--row-->

<?php  
   if( !empty($coor_lat) and !empty($coor_lon) ){
	  echo '<div class="map"><div class="overlay-map"><div class="overlay-content"><h4 class="text-uppercase no-margin"><b> '.$type.' para '.$finality.'</b></h4><br><i class="fa fa-map"></i>'.$address_street.', '.$address_number.','.$address_neighborhood.', '.$address_city.'-'.$address_state.'<br><span class="hidden-sm hidden-xs"><i class="fa fa-whatsapp"></i> (46) 9-9901-7566<br><i class="fa fa-phone"></i>  (46) 3543-4405</span></div></div><div id="mapa"></div></div>';
	   }
?>
  
  <!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">INFORMAÇÕES</h4>
      </div>
      <div class="modal-body">
       <p>PREENCHA OS CAMPOS ABAIXO PARA SABER MAIS SOBRE ESTE IMÓVEL</p>
        <div class="contact-form">
					<form name="sentMessage-tirar" id="propostForm" enctype="application/x-www-form-urlencoded" method="post" action="/envia2.php">
						<div CLASS="form-group row">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-bottom:20px;">
								<input type="text" class="form-control" id="nome" name="nome" placeholder="Nome*" required>
							</div>
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-bottom:20px;">
								<input type="email" class="form-control" id="email" name="email" placeholder="Email*" required>
							</div>
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-bottom:20px;">
								<input type="tel" class="form-control" id="telefone" name="telefone" placeholder="Telefone*" required>
							</div>
							<div CLASS="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<textarea style="resize:none; height:100px;" type="text" class="form-control" id="mensagem" name="mensagem" placeholder="Proposta*" required></textarea>
							</div>
							<input type="hidden" name="link" id="link" value="<?php echo $current_url; ?>">
						</div>
						<button type="submit" class="btn btn-primary">Pedir informações</button>
					</form>
					<div class="space-20"></div>
					<div id="resposta"></div>
				</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
        
      </div>
    </div>
  </div>
</div>
  
  <script type="text/javascript"  async defer>
	jQuery(document).ready(function(e){

		jQuery('#propostForm').submit(function(e){
			e.preventDefault();
			var dados = jQuery( this ).serialize();
e.preventDefault();
e.preventDefault();
			jQuery.ajax({
				type: "POST",
				url: "/envia3.php",
				data: dados,
				success: function( data )
				{

					$("#resposta").html(data);
					document.getElementById('propostForm').reset();						
				}
			});



		});

	});
</script>
	  
<script>
	$(document).ready(function () {
		$('[DATA-CONTROL="popover-hover"]').popover({
			html: true,
			trigger: 'hover'
		});

		$('[DATA-CONTROL="popover-focus"]').popover({
			html: true,
			trigger: 'focus'
		});
	});
</script>
<script src="/plugins/fotorama-4.6.4/fotorama.js" async defer></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDKG7qzYC4UNlbMNJq2vf655WyfpWXyv04&libraries=places"></script>
<script src="/app/javascript/api-geolocation.js"></script>
<?php 
if( !empty($coor_lat) and !empty($coor_lon) )
{
?>
<script type="text/javascript">
$(document).ready(function(){
    var position = {
        coords: {
            latitude: '<?php echo $coor_lat ?>',
            longitude: '<?php echo $coor_lon ?>',
        },
    }
	
    google.maps.event.addDomListener(window, 'load', initialize(position));
    google.maps.event.addDomListener(window, 'load', markerDrag());
});
</script>

<?php
}
?>

<?php
    }
    else
    {
        echo '<div class="container"><div class="space-40 clearfix"></div><div class="alert alert-warning">
             <h4><i class="icon fa fa-warning"></i> Atenção!</h4>
             Nenhum imóvel identificado com essas credenciais.<br>
             Verifique se o endereço está correto, se essa mensagem continuar aparecendo,  entre em contato com o administrador.
             </div></div>';    
    }//. Se o select encontrou o usuário
    
}//.se existe $_GET['actionid']
    

?>

<?php require_once('footer.php'); ?>


