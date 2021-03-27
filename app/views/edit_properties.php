<?php
if( $_SESSION['user_type'] == 'administrador' or $_SESSION['user_type'] == 'suporte' or isset($_SESSION['properties_edit']))
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
            $purifier_detalhes = $purifier->purify($detalhes);      

?>

<link href="/plugins/datepicker/datepicker3.css" rel="stylesheet" type="text/css">

<SECTION CLASS="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <H4 CLASS="text-darkgray"><STRONG>Editar Imóvel</STRONG></H4>
            <OL CLASS="breadcrumb bg-white">
                <LI><a href="/app/admin/home"><I CLASS="fa fa-home"></I></a></LI>
                <LI><a href="/app/admin/properties"><i class="fa fa-industry"></i> Imóveis</a></LI>
                <LI><a href="/app/admin/preview_property/<?php echo base64_encode($actionid) ?>"><i class="fa fa-edit"></i> Ref: <?php echo $actionid ?> </a></LI>
            </OL>
        </div>
    </div>
</SECTION>

<SECTION CLASS="container-fluid">
    <DIV CLASS="row">

        <form name="form-edit-properties" method="POST" id="form-edit-properties" action="/app/modules/properties/update_properties.php" role="form" DATA-TOGGLE="validator" enctype="APPLICATION/X-WWW-FORM-URLENCODED" NOVALIDATE data-action="submit-ajax" data-form-reset="noreset" autocomplete="off">
			
            <input type="HIDDEN" name="actionid" value="<?php echo base64_encode($actionid) ?>">
            <input type="HIDDEN" name="user_id" value="<?php echo $_SESSION['user_id'] ?>">
            <input type="HIDDEN" name="form-token" value="<?php echo $_SESSION['secret_form_token'] ?>">
            
            <div class="col-md-6 col-sm-6 col-xs-12">
                
                <div class="box box-solid">
                    <div class="box-header">
                        <STRONG>Informações principais</STRONG>
                    </div>
                    <div class="box-body">
                        
                    <DIV CLASS="form-group has-feedback">
                        <LABEL FOR="properties-situation"><SPAN CLASS="required">*</SPAN> Situação do Imóvel</LABEL>
                        <DIV CLASS="msg-validation">
                            <SELECT CLASS="form-control" NAME="properties-situation" id="properties-situation" REQUIRED>
                                <OPTION VALUE="<?php echo (!empty($situation)) ? $situation : '' ; ?>"><?php echo (!empty($situation)) ? ucwords($situation) : '...' ; ?></OPTION>
                                <OPTION VALUE="novo">Novo</OPTION>
                                <OPTION VALUE="usado">Usado</OPTION>
                                <OPTION VALUE="construção">Em Construção</OPTION>  
                                <OPTION VALUE="planta">Na Planta</OPTION>  
                                <OPTION VALUE="seminovo">Seminovo</OPTION>  
                                <OPTION VALUE="outro">Outro</OPTION>
                            </SELECT>
                        </DIV>
                        <SPAN CLASS="fa form-control-feedback"></SPAN>
                        <DIV CLASS="help-block with-errors"></DIV> 
                    </DIV>
                        
                    <DIV CLASS="form-group has-feedback">
                        <LABEL FOR="properties-finality"><SPAN CLASS="required">*</SPAN> Finalidade</LABEL>
                        <DIV CLASS="msg-validation">
                            <SELECT CLASS="form-control" NAME="properties-finality" id="properties-finality" REQUIRED>
                                <OPTION VALUE="<?php echo (!empty($finality)) ? $finality : '' ; ?>"><?php echo (!empty($finality)) ? ucwords($finality) : '...' ; ?></OPTION>
                                <OPTION VALUE="venda">Somente venda</OPTION>
                                <OPTION VALUE="venda-aluguel">Venda ou aluguel</OPTION>
                                <OPTION VALUE="aluguel">Aluguel</OPTION>  
                                <OPTION VALUE="temporada">Aluguel por temporada</OPTION> 
                                <OPTION VALUE="arrendamento">Arrendamento de terra</OPTION> 
                            </SELECT>
                        </DIV>
                        <SPAN CLASS="fa form-control-feedback"></SPAN>
                        <DIV CLASS="help-block with-errors"></DIV> 
                    </DIV>
                        
                        <DIV CLASS="form-group has-feedback">
                            <LABEL FOR="properties-segment"><SPAN CLASS="required">*</SPAN> Segmento</LABEL>
                            <DIV CLASS="msg-validation">
                                <SELECT CLASS="form-control" NAME="properties-segment" id="properties-segment" REQUIRED>
                                    <OPTION VALUE="<?php echo (!empty($segment)) ? $segment : '' ; ?>"><?php echo (!empty($segment)) ? ucwords($segment) : '...' ; ?></OPTION>
                                    <OPTION VALUE="residencial">Residencial</OPTION>
                                    <OPTION VALUE="comercial">Comercial</OPTION>
                                    <OPTION VALUE="residencialcomercial">Residencial/Comercial</OPTION>
                                    <OPTION VALUE="rural">Rural</OPTION>    
                                </SELECT>
                            </DIV>
                            <SPAN CLASS="fa form-control-feedback"></SPAN>
                            <DIV CLASS="help-block with-errors"></DIV> 
                        </DIV>
                        
                        <DIV CLASS="form-group has-feedback">
                            <LABEL FOR="properties-type"><SPAN CLASS="required">*</SPAN> Tipo do Imóvel</LABEL>
                            <DIV CLASS="msg-validation">
                                <SELECT CLASS="form-control" NAME="properties-type" id="properties-type" REQUIRED>
                                    <OPTION VALUE="<?php echo (!empty($type)) ? $type : '' ; ?>"><?php echo (!empty($type)) ? ucwords($type) : '...' ; ?></OPTION>
                                </SELECT>
                            </DIV>
                            <SPAN CLASS="fa form-control-feedback"></SPAN>
                            <DIV CLASS="help-block with-errors"></DIV> 
                        </DIV>
                        
                        <DIV CLASS="form-group has-feedback">
                            <LABEL FOR="properties-owner"><SPAN CLASS="required">*</SPAN> Proprietário do Imóvel:</LABEL>
                            <DIV CLASS="msg-validation">
                              <SELECT CLASS="form-control" NAME="properties-owner" id="properties-owner" REQUIRED>
                                <option value="<?php echo base64_encode($owner); ?>">Manter informação</option>
                                <?php echo  listClients(); ?>
                              </SELECT>
                            </DIV>
                            <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
                            <DIV CLASS="help-block with-errors"></DIV> 
                        </DIV>
    
                    </div>
                </div><!--//. box  -->
                
                <div class="box box-solid">
                    <div class="box-header">
                        <strong>Valores</strong>
                    </div>
                    <div class="box-body">
                        
                        <DIV CLASS="form-group has-feedback">
                            <LABEL FOR="properties-value-total">Valor total do imóvel</LABEL>
                            <DIV CLASS="msg-validation">
                              <DIV CLASS="input-group">
                                  <SPAN CLASS="input-group-addon hidden-xs">$</SPAN>
                                  <input class="form-control" type="text" name="properties-value-total" placeholder="Exemplo: 180.435,00" data-control="mask-money" id="properties-value-total" value="<?php echo (!empty($value_total)) ? decimalMoeda($value_total) : '' ; ?>">
                              </DIV>
                            </DIV>
                            <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
                            <DIV CLASS="help-block with-errors"></DIV> 
                        </DIV>
                        
                        <div class="form-group">
                            <div class="checkbox">
                              <label>
                                 <input type="checkbox" name="properties-hidden-value-total" id="properties-hidden-value-total" <?php echo ($hidden_value_total === 'Y') ? 'checked' : '' ; ?> > Deixar valor sob consulta
                              </label>
                            </div>
                        </div>
                        
                        <DIV CLASS="form-group has-feedback">
                            <LABEL FOR="properties-value-monthly">Valor aluguel</LABEL>
                            <DIV CLASS="msg-validation">
                              <DIV CLASS="input-group">
                                  <SPAN CLASS="input-group-addon hidden-xs">$</SPAN>
                                  <input class="form-control" type="text" name="properties-value-monthly" placeholder="Exemplo: 435,00" data-control="mask-money" id="properties-value-monthly" value="<?php echo (!empty($value_monthly)) ? decimalMoeda($value_monthly) : '' ; ?>">
                              </DIV>
                            </DIV>
                            <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
                            <DIV CLASS="help-block with-errors"></DIV> 
                        </DIV>
                        
                        <div class="form-group">
                            <div class="checkbox">
                              <label>
                                 <input type="checkbox" name="properties-hidden-value-monthly" id="properties-hidden-value-monthly" <?php echo ($hidden_value_monthly === 'Y') ? 'checked' : '' ; ?> > Deixar valor do aluguel sob consulta
                              </label>
                            </div>
                        </div>
                        
                        <p><Strong>Se for por temporada, o valor refere-se à:</Strong></p>
                         <div class="row">
                              <div class="col-md-6 col-sm-6 col-xs-12">
                                  <div class="radio">  
                                      <label>
                                           <input type="radio" name="properties-value-refers" value="daily" <?php echo ($value_refers === 'daily') ? 'checked' : '' ; ?> > Valor diário
                                      </label>
                                  </div>
                              </div>
                              <div class="col-md-6 col-sm-6 col-xs-12">
                                  <div class="radio">  
                                      <label>
                                           <input type="radio" name="properties-value-refers" value="weekly"  <?php echo ($value_refers === 'weekly') ? 'checked' : '' ; ?> > Valor semanal
                                      </label>
                                  </div>
                              </div>
                              <div class="col-md-6 col-sm-6 col-xs-12">
                                  <div class="radio">  
                                      <label>
                                           <input type="radio" name="properties-value-refers" value="biweekly"  <?php echo ($value_refers === 'biweekly') ? 'checked' : '' ; ?> > Valor quinzenal
                                      </label>
                                  </div>
                              </div>
                              <div class="col-md-6 col-sm-6 col-xs-12">
                                  <div class="radio">  
                                      <label>
                                           <input type="radio" name="properties-value-refers" value="monthly"  <?php echo ($value_refers === 'monthly') ? 'checked' : '' ; ?> > Valor mensal
                                      </label>
                                  </div>
                              </div>
                              <div class="col-md-6 col-sm-6 col-xs-12">
                                  <div class="radio">  
                                      <label>
                                           <input type="radio" name="properties-value-refers" value="other"  <?php echo ($value_refers === 'other') ? 'checked' : '' ; ?> > Outro
                                      </label>
                                  </div>
                              </div>
                         </div>
                        
                        <div class="clearfix space-20"></div>
                        
                        <DIV CLASS="form-group has-feedback">
                            <LABEL FOR="properties-condominium-value">Taxa de condomínio</LABEL>
                              <DIV CLASS="msg-validation">
                              <DIV CLASS="input-group">
                                  <SPAN CLASS="input-group-addon hidden-xs">$</SPAN>
                                  <input class="form-control" type="text" name="properties-condominium-value" placeholder="0,00" data-control="mask-money" id="properties-condominium-value" value="<?php echo (!empty($condominium_value)) ? decimalMoeda($condominium_value) : '' ; ?>">
                              </DIV>
                            </DIV>
                            <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
                            <DIV CLASS="help-block with-errors"></DIV> 
                        </DIV>

                        <div class="checkbox">  
                          <label>
                             <input type="checkbox" name="properties-hidden-condominium-value" id="properties-hidden-condominium-value" <?php echo ($hidden_condominium_value === 'Y') ? 'checked' : '' ; ?>> Deixar sob consulta
                          </label>
                        </div>

                    </div>
                </div><!--//. box  -->
                
                
                <div class="box box-solid">
                    <div class="box-header">
                        <strong>Detalhes da Construção</strong>
                    </div>
                    <div class="box-body">                      
                        <DIV CLASS="form-group has-feedback">
                            <LABEL FOR="properties-material"> Material da construção</LABEL>
                            <DIV CLASS="msg-validation">
                                <SELECT CLASS="form-control" NAME="properties-material" id="properties-material">
                                    OPTION VALUE="<?php echo (!empty($construction_area)) ? $construction_area : '' ; ?>"><?php echo (!empty($construction_area)) ? ucwords($construction_area) : '...' ; ?></OPTION>
                                    <OPTION VALUE="alvenaria">Alvenaria</OPTION>
                                    <OPTION VALUE="container">Container</OPTION>
                                    <OPTION VALUE="madeira">Madeira</OPTION>
                                    <OPTION VALUE="reciclável">Material reciclável</OPTION>  
                                    <OPTION VALUE="mesclado">Mesclado</OPTION> 
                                    <OPTION VALUE="mesclado">Outro</OPTION> 
                                </SELECT>
                            </DIV>
                            <SPAN CLASS="fa form-control-feedback"></SPAN>
                            <DIV CLASS="help-block with-errors"></DIV> 
                        </DIV>
                        
                        
                        <div class="row">
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <DIV CLASS="form-group has-feedback">
                                    <LABEL FOR="properties-total-area">Área total</LABEL>
                                    <DIV CLASS="msg-validation">
                                        <input class="form-control" type="text" name="properties-total-area" placeholder="m²" id="properties-total-area" pattern="[0-9,]+$" value="<?php echo (!empty($total_area)) ? $total_area : '0' ; ?>">
                                    </DIV>
                                    <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
                                    <DIV CLASS="help-block with-errors"></DIV> 
                                </DIV>
                            </div>
                            
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <DIV CLASS="form-group has-feedback">
                                    <LABEL FOR="properties-construct-area">Área construida</LABEL>
                                    <DIV CLASS="msg-validation">
                                        <input class="form-control" type="text" name="properties-construct-area" placeholder="m²" id="properties-construct-area" pattern="[0-9,]+$" value="<?php echo (!empty($construct_area)) ? $construct_area : '0' ; ?>">
                                    </DIV>
                                    <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
                                    <DIV CLASS="help-block with-errors"></DIV> 
                                </DIV>
                            </div>
                        </div>
                        
                        <div class="row">
                           
                            <div class="col-md-3 col-sm-3 col-xs-6">
                                <DIV CLASS="form-group has-feedback">
                                    <LABEL FOR="properties-number-rooms">Quartos</LABEL>
                                    <DIV CLASS="msg-validation">
                                        <input class="form-control" type="number" name="properties-number-rooms" value="0" id="properties-number-rooms" pattern="[0-9]+$" value="<?php echo (!empty($number_rooms)) ? $number_rooms : '0' ; ?>">
                                    </DIV>
                                    <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
                                    <DIV CLASS="help-block with-errors"></DIV> 
                                </DIV>
                            </div>
                            
                            <div class="col-md-3 col-sm-3 col-xs-6">
                                <DIV CLASS="form-group has-feedback">
                                    <LABEL FOR="properties-number-bathrooms">Banheiros</LABEL>
                                    <DIV CLASS="msg-validation">
                                        <input class="form-control" type="number" name="properties-number-bathrooms" value="0" id="properties-number-bathrooms" pattern="[0-9]+$" value="<?php echo (!empty($number_bathrooms)) ? $number_bathrooms : '0' ; ?>">
                                    </DIV>
                                    <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
                                    <DIV CLASS="help-block with-errors"></DIV> 
                                </DIV>
                            </div>
                            
                            <div class="col-md-3 col-sm-3 col-xs-6">
                                <DIV CLASS="form-group has-feedback">
                                    <LABEL FOR="properties-number-garage"> Garagem</LABEL>
                                    <DIV CLASS="msg-validation">
                                        <input class="form-control" type="number" name="properties-number-garage" value="0" id="properties-number-garage" pattern="[0-9]+$" value="<?php echo (!empty($number_garage)) ? $number_garage : '0' ; ?>">
                                    </DIV>
                                    <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
                                    <DIV CLASS="help-block with-errors"></DIV> 
                                </DIV>
                            </div>
                            
                            <div class="col-md-3 col-sm-3 col-xs-6">
                                <DIV CLASS="form-group has-feedback">
                                    <LABEL FOR="properties-number-suites">Suites</LABEL>
                                    <DIV CLASS="msg-validation">
                                        <input class="form-control" type="number" name="properties-number-suites" value="0" id="properties-number-suites" pattern="[0-9]+$" value="<?php echo (!empty($number_suites)) ? $number_suites : '0' ; ?>">
                                    </DIV>
                                    <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
                                    <DIV CLASS="help-block with-errors"></DIV> 
                                </DIV>
                            </div>
                            
                        </div>
                        
                    </div>
                </div><!--//. box  -->
                
                
                <div class="box box-solid">
                    <div class="box-header">
                        <STRONG>Imóvel na planta ou em construção</STRONG>
                    </div>
                    <div class="box-body">
                        
                        <DIV CLASS="form-group has-feedback">
                            <LABEL FOR="properties-finalization-date">Data prevista para a entrega</LABEL>
                            <DIV CLASS="msg-validation">
                              <DIV CLASS="input-group date" data-control="datepicker">
                                  <SPAN CLASS="input-group-addon hidden-xs"><i class="fa fa-calendar"></i></SPAN>
                                  <input class="form-control" type="text" name="properties-finalization-date" id="properties-finalization-date" data-control="mask-date" value="<?php echo (!empty($finalization_date)) ? inverteData($finalization_date) : '' ; ?>">
                              </DIV>
                            </DIV>
                            <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
                            <DIV CLASS="help-block with-errors"></DIV> 
                        </DIV>
                        
                    </div>
                </div><!--//. box  -->
                
                
            </div><!--//. col left principal -->
            
            
            
            <div class="col-md-6 col-sm-6 col-xs-12">
                
                <div class="box box-solid">
                    <div class="box-header">
                        <STRONG>Identifique o imóvel no mapa</STRONG>
                    </div>
                    <div class="box-body">
                        <div class="row-fluid">
                            <div class="">
                                <div class="form-group">
                                    <label class="sr-only" for="txtEndereco">Endereço:</label>
                                    <div class="input-group">
                                       <input type="text" class="form-control" id="txtEndereco" name="txtEndereco" placeholder="Pesquisar endereço">
                                        <div class="full-form-group">
                                        <a href="javascript:;" class="btn btn-primary btn-flat" title="Encontre no mapa"><i class="fa fa-search"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="mapa"></div>
                            
                            <div class="clearfix space-20"></div>
                            
                            <div class="row">
     
                                <div class="col-md-6 col-sm-6 col-xs-12 hidden">
                                    <DIV CLASS="form-group">
                                        <LABEL FOR="properties-address-city"> Latitude</LABEL>
                                        <input type="text" id="txtLatitude" name="txtLatitude" CLASS="form-control"  />
                                    </DIV>
                                </div>
                                
                                <div class="col-md-6 col-sm-6 col-xs-12 hidden">
                                    <DIV CLASS="form-group">
                                        <LABEL FOR="properties-address-city"> Longitude</LABEL>
                                        <input type="text" id="txtLongitude" name="txtLongitude" CLASS="form-control"/>
                                    </DIV>
                                </div>
                                
                                <div class="col-md-8 col-sm-8 col-xs-12">               
                                    <div class="form-group">
                                        <div class="checkbox">
                                          <label>
                                             <input type="checkbox" name="properties-save-coords" id="properties-save-coords" <?php echo ( !empty($coor_lat) and !empty($coor_lon)) ? 'checked' : '' ;?> > Desejo guardar essas informações e exibir em meu site
                                          </label>
                                        </div>
                                    </div>
                                </div> 
                                
                                <div class="col-md-4 col-sm-4 col-xs-12">
                                   <a href="javascript:;" class="pull-right ico-help" id="getLocation"><img src="/app/images/icons/my-location.png" tabindex= "0" role= "button" DATA-CONTROL="popover-hover" DATA-PLACEMENT="auto" DATA-TOGGLE="popover" DATA-CONTAINER="body" DATA-HTML="true" DATA-TRIGGER="focus" TITLE="Ajuda" DATA-CONTENT="Clique para obter sua posição atual, é necessário permitir a localização."></a> 
                                </div>              
                            </div>                      
                            
                        </div>

                    </div>
                </div><!--//. box  -->
                
                <div class="box box-solid">
                    <div class="box-header">
                        <STRONG>Inserir endereço manualmente</STRONG>
                    </div>
                    <div class="box-body">
                    
                        <DIV CLASS="form-group has-feedback">
                            <LABEL FOR="properties-address-state"> Estado</LABEL>
                            <DIV CLASS="msg-validation">
                                <SELECT CLASS="form-control" NAME="properties-address-state" id="properties-address-state">
                                    <OPTION VALUE="<?php echo (!empty($address_state)) ? $address_state : '' ; ?>"><?php echo (!empty($address_state)) ? $address_state : 'Informe o estado' ; ?></OPTION>
                                </SELECT>
                            </DIV>
                            <SPAN CLASS="form-control-feedback"></SPAN>
                            <DIV CLASS="help-block with-errors"></DIV> 
                        </DIV>
                        
                        <DIV CLASS="form-group has-feedback">
                            <LABEL FOR="properties-address-city"> Cidade</LABEL>
                            <DIV CLASS="msg-validation">
                                <SELECT CLASS="form-control" NAME="properties-address-city" id="properties-address-city">
                                    <OPTION VALUE="<?php echo (!empty($address_city)) ? $address_city : '' ; ?>"><?php echo (!empty($address_city)) ? $address_city : '' ; ?></OPTION>
                                </SELECT>
                            </DIV>
                            <SPAN CLASS=" form-control-feedback"></SPAN>
                            <DIV CLASS="help-block with-errors"></DIV> 
                        </DIV>
                        
                        <div class="row">
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                <DIV CLASS="form-group has-feedback">
                                    <LABEL FOR="properties-address-street">Rua</LABEL>
                                    <DIV CLASS="msg-validation">
                                        <input class="form-control" type="text" name="properties-address-street" id="properties-address-street" value="<?php echo (!empty($address_street)) ? $address_street : '' ; ?>">
                                    </DIV>
                                    <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
                                    <DIV CLASS="help-block with-errors"></DIV> 
                                </DIV>
                            </div>
                            
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <DIV CLASS="form-group has-feedback">
                                    <LABEL FOR="properties-address-number">Número</LABEL>
                                    <DIV CLASS="msg-validation">
                                        <input class="form-control" type="text" name="properties-address-number" id="properties-address-number" value="<?php echo (!empty($address_number)) ? $address_number : '' ; ?>">
                                    </DIV>
                                    <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
                                    <DIV CLASS="help-block with-errors"></DIV> 
                                </DIV>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                <DIV CLASS="form-group has-feedback">
                                    <LABEL FOR="properties-address-neighborhood">Bairro</LABEL>
                                    <DIV CLASS="msg-validation">
                                        <input class="form-control" type="text" name="properties-address-neighborhood" id="properties-address-neighborhood" value="<?php echo (!empty($address_neighborhood)) ? $address_neighborhood : '' ; ?>">
                                    </DIV>
                                    <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
                                    <DIV CLASS="help-block with-errors"></DIV> 
                                </DIV>
                            </div>
                            
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <DIV CLASS="form-group has-feedback">
                                    <LABEL FOR="properties-address-postal-code">CEP</LABEL>
                                    <DIV CLASS="msg-validation">
                                        <input class="form-control" type="text" name="properties-address-postal-code" id="properties-address-postal-code" data-control="mask-postal-code" value="<?php echo (!empty($address_postal_code)) ? $address_postal_code : '' ; ?>">
                                    </DIV>
                                    <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
                                    <DIV CLASS="help-block with-errors"></DIV> 
                                </DIV>
                            </div>
                        </div>             
                    
                    </div>
                </div><!--//. box  -->
                
                <div class="box box-solid">
                    <div class="box-header">
                        <STRONG>Estabelecimentos próximos</STRONG>
                    </div>
                    <div class="box-body">
                        
                        <div class="col-md-2 col-sm-3 col-xs-4">
                            <label for="near-hospital">
                                <div class="mini-box-near <?php echo ($hospital === 'Y') ? 'checked' : '' ; ?>" tabindex= "0" role= "button" DATA-CONTROL="popover-hover" DATA-PLACEMENT="auto" DATA-TOGGLE="popover" DATA-CONTAINER="body" DATA-HTML="true" DATA-TRIGGER="focus" data-title="false" DATA-CONTENT="Hospital">
                                    <img src="/app/images/icons/properties-icons/cardiogram.png" class="img-reponsive">
                                    <input type="checkbox" name="near-hospital" id="near-hospital" data-control="near" class="hidden-checkbox" <?php echo ($hospital === 'Y') ? 'checked' : '' ; ?> >
                                    <i class="fa fa-check"></i>
                                </div>
                            </label>
                        </div><!--/.col-3 item -->
                        
                        <div class="col-md-2 col-sm-3 col-xs-4">
                            <label for="near-restaurant">
                                <div class="mini-box-near <?php echo ($restaurant === 'Y') ? 'checked' : '' ; ?>" tabindex= "0" role= "button" DATA-CONTROL="popover-hover" DATA-PLACEMENT="auto" DATA-TOGGLE="popover" DATA-CONTAINER="body" DATA-HTML="true" DATA-TRIGGER="focus" data-title="false" DATA-CONTENT="Restaurante">
                                    <img src="/app/images/icons/properties-icons/dish.png" class="img-reponsive">
                                    <input type="checkbox" name="near-restaurant" id="near-restaurant" data-control="near" class="hidden-checkbox" <?php echo ($restaurant === 'Y') ? 'checked' : '' ; ?> >
                                    <i class="fa fa-check"></i>
                                </div>
                            </label>
                        </div><!--/.col-3 item -->
                        
                        <div class="col-md-2 col-sm-3 col-xs-4">
                            <label for="near-bakery">
                                <div class="mini-box-near <?php echo ($bakery === 'Y') ? 'checked' : '' ; ?>" tabindex= "0" role= "button" DATA-CONTROL="popover-hover" DATA-PLACEMENT="auto" DATA-TOGGLE="popover" DATA-CONTAINER="body" DATA-HTML="true" DATA-TRIGGER="hover" data-title="false" DATA-CONTENT="Cafeteria/Padaria">
                                    <img src="/app/images/icons/properties-icons/doughnut.png" class="img-reponsive">
                                    <input type="checkbox" name="near-bakery" id="near-bakery" data-control="near" class="hidden-checkbox"  <?php echo ($bakery === 'Y') ? 'checked' : '' ; ?> >
                                    <i class="fa fa-check"></i>
                                </div>
                            </label>
                        </div><!--/.col-3 item -->
                        
                        <div class="col-md-2 col-sm-3 col-xs-4">
                            <label for="near-road">
                                <div class="mini-box-near <?php echo ($road === 'Y') ? 'checked' : '' ; ?>" tabindex= "0" role= "button" DATA-CONTROL="popover-hover" DATA-PLACEMENT="auto" DATA-TOGGLE="popover" DATA-CONTAINER="body" DATA-HTML="true" DATA-TRIGGER="focus" data-title="false" DATA-CONTENT="Terminal Rodoviário">
                                    <img src="/app/images/icons/properties-icons/school-bus.png" class="img-reponsive">
                                    <input type="checkbox" name="near-road" id="near-road" data-control="near" class="hidden-checkbox" <?php echo ($road === 'Y') ? 'checked' : '' ; ?> >
                                    <i class="fa fa-check"></i>
                                </div>
                            </label>
                        </div><!--/.col-3 item -->
                        
                        <div class="col-md-2 col-sm-3 col-xs-4">
                            <label for="near-fuel">
                                <div class="mini-box-near <?php echo ($fuel === 'Y') ? 'checked' : '' ; ?> " tabindex= "0" role= "button" DATA-CONTROL="popover-hover" DATA-PLACEMENT="auto" DATA-TOGGLE="popover" DATA-CONTAINER="body" DATA-HTML="true" DATA-TRIGGER="focus" data-title="false" DATA-CONTENT="Posto de Combustível">
                                    <img src="/app/images/icons/properties-icons/gas-station.png" class="img-reponsive">
                                    <input type="checkbox" name="near-fuel" id="near-fuel" data-control="near" class="hidden-checkbox" <?php echo ($fuel === 'Y') ? 'checked' : '' ; ?> >
                                    <i class="fa fa-check"></i>
                                </div>
                            </label>
                        </div><!--/.col-3 item -->
                        
                        <div class="col-md-2 col-sm-3 col-xs-4">
                            <label for="near-fast-food">
                                <div class="mini-box-near <?php echo ($fast_food === 'Y') ? 'checked' : '' ; ?> " tabindex= "0" role= "button" DATA-CONTROL="popover-hover" DATA-PLACEMENT="auto" DATA-TOGGLE="popover" DATA-CONTAINER="body" DATA-HTML="true" DATA-TRIGGER="focus" data-title="false" DATA-CONTENT="Fast Food">
                                    <img src="/app/images/icons/properties-icons/hamburguer.png" class="img-reponsive">
                                    <input type="checkbox" name="near-fast-food" id="near-fast-food" data-control="near" class="hidden-checkbox" <?php echo ($fast_food === 'Y') ? 'checked' : '' ; ?> >
                                    <i class="fa fa-check"></i>
                                </div>
                            </label>
                        </div><!--/.col-3 item -->
                        
                        <div class="col-md-2 col-sm-3 col-xs-4">
                            <label for="near-beach">
                                <div class="mini-box-near <?php echo ($beach === 'Y') ? 'checked' : '' ; ?> " tabindex= "0" role= "button" DATA-CONTROL="popover-hover" DATA-PLACEMENT="auto" DATA-TOGGLE="popover" DATA-CONTAINER="body" DATA-HTML="true" DATA-TRIGGER="focus" data-title="false" DATA-CONTENT="Praia">
                                    <img src="/app/images/icons/properties-icons/sun-umbrella.png" class="img-reponsive">
                                    <input type="checkbox" name="near-beach" id="near-beach" data-control="near" class="hidden-checkbox" <?php echo ($beach === 'Y') ? 'checked' : '' ; ?> >
                                    <i class="fa fa-check"></i>
                                </div>
                            </label>
                        </div><!--/.col-3 item -->
                        
                        <div class="col-md-2 col-sm-3 col-xs-4">
                            <label for="near-summer-club">
                                <div class="mini-box-near <?php echo ($summer_club === 'Y') ? 'checked' : '' ; ?> " tabindex= "0" role= "button" DATA-CONTROL="popover-hover" DATA-PLACEMENT="auto" DATA-TOGGLE="popover" DATA-CONTAINER="body" DATA-HTML="true" DATA-TRIGGER="focus" data-title="false" DATA-CONTENT="Clube de Verão">
                                <img src="/app/images/icons/properties-icons/swimming-pool.png" class="img-reponsive">
                                    <input type="checkbox" name="near-summer-club" id="near-summer-club" data-control="near" class="hidden-checkbox" <?php echo ($summer_club === 'Y') ? 'checked' : '' ; ?> >
                                    <i class="fa fa-check"></i>
                                </div>
                            </label>
                        </div><!--/.col-3 item -->
                        
                        <div class="col-md-2 col-sm-3 col-xs-4">
                            <label for="near-airport">
                                <div class="mini-box-near <?php echo ($airport === 'Y') ? 'checked' : '' ; ?> " tabindex= "0" role= "button" DATA-CONTROL="popover-hover" DATA-PLACEMENT="auto" DATA-TOGGLE="popover" DATA-CONTAINER="body" DATA-HTML="true" DATA-TRIGGER="focus" data-title="false" DATA-CONTENT="Aeroporto">
                                <img src="/app/images/icons/properties-icons/departure.png" class="img-reponsive">
                                    <input type="checkbox" name="near-airport" id="near-airport" data-control="near" class="hidden-checkbox" <?php echo ($airport === 'Y') ? 'checked' : '' ; ?> >
                                    <i class="fa fa-check"></i>
                                </div>
                            </label>
                        </div><!--/.col-3 item -->
                        
                        <div class="col-md-2 col-sm-3 col-xs-4">
                            <label for="near-school">
                                <div class="mini-box-near <?php echo ($school === 'Y') ? 'checked' : '' ; ?> " tabindex= "0" role= "button" DATA-CONTROL="popover-hover" DATA-PLACEMENT="auto" DATA-TOGGLE="popover" DATA-CONTAINER="body" DATA-HTML="true" DATA-TRIGGER="focus" data-title="false" DATA-CONTENT="Colégio">
                                <img src="/app/images/icons/properties-icons/blackboard.png" class="img-reponsive">
                                    <input type="checkbox" name="near-school" id="near-school" data-control="near" class="hidden-checkbox" <?php echo ($school === 'Y') ? 'checked' : '' ; ?> >
                                    <i class="fa fa-check"></i>
                                </div>
                            </label>
                        </div><!--/.col-3 item -->
                        
                        <div class="col-md-2 col-sm-3 col-xs-4">
                            <label for="near-university">
                                <div class="mini-box-near <?php echo ($university === 'Y') ? 'checked' : '' ; ?> " tabindex= "0" role= "button" DATA-CONTROL="popover-hover" DATA-PLACEMENT="auto" DATA-TOGGLE="popover" DATA-CONTAINER="body" DATA-HTML="true" DATA-TRIGGER="focus" data-title="false" DATA-CONTENT="Universidade">
                                <img src="/app/images/icons/properties-icons/mortarboard.png" class="img-reponsive">
                                    <input type="checkbox" name="near-university" id="near-university" data-control="near" class="hidden-checkbox" <?php echo ($university === 'Y') ? 'checked' : '' ; ?> >
                                    <i class="fa fa-check"></i>
                                </div>
                            </label>
                        </div><!--/.col-3 item -->
                        
                        <div class="col-md-2 col-sm-3 col-xs-4">
                            <label for="near-shopping">
                                <div class="mini-box-near <?php echo ($shopping === 'Y') ? 'checked' : '' ; ?> " tabindex= "0" role= "button" DATA-CONTROL="popover-hover" DATA-PLACEMENT="auto" DATA-TOGGLE="popover" DATA-CONTAINER="body" DATA-HTML="true" DATA-TRIGGER="focus" data-title="false" DATA-CONTENT="Shopping">
                                <img src="/app/images/icons/properties-icons/shopping-bag.png" class="img-reponsive">
                                    <input type="checkbox" name="near-shopping" id="near-shopping" data-control="near" class="hidden-checkbox" <?php echo ($shopping === 'Y') ? 'checked' : '' ; ?> >
                                    <i class="fa fa-check"></i>
                                </div>
                            </label>
                        </div><!--/.col-3 item -->
                        
                        <div class="col-md-2 col-sm-3 col-xs-4">
                            <label for="near-supermarket">
                                <div class="mini-box-near <?php echo ($supermarket === 'Y') ? 'checked' : '' ; ?> " tabindex= "0" role= "button" DATA-CONTROL="popover-hover" DATA-PLACEMENT="auto" DATA-TOGGLE="popover" DATA-CONTAINER="body" DATA-HTML="true" DATA-TRIGGER="focus" data-title="false" DATA-CONTENT="Supermercado">
                                <img src="/app/images/icons/properties-icons/cart.png" class="img-reponsive">
                                    <input type="checkbox" name="near-supermarket" id="near-supermarket" data-control="near" class="hidden-checkbox" <?php echo ($supermarket === 'Y') ? 'checked' : '' ; ?> >
                                    <i class="fa fa-check"></i>
                                </div>
                            </label>
                        </div><!--/.col-3 item -->
                        
                        <div class="col-md-2 col-sm-3 col-xs-4">
                            <label for="near-park">
                                <div class="mini-box-near <?php echo ($park === 'Y') ? 'checked' : '' ; ?> " tabindex= "0" role= "button" DATA-CONTROL="popover-hover" DATA-PLACEMENT="auto" DATA-TOGGLE="popover" DATA-CONTAINER="body" DATA-HTML="true" DATA-TRIGGER="focus" data-title="false" DATA-CONTENT="Praças, área de lazer, etc">
                                <img src="/app/images/icons/properties-icons/park.png" class="img-reponsive">
                                    <input type="checkbox" name="near-park" id="near-park" data-control="near" class="hidden-checkbox" <?php echo ($park === 'Y') ? 'checked' : '' ; ?> >
                                    <i class="fa fa-check"></i>
                                </div>
                            </label>
                        </div><!--/.col-3 item -->
                        
                        <div class="col-md-2 col-sm-3 col-xs-4">
                            <label for="near-drogstore">
                                <div class="mini-box-near <?php echo ($drogstore === 'Y') ? 'checked' : '' ; ?> " tabindex= "0" role= "button" DATA-CONTROL="popover-hover" DATA-PLACEMENT="auto" DATA-TOGGLE="popover" DATA-CONTAINER="body" DATA-HTML="true" DATA-TRIGGER="focus" data-title="false" DATA-CONTENT="Farmácia, drogaria">
                                <img src="/app/images/icons/properties-icons/drugs.png" class="img-reponsive">
                                    <input type="checkbox" name="near-drogstore" id="near-drogstore" data-control="near" class="hidden-checkbox" <?php echo ($drogstore === 'Y') ? 'checked' : '' ; ?> >
                                    <i class="fa fa-check"></i>
                                </div>
                            </label>
                        </div><!--/.col-3 item -->
                        
                        <div class="col-md-2 col-sm-3 col-xs-4">
                            <label for="near-academy">
                                <div class="mini-box-near <?php echo ($academy === 'Y') ? 'checked' : '' ; ?> " tabindex= "0" role= "button" DATA-CONTROL="popover-hover" DATA-PLACEMENT="auto" DATA-TOGGLE="popover" DATA-CONTAINER="body" DATA-HTML="true" DATA-TRIGGER="focus" data-title="false" DATA-CONTENT="Academia">
                                <img src="/app/images/icons/properties-icons/dumbbell.png" class="img-reponsive">
                                    <input type="checkbox" name="near-academy" id="near-academy" data-control="near" class="hidden-checkbox" <?php echo ($academy === 'Y') ? 'checked' : '' ; ?> >
                                    <i class="fa fa-check"></i>
                                </div>
                            </label>
                        </div><!--/.col-3 item -->
                        
                        <div class="col-md-2 col-sm-3 col-xs-4">
                            <label for="near-bank">
                                <div class="mini-box-near <?php echo ($bank === 'Y') ? 'checked' : '' ; ?> " tabindex= "0" role= "button" DATA-CONTROL="popover-hover" DATA-PLACEMENT="auto" DATA-TOGGLE="popover" DATA-CONTAINER="body" DATA-HTML="true" DATA-TRIGGER="focus" data-title="false" DATA-CONTENT="Banco">
                                <img src="/app/images/icons/properties-icons/bank.png" class="img-reponsive">
                                    <input type="checkbox" name="near-bank" id="near-bank" data-control="near" class="hidden-checkbox" <?php echo ($bank === 'Y') ? 'checked' : '' ; ?> >
                                    <i class="fa fa-check"></i>
                                </div>
                            </label>
                        </div><!--/.col-3 item -->
                        
                        <div class="col-md-2 col-sm-3 col-xs-4">
                            <label for="near-police">
                                <div class="mini-box-near <?php echo ($police === 'Y') ? 'checked' : '' ; ?> " tabindex= "0" role= "button" DATA-CONTROL="popover-hover" DATA-PLACEMENT="auto" DATA-TOGGLE="popover" DATA-CONTAINER="body" DATA-HTML="true" DATA-TRIGGER="focus" data-title="false" DATA-CONTENT="Posto policial">
                                <img src="/app/images/icons/properties-icons/police-car.png" class="img-reponsive">
                                    <input type="checkbox" name="near-police" id="near-police" data-control="near" class="hidden-checkbox" <?php echo ($police === 'Y') ? 'checked' : '' ; ?> >
                                    <i class="fa fa-check"></i>
                                </div>
                            </label>
                        </div><!--/.col-3 item -->
                        
                        <div class="col-md-2 col-sm-3 col-xs-4">
                            <label for="near-firefighter">
                                <div class="mini-box-near <?php echo ($firefighter === 'Y') ? 'checked' : '' ; ?> " tabindex= "0" role= "button" DATA-CONTROL="popover-hover" DATA-PLACEMENT="auto" DATA-TOGGLE="popover" DATA-CONTAINER="body" DATA-HTML="true" DATA-TRIGGER="focus" data-title="false" DATA-CONTENT="Bombeiros">
                                <img src="/app/images/icons/properties-icons/firefighter.png" class="img-reponsive">
                                    <input type="checkbox" name="near-firefighter" id="near-firefighter" data-control="near" class="hidden-checkbox" <?php echo ($firefighter === 'Y') ? 'checked' : '' ; ?> >
                                    <i class="fa fa-check"></i>
                                </div>
                            </label>
                        </div><!--/.col-3 item -->
                        
                        
                    </div>
                </div><!--//. box  -->

            </div><!--//. col right -->
            
            <div class="clearfix space-30"></div>
            
            <div class="col-md-12">
            	<div class="box box-solid">
                    <div class="box-header">
                        <STRONG>Descreva mais detalhadamente o imóvel</STRONG>
                    </div>
                    <div class="box-body no-padding">
                        <TEXTAREA NAME="properties-details" CLASS="tiny"  ID="properties-details" STYLE="resize:none; min-height: 255px"><?php echo (!empty($detalhes)) ? $purifier_detalhes : '' ; ?></TEXTAREA>
                        <div class="clearfix"></div>
                    </div>
                </div><!--//. box --> 
            </div>
            
            <div class="clearfix space-30"></div>
            
            <DIV CLASS="col-md-12">
                <button type="submit" class="btn btn-primary btn-flat">Atualizar</button>
            </DIV>
            
        </form>
        
    </DIV>
</SECTION>

<DIV CLASS="clearfix space-30"></DIV>
<script src="/plugins/bootstrap-validator-master/dist/validator.min.js"></script>
<script src="/plugins/cidades-estados/estados-cidades.js"></script>
<script type="text/javascript" charset="utf-8">
  new dgCidadesEstados({
    cidade: document.getElementById('properties-address-city'),
    estado: document.getElementById('properties-address-state'),
    estadoVal: '<?php echo (!empty($address_state)) ? $address_state : 'PR' ; ?>',
    cidadeVal: '<?php echo (!empty($address_city)) ? $address_city : 'Realeza' ; ?>'
  })
</script>
<script src="/plugins/input-mask/jquery.maskMoney.js"></script>
<script src="/plugins/input-mask/jquery.maskAll.js"></script>
<script src="/app/javascript/properties.js"></script>
<script src="/plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="/plugins/datepicker/locales/bootstrap-datepicker.pt-BR.js"></script>
<script>
$(document).ready(function() {
    $('[data-control="datepicker"]').datepicker({
        format: 'dd/mm/yyyy',
    });
});
</script>

<script src="/plugins/tinymce/tinymce.min.js"></script>
<script>
tinymce.init({
    language : 'pt_BR',
    content_css : "/plugins/tinymce/css/properties.css",
	convert_urls: false,
    selector: ".tiny",
    constrain_menus : true,
	plugins: "textcolor",
    textcolor_rows: 5,
	textcolor_cols: 8,
    plugins: [
        'advlist autolink lists link image charmap print preview hr anchor pagebreak',
        'searchreplace wordcount visualblocks visualchars code fullscreen',
        'insertdatetime media nonbreaking save table contextmenu directionality',
        'emoticons template paste textcolor colorpicker textpattern imagetools'
    ],
    toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | forecolor backcolor ",
	 image_prepend_url: "", // caminho para visualizar no editor
	 image_advtab: true,
	 image_dimensions: true,
	 paste_data_images: true,
	 images_upload_url: "",
	 images_upload_base_path: "", // caminho para visualização no site
	 images_upload_handler: function(blobInfo, success, failure) {
        console.log(blobInfo.blob());
        success('url');
	 },
	textcolor_map: [
    "000000", "Black",
    "993300", "Burnt orange",
    "333300", "Dark olive",
    "003300", "Dark green",
    "003366", "Dark azure",
		"FF0000", "Red",
		"0066FF", "Blue",
		"FFFF00", "Yellow",
		"FF6600", "Orange",
		"666666", "Gray Medium",
    ],
    style_formats_merge: true,
    style_formats: [
        {
            title: 'Personalizado',
            items: [
                {title: 'Botão azul', inline: 'span', classes: 'botao'},
                {title: 'Botão verde', inline: 'span', classes: 'botao_verde'},
                {title: 'Título cinza', inline: 'h1', classes: 'titulo_cinza'}
            ]
        }
    ],
  
color_picker_callback: function(callback, value) {
        callback('#000')},
	
setup: function (editor) { editor.on('change', function () { tinymce.triggerSave(); }); }

});
</script>
<script src="/app/javascript/control_forms.js"></script>
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
google.maps.event.addDomListener(window, 'load', autoComplete());
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