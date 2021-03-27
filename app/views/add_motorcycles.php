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
            <H4 CLASS="text-darkgray"><STRONG>Adicionar Motos</STRONG></H4>
            <OL CLASS="breadcrumb bg-white">
                <LI><a href="admin.php"><I CLASS="fa fa-home"></I></a></LI>
                <LI><a href="admin.php?page=vehicles"><I CLASS="fa fa-car"></I> Veículos</a></LI>
                <LI><a href="admin.php?page=add_motorcycles"><I CLASS="fa fa-motorcycle"></I> Adicionar Motos</a></LI>
            </OL>
        </div>
    </div>
</SECTION>

<DIV CLASS="space30"></DIV>

<SECTION CLASS="row">
  <DIV CLASS="container">
  
  <form name="form-motorcycle" method="POST" id="form-motorcycle" action="/app/modules/vehicles/insert_motorcycle.php" role="form" DATA-TOGGLE="validator" enctype="application/x-www-form-urlencoded" data-form-reset="reset">
  
  <input type="HIDDEN" name="user_id" value="<?php echo $_SESSION['user_id'] ?>">
  <input type="HIDDEN" name="form-token" value="<?php echo$_SESSION['secret_form_token '] ?>">
  
    <DIV CLASS="col-lg-6 col-md-6 col-sm-6 col-xs-12">      
      
      <DIV CLASS="box box-primary">
        <DIV CLASS="box-header ">
          <STRONG>Veículo </STRONG>
        </DIV>
        <DIV CLASS="box-body">
        
          <DIV CLASS="form-group has-feedback">
            <LABEL FOR="motorcycle-marca"><SPAN CLASS="required">*</SPAN> Marca</LABEL>
            <DIV CLASS="msg-validation">
              <SELECT CLASS="form-control" NAME="motorcycle-marca" id="motorcycle-marca" REQUIRED>
                <OPTION VALUE="">Escolha uma marca</OPTION>
                <?php
                   echo  generateListBrands('motorcycle');
                ?>
              </SELECT>
            </DIV>
            <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
            <DIV CLASS="help-block with-errors"></DIV> 
          </DIV>
          
          <DIV CLASS="form-group has-feedback">
            <LABEL FOR="motorcycle-modelo"><SPAN CLASS="required">*</SPAN> Modelo</LABEL>
            <DIV CLASS="msg-validation">
              <SELECT CLASS="form-control" name="motorcycle-modelo" id="motorcycle-modelo" REQUIRED>
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
            <LABEL><SPAN CLASS="required">*</SPAN> Estilo da moto</LABEL>
            <div class="clearfix"></div>
            <div class="row">
            
                <div CLASS="col-md-6">
                  <label class="no-bold">
                    <input name="motorcycle-estilo" value="ciclomotor" type="radio" required> Ciclomotor
                  </label>
                </div>
                
                <div CLASS="col-md-6">
                  <label class="no-bold">
                    <input name="motorcycle-estilo" value="motociclo " type="radio" required> Motociclos 
                  </label>
                </div>
                
              <div CLASS="col-md-6">
                <label class="no-bold">
                  <input name="motorcycle-estilo" value="scooter" type="radio" required> Scooter ou Vespa
                </label>
              </div>
              
              <div CLASS="col-md-6">
                <label class="no-bold">
                  <input name="motorcycle-estilo" value="sport" type="radio" required> Sport
                </label>
              </div>
                
              <div CLASS="col-md-6">
                <label class="no-bold">
                  <input name="motorcycle-estilo" value="custom" type="radio" required> Custom
                </label>
              </div>
              
              <div CLASS="col-md-6">
                <label class="no-bold">
                  <input name="motorcycle-estilo" value="roadster" type="radio" required> Roadsters
                </label>
              </div>
              
              <div CLASS="col-md-6">
                <label class="no-bold">
                  <input name="motorcycle-estilo" value="chopper" type="radio" required> Chopper
               </label>
              </div>
              
              <div CLASS="col-md-6">
                <label class="no-bold">
                  <input name="motorcycle-estilo" value="naked" type="radio" required> Naked
                </label>
              </div>
              
              <div CLASS="col-md-6">
                <label class="no-bold">
                  <input name="motorcycle-estilo" value="off-road" type="radio" required> Off-road
                </label>
              </div>
                            
              <div CLASS="col-md-6">
                <label class="no-bold">
                  <input name="motorcycle-estilo" value="motard" type="radio" required> Motard
                </label>
              </div>
              
              <div CLASS="col-md-6">
                <label class="no-bold">
                  <input name="motorcycle-estilo" value="pocketbike" type="radio" required> Pocketbike
                </label>
              </div>
              
              <div CLASS="col-md-6">
                <label class="no-bold">
                  <input name="motorcycle-estilo" value="street" type="radio" required> Street
                </label>
              </div>
              
              <div CLASS="col-md-6">
                <label class="no-bold">
                  <input name="motorcycle-estilo" value="underbone" type="radio" required> Underbone
                </label>
              </div>
              
              <div CLASS="col-md-6">
                <label class="no-bold">
                  <input name="motorcycle-estilo" value="baby" type="radio" required> Baby
                </label>
              </div>

            </div>
            <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
            <DIV CLASS="help-block with-errors"></DIV>            
          </DIV>
          
          <DIV CLASS="form-group has-feedback">
            <LABEL for="motorcycle-submodelo"><SPAN CLASS="required">*</SPAN> Submodelo</LABEL>
            <DIV CLASS="msg-validation">
              <input type="text" name="motorcycle-submodelo" class="form-control" placeholder="Titan" required>
            </DIV>
            <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
            <DIV CLASS="help-block with-errors"></DIV> 
          </DIV>
          
          <DIV CLASS="row">
            <DIV CLASS="col-md-6 form-group has-feedback">
              <LABEL FOR="motorcycle-ano-fab"><SPAN CLASS="required">*</SPAN> Ano Fabricação</LABEL>
              <DIV CLASS="msg-validation">
                <SELECT CLASS="form-control" NAME="motorcycle-ano-fab" REQUIRED>
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
              <LABEL FOR="motorcycle-ano-mod"><SPAN CLASS="required">*</SPAN> Ano Modelo</LABEL>
              <DIV CLASS="msg-validation">
                <SELECT CLASS="form-control" NAME="motorcycle-ano-mod" REQUIRED>
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
            <LABEL FOR="motorcycle-valor"><SPAN CLASS="required">*</SPAN> Valor</LABEL>
            <DIV CLASS="msg-validation">
              <DIV CLASS="input-group">
                <SPAN CLASS="input-group-addon hidden-xs">$</SPAN>
                <input class="form-control" type="text" name="motorcycle-valor" placeholder="Exemplo: 120.435,00" data-control="mask-money" REQUIRED>
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
            <LABEL for="motorcycle-km">Quilometragem </LABEL>
            <DIV CLASS="msg-validation">
              <input type="text" name="motorcycle-km" class="form-control">
            </DIV>
            <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
            <DIV CLASS="help-block with-errors"></DIV> 
          </DIV>
          
          <DIV CLASS="form-group has-feddback">
            <LABEL for="motorcycle-cor"><SPAN CLASS="required">*</SPAN> Cor </LABEL>
            <DIV CLASS="msg-validation">
              <input type="text" name="motorcycle-cor" class="form-control" REQUIRED>
             </DIV>
            <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
            <DIV CLASS="help-block with-errors"></DIV> 
          </DIV>
          
          <DIV CLASS="form-group has-feedback">
            <LABEL for="motorcycle-combustivel"><SPAN CLASS="required">*</SPAN> Combustível </LABEL>
            <DIV CLASS="msg-validation">
              <SELECT CLASS="form-control" NAME="motorcycle-combustivel" REQUIRED> 
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
            <LABEL for="motorcycle-placa">Final da placa</LABEL>
            <DIV CLASS="msg-validation">
              <input type="text" name="motorcycle-placa" class="form-control">
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
            
              <div CLASS="col-md-6 no-padding">
                <label FOR="motorcycle-rodas">
                  <input type="checkbox" name="motorcycle-rodas">
                  Rodas de liga leve
                </label>
              </div>
     
              <div CLASS="col-md-6 no-padding">
                <label FOR="motorcycle-abs">
                  <input type="checkbox" name="motorcycle-abs">
                  Freios ABS
                </label>
              </div>
              
              <div CLASS="col-md-6 no-padding">
                <label FOR="motorcycle-chave-reserva">
                  <input type="checkbox" name="motorcycle-chave-reserva">
                  Chave reserva
                </label>
              </div>
              
              <div CLASS="col-md-6 no-padding">
                <label FOR="motorcycle-manual">
                  <input type="checkbox" name="motorcycle-manual">
                  Manual de instruções
                </label>
              </div>
              
              <div CLASS="col-md-6 no-padding">
                <label FOR="motorcycle-computador">
                  <input type="checkbox" name="motorcycle-computador">
                  Computador de bordo
                </label>
              </div>
              
              <div CLASS="col-md-6 no-padding">
                <label FOR="motorcycle-injecao">
                  <input type="checkbox" name="motorcycle-injecao">
                  Injeção Eletronica
                </label>
              </div>
              
              <div CLASS="col-md-6 no-padding">
                <label FOR="motorcycle-freio-disco-dianteiro">
                  <input type="checkbox" name="motorcycle-freio-dianteiro">
                  Freio dianteiro a disco
                </label>
              </div>
              
              <div CLASS="col-md-6 no-padding">
                <label FOR="motorcycle-freio-disco-traseiro">
                  <input type="checkbox" name="motorcycle-freio-traseiro">
                  Freio traseiro a disco
                </label>
              </div>
              
              <div CLASS="col-md-6 no-padding">
                <label FOR="motorcycle-freio-disco">
                  <input type="checkbox" name="motorcycle-freio-disco">
                  Freio a disco nas 2 rodas
                </label>
              </div>
              
              <div CLASS="col-md-6 no-padding">
                <label FOR="motorcycle-objetos">
                  <input type="checkbox" name="motorcycle-objetos">
                  Porta objetos
                </label>
              </div>
              
              <div CLASS="col-md-6 no-padding">
                <label FOR="motorcycle-amortecedor">
                  <input type="checkbox" name="motorcycle-amortecedor">
                  Amortecedor de direção
                </label>
              </div>
              
              <div CLASS="col-md-6 no-padding">
                <label FOR="motorcycle-escapamento">
                  <input type="checkbox" name="motorcycle-escapamento">
                  Escapamento esportivo
                </label>
              </div>
              
              <div CLASS="col-md-6 no-padding">
                <label FOR="motorcycle-partida">
                  <input type="checkbox" name="motorcycle-partida">
                  Partida Elétrica
                </label>
              </div>
              
              <div CLASS="col-md-6 no-padding">
                <label FOR="motorcycle-bau">
                  <input type="checkbox" name="motorcycle-bau">
                  Báu
                </label>
              </div>
              
              <div CLASS="col-md-6 no-padding">
                <label FOR="motorcycle-neblina">
                  <input type="checkbox" name="motorcycle-neblina">
                  Faróis de neblina
                </label>
              </div>
              
            </DIV><!--//.checkbox-->
            
        </DIV>
      </DIV><!--//.box-->
      
      <DIV CLASS="box box-primary">
        <DIV CLASS="box-header ">
          <STRONG>Outras informações </STRONG>
        </DIV>
        <DIV CLASS="box-body">
        
          <DIV CLASS="form-group has-feedback">
            <LABEL FOR="motorcycle-loja"><SPAN CLASS="required">*</SPAN> Local que se encontra o veículo</LABEL>
            <DIV CLASS="msg-validation">
              <SELECT CLASS="form-control" NAME="motorcycle-loja" REQUIRED>
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
                    <input name="motorcycle-troca" value="sim" type="radio" required>Sim
                  </label>
                </div>
             </div>
             <div class="radio-inline">
                <div class="no-padding">
                  <label class="no-bold label-inline">
                    <input name="motorcycle-troca" value="não" type="radio" required>Não
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
                    <input name="motorcycle-estado" value="novo" type="radio" required>Novo
                  </label>
                </div>
             </div>
             <div class="radio-inline">
                <div class="no-padding">
                  <label class="no-bold label-inline">
                    <input name="motorcycle-estado" value="seminovo" type="radio" required>Semi-novo
                  </label>
                </div>
            </div>
            <div class="radio-inline">
                <div class="no-padding">
                  <label class="no-bold label-inline">
                    <input name="motorcycle-estado" value="usado" type="radio" required>Usado
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
                    <input name="motorcycle-unico-dono" value="sim" type="radio" required>Sim
                  </label>
                </div>
             </div>
             <div class="radio-inline">
                <div class="no-padding">
                  <label class="no-bold label-inline">
                    <input name="motorcycle-unico-dono" value="não" type="radio" required>Não
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
              <TEXTAREA CLASS="form-control count-caractere" DATA-MAX-CARACTERE="1024" NAME="motorcycle-obs" ID="motorcycle-obs" STYLE="resize:none; min-height:160px !important"><?php echo signatureObsVehicles(); ?></TEXTAREA>
            </DIV>
            <DIV CLASS="restante-caractere"></DIV>
            <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
            <DIV CLASS="help-block with-errors"></DIV>  
          </DIV>
          
        </DIV>
      </DIV><!--//.box-->   
      
    </DIV><!--//.col 6 right -->
   
   <DIV CLASS="clearfix"></DIV>
   
     <DIV CLASS="col-md-12">
      <button type="submit" class="btn btn-primary btn-flat" id="veiculo-post" ><I CLASS="fa fa-check" ARIA-HIDDEN="true"></I>
 Publicar</button>
      </DIV>
      
    </form> 
    
    <DIV CLASS="clearfix space-20"></DIV>
    
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

