<?php
use config\connect_db;

if( ($_SESSION['user_master_perms'] !== 'administrador') )
{
    if( $_SESSION['vehicles_read'] !== '1' )
    {
        die ('<script>location.href="/app/admin.php?page=access_denied";</script>');
    }
}

if( empty($_GET['id']) or !isset($_GET['id']) )
{
    die ('<script>location.href="/app/admin.php?page=404";</script>');
}
else
{
    $id_car = filterString(base64_decode($_GET['id']), 'INT');
}

$con_db = new config\connect_db();
$con = $con_db->connect();

$vehicle = $con->prepare("SELECT `marca`, `modelo`, `submodelo`, `ano_fab`, `ano_mod`, `combustivel`, `carroceria`, `km`, `cor`, `portas`, `final_placa`, `cambio`, `obs`,  `airbag`, `ar_condicionado`, `direcao`, `alarme`, `cd_player`, `rodas`, `travas`, `vidros`, `camera`, `sensor_estacionamento`, `chave_reserva`, `manual`, `desembacador_traseiro`, `computador`, `ar_quente`, `multimidia`, `controles_volante`, `volante_regulagem`, `retrovisor`, `gps`, `abs`, `farois_neblina`, `capota`, `teto_solar`, `bancos_couro`, `farois_regulagem`, `sensor_chuva`, `brake_light`, `limpador_traseiro`, `aerofolio`, `engate_traseiro`, `estribo`, `santantonio`, `mascara_negra`, `local_loja`, `unico_dono`, `troca`, `estado`, `valor`  FROM `cars` LEFT JOIN `cars_optional` ON (`cars`.`id` = `cars_optional`.`id_car`) WHERE `cars`.`id` = ? ") or die(mysqli_error($con));
$vehicle->bind_param('i', $id_car);
$vehicle->execute();
$vehicle->store_result();
$vehicle->bind_result($marca, $modelo, $submodelo, $ano_fab, $ano_mod, $combustivel, $carroceria, $km, $cor, $portas, $final_placa, $cambio, $obs, $airbag, $ar_condicionado, $direcao, $alarme, $cd_player, $rodas, $travas, $vidros, $camera, $sensor_estacionamento, $chave_reserva, $manual, $desembacador_traseiro, $computador, $ar_quente, $multimidia, $controles_volante, $volante_regulagem, $retrovisor, $gps, $abs, $farois_neblina, $capota, $teto_solar, $bancos_couro, $farois_regulagem, $sensor_chuva, $brake_light, $limpador_traseiro, $aerofolio, $engate_traseiro, $estribo, $santantonio, $mascara_negra, $local_loja, $unico_dono, $troca, $estado, $valor );
$vehicle->fetch();
$rows = $vehicle->affected_rows;
$vehicle->free_result();
$vehicle->close();
  
$airbag = isChecked($airbag);
$ar_condicionado = isChecked($ar_condicionado);
$direcao = isChecked($direcao);
$alarme = isChecked($alarme);
$cd_player = isChecked($cd_player);
$rodas = isChecked($rodas);
$travas = isChecked($travas);
$vidros = isChecked($vidros);
$camera = isChecked($camera);
$sensor_estacionamento = isChecked($sensor_estacionamento);
$chave_reserva = isChecked($chave_reserva);
$manual = isChecked($manual);
$desembacador_traseiro = isChecked($desembacador_traseiro);
$computador = isChecked($computador);
$ar_quente = isChecked($ar_quente);
$multimidia = isChecked($multimidia);
$controles_volante = isChecked($controles_volante);
$volante_regulagem = isChecked($volante_regulagem);
$retrovisor = isChecked($retrovisor);
$gps = isChecked($gps);
$abs = isChecked($abs);
$farois_neblina = isChecked($farois_neblina);
$capota = isChecked($capota);
$teto_solar = isChecked($teto_solar);
$bancos_couro = isChecked($bancos_couro);
$farois_regulagem = isChecked($farois_regulagem);
$sensor_chuva = isChecked($sensor_chuva);
$brake_light = isChecked($brake_light);
$limpador_traseiro = isChecked($limpador_traseiro);
$aerofolio = isChecked($aerofolio);
$engate_traseiro = isChecked($engate_traseiro);
$estribo = isChecked($estribo);
$santantonio = isChecked($santantonio);
$mascara_negra = isChecked($mascara_negra);

if( $vehicle and $rows <= 0 )
{
    die('<script>location.href="/app/admin.php?page=404";</script>');
}

if( $local_loja === 'loja1' )
{
    $cidade_loja = 'Pérola D\'oeste';
}
else if( $local_loja === 'loja2' )
{
    $cidade_loja = 'Realeza';
}
else
{
    $cidade_loja = '';
}

?>
<SECTION CLASS="row">
    <div class="container">
        <div class="col-md-12">
            <H4 CLASS="text-darkgray"><STRONG>Editar veículo</STRONG></H4>
            <OL CLASS="breadcrumb bg-white">
                <LI><a href="admin.php"><I CLASS="fa fa-home"></I></a></LI>
                <LI><a href="admin.php?page=vehicles"><I CLASS="fa fa-car"></I> Veículos</a></LI>
                <LI><a href="admin.php?page=edit_cars&id=<?php echo base64_encode($id_car) ?>"><I CLASS="fa fa-car"></I> <?php echo $marca.' '.$modelo.' '.$submodelo ?> </a></LI>
            </OL>
        </div>
    </div>
</SECTION>

<DIV CLASS="space30"></DIV>

<SECTION CLASS="row">
  <DIV CLASS="container">
  
  <form name="form-edit-car" method="POST" id="form-edit-car" action="/app/modules/vehicles/update_car.php" role="form" DATA-TOGGLE="validator" enctype="application/x-www-form-urlencoded" data-action="submit-ajax" data-form-reset="noreset">
  
  <input type="HIDDEN" name="user_id" value="<?php echo $_SESSION['user_id'] ?>">
  <input type="HIDDEN" name="form-token" value="<?php echo$_SESSION['secret_form_token '] ?>">
  <input type="HIDDEN" name="id-car" value="<?php echo $id_car ?>">
  
    <DIV CLASS="col-lg-6 col-md-6 col-sm-6 col-xs-12">      
      
      <DIV CLASS="box box-primary">
        <DIV CLASS="box-header ">
          <STRONG>Veículo </STRONG>
        </DIV>
        <DIV CLASS="box-body">
        
          <DIV CLASS="form-group has-feedback">
            <LABEL FOR="car-marca"><SPAN CLASS="required">*</SPAN> Marca</LABEL>
            <DIV CLASS="msg-validation">
              <SELECT CLASS="form-control" NAME="car-marca" id="car-marca" REQUIRED>
                <OPTION VALUE="<?php echo (isset($marca)) ? $marca : '' ; ?>">Marca atual (<?php echo (isset($marca)) ? ucwords($marca) : '' ; ?>)</OPTION>
                <?php
                   echo  generateListBrands('car');
                ?>
              </SELECT>
            </DIV>
            <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
            <DIV CLASS="help-block with-errors"></DIV> 
          </DIV>
          
          <DIV CLASS="form-group has-feedback">
            <LABEL FOR="car-modelo"><SPAN CLASS="required">*</SPAN> Modelo</LABEL>
            <DIV CLASS="msg-validation">
              <SELECT CLASS="form-control" name="car-modelo" id="car-modelo" REQUIRED>
                <OPTION VALUE="<?php echo (isset($modelo)) ? $modelo : '' ; ?>">Modelo atual (<?php echo (isset($modelo)) ? ucwords($modelo) : '' ; ?>)</OPTION>
              </SELECT>
            </DIV>
            <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
            <DIV CLASS="help-block with-errors"></DIV>            
          </DIV>
          
          <div class="callout callout-info" role="alert">
              Caso não encontre a categoria ou a marca desejada nas opções acima, você pode cadastrá-la <a href="admin.php?page=options_vehicles">nestá página</a>.
          </div>
          
          <DIV CLASS="form-group has-feedback">
            <LABEL><SPAN CLASS="required">*</SPAN> Tipo de carroceria</LABEL>
            <div class="clearfix"></div>
            <div class="radio-inline">
                <div class="no-padding">
                  <label class="no-bold">
                    <input name="car-carroceria" value="hatch" type="radio" <?php echo (isset($carroceria) and $carroceria === 'hatch') ? 'checked' : '' ; ?> required>Hatchback (hatch ou HB)
                  </label>
                </div>
                
                <div class="no-padding">
                  <label class="no-bold">
                    <input name="car-carroceria" value="sedã" type="radio" <?php echo (isset($carroceria) and $carroceria === 'sedã') ? 'checked' : '' ; ?> required>Sedã
                  </label>
                </div>
                
              <div class="no-padding">
                <label class="no-bold">
                  <input name="car-carroceria" value="cupê" type="radio" <?php echo (isset($carroceria) and $carroceria === 'cupê') ? 'checked' : '' ; ?> required>Cupê
                </label>
              </div>
              
              <div class="no-padding">
                <label class="no-bold">
                  <input name="car-carroceria" value="monovolume" type="radio" <?php echo (isset($carroceria) and $carroceria === 'monovolume') ? 'checked' : '' ; ?> required>Monovolume ou minivan (MPV)
                </label>
              </div>
                
              <div class="no-padding">
                <label class="no-bold">
                  <input name="car-carroceria" value="vã" type="radio" <?php echo (isset($carroceria) and $carroceria === 'vã') ? 'checked' : '' ; ?> required>Vã
                </label>
              </div>
              
              <div class="no-padding">
                <label class="no-bold">
                  <input name="car-carroceria" value="furgão" type="radio" <?php echo (isset($carroceria) and $carroceria === 'furgão') ? 'checked' : '' ; ?> required>Furgão
                </label>
              </div>
              
              <div class="no-padding">
                <label class="no-bold">
                  <input name="car-carroceria" value="suv" type="radio" <?php echo (isset($carroceria) and $carroceria === 'suv') ? 'checked' : '' ; ?> required>SUV
               </label>
              </div>
              
              <div class="no-padding">
                <label class="no-bold">
                  <input name="car-carroceria" value="picape" type="radio" <?php echo (isset($carroceria) and $carroceria === 'picape') ? 'checked' : '' ; ?> required>Picape (Pick-up)
                </label>
              </div>
              
              <div class="no-padding">
                <label class="no-bold">
                  <input name="car-carroceria" value="jipe" type="radio" <?php echo (isset($carroceria) and $carroceria === 'jipe') ? 'checked' : '' ; ?> required>Jipe
                </label>
              </div>
                            
              <div class="no-padding">
                <label class="no-bold">
                  <input name="car-carroceria" value="esportivo" type="radio" <?php echo (isset($carroceria) and $carroceria === 'esportivo') ? 'checked' : '' ; ?> required>Esportivo
                </label>
              </div>
              
              <div class="no-padding">
                <label class="no-bold">
                  <input name="car-carroceria" value="crossover" type="radio" <?php echo (isset($carroceria) and $carroceria === 'crossover') ? 'checked' : '' ; ?> required>Crossover
                </label>
              </div>
              
              <div class="no-padding">
                <label class="no-bold">
                  <input name="car-carroceria" value="outro" type="radio" <?php echo (isset($carroceria) and $carroceria === 'outro') ? 'checked' : '' ; ?> required>Outro
                </label>
              </div>

            </div>
            <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
            <DIV CLASS="help-block with-errors"></DIV>            
          </DIV>
          
          <DIV CLASS="form-group has-feedback">
            <LABEL for="car-submodelo"><SPAN CLASS="required">*</SPAN> Versão</LABEL>
            <DIV CLASS="msg-validation">
              <input type="text" name="car-submodelo" class="form-control" value="<?php echo (isset($submodelo)) ? $submodelo : '' ; ?>" placeholder="Ex: 1.6 Highlife" required>
            </DIV>
            <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
            <DIV CLASS="help-block with-errors"></DIV> 
          </DIV>
          
          <DIV CLASS="row">
            <DIV CLASS="col-md-6 form-group has-feedback">
              <LABEL FOR="car-ano-fab"><SPAN CLASS="required">*</SPAN> Ano Fabricação</LABEL>
              <DIV CLASS="msg-validation">
                <SELECT CLASS="form-control" NAME="car-ano-fab" REQUIRED>
                  <OPTION VALUE="<?php echo (isset($ano_fab)) ? $ano_fab : '' ; ?>">Data atual (<?php echo (isset($ano_fab)) ? $ano_fab : '' ; ?>)</OPTION>
                  <?php  
                    echo listYears()
                  ?>
                 </SELECT>  
                </select>        
              </DIV>
              <DIV CLASS="help-block with-errors"></DIV> 
            </DIV>
            
            <DIV CLASS="col-md-6 form-group has-feedback">
              <LABEL FOR="car-ano-mod"><SPAN CLASS="required">*</SPAN> Ano Modelo</LABEL>
              <DIV CLASS="msg-validation">
                <SELECT CLASS="form-control" NAME="car-ano-mod" REQUIRED>
                  <OPTION VALUE="<?php echo (isset($ano_mod)) ? $ano_mod : '' ; ?>">Data atual (<?php echo (isset($ano_mod)) ? $ano_mod : '' ; ?>)</OPTION>
                  <?php  
                    echo listYears($ini, $fim)
                  ?>
                 </SELECT>      
              </DIV>
              <DIV CLASS="help-block with-errors"></DIV> 
            </DIV>
          </DIV>
          
          <DIV CLASS="form-group has-feedback">
            <LABEL FOR="car-valor"><SPAN CLASS="required">*</SPAN> Valor</LABEL>
            <DIV CLASS="msg-validation">
              <DIV CLASS="input-group">
                <SPAN CLASS="input-group-addon hidden-xs">$</SPAN>
                <input class="form-control" type="text" name="car-valor" placeholder="Exemplo: 120.435,00" data-control="mask-money" value="<?php echo (isset($valor)) ? decimalMoeda($valor) : '' ; ?>" REQUIRED>
              </DIV>
            </DIV>
            <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
            <DIV CLASS="help-block with-errors"></DIV> 
          </DIV>
     

        </DIV>
      </DIV><!--//.box-->
      
      <DIV CLASS="box box-primary">
        <DIV CLASS="box-header ">
          <STRONG>Características principais </STRONG>
        </DIV>
        <DIV CLASS="box-body">
        
          <DIV CLASS="form-group has-feedback">
            <LABEL for="car-km">Quilometragem </LABEL>
            <DIV CLASS="msg-validation">
              <input type="text" name="car-km" class="form-control" value="<?php echo (isset($km)) ? $km : '' ; ?>">
            </DIV>
            <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
            <DIV CLASS="help-block with-errors"></DIV> 
          </DIV>
          
          <DIV CLASS="form-group has-feddback">
            <LABEL for="car-cor"><SPAN CLASS="required">*</SPAN> Cor </LABEL>
            <DIV CLASS="msg-validation">
              <input type="text" name="car-cor" class="form-control" REQUIRED value="<?php echo (isset($cor)) ? ucwords($cor) : '' ; ?>">
             </DIV>
            <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
            <DIV CLASS="help-block with-errors"></DIV> 
          </DIV>
          
          <DIV CLASS="form-group has-feedback">
            <LABEL for="car-portas"><SPAN CLASS="required">*</SPAN> Número de portas  </LABEL>
            <DIV CLASS="msg-validation">
              <input type="RANGE" step="1" min="2" max="10" name="car-portas" class="form-control" id="car-portas" style="outline:none !important" required value="<?php echo (isset($portas)) ? $portas : '' ; ?>">
              <SPAN ID="range-car-portas">4 portas</SPAN>
            </DIV>
            <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
            <DIV CLASS="help-block with-errors"></DIV> 
          </DIV>
          
          <DIV CLASS="form-group has-feedback">
            <LABEL for="car-combustivel"><SPAN CLASS="required">*</SPAN> Combustível </LABEL>
            <DIV CLASS="msg-validation">
              <SELECT CLASS="form-control" NAME="car-combustivel" REQUIRED> 
                <OPTION VALUE="<?php echo (isset($combustivel)) ? $combustivel : '' ; ?>">Atual (<?php echo (isset($combustivel)) ? ucwords($combustivel) : '' ; ?>)</OPTION>
                <OPTION VALUE="etanol">Etanol</OPTION>
                <OPTION VALUE="gasolina">Gasolina</OPTION>
                <OPTION VALUE="diesel">Diesel</OPTION> 
                <OPTION VALUE="gnv">GNV</OPTION>
                <OPTION VALUE="flex">Flex</OPTION>      
              </SELECT>
             </DIV>
            <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
            <DIV CLASS="help-block with-errors"></DIV> 
          </DIV>
          
          <DIV CLASS="form-group has-feedback">
            <LABEL for="car-placa">Final da placa</LABEL>
            <DIV CLASS="msg-validation">
              <input type="text" name="car-placa" class="form-control" id="car-placa" value="<?php echo (isset($final_placa)) ? $final_placa : '' ; ?>">
            </DIV>
            <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
            <DIV CLASS="help-block with-errors"></DIV> 
          </DIV>
          
          <DIV CLASS="form-group has-feedback">
            <LABEL for="car-cambio"><SPAN CLASS="required">*</SPAN> Tipo Câmbio</LABEL>
            <DIV CLASS="msg-validation">
              <SELECT CLASS="form-control" NAME="car-cambio" REQUIRED> 
                <OPTION VALUE="<?php echo (isset($cambio)) ? $cambio : '' ; ?>">Valor atual (<?php echo (isset($cambio)) ? ucwords($cambio) : '' ; ?>)</OPTION>
                <OPTION VALUE="manual">Manual</OPTION>
                <OPTION VALUE="automático">Automático</OPTION>    
              </SELECT>
            </DIV>
            <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
            <DIV CLASS="help-block with-errors"></DIV> 
          </DIV>
          
        </DIV>
      </DIV><!--//.box-->
      
    </DIV><!--//.col 6 left -->
    
    
    <DIV CLASS="col-lg-6 col-md-6 col-sm-6 col-xs-12">

      <DIV CLASS="box box-primary">
        <DIV CLASS="box-header ">
          <STRONG>Opcionais</STRONG>
          <DIV CLASS="pull-right checkbox-inline">
            <LABEL class="no-bold">          
              <input type="checkbox" name="check-all-opcionais"  class="checkbox-inline" id="check-all-opcionais">Marcar todas   
            </LABEL>
          </DIV>
        </DIV>
        <DIV CLASS="box-body">
        
            <DIV CLASS="checkbox" ID="checkbox-opcionais">
            
              <DIV CLASS="col-md-6 no-padding">
                <LABEL FOR="car-airbag">
                  <input type="checkbox" name="car-airbag" id="car-airbag" <?php echo (isset($airbag)) ? $airbag : '' ; ?>>
                  Airbag
                </LABEL>
              </DIV>
              
              <DIV CLASS="col-md-6 no-padding">
                <LABEL FOR="car-ar-condicionado">
                  <input type="checkbox" name="car-ar-condicioando" id="car-ar-condicionado" <?php echo (isset($ar_condicionado)) ? $ar_condicionado : '' ; ?>>
                  Ar condicionado 
                </LABEL>
              </DIV>
              
              <DIV CLASS="col-md-6 no-padding">
                <LABEL FOR="car-direcao">
                  <input type="checkbox" name="car-direcao" id="car-direcao" <?php echo (isset($direcao)) ? $direcao : '' ; ?>>
                  Direção hidráulica 
                </LABEL>
              </DIV>
              
              <DIV CLASS="col-md-6 no-padding">
                <LABEL FOR="car-alarme">
                  <input type="checkbox" name="car-alarme" id="car-alarme" <?php echo (isset($alarme)) ? $alarme : '' ; ?>>
                  Alarme 
                </LABEL>
              </DIV>
              
              <DIV CLASS="col-md-6 no-padding">
                <LABEL FOR="car-cd-player">
                  <input type="checkbox" name="car-cd-player" id="car-cd-player" <?php echo (isset($cd_player)) ? $cd_player : '' ; ?>>
                  Cd player
                </LABEL>
              </DIV>
              
              <DIV CLASS="col-md-6 no-padding">
                <LABEL FOR="car-rodas">
                  <input type="checkbox" name="car-rodas" id="car-rodas" <?php echo (isset($rodas)) ? $rodas : '' ; ?>>
                  Rodas de liga leve
                </LABEL>
              </DIV>
              
              <DIV CLASS="col-md-6 no-padding">
                <LABEL FOR="car-travas">
                  <input type="checkbox" name="car-travas" id="car-travas" <?php echo (isset($travas)) ? $travas : '' ; ?>>
                  Travas elétricas
                </LABEL>
              </DIV>
              
              <DIV CLASS="col-md-6 no-padding">
                <LABEL FOR="car-vidros">
                  <input type="checkbox" name="car-vidros" id="car-vidros" <?php echo (isset($vidros)) ? $vidros : '' ; ?>>
                  Vidros elétricos
                </LABEL>
              </DIV>
              
              <DIV CLASS="col-md-6 no-padding">
                <LABEL FOR="car-abs">
                  <input type="checkbox" name="car-abs" id="car-abs" <?php echo (isset($abs)) ? $abs : '' ; ?>>
                  Freios ABS
                </LABEL>
              </DIV>
              
              <DIV CLASS="col-md-6 no-padding">
                <LABEL FOR="car-neblina">
                  <input type="checkbox" name="car-neblina" id="car-neblina" <?php echo (isset($farois_neblina)) ? $farois_neblina : '' ; ?>>
                  Faróis de neblina
                </LABEL>
              </DIV>
              
              <DIV CLASS="col-md-6 no-padding">
                <LABEL FOR="car-couro">
                  <input type="checkbox" name="car-couro" id="car-couro" <?php echo (isset($bancos_couro)) ? $bancos_couro : '' ; ?>>
                  Bancos em couro
                </LABEL>
              </DIV>
              
              <DIV CLASS="col-md-6 no-padding">
                <LABEL FOR="car-teto-solar">
                  <input type="checkbox" name="car-teto-solar" id="car-teto-solar" <?php echo (isset($teto_solar)) ? $teto_solar : '' ; ?>>
                  Teto solar
                </LABEL>
              </DIV>
              
              <DIV CLASS="col-md-6 no-padding">
                <LABEL FOR="car-camera">
                  <input type="checkbox" name="car-camera" id="car-camera" <?php echo (isset($camera)) ? $camera : '' ; ?>>
                  Câmera de ré
                </LABEL>
              </DIV>
              
              <DIV CLASS="col-md-6 no-padding">
                <LABEL FOR="car-estacionamento">
                  <input type="checkbox" name="car-estacionamento" id="car-estacionamento" <?php echo (isset($sensor_estacionamento)) ? $sensor_estacionamento : '' ; ?>>
                  Sensor de estacionamento
                </LABEL>
              </DIV>
              
              <DIV CLASS="col-md-6 no-padding">
                <LABEL FOR="car-chave-reserva">
                  <input type="checkbox" name="car-chave-reserva" id="car-chave-reserva" <?php echo (isset($chave_reserva)) ? $chave_reserva : '' ; ?>>
                  Chave reserva
                </LABEL>
              </DIV>
              
              <DIV CLASS="col-md-6 no-padding">
                <LABEL FOR="car-manual">
                  <input type="checkbox" name="car-manual" id="car-manual" <?php echo (isset($manual)) ? $manual : '' ; ?>>
                  Manual de instruções
                </LABEL>
              </DIV>
              
              <DIV CLASS="col-md-6 no-padding">
                <LABEL FOR="car-desembacador">
                  <input type="checkbox" name="car-desembacador" id="car-desembacador" <?php echo (isset($desembacador_traseiro)) ? $desembacador_traseiro : '' ; ?>>
                  Desembaçador traseiro
                </LABEL>
              </DIV>
              
              <DIV CLASS="col-md-6 no-padding">
                <LABEL FOR="car-computador">
                  <input type="checkbox" name="car-computador" id="car-computador" <?php echo (isset($computador)) ? $computador : '' ; ?>>
                  Computador de bordo
                </LABEL>
              </DIV>
              
              <DIV CLASS="col-md-6 no-padding">
                <LABEL FOR="car-ar-quente">
                  <input type="checkbox" name="car-ar-quente" id="car-ar-quente" <?php echo (isset($ar_quente)) ? $ar_quente : '' ; ?>>
                  Ar quente
                </LABEL>
              </DIV>
              
              <DIV CLASS="col-md-6 no-padding">
                <LABEL FOR="car-multimidia">
                  <input type="checkbox" name="car-multimidia" id="car-multimidia" <?php echo (isset($multimidia)) ? $multimidia : '' ; ?>>
                  Central Multimídia
                </LABEL>
              </DIV>
              
              <DIV CLASS="col-md-6 no-padding">
                <LABEL FOR="car-controles-volante">
                  <input type="checkbox" name="car-controles-volante" id="car-controles-volante" <?php echo (isset($controles_volante)) ? $controles_volante : '' ; ?>>
                  Controles no volante
                </LABEL>
              </DIV>
              
              <DIV CLASS="col-md-6 no-padding">
                <LABEL FOR="car-volante-regulagem">
                  <input type="checkbox" name="car-volante-regulagem" id="car-volante-regulagem" <?php echo (isset($volante_regulagem)) ? $volante_regulagem : '' ; ?>>
                  Volante com regulagem de altura
                </LABEL>
              </DIV>
              
              <DIV CLASS="col-md-6 no-padding">
                <LABEL FOR="car-retrovisor">
                  <input type="checkbox" name="car-retrovisor" id="car-retrovisor" <?php echo (isset($retrovisor)) ? $retrovisor : '' ; ?>>
                  Retrovisor elétrico
                </LABEL>
              </DIV>
              
              <DIV CLASS="col-md-6 no-padding">
                <LABEL FOR="car-gps">
                  <input type="checkbox" name="car-gps" id="car-gps" <?php echo (isset($gps)) ? $gps : '' ; ?>>
                  Gps
                </LABEL>
              </DIV>
              
              <DIV CLASS="col-md-6 no-padding">
                <LABEL FOR="car-capota">
                  <input type="checkbox" name="car-capota" id="car-capota" <?php echo (isset($capota)) ? $capota : '' ; ?>>
                  Capota marítima
                </LABEL>
              </DIV>
              
              <DIV CLASS="col-md-6 no-padding">
                <LABEL FOR="car-farois-regulagem">
                  <input type="checkbox" name="car-farois-regulagem" id="car-farois-regulagem" <?php echo (isset($farois_regulagem)) ? $farois_regulagem : '' ; ?>>
                  Faróis com regulagem
                </LABEL>
              </DIV>
              
              <DIV CLASS="col-md-6 no-padding">
                <LABEL FOR="car-sensor-chuva">
                  <input type="checkbox" name="car-sensor-chuva" id="car-sensor-chuva" <?php echo (isset($sensor_chuva)) ? $sensor_chuva : '' ; ?>>
                  Sensores de chuva
                </LABEL>
              </DIV>
              
              <DIV CLASS="col-md-6 no-padding">
                <LABEL FOR="car-brake-light">
                  <input type="checkbox" name="car-brake-light" id="car-brake-light" <?php echo (isset($brake_light)) ? $brake_light : '' ; ?>>
                  Brake Light (luzes de freio)
                </LABEL>
              </DIV>
              
              <DIV CLASS="col-md-6 no-padding">
                <LABEL FOR="car-limpador-traseiro">
                  <input type="checkbox" name="car-limpador-traseiro" id="car-limpador-traseiro" <?php echo (isset($limpador_traseiro)) ? $limpador_traseiro : '' ; ?>>
                  Limpador de vidros traseiro
                </LABEL>
              </DIV>
              
              <DIV CLASS="col-md-6 no-padding">
                <LABEL FOR="car-aerofolio">
                  <input type="checkbox" name="car-aerofolio" id="car-aerofolio" <?php echo (isset($aerofolio)) ? $aerofolio : '' ; ?>>
                  Aerofólio
                </LABEL>
              </DIV>
              
              <DIV CLASS="col-md-6 no-padding">
                <LABEL FOR="car-engate">
                  <input type="checkbox" name="car-engate" id="car-engate" <?php echo (isset($engate_traseiro)) ? $engate_traseiro : '' ; ?>>
                  Engate traseiro
                </LABEL>
              </DIV>
              
              <DIV CLASS="col-md-6 no-padding">
                <LABEL FOR="car-estribo">
                  <input type="checkbox" name="car-estribo" id="car-estribo" <?php echo (isset($estribo)) ? $estribo : '' ; ?>>
                  Estribo
                </LABEL>
              </DIV>
              
              <DIV CLASS="col-md-6 no-padding">
                <LABEL FOR="car-santoantonio">
                  <input type="checkbox" name="car-santoantonio" id="car-santoantonio" <?php echo (isset($santantonio)) ? $santantonio : '' ; ?>>
                  Santo Antônio
                </LABEL>
              </DIV>
              
              <DIV CLASS="col-md-6 no-padding">
                <LABEL FOR="car-mascara-negra">
                  <input type="checkbox" name="car-mascara-negra" id="car-mascara-negra" <?php echo (isset($mascara_negra)) ? $mascara_negra : '' ; ?>>
                  Faróis com mascára negra
                </LABEL>
              </DIV>
              
            </DIV><!--//.checkbox-->
            
        </DIV>
      </DIV><!--//.box-->
      
      <DIV CLASS="box box-primary">
        <DIV CLASS="box-header ">
          <STRONG>Outras informações </STRONG>
        </DIV>
        <DIV CLASS="box-body">
        
          <DIV CLASS="form-group has-feedback">
            <LABEL FOR="car-loja"><SPAN CLASS="required">*</SPAN> Local que se encontra o veículo</LABEL>
            <DIV CLASS="msg-validation">
              <SELECT CLASS="form-control" NAME="car-loja" REQUIRED>
                <OPTION VALUE="<?php echo (isset($local_loja)) ? $local_loja : '' ; ?>">Valor atual (<?php echo $cidade_loja ?>)</OPTION>
                <OPTION VALUE="loja1">Pérola D'oeste</OPTION>
                <OPTION VALUE="loja2">Realeza</OPTION>
              </SELECT>
            </DIV>
            <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
            <DIV CLASS="help-block with-errors"></DIV> 
          </DIV>
          
          <DIV CLASS="form-group has-feedback">
            <LABEL><SPAN CLASS="required">*</SPAN> Aceita-se troca?</LABEL>
            <div class="clearfix"></div>
            <div class="radio-inline">
                <div class="no-padding">
                  <label class="no-bold">
                    <input name="car-troca" value="sim" type="radio" required <?php echo (isset($troca) and $troca === 'sim') ? 'checked' : '' ; ?> >Sim
                  </label>
                </div>
             </div>
             <div class="radio-inline">
                <div class="no-padding">
                  <label class="no-bold label-inline">
                    <input name="car-troca" value="não" type="radio" required <?php echo (isset($troca) and $troca === 'não') ? 'checked' : '' ; ?>>Não
                  </label>
                </div>
            </div>
            <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
            <DIV CLASS="help-block with-errors"></DIV>            
          </DIV>
          
          <DIV CLASS="form-group has-feedback">
            <LABEL><SPAN CLASS="required">*</SPAN> Estado do veículo</LABEL>
            <div class="clearfix"></div>
            <div class="radio-inline">
                <div class="no-padding">
                  <label class="no-bold">
                    <input name="car-estado" value="novo" type="radio" required <?php echo (isset($estado) and $estado === 'novo') ? 'checked' : '' ; ?>>Novo
                  </label>
                </div>
             </div>
             <div class="radio-inline">
                <div class="no-padding">
                  <label class="no-bold label-inline">
                    <input name="car-estado" value="seminovo" type="radio" required <?php echo (isset($estado) and $estado === 'seminovo') ? 'checked' : '' ; ?>>Seminovo
                  </label>
                </div>
            </div>
            <div class="radio-inline">
                <div class="no-padding">
                  <label class="no-bold label-inline">
                    <input name="car-estado" value="usado" type="radio" required <?php echo (isset($estado) and $estado === 'usado') ? 'checked' : '' ; ?>>Usado
                  </label>
                </div>
            </div>
            <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
            <DIV CLASS="help-block with-errors"></DIV>            
          </DIV>
          
          <DIV CLASS="form-group has-feedback">
            <LABEL><SPAN CLASS="required">*</SPAN> Único dono?</LABEL>
            <div class="clearfix"></div>
            <div class="radio-inline">
                <div class="no-padding">
                  <label class="no-bold">
                    <input name="car-unico-dono" value="sim" type="radio" required <?php echo (isset($unico_dono) and $unico_dono === 'sim') ? 'checked' : '' ; ?>>Sim
                  </label>
                </div>
             </div>
             <div class="radio-inline">
                <div class="no-padding">
                  <label class="no-bold label-inline">
                    <input name="car-unico-dono" value="não" type="radio" required <?php echo (isset($unico_dono) and $unico_dono === 'não') ? 'checked' : '' ; ?>>Não
                  </label>
                </div>
            </div>
            <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
            <DIV CLASS="help-block with-errors"></DIV>            
          </DIV>

          
        </div>
      </DIV><!--//.box-->
      
      <DIV CLASS="box box-primary">
        <DIV CLASS="box-header ">
          <STRONG>Observações do vendedor </STRONG>
        </DIV>
        <DIV CLASS="box-body">
          <div class="callout callout-info" role="alert">
             É importante o preenchimento deste campo, esse é o texto que aparecerá nas postagens quando alguem compartilhar essa publicação nas redes sociais.
          </div>
          <DIV CLASS="form-group has-feedback">
            <LABEL>Detalhes</LABEL>
            <DIV CLASS="msg-validation">
              <TEXTAREA CLASS="form-control count-caractere" DATA-MAX-CARACTERE="1024" NAME="car-obs" ID="car-obs" STYLE="resize:none; min-height:160px !important"><?php echo (isset($obs)) ? $obs : '' ; ?></TEXTAREA>
            </DIV>
            <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
            <DIV CLASS="help-block with-errors"></DIV> 
            <DIV CLASS="restante-caractere"></DIV>
          </DIV>
          
        </DIV>
      </DIV><!--//.box-->   
      
    </DIV><!--//.col 6 right -->
   
   <DIV CLASS="clearfix"></DIV>
   
      <DIV CLASS="col-md-12">
        <button type="submit" class="btn btn-primary btn-flat" id="veiculo-post">Atualizar</button>
      </DIV>
      
    </form> 
    
    <DIV CLASS="clearfix space-20"></DIV>
    
  </DIV>
</SECTION> 

<script src="/app/javascript/generatePreviewsImages.js" DEFER ASYNC></script>
<script src="/plugins/bootstrap-validator-master/dist/validator.min.js"></script>
<script src="/app/javascript/vehicle.js"></script>
<script src="/plugins/input-mask/jquery.maskMoney.js"></script>
<script type="text/javascript">
$(function(){
  $('[data-control="mask-money"]').maskMoney({
    symbol:'R$ ', 
    showSymbol:false,
    thousands:'.', 
    decimal:',', 
    symbolStay: false,
  });
})
</script>

