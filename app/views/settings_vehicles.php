<?php
use config\connect_db;
use app\controls\errors;

$errors = new errors();

if( ($_SESSION['user_master_perms'] !== 'administrador') )
{
    if($_SESSION['vehicles_read'] !== '1')
    {
        die ('<script>location.href="/app/admin.php?page=access_denied";</script>');
    }
}
?>
<SECTION CLASS="row">
    <div class="container">
        <div class="col-md-12">
            <H4 CLASS="text-darkgray"><STRONG>Configurações</STRONG></H4>
            <OL CLASS="breadcrumb bg-white">
                <LI><a href="admin.php"><I CLASS="fa fa-home"></I></a></LI>
                <LI><a href="admin.php?page=stock_vehicles"><I CLASS="fa fa-car"></I> Veículos</a></LI>
                <LI><a href="admin.php?page=settings_vehicles"><I CLASS="fa fa-cogs"></I> Configurações</a></LI>
            </OL>
        </div>
    </div>
</SECTION>

<DIV CLASS="space30"></DIV>

<SECTION CLASS="row">
    <DIV CLASS="container">
  
        <DIV CLASS="col-lg-6 col-md-6 col-sm-6 col-xs-12">      
      
            <DIV CLASS="box box-primary">
                <form name="form-veiculo-nova-marca" method="POST" id="form-veiculo-nova-marca" action="/app/modules/vehicles/register_mark.php" role="form" DATA-TOGGLE="validator" enctype="application/x-www-form-urlencoded" data-action="submit-ajax" data-form-reset="reset">
                
                <input type="HIDDEN" name="user_id" value="<?php echo $_SESSION['user_id'] ?>">
                <input type="HIDDEN" name="form-token" value="<?php echo$_SESSION['secret_form_token '] ?>">
  
                <DIV CLASS="box-header ">
                  <STRONG>Cadastrar nova marca </STRONG>
                </DIV>
                <DIV CLASS="box-body">
                
                    <DIV CLASS="form-group has-feedback">
                        <LABEL FOR="veiculo-categoria"><SPAN CLASS="required">*</SPAN> Categoria</LABEL>
                        <DIV CLASS="msg-validation">
                            <SELECT CLASS="form-control" NAME="veiculo-categoria" ID="veiculo-categoria" REQUIRED>
                                <OPTION VALUE="">Escolha uma categoria</OPTION>
                                <OPTION VALUE="car">Carro</OPTION>
                                <OPTION VALUE="motorcycle">Moto</OPTION>
                                <OPTION VALUE="truck">Caminhão</OPTION>
                            </SELECT>
                        </DIV>
                        <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
                        <DIV CLASS="help-block with-errors"></DIV> 
                    </DIV>
                  
                    <DIV CLASS="form-group has-feedback">
                        <LABEL FOR="veiculo-nova-marca"><SPAN CLASS="required">*</SPAN> Nome da nova marca</LABEL>
                        <DIV CLASS="msg-validation">
                            <input type="text" class="form-control" name="veiculo-nova-marca" id="veiculo-nova-marca" REQUIRED>
                        </DIV>
                        <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
                        <DIV CLASS="help-block with-errors"></DIV>            
                    </DIV>

                </DIV>
                <div class="box-footer">                 
                    <button type="submit" class="btn btn-primary btn-flat">Cadastrar</button>
                </div>
                
                </form>
            </DIV><!--//.box-->
            
            <DIV CLASS="box box-primary">
                <form name="form-veiculo-nova-marca" method="POST" id="form-veiculo-nova-marca" action="/app/modules/vehicles/update_signature.php" role="form" DATA-TOGGLE="validator" enctype="application/x-www-form-urlencoded" data-action="submit-ajax" data-form-reset="reset">
  
                <input type="HIDDEN" name="user_id" value="<?php echo $_SESSION['user_id'] ?>">
                <input type="HIDDEN" name="form-token" value="<?php echo$_SESSION['secret_form_token '] ?>">
  
                <DIV CLASS="box-header ">
                  <STRONG>Observação do vendedor padrão. </STRONG>
                </DIV>
                <DIV CLASS="box-body">

                    <DIV CLASS="form-group has-feedback">
                        <LABEL FOR="veiculo-signature"><SPAN CLASS="required">*</SPAN> Assinatura</LABEL>
                        <img src="/app/images/icons/pupover-ico.png" CLASS="pull-right ico-help" ARIA-HIDDEN="true"  DATA-PLACEMENT="auto" DATA-TOGGLE="popover" DATA-CONTAINER="body" DATA-HTML="true" DATA-TRIGGER="focus" TITLE="<strong>Ajuda</strong>" DATA-CONTENT='Texto padrão para exibição caso não seja preenchido o campo "Obervações do vendedor".'>
                        <DIV CLASS="msg-validation">
                            <TEXTAREA CLASS="form-control count-caractere" DATA-MAX-CARACTERE="1024" NAME="veiculo-signature" ID="veiculo-signature" STYLE="resize:none; min-height:200px !important"><?php echo signatureObsVehicles();?></TEXTAREA>
                        </DIV>
                        <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
                        <DIV CLASS="help-block with-errors"></DIV> 
                        <DIV CLASS="restante-caractere"></DIV>           
                    </DIV>

                </DIV>
                <div class="box-footer">                 
                    <button type="submit" class="btn btn-primary btn-flat">Alterar</button>
                </div>
                
                </form>
            </DIV><!--//.box-->
      
      
        </DIV><!--//.col 6 right -->
        
        <DIV CLASS="col-lg-6 col-md-6 col-sm-6 col-xs-12">      
      
            <DIV CLASS="box box-primary">
                <form name="form-veiculo-novo-modelo" method="POST" id="form-veiculo-novo-modelo" action="/app/modules/vehicles/register_model.php" role="form" DATA-TOGGLE="validator" enctype="application/x-www-form-urlencoded" data-action="submit-ajax">
  
                <input type="HIDDEN" name="user_id" value="<?php echo $_SESSION['user_id'] ?>">
                <input type="HIDDEN" name="form-token" value="<?php echo$_SESSION['secret_form_token '] ?>">
  
                <DIV CLASS="box-header ">
                  <STRONG>Cadastrar novo modelo </STRONG>
                </DIV>
                <DIV CLASS="box-body">
                
                    <DIV CLASS="form-group has-feedback">
                        <LABEL FOR="veiculo-categoria-2"><SPAN CLASS="required">*</SPAN> Categoria</LABEL>
                        <DIV CLASS="msg-validation">
                            <SELECT CLASS="form-control" NAME="veiculo-categoria-2" ID="veiculo-categoria-2" data-action="select_brand" REQUIRED>
                                <OPTION VALUE="">Escolha uma categoria</OPTION>
                                <OPTION VALUE="car">Carros</OPTION>
                                <OPTION VALUE="motorcycle">Motos</OPTION>
                                <OPTION VALUE="truck">Caminhões</OPTION>
                            </SELECT>
                        </DIV>
                        <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
                        <DIV CLASS="help-block with-errors"></DIV> 
                    </DIV>
                    
                    <DIV CLASS="form-group has-feedback">
                        <LABEL FOR="veiculo-marca-2"><SPAN CLASS="required">*</SPAN> Marca</LABEL>
                        <DIV CLASS="msg-validation">
                            <SELECT CLASS="form-control" NAME="veiculo-marca-2" ID="veiculo-marca-2" data-action="select_model" REQUIRED>
                                <OPTION VALUE="">Escolha uma categoria para carregar as marcas</OPTION>
                            </SELECT>
                        </DIV>
                        <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
                        <DIV CLASS="help-block with-errors"></DIV> 
                    </DIV>
                  
                    <DIV CLASS="form-group has-feedback">
                        <LABEL FOR="veiculo-modelo"><SPAN CLASS="required">*</SPAN> Nome do novo modelo</LABEL>
                        <DIV CLASS="msg-validation">
                            <input type="text" class="form-control" name="veiculo-modelo" id="veiculo-modelo" REQUIRED>
                        </DIV>
                        <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
                        <DIV CLASS="help-block with-errors"></DIV>            
                    </DIV>
                    

                </DIV>
                <div class="box-footer">                 
                    <button type="submit" class="btn btn-primary btn-flat">Cadastrar</button>
                </div>
                
                </form>
            </DIV><!--//.box-->
      
      
        </DIV><!--//.col 6 right -->
   
        <DIV CLASS="clearfix"></DIV>
    
    </DIV>
</SECTION> 


<script src="/plugins/bootstrap-validator-master/dist/validator.min.js"></script>
<script src="/app/javascript/vehicle.js"></script>