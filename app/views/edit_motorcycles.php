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
    $id_motorcycle = filterString(base64_decode($_GET['id']), 'INT');
}

$con_db = new config\connect_db();
$con = $con_db->connect();

$vehicle = $con->prepare("SELECT `marca`, `modelo`, `submodelo`, `ano_fab`, `ano_mod`, `combustivel`, `estilo`, `km`, `cor`, `final_placa`, `obs`, `local_loja`, `unico_dono`, `troca`, `estado`, `valor`, `rodas`, `abs`, `chave_reserva`, `manual`, `computador`, `injecao`, `disco`, `disco_dianteiro`, `disco_traseiro`, `porta_objetos`, `amortecedor_direcao`, `escapamento`, `partida`, `bau`, `neblina`  FROM `motorcycles` LEFT JOIN `motorcycles_optional` ON (`motorcycles`.`id` = `motorcycles_optional`.`id_motorcycle`) WHERE `motorcycles`.`id` = ? ");
$vehicle->bind_param('i', $id_motorcycle);
$vehicle->execute();
$vehicle->store_result();
$vehicle->bind_result($marca, $modelo, $submodelo, $ano_fab, $ano_mod, $combustivel, $estilo, $km, $cor, $final_placa, $obs, $local_loja, $unico_dono, $troca, $estado, $valor, $rodas, $abs, $chave_reserva, $manual, $computador, $injecao, $disco, $disco_dianteiro, $disco_traseiro, $porta_objetos, $amortecedor_direcao, $escapamento, $partida, $bau, $neblina );
$vehicle->fetch();
$rows = $vehicle->affected_rows;
$vehicle->free_result();
$vehicle->close();

if( $vehicle and $rows <= 0 )
{
    die('<script>location.href="/app/admin.php?page=404";</script>');
}

$rodas = isChecked($rodas);
$abs = isChecked($abs);
$chave_reserva = isChecked($chave_reserva);
$manual = isChecked($manual);
$computador = isChecked($computador);
$injecao = isChecked($injecao);
$disco = isChecked($disco);
$disco_dianteiro = isChecked($disco_dianteiro);
$disco_traseiro = isChecked($disco_traseiro);
$porta_objetos = isChecked($porta_objetos);
$amortecedor_direcao = isChecked($amortecedor_direcao);
$escapamento = isChecked($escapamento);
$partida = isChecked($partida);
$bau = isChecked($bau);
$neblina = isChecked($neblina);

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

echo $estilo;

?>
<SECTION CLASS="row">
    <div class="container">
        <div class="col-md-12">
            <H4 CLASS="text-darkgray"><STRONG>Adicionar Carros</STRONG></H4>
            <OL CLASS="breadcrumb bg-white">
                <LI><a href="admin.php"><I CLASS="fa fa-home"></I></a></LI>
                <LI><a href="admin.php?page=vehicles"><I CLASS="fa fa-car"></I> Veículos</a></LI>
                <LI><a href="admin.php?page=edit_motorcycles&id=<?php echo base64_encode($id_motorcycle) ?>"><I CLASS="fa fa-motorcycle"></I> <?php echo $marca.' '.$modelo.' '.$submodelo ?></a></LI>
            </OL>
        </div>
    </div>
</SECTION>

<DIV CLASS="space30"></DIV>

<SECTION CLASS="row">
  <DIV CLASS="container">
  
  <form name="form-edit-motorcycle" method="POST" id="form-edit-motorcycle" action="/app/modules/vehicles/update_motorcycle.php" role="form" DATA-TOGGLE="validator" enctype="application/x-www-form-urlencoded" data-action="submit-ajax" data-form-reset="noreset">
  
  <input type="HIDDEN" name="user_id" value="<?php echo $_SESSION['user_id'] ?>">
  <input type="HIDDEN" name="form-token" value="<?php echo$_SESSION['secret_form_token '] ?>">
  <input type="HIDDEN" name="id-motorcycle" value="<?php echo $id_motorcycle ?>">
  
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
                <OPTION VALUE="<?php echo (isset($marca)) ? $marca : '' ; ?>">Marca atual (<?php echo (isset($marca)) ? ucwords($marca) : '' ; ?>)</OPTION>
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
            <LABEL><SPAN CLASS="required">*</SPAN> Estilo da moto</LABEL>
            <div class="clearfix"></div>
            <div class="row">
            
                <div CLASS="col-md-6">
                  <label class="no-bold">
                    <input name="motorcycle-estilo" value="ciclomotor" type="radio" <?php echo (isset($estilo) and $estilo === 'ciclomotor') ? 'checked' : '' ; ?> required> Ciclomotor
                  </label>
                </div>
                
                <div CLASS="col-md-6">
                  <label class="no-bold">
                    <input name="motorcycle-estilo" value="motociclo" type="radio" <?php echo (isset($estilo) and $estilo === 'motociclo') ? 'checked' : '' ; ?> required> Motociclos 
                  </label>
                </div>
                
              <div CLASS="col-md-6">
                <label class="no-bold">
                  <input name="motorcycle-estilo" value="scooter" type="radio" <?php echo (isset($estilo) and $estilo === 'scooter') ? 'checked' : '' ; ?> required> Scooter ou Vespa
                </label>
              </div>
              
              <div CLASS="col-md-6">
                <label class="no-bold">
                  <input name="motorcycle-estilo" value="sport" type="radio" <?php echo (isset($estilo) and $estilo === 'sport') ? 'checked' : '' ; ?> required> Sport
                </label>
              </div>
                
              <div CLASS="col-md-6">
                <label class="no-bold">
                  <input name="motorcycle-estilo" value="custom" type="radio" <?php echo (isset($estilo) and $estilo === 'custom') ? 'checked' : '' ; ?> required> Custom
                </label>
              </div>
              
              <div CLASS="col-md-6">
                <label class="no-bold">
                  <input name="motorcycle-estilo" value="roadster" type="radio" <?php echo (isset($estilo) and $estilo === 'roadster') ? 'checked' : '' ; ?> required> Roadsters
                </label>
              </div>
              
              <div CLASS="col-md-6">
                <label class="no-bold">
                  <input name="motorcycle-estilo" value="chopper" type="radio" <?php echo (isset($estilo) and $estilo === 'chopper') ? 'checked' : '' ; ?> required> Chopper
               </label>
              </div>
              
              <div CLASS="col-md-6">
                <label class="no-bold">
                  <input name="motorcycle-estilo" value="naked" type="radio" <?php echo (isset($estilo) and $estilo === 'naked') ? 'checked' : '' ; ?> required> Naked
                </label>
              </div>
              
              <div CLASS="col-md-6">
                <label class="no-bold">
                  <input name="motorcycle-estilo" value="off-road" type="radio" <?php echo (isset($estilo) and $estilo === 'off-road') ? 'checked' : '' ; ?> required> Off-road
                </label>
              </div>
                            
              <div CLASS="col-md-6">
                <label class="no-bold">
                  <input name="motorcycle-estilo" value="motard" type="radio" <?php echo (isset($estilo) and $estilo === 'motard') ? 'checked' : '' ; ?> required> Motard
                </label>
              </div>
              
              <div CLASS="col-md-6">
                <label class="no-bold">
                  <input name="motorcycle-estilo" value="pocketbike" type="radio" <?php echo (isset($estilo) and $estilo === 'pocketbike') ? 'checked' : '' ; ?> required> Pocketbike
                </label>
              </div>
              
              <div CLASS="col-md-6">
                <label class="no-bold">
                  <input name="motorcycle-estilo" value="street" type="radio" <?php echo (isset($estilo) and $estilo === 'street') ? 'checked' : '' ; ?> required> Street
                </label>
              </div>
              
              <div CLASS="col-md-6">
                <label class="no-bold">
                  <input name="motorcycle-estilo" value="underbone" type="radio" <?php echo (isset($estilo) and $estilo === 'underbone') ? 'checked' : '' ; ?> required> Underbone
                </label>
              </div>
              
              <div CLASS="col-md-6">
                <label class="no-bold">
                  <input name="motorcycle-estilo" value="baby" type="radio" <?php echo (isset($estilo) and $estilo === 'baby') ? 'checked' : '' ; ?> required> Baby
                </label>
              </div>

            </div>
            <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
            <DIV CLASS="help-block with-errors"></DIV>            
          </DIV>
          
          <DIV CLASS="form-group has-feedback">
            <LABEL for="motorcycle-submodelo"><SPAN CLASS="required">*</SPAN> Submodelo</LABEL>
            <DIV CLASS="msg-validation">
              <input type="text" name="motorcycle-submodelo" class="form-control" placeholder="Titan" required value="<?php echo (isset($submodelo)) ? $submodelo : '' ; ?>">
            </DIV>
            <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
            <DIV CLASS="help-block with-errors"></DIV> 
          </DIV>
          
          <DIV CLASS="row">
            <DIV CLASS="col-md-6 form-group has-feedback">
              <LABEL FOR="motorcycle-ano-fab"><SPAN CLASS="required">*</SPAN> Ano Fabricação</LABEL>
              <DIV CLASS="msg-validation">
                <SELECT CLASS="form-control" NAME="motorcycle-ano-fab" REQUIRED>
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
              <LABEL FOR="motorcycle-ano-mod"><SPAN CLASS="required">*</SPAN> Ano Modelo</LABEL>
              <DIV CLASS="msg-validation">
                <SELECT CLASS="form-control" NAME="motorcycle-ano-mod" REQUIRED>
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
            <LABEL FOR="motorcycle-valor"><SPAN CLASS="required">*</SPAN> Valor</LABEL>
            <DIV CLASS="msg-validation">
              <DIV CLASS="input-group">
                <SPAN CLASS="input-group-addon hidden-xs">$</SPAN>
                <input class="form-control" type="text" name="motorcycle-valor" placeholder="Exemplo: 120.435,00" data-control="mask-money" REQUIRED value="<?php echo (isset($valor)) ? decimalMoeda($valor) : '' ; ?>">
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
              <input type="text" name="motorcycle-km" class="form-control" value="<?php echo (isset($km)) ? $km : '' ; ?>">
            </DIV>
            <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
            <DIV CLASS="help-block with-errors"></DIV> 
          </DIV>
          
          <DIV CLASS="form-group has-feddback">
            <LABEL for="motorcycle-cor"><SPAN CLASS="required">*</SPAN> Cor </LABEL>
            <DIV CLASS="msg-validation">
              <input type="text" name="motorcycle-cor" class="form-control" REQUIRED value="<?php echo (isset($cor)) ? ucwords($cor) : '' ; ?>">
             </DIV>
            <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
            <DIV CLASS="help-block with-errors"></DIV> 
          </DIV>
          
          <DIV CLASS="form-group has-feedback">
            <LABEL for="motorcycle-combustivel"><SPAN CLASS="required">*</SPAN> Combustível </LABEL>
            <DIV CLASS="msg-validation">
              <SELECT CLASS="form-control" NAME="motorcycle-combustivel" REQUIRED> 
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
            <LABEL for="motorcycle-placa">Final da placa</LABEL>
            <DIV CLASS="msg-validation">
              <input type="text" name="motorcycle-placa" class="form-control" value="<?php echo (isset($final_placa)) ? $final_placa : '' ; ?>">
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
                  <input type="checkbox" name="motorcycle-rodas" <?php echo (isset($rodas)) ? $rodas : '' ; ?>>
                  Rodas de liga leve
                </label>
              </div>
     
              <div CLASS="col-md-6 no-padding">
                <label FOR="motorcycle-abs">
                  <input type="checkbox" name="motorcycle-abs" <?php echo (isset($abs)) ? $abs : '' ; ?>>
                  Freios ABS
                </label>
              </div>
              
              <div CLASS="col-md-6 no-padding">
                <label FOR="motorcycle-chave-reserva">
                  <input type="checkbox" name="motorcycle-chave-reserva" <?php echo (isset($chave_reserva)) ? $chave_reserva : '' ; ?>>
                  Chave reserva
                </label>
              </div>
              
              <div CLASS="col-md-6 no-padding">
                <label FOR="motorcycle-manual">
                  <input type="checkbox" name="motorcycle-manual" <?php echo (isset($manual)) ? $manual : '' ; ?>>
                  Manual de instruções
                </label>
              </div>
              
              <div CLASS="col-md-6 no-padding">
                <label FOR="motorcycle-computador">
                  <input type="checkbox" name="motorcycle-computador"  <?php echo (isset($computador)) ? $computador : '' ; ?>>
                  Computador de bordo
                </label>
              </div>
              
              <div CLASS="col-md-6 no-padding">
                <label FOR="motorcycle-injecao">
                  <input type="checkbox" name="motorcycle-injecao" <?php echo (isset($injecao)) ? $injecao : '' ; ?>>
                  Injeção Eletronica
                </label>
              </div>
              
              <div CLASS="col-md-6 no-padding">
                <label FOR="motorcycle-freio-disco-dianteiro">
                  <input type="checkbox" name="motorcycle-freio-dianteiro" <?php echo (isset($disco_dianteiro)) ? $disco_dianteiro : '' ; ?>>
                  Freio dianteiro a disco
                </label>
              </div>
              
              <div CLASS="col-md-6 no-padding">
                <label FOR="motorcycle-freio-disco-traseiro">
                  <input type="checkbox" name="motorcycle-freio-traseiro" <?php echo (isset($disco_traseiro)) ? $disco_traseiro : '' ; ?>>
                  Freio traseiro a disco
                </label>
              </div>
              
              <div CLASS="col-md-6 no-padding">
                <label FOR="motorcycle-freio-disco">
                  <input type="checkbox" name="motorcycle-freio-disco" <?php echo (isset($disco)) ? $disco : '' ; ?>>
                  Freio a disco nas 2 rodas
                </label>
              </div>
              
              <div CLASS="col-md-6 no-padding">
                <label FOR="motorcycle-objetos">
                  <input type="checkbox" name="motorcycle-objetos" <?php echo (isset($porta_objetos)) ? $porta_objetos : '' ; ?>>
                  Porta objetos
                </label>
              </div>
              
              <div CLASS="col-md-6 no-padding">
                <label FOR="motorcycle-amortecedor">
                  <input type="checkbox" name="motorcycle-amortecedor" <?php echo (isset($amortecedor_direcao)) ? $amortecedor_direcao : '' ; ?>>
                  Amortecedor de direção
                </label>
              </div>
              
              <div CLASS="col-md-6 no-padding">
                <label FOR="motorcycle-escapamento">
                  <input type="checkbox" name="motorcycle-escapamento" <?php echo (isset($escapamento)) ? $escapamento : '' ; ?>>
                  Escapamento esportivo
                </label>
              </div>
              
              <div CLASS="col-md-6 no-padding">
                <label FOR="motorcycle-partida">
                  <input type="checkbox" name="motorcycle-partida" <?php echo (isset($partida)) ? $partida : '' ; ?>>
                  Partida Elétrica
                </label>
              </div>
              
              <div CLASS="col-md-6 no-padding">
                <label FOR="motorcycle-bau">
                  <input type="checkbox" name="motorcycle-bau" <?php echo (isset($bau)) ? $bau : '' ; ?>>
                  Báu
                </label>
              </div>
              
              <div CLASS="col-md-6 no-padding">
                <label FOR="motorcycle-neblina">
                  <input type="checkbox" name="motorcycle-neblina" <?php echo (isset($neblina)) ? $neblina : '' ; ?>>
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
                    <input name="motorcycle-troca" value="sim" type="radio" required <?php echo (isset($troca) and $troca === 'sim') ? 'checked' : '' ; ?>>Sim
                  </label>
                </div>
             </div>
             <div class="radio-inline">
                <div class="no-padding">
                  <label class="no-bold label-inline">
                    <input name="motorcycle-troca" value="não" type="radio" required <?php echo (isset($troca) and $troca === 'não') ? 'checked' : '' ; ?>>Não
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
                    <input name="motorcycle-estado" value="novo" type="radio" required <?php echo (isset($estado) and $estado === 'novo') ? 'checked' : '' ; ?>>Novo
                  </label>
                </div>
             </div>
             <div class="radio-inline">
                <div class="no-padding">
                  <label class="no-bold label-inline">
                    <input name="motorcycle-estado" value="seminovo" type="radio" required <?php echo (isset($estado) and $estado === 'seminovo') ? 'checked' : '' ; ?>>Semi-novo
                  </label>
                </div>
            </div>
            <div class="radio-inline">
                <div class="no-padding">
                  <label class="no-bold label-inline">
                    <input name="motorcycle-estado" value="usado" type="radio" required <?php echo (isset($estado) and $estado === 'usado') ? 'checked' : '' ; ?>>Usado
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
                    <input name="motorcycle-unico-dono" value="sim" type="radio" required <?php echo (isset($unico_dono) and $unico_dono === 'sim') ? 'checked' : '' ; ?>>Sim
                  </label>
                </div>
             </div>
             <div class="radio-inline">
                <div class="no-padding">
                  <label class="no-bold label-inline">
                    <input name="motorcycle-unico-dono" value="não" type="radio" required <?php echo (isset($unico_dono) and $unico_dono === 'não') ? 'checked' : '' ; ?>>Não
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
              <TEXTAREA CLASS="form-control count-caractere" DATA-MAX-CARACTERE="1024" NAME="motorcycle-obs" ID="motorcycle-obs" STYLE="resize:none; min-height:160px !important"><?php echo (isset($obs)) ? $obs : '' ; ?></TEXTAREA>
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
      <button type="submit" class="btn btn-primary btn-flat">Atualizar</button>
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

