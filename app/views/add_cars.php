<?php
use config\connect_db;

if( ($_SESSION['user_master_perms'] !== 'administrador') )
{
    if( $_SESSION['vehicles_read'] !== '1' )
    {
        die ('<script>location.href="/app/admin.php?page=access_denied";</script>');
    }
}
?>
<SECTION CLASS="row">
    <div class="container">
        <div class="col-md-12">
            <H4 CLASS="text-darkgray"><STRONG>Adicionar Carros</STRONG></H4>
            <OL CLASS="breadcrumb bg-white">
                <LI><a href="admin.php"><I CLASS="fa fa-home"></I></a></LI>
                <LI><a href="admin.php?page=vehicles"><I CLASS="fa fa-car"></I> Veículos</a></LI>
                <LI><a href="admin.php?page=add_cars"><I CLASS="fa fa-car"></I> Adicionar Carros</a></LI>
            </OL>
        </div>
    </div>
</SECTION>

<DIV CLASS="space30"></DIV>

<SECTION CLASS="row">
  <DIV CLASS="container">
  
  <form name="form-car" method="POST" id="form-car" action="/app/modules/vehicles/insert_car.php" role="form" DATA-TOGGLE="validator" enctype="application/x-www-form-urlencoded" data-form-reset="reset">
  
  <input type="HIDDEN" name="user_id" value="<?php echo $_SESSION['user_id'] ?>">
  <input type="HIDDEN" name="form-token" value="<?php echo $_SESSION['secret_form_token'] ?>">
  
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
                <OPTION VALUE="">Escolha uma marca</OPTION>
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
                <OPTION VALUE="">Selecione uma marca para continuar</OPTION>
              </SELECT>
            </DIV>
            <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
            <DIV CLASS="help-block with-errors"></DIV>            
          </DIV>
          
          <div class="callout callout-info" role="alert">
              Caso não encontre a categoria ou a marca desejada nas opções acima, você pode cadastrá-la <a href="admin.php?page=settings_vehicles">nestá página</a>.
          </div>
          
          <DIV CLASS="form-group has-feedback">
            <LABEL><SPAN CLASS="required">*</SPAN> Tipo de carroceria</LABEL>
            <div class="clearfix"></div>
            <div class="radio-inline">
                <div class="no-padding">
                  <label class="no-bold">
                    <input name="car-carroceria" value="hatch" type="radio" required>Hatchback (hatch ou HB)
                  </label>
                </div>
                
                <div class="no-padding">
                  <label class="no-bold">
                    <input name="car-carroceria" value="sedã" type="radio" required>Sedã
                  </label>
                </div>
                
              <div class="no-padding">
                <label class="no-bold">
                  <input name="car-carroceria" value="cupê" type="radio" required>Cupê
                </label>
              </div>
              
              <div class="no-padding">
                <label class="no-bold">
                  <input name="car-carroceria" value="monovolume" type="radio" required>Monovolume ou minivan (MPV)
                </label>
              </div>
                
              <div class="no-padding">
                <label class="no-bold">
                  <input name="car-carroceria" value="vã" type="radio" required>Vã
                </label>
              </div>
              
              <div class="no-padding">
                <label class="no-bold">
                  <input name="car-carroceria" value="furgão" type="radio" required>Furgão
                </label>
              </div>
              
              <div class="no-padding">
                <label class="no-bold">
                  <input name="car-carroceria" value="suv" type="radio" required>SUV
               </label>
              </div>
              
              <div class="no-padding">
                <label class="no-bold">
                  <input name="car-carroceria" value="picape" type="radio" required>Picape (Pick-up)
                </label>
              </div>
              
              <div class="no-padding">
                <label class="no-bold">
                  <input name="car-carroceria" value="jipe" type="radio" required>Jipe
                </label>
              </div>
                            
              <div class="no-padding">
                <label class="no-bold">
                  <input name="car-carroceria" value="esportivo" type="radio" required>Esportivo
                </label>
              </div>
              
              <div class="no-padding">
                <label class="no-bold">
                  <input name="car-carroceria" value="crossover" type="radio" required>Crossover
                </label>
              </div>
              
              <div class="no-padding">
                <label class="no-bold">
                  <input name="car-carroceria" value="outro" type="radio" required>Outro
                </label>
              </div>

            </div>
            <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
            <DIV CLASS="help-block with-errors"></DIV>            
          </DIV>
          
          <DIV CLASS="form-group has-feedback">
            <LABEL for="car-submodelo"><SPAN CLASS="required">*</SPAN> Versão</LABEL>
            <DIV CLASS="msg-validation">
              <input type="text" name="car-submodelo" class="form-control" placeholder="Ex: 1.6 Highlife" required>
            </DIV>
            <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
            <DIV CLASS="help-block with-errors"></DIV> 
          </DIV>
          
          <DIV CLASS="row">
            <DIV CLASS="col-md-6 form-group has-feedback">
              <LABEL FOR="car-ano-fab"><SPAN CLASS="required">*</SPAN> Ano Fabricação</LABEL>
              <DIV CLASS="msg-validation">
                <SELECT CLASS="form-control" NAME="car-ano-fab" REQUIRED>
                  <OPTION VALUE="">Informe a data</OPTION>
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
                  <OPTION VALUE="">Informe a data</OPTION>
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
                <input class="form-control" type="text" name="car-valor" placeholder="Exemplo: 120.435,00" data-control="mask-money" REQUIRED>
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
              <input type="text" name="car-km" class="form-control">
            </DIV>
            <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
            <DIV CLASS="help-block with-errors"></DIV> 
          </DIV>
          
          <DIV CLASS="form-group has-feddback">
            <LABEL for="car-cor"><SPAN CLASS="required">*</SPAN> Cor </LABEL>
            <DIV CLASS="msg-validation">
              <input type="text" name="car-cor" class="form-control" REQUIRED>
             </DIV>
            <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
            <DIV CLASS="help-block with-errors"></DIV> 
          </DIV>
          
          <DIV CLASS="form-group has-feedback">
            <LABEL for="car-portas"><SPAN CLASS="required">*</SPAN> Número de portas  </LABEL>
            <DIV CLASS="msg-validation">
              <input type="RANGE" step="1" value="4" min="2" max="10" name="car-portas" class="form-control" id="car-portas" style="outline:none !important" required>
              <SPAN ID="range-car-portas">4 portas</SPAN>
            </DIV>
            <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
            <DIV CLASS="help-block with-errors"></DIV> 
          </DIV>
          
          <DIV CLASS="form-group has-feedback">
            <LABEL for="car-combustivel"><SPAN CLASS="required">*</SPAN> Combustível </LABEL>
            <DIV CLASS="msg-validation">
              <SELECT CLASS="form-control" NAME="car-combustivel" REQUIRED> 
                <OPTION VALUE="">......</OPTION>
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
              <input type="text" name="car-placa" class="form-control" id="car-placa">
            </DIV>
            <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
            <DIV CLASS="help-block with-errors"></DIV> 
          </DIV>
          
          <DIV CLASS="form-group has-feedback">
            <LABEL for="car-cambio"><SPAN CLASS="required">*</SPAN> Tipo Câmbio</LABEL>
            <DIV CLASS="msg-validation">
              <SELECT CLASS="form-control" NAME="car-cambio" REQUIRED> 
                <OPTION VALUE="">......</OPTION>
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
              <input type="checkbox" name="check-all-opcionais" class="checkbox-inline" id="check-all-opcionais">Marcar todas               
            </LABEL>
          </DIV>
        </DIV>
        <DIV CLASS="box-body">
        
            <DIV CLASS="checkbox" ID="checkbox-opcionais">
            
              <DIV CLASS="col-md-6 no-padding">
                <LABEL FOR="car-airbag">
                  <input type="checkbox" name="car-airbag">
                  Airbag
                </LABEL>
              </DIV>
              
              <DIV CLASS="col-md-6 no-padding">
                <LABEL FOR="car-ar-condicionado">
                  <input type="checkbox" name="car-ar-condicioando">
                  Ar condicionado 
                </LABEL>
              </DIV>
              
              <DIV CLASS="col-md-6 no-padding">
                <LABEL FOR="car-direcao">
                  <input type="checkbox" name="car-direcao">
                  Direção hidráulica
                </LABEL>
              </DIV>
              
              <DIV CLASS="col-md-6 no-padding">
                <LABEL FOR="car-alarme">
                  <input type="checkbox" name="car-alarme">
                  Alarme 
                </LABEL>
              </DIV>
              
              <DIV CLASS="col-md-6 no-padding">
                <LABEL FOR="car-cd-player">
                  <input type="checkbox" name="car-cd-player">
                  Cd player
                </LABEL>
              </DIV>
              
              <DIV CLASS="col-md-6 no-padding">
                <LABEL FOR="car-rodas">
                  <input type="checkbox" name="car-rodas">
                  Rodas de liga leve
                </LABEL>
              </DIV>
              
              <DIV CLASS="col-md-6 no-padding">
                <LABEL FOR="car-travas">
                  <input type="checkbox" name="car-travas">
                  Travas elétricas
                </LABEL>
              </DIV>
              
              <DIV CLASS="col-md-6 no-padding">
                <LABEL FOR="car-vidros">
                  <input type="checkbox" name="car-vidros">
                  Vidros elétricos
                </LABEL>
              </DIV>
              
              <DIV CLASS="col-md-6 no-padding">
                <LABEL FOR="car-abs">
                  <input type="checkbox" name="car-abs">
                  Freios ABS
                </LABEL>
              </DIV>
              
              <DIV CLASS="col-md-6 no-padding">
                <LABEL FOR="car-neblina">
                  <input type="checkbox" name="car-neblina">
                  Faróis de neblina
                </LABEL>
              </DIV>
              
              <DIV CLASS="col-md-6 no-padding">
                <LABEL FOR="car-couro">
                  <input type="checkbox" name="car-couro">
                  Bancos em couro
                </LABEL>
              </DIV>
              
              <DIV CLASS="col-md-6 no-padding">
                <LABEL FOR="car-teto-solar">
                  <input type="checkbox" name="car-teto-solar">
                  Teto solar
                </LABEL>
              </DIV>
              
              <DIV CLASS="col-md-6 no-padding">
                <LABEL FOR="car-camera" CLASS="checkbox-inline">
                  <input type="checkbox" name="car-camera">
                  Câmera de ré
                </LABEL>
              </DIV>
              
              <DIV CLASS="col-md-6 no-padding">
                <LABEL FOR="car-estacionamento">
                  <input type="checkbox" name="car-estacionamento">
                  Sensor de estacionamento
                </LABEL>
              </DIV>
              
              <DIV CLASS="col-md-6 no-padding">
                <LABEL FOR="car-chave-reserva">
                  <input type="checkbox" name="car-chave-reserva">
                  Chave reserva
                </LABEL>
              </DIV>
              
              <DIV CLASS="col-md-6 no-padding">
                <LABEL FOR="car-manual">
                  <input type="checkbox" name="car-manual">
                  Manual de instruções
                </LABEL>
              </DIV>
              
              <DIV CLASS="col-md-6 no-padding">
                <LABEL FOR="car-desembacador">
                  <input type="checkbox" name="car-desembacador">
                  Desembaçador traseiro
                </LABEL>
              </DIV>
              
              <DIV CLASS="col-md-6 no-padding">
                <LABEL FOR="car-computador">
                  <input type="checkbox" name="car-computador">
                  Computador de bordo
                </LABEL>
              </DIV>
              
              <DIV CLASS="col-md-6 no-padding">
                <LABEL FOR="car-ar-quente">
                  <input type="checkbox" name="car-ar-quente">
                  Ar quente
                </LABEL>
              </DIV>
              
              <DIV CLASS="col-md-6 no-padding">
                <LABEL FOR="car-multimidia">
                  <input type="checkbox" name="car-multimidia">
                  Central Multimídia
                </LABEL>
              </DIV>
              
              <DIV CLASS="col-md-6 no-padding">
                <LABEL FOR="car-controles-volante">
                  <input type="checkbox" name="car-controles-volante">
                  Controles no volante
                </LABEL>
              </DIV>
              
              <DIV CLASS="col-md-6 no-padding">
                <LABEL FOR="car-volante-regulagem">
                  <input type="checkbox" name="car-volante-regulagem">
                  Volante com regulagem de altura
                </LABEL>
              </DIV>
              
              <DIV CLASS="col-md-6 no-padding">
                <LABEL FOR="car-retrovisor">
                  <input type="checkbox" name="car-retrovisor">
                  Retrovisor elétrico
                </LABEL>
              </DIV>
              
              <DIV CLASS="col-md-6 no-padding">
                <LABEL FOR="car-gps">
                  <input type="checkbox" name="car-gps">
                  Gps
                </LABEL>
              </DIV>
              
              <DIV CLASS="col-md-6 no-padding">
                <LABEL FOR="car-capota">
                  <input type="checkbox" name="car-capota">
                  Capota marítima
                </LABEL>
              </DIV>
              
              <DIV CLASS="col-md-6 no-padding">
                <LABEL FOR="car-farois-regulagem">
                  <input type="checkbox" name="car-farois-regulagem">
                  Faróis com regulagem
                </LABEL>
              </DIV>
              
              <DIV CLASS="col-md-6 no-padding">
                <LABEL FOR="car-sensor-chuva">
                  <input type="checkbox" name="car-sensor-chuva">
                  Sensores de chuva
                </LABEL>
              </DIV>
              
              <DIV CLASS="col-md-6 no-padding">
                <LABEL FOR="car-brake-light">
                  <input type="checkbox" name="car-brake-light">
                  Brake Light (luzes de freio)
                </LABEL>
              </DIV>
              
              <DIV CLASS="col-md-6 no-padding">
                <LABEL FOR="car-limpador-traseiro">
                  <input type="checkbox" name="car-limpador-traseiro">
                  Limpador de vidros traseiro
                </LABEL>
              </DIV>
              
              <DIV CLASS="col-md-6 no-padding">
                <LABEL FOR="car-aerofolio">
                  <input type="checkbox" name="car-aerofolio">
                  Aerofólio
                </LABEL>
              </DIV>
              
              <DIV CLASS="col-md-6 no-padding">
                <LABEL FOR="car-engate">
                  <input type="checkbox" name="car-engate">
                  Engate traseiro
                </LABEL>
              </DIV>
              
              <DIV CLASS="col-md-6 no-padding">
                <LABEL FOR="car-estribo">
                  <input type="checkbox" name="car-estribo">
                  Estribo
                </LABEL>
              </DIV>
              
              <DIV CLASS="col-md-6 no-padding">
                <LABEL FOR="car-santoantonio">
                  <input type="checkbox" name="car-santoantonio">
                  Santo Antônio
                </LABEL>
              </DIV>
              
              <DIV CLASS="col-md-6 no-padding">
                <LABEL FOR="car-mascara-negra">
                  <input type="checkbox" name="car-mascara-negra">
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
                <OPTION VALUE="">Informe a loja</OPTION>
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
                    <input name="car-troca" value="sim" type="radio" required>Sim
                  </label>
                </div>
             </div>
             <div class="radio-inline">
                <div class="no-padding">
                  <label class="no-bold label-inline">
                    <input name="car-troca" value="não" type="radio" required>Não
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
                    <input name="car-estado" value="novo" type="radio" required>Novo
                  </label>
                </div>
             </div>
             <div class="radio-inline">
                <div class="no-padding">
                  <label class="no-bold label-inline">
                    <input name="car-estado" value="seminovo" type="radio" required>Semi-novo
                  </label>
                </div>
            </div>
            <div class="radio-inline">
                <div class="no-padding">
                  <label class="no-bold label-inline">
                    <input name="car-estado" value="usado" type="radio" required>Usado
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
                    <input name="car-unico-dono" value="sim" type="radio" required>Sim
                  </label>
                </div>
             </div>
             <div class="radio-inline">
                <div class="no-padding">
                  <label class="no-bold label-inline">
                    <input name="car-unico-dono" value="não" type="radio" required>Não
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
              <TEXTAREA CLASS="form-control count-caractere" DATA-MAX-CARACTERE="1024" NAME="car-obs" ID="car-obs" STYLE="resize:none; min-height:160px !important"><?php echo signatureObsVehicles(); ?></TEXTAREA>
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
      <button type="submit" class="btn btn-primary btn-flat" id="veiculo-post">Publicar</button>
      </DIV>
      
    </form> 
    
    <DIV CLASS="clearfix space30"></DIV>
    
    <DIV CLASS="clearfix col-md-12" ID="return-ajax-insert-car"></DIV>
    
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

