<?php
if( $_SESSION['user_type'] == 'administrador' or $_SESSION['user_type'] == 'suporte' or isset($_SESSION['properties_view']) )
{
    if(!isset($_GET['actionid']) or empty($_GET['actionid']))
    {
         echo '<div class="alert alert-warning">
              <h4><i class="icon fa fa-warning"></i> Atenção!</h4>
              Nenhum imóvel identificado com essas credenciais.<br>
              Verifique se o endereço está correto, se essa mensagem continuar aparecendo, procure por ajuda na página de <a href="/app/admin/support">Ajuda & Suporte</a> ou entre em contato com o administrador.
              </div>'; 
    }
    else
    {
        $actionid = filterString(base64_decode($_GET['actionid']), 'INT');

        $con_db = new config\connect_db();
        $con = $con_db->connect();
        
        $properties = $con->prepare("SELECT pr.owner_id, pr.status, pr.ref, pr.situation, pr.finality, pr.segment, pr.type, pr.value_total, pr.hidden_value_total, pr.value_monthly, pr.hidden_value_monthly, pr.value_refers, pr.construction_material, pr.total_area, pr.construct_area, pr.number_rooms, pr.number_bathrooms, pr.number_garage, pr.number_suites, pr.condominium_value, pr.hidden_condominium_value, pr.finalization_date, pr.coor_lat, pr.coor_lon, pr.address_state, pr.address_city, pr.address_street, pr.address_number, pr.address_neighborhood, pr.address_postal_code, pr.confirm_address, pr.details, pr.cover_image, pr.featured, pl.hospital, pl.restaurant, pl.bakery, pl.road, pl.fuel, pl.fast_food, pl.beach, pl.summer_club, pl.airport, pl.school, pl.university, pl.shopping, pl.supermarket, pl.park, pl.drogstore, pl.academy, pl.bank, pl.police, pl.firefighter FROM properties pr LEFT JOIN properties_places pl ON (pl.properties_id = pr.id) WHERE pr.id = ?");
        $properties->bind_param('i', $actionid);
        $properties->execute();
        $properties->store_result();
        $properties->bind_result($owner, $status, $ref, $situation, $finality, $segment, $type, $value_total, $hidden_value_total, $value_monthly, $hidden_value_monthly, $value_refers, $construction_material, $total_area, $construct_area, $number_rooms, $number_bathrooms, $number_garage, $number_suites, $condominium_value, $hidden_condominium_value, $finalization_date, $coor_lat, $coor_lon, $address_state, $address_city, $address_street, $address_number, $address_neighborhood, $address_postal_code, $confirm_address, $detalhes, $cover_image, $featured, $hospital, $restaurant, $bakery, $road, $fuel, $fast_food, $beach, $summer_club, $airport, $school, $university, $shopping, $supermarket, $park, $drogstore, $academy, $bank, $police, $firefighter);
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
                    $finalization = '<br> <small class="text-info">Data previsto para entrega '.inverteData($finalization_date).'</small>';
                }
                else
                {
                    $finalization = '<br> <small class="text-info">Data de entrega não informada;</small>';
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
                    $venda = '<p>Venda:<strong class="text-blue"> R$ '.decimalMoeda($value_total).' </strong> - Sob consulta</p>';
                }
                else if($hidden_value_total === 'Y' and empty($value_total))
                {
                    $venda = '<p>Venda:<strong class="text-blue"> Não informado </strong> - Sob consulta</p>';
                }
                else if($hidden_value_total === 'N' and !empty($value_total))
                {
                    $venda = '<p>Venda:<strong class="text-blue"> R$ '.decimalMoeda($value_total).' </strong></p>';
                }
                else if($hidden_value_total === 'N' and empty($value_total))
                {
                    $venda = '<p>Venda:<strong class="text-blue"> Não informado </strong></p>';
                }
                else
                {
                    $venda = '<p>Venda:<strong class="text-blue"> Indefinido </strong></p>';
                }

                if($hidden_value_monthly === 'Y' and !empty($value_monthly))
                {
                    $aluguel = '<p>Aluguel:<strong class="text-blue"> R$ '.decimalMoeda($value_monthly).' </strong> - Sob consulta</p>';
                }
                else if($hidden_value_monthly === 'Y' and empty($value_monthly))
                {
                    $aluguel = '<p>Aluguel:<strong class="text-blue"> Não informado </strong> - Sob consulta</p>';
                }
                else if($hidden_value_monthly === 'N' and !empty($value_monthly))
                {
                    $aluguel = '<p>Aluguel:<strong class="text-blue"> R$ '.decimalMoeda($value_monthly).' </strong></p>';
                }
                else if($hidden_value_monthly === 'N' and empty($value_monthly))
                {
                    $aluguel = '<p>Aluguel:<strong class="text-blue"> Não informado </strong></p>';
                }
                else
                {
                    $aluguel = '<p>Aluguel:<strong class="text-blue"> Indefinido </strong></p>';
                }

            }
            else if( $finality === 'venda' )
            {
                $finality = 'venda';
                if($hidden_value_total == 'Y' and !empty($value_total))
                {
                    $venda = '<p>Venda:<strong class="text-blue"> R$ '.decimalMoeda($value_total).' </strong> - Sob consulta</p>';
                }
                else if($hidden_value_total == 'Y' and empty($value_total))
                {
                    $venda = '<p>Venda:<strong class="text-blue"> Não informado </strong> - Sob consulta</p>';
                }
                else if($hidden_value_total == 'N' and !empty($value_total))
                {
                    $venda = '<p>Venda:<strong class="text-blue"> R$ '.decimalMoeda($value_total).' </strong></p>';
                }
                else if($hidden_value_total == 'N' and empty($value_total))
                {
                    $venda = '<p>Venda:<strong class="text-blue"> Não informado </strong></p>';
                }
                else
                {
                    $venda = '<p>Venda:<strong class="text-blue"> Indefinido </strong></p>';
                }

                $aluguel = '';
            }
            else if( $finality === 'aluguel' or $finality === 'temporada' or $finality === 'arrendamento')
            {
                $finality = 'aluguel';
                if($hidden_value_monthly == 'Y' and !empty($value_monthly))
                {
                    $aluguel = '<p>Aluguel:<strong class="text-blue"> R$ '.decimalMoeda($value_monthly).' </strong> - Sob consulta</p>';
                }
                else if($hidden_value_monthly == 'Y' and empty($value_monthly))
                {
                    $aluguel = '<p>Aluguel:<strong class="text-blue"> Não informad </strong> - Sob consulta</p>';
                }
                else if($hidden_value_monthly == 'N' and !empty($value_monthly))
                {
                    $aluguel = '<p>Aluguel:<strong class="text-blue"> R$ '.decimalMoeda($value_monthly).' </strong></p>';
                }
                else if($hidden_value_monthly == 'N' and empty($value_monthly))
                {
                    $aluguel = '<p>Aluguel:<strong class="text-blue"> Não informado </strong></p>';
                }
                else
                {
                    $aluguel = '<p>Aluguel:<strong class="text-blue"> Indefinido </strong></p>';
                }

                $venda = '';

            }
            else
            {
                $venda = '<p>Indefinido</p>';
                $aluguel = '<p>Indefinido</p>';
            }

?>
<link rel="stylesheet" href="/plugins/fotorama-4.6.4/fotorama.css">
<SECTION CLASS="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <H4 CLASS="text-darkgray text-capitalize"><STRONG><?php echo $type . ' para '. $finality ?></STRONG></H4>
            <OL CLASS="breadcrumb bg-white">
                <LI><a href="/app/admin/home"><I CLASS="fa fa-home"></I></a></LI>
                <LI><a href="/app/admin/properties"><i class="fa fa-industry"></i> Imóveis</a></LI>
                <LI><a href="/app/admin/preview_property/<?php echo base64_encode($actionid) ?>"><i class="fa fa-eye"></i> Ref: <?php echo $actionid ?> </a></LI>
            </OL>
        </div>
    </div>
</SECTION>

<DIV CLASS="clearfix"></DIV>

<SECTION CLASS="container-fluid">
    <DIV CLASS="row">
      
        <div class="col-md-12">
            <a class="btn btn-flat btn-success pull-right margin-left uppercase" role="button" href="/app/admin/images_properties/<?php echo base64_encode($actionid); ?>"><i class="fa fa-picture-o"></i> Gerenciar Imagens</a>
            
            <a class="btn btn-flat btn-primary pull-right margin-left uppercase" role="button" href="/app/admin/edit_properties/<?php echo base64_encode($actionid); ?>"><i class="fa fa-edit"></i> EDITAR IMÓVEL</a>
            
            <a class="btn btn-info btn-flat pull-right uppercase" data-action="load-info-client" data-client-id="<?php echo base64_encode($owner) ; ?>" role="button" tabindex="1"><i class="fa fa-info"></i> Informações do proprietário</a>
        </div>
        
        <DIV CLASS="clearfix space-30"></DIV>
   
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
                        <li><a href="#tab-construct" data-toggle="tab" aria-expanded="true"><i class="fa fa-home"></i> <span class="hidden-xs">Detalhes</span></a></li>
                      <?php  
                      if( !empty($coor_lat) and !empty($coor_lon) ){
                        echo '<li><a href="#tab-mapa" data-toggle="tab" aria-expanded="false"><i class="fa fa-map-marker"></i> <span class="hidden-xs">Mapa</span></a></li>';
                       }
                      ?>
                      <li><a href="#tab-locais" data-toggle="tab"><i class="fa fa-map-pin"></i> <span class="hidden-xs">Locais próximos</span></a></li>
                    </ul>
                    <div class="tab-content">
                      <div class="tab-pane active" id="tab-principal">
                          
                        <div class="space-20"></div>
                        <?php echo $status; ?>
                        <p class="text-capitalize"><strong>Situação:</strong> <?php echo $situation.$finalization;?></p>
                        <p class="text-capitalize"><strong>Finalidade:</strong> <?php echo $finality; ?></p>
                        <p class="text-capitalize"><strong>Segmento:</strong> <?php echo $segment; ?></p>
                        <p class="text-capitalize"><strong>Tipo:</strong> <?php echo $type; ?></p>
                        <h4 class="text-info"><strong>Endereço:</strong></h4>
                        <?php echo "<i class='fa fa-map'></i> ".$address_street.", ".$address_number.",".$address_neighborhood.", ".$address_city."-".$address_state; ?>
                        <h3 class="text-info"><strong>Valores:</strong></h3>
                        <?php echo  $venda; ?>
                        <?php echo $aluguel; ?>  
                      </div>
                      <!-- /.tab-pane -->
                        <?php  
                        if( !empty($coor_lat) and !empty($coor_lon) ){
                            echo '<div class="tab-pane" id="tab-mapa"><div id="mapa"></div></div>';
                        }
                        ?>
                        
                       <!-- /.tab-pane -->
                      <div class="tab-pane" id="tab-construct">
                          
                          <div class="space-20"></div>
                          <p class="text-capitalize"><strong>Material da contrução:</strong> <?php echo (!empty($construct_material)) ? $construct_material : 'Não informado' ; ?></p>
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

                      </div>
                      <!-- /.tab-pane -->
                    </div>
                    <!-- /.tab-content -->
                  </div><!-- /.tab-content -->

               </div>
           </div>

       </div><!-- //.col-right-->
      
       <div class="clearfix space-30"></div>

       <div class="col-md-12">       
          <div class="box box-solid">
              <div class="box-header"><strong>Palavras do vendedor</strong></div>
              <div class="box-body">
                  <?php echo $clean_detalhes; ?>
              </div>
          </div>  
       </div>
      
  </DIV>
</SECTION>

<script src="/plugins/fotorama-4.6.4/fotorama.js" async defer></script>
<script src="/app/javascript/properties.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCbdp4L5TVm9zT1UVgQen1nqQ0TgN869cQ&libraries=places"></script>
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
    google.maps.event.addDomListener(window, 'load', autoComplete());
    google.maps.event.addDomListener(window, 'load', geocodeLatLng(position));
});
</script>

<?php 
}
?>

<?php
        }
        else
        {
            echo '<div class="alert alert-warning">
                 <h4><i class="icon fa fa-warning"></i> Atenção!</h4>
                 Nenhum imóvel identificado com essas credenciais.<br>
                 Verifique se o endereço está correto, se essa mensagem continuar aparecendo, procure por ajuda na página de <a href="/app/admin/support">Ajuda & Suporte</a> ou entre em contato com o administrador.
                 </div>';    
        }//. Se o select encontrou o usuário
        
    }//.se existe $_GET['actionid']
    
}
else
{
    echo '<DIV CLASS="error-page">',
    '<P CLASS=" headline text-yellow"> <I CLASS="fa fa-lock fa-2x" ARIA-HIDDEN="true"></I></P>',
    '<DIV CLASS="error-content">',
    '<H3><I CLASS="fa fa-warning text-yellow"></I> Oops! Você não tem permissão para acessar esta página.</H3>',
    '<P>Você não tem privilégio suficiente para acessar esta página!<BR>
        Retorne a página <a href="/app/index/home">inicial</a>.',
    '</P>',
    '</DIV> ',
    '</DIV>';
}
?>