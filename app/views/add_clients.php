<?php
if( $_SESSION['user_type'] == 'administrador' or $_SESSION['user_type'] == 'suporte' or isset($_SESSION['clients_create']))
{
?>
<link href="/plugins/datepicker/datepicker3.css" rel="stylesheet" type="text/css">

<SECTION CLASS="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <H4 CLASS="text-darkgray"><STRONG>Cadastrar Clientes</STRONG></H4>
            <OL CLASS="breadcrumb bg-white">
                <LI><a href="/app/admin/home"><I CLASS="fa fa-home"></I></a></LI>
                <LI><a href="/app/admin/clients"><i class="fa fa-users"></i> Clientes</a></LI>
            </OL>
        </div>
    </div>
</SECTION>

<DIV CLASS="space30"></DIV>

<SECTION CLASS="container-fluid">
    <DIV CLASS="row">
  
    <form name="form-add-clients" method="POST" id="form-add-clients" action="/app/modules/clients/insert_clients.php" role="form" DATA-TOGGLE="validator" enctype="APPLICATION/X-WWW-FORM-URLENCODED" NOVALIDATE data-action="submit-ajax" data-form-reset="reset" autocomplete="off">

        <input type="HIDDEN" name="user_id" value="<?php echo $_SESSION['user_id'] ?>">
        <input type="HIDDEN" name="form-token" value="<?php echo $_SESSION['secret_form_token'] ?>">
  
        <DIV CLASS="col-lg-6 col-md-6 col-sm-6 col-xs-12">  
        
            <DIV CLASS="box box-solid">
                <DIV CLASS="box-header ">
                        <STRONG>Informações Principais</STRONG>
                </DIV>
                <DIV CLASS="box-body">
                
                    <DIV CLASS="form-group has-feedback">
                        <LABEL FOR="client-type"><SPAN CLASS="required">*</SPAN> Tipo de cliente:</LABEL>
                        <DIV CLASS="msg-validation">
                            <SELECT CLASS="form-control" NAME="client-type" ID="client-type" REQUIRED>
                                <OPTION VALUE="">Escolha o tipo de cliente</OPTION>
                                <OPTION VALUE="physical">Pessoa Fisica</OPTION>
                                <OPTION VALUE="juridical">Pessoa Jurídica</OPTION>
                            </SELECT>
                        </DIV>
                        <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
                        <DIV CLASS="help-block with-errors"></DIV> 
                    </DIV>
                    
                    <div class="alert alert-info alert-dismissible visible-xs">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h4><i class="icon fa fa-info"></i> Atenção</h4>
                        Caso tenha escolhido cadastrar uma pessoa jurídica, continue o preenchimento na parte indicada!
                    </div>
                    
                </DIV>
            </DIV> <!--//.box -->
            
             <DIV CLASS="box box-solid" id="inputs-person-juridical">
                <DIV CLASS="box-header ">
                    <STRONG>Caso o cliente seja pessoa jurídica, preencha aqui</STRONG>
                </DIV>
                <DIV CLASS="box-body">
                    
                    <div class="row">   
                        <DIV CLASS="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <DIV CLASS="form-group has-feedback">
                                <LABEL FOR="client-social-name">Razão social:</LABEL>
                                <DIV CLASS="msg-validation">
                                    <input type="text" class="form-control" name="client-social-name" id="client-social-name">
                                </DIV>
                                <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
                                <DIV CLASS="help-block with-errors"></DIV>            
                            </DIV>
                        </DIV>
                        <DIV CLASS="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <DIV CLASS="form-group has-feedback">
                                <LABEL FOR="client-cnpj">CNPJ:</LABEL>
                                <DIV CLASS="msg-validation">
                                    <input type="text" class="form-control" name="client-cnpj" id="client-cnpj" pattern="[0-9]{2}.?[0-9]{3}.?[0-9]{3}/?[0-9]{4}-?[0-9]{2}" data-control="mask-cnpj">
                                </DIV>
                                <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
                                <DIV CLASS="help-block with-errors"></DIV>            
                            </DIV>
                        </DIV> 
                    </div>
                    
                    <div class="row"> 
                        <DIV CLASS="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <DIV CLASS="form-group has-feedback">
                                <LABEL FOR="client-fantasy-name"><SPAN CLASS="required">*</SPAN> Nome fantasia:</LABEL>
                                <DIV CLASS="msg-validation">
                                    <input type="text" class="form-control" name="client-fantasy-name" id="client-fantasy-name" data-control="input-juridical" REQUIRED>
                                </DIV>
                                <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
                                <DIV CLASS="help-block with-errors"></DIV>            
                            </DIV>
                        </DIV>
                        <DIV CLASS="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <DIV CLASS="form-group has-feedback">
                                <LABEL FOR="client-responsible">Nome do responsável:</LABEL>
                                <DIV CLASS="msg-validation">
                                    <input type="text" class="form-control" name="client-responsible" id="client-responsible">
                                </DIV>
                                <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
                                <DIV CLASS="help-block with-errors"></DIV>            
                            </DIV>
                        </DIV>
                     </DIV>
                    
                    
                </DIV>
            </DIV> <!--//.box -->
            
            <DIV CLASS="box box-solid" id="inputs-person-physical">
                <DIV CLASS="box-header ">
                    <STRONG>Caso o cliente seja pessoa fisica, preencha aqui</STRONG>
                </DIV>
                <DIV CLASS="box-body">
                    
                    <div class="row">   
                        <DIV CLASS="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <DIV CLASS="form-group has-feedback">
                                <LABEL FOR="client-name"><SPAN CLASS="required">*</SPAN> Nome:</LABEL>
                                <DIV CLASS="msg-validation">
                                    <input type="text" class="form-control" name="client-name" id="client-name" data-control="input-physical" REQUIRED>
                                </DIV>
                                <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
                                <DIV CLASS="help-block with-errors"></DIV>            
                            </DIV>
                        </DIV>
                        
                        <DIV CLASS="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <DIV CLASS="form-group has-feedback">
                                <LABEL FOR="client-last-name"><SPAN CLASS="required">*</SPAN> Sobrenome:</LABEL>
                                <DIV CLASS="msg-validation">
                                    <input type="text" class="form-control" name="client-last-name" id="client-last-name" data-control="input-physical" REQUIRED>
                                </DIV>
                                <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
                                <DIV CLASS="help-block with-errors"></DIV>            
                            </DIV>
                        </DIV>
                        
                    </div>
                     
                    <div class="row"> 
                        <DIV CLASS="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <DIV CLASS="form-group has-feedback">
                                <LABEL FOR="client-birth-date">Data de nascimento:</LABEL>
                                <DIV CLASS="msg-validation">
                                  <DIV CLASS="input-group date input-group-sm" data-control="datepicker">
                                      <SPAN CLASS="input-group-addon hidden-xs"><i class="fa fa-calendar" style="height: 10px !important;"></i></SPAN>
                                      <input type="text" class="form-control date" name="client-birth-date" id="client-birth-date" data-control="mask-date" data-control="datepicker">
                                  </DIV>
                                </DIV>
                                <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
                                <DIV CLASS="help-block with-errors"></DIV>            
                            </DIV>
                        </DIV>
                        
                        <DIV CLASS="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <DIV CLASS="form-group has-feedback">
                                <LABEL FOR="client-genre"><SPAN CLASS="required">*</SPAN> Sexo:</LABEL>
                                <div class="clearfix"></div>
                                <div class="radio-inline">
                                    <label>
                                      <input name="client-genre" value="male" type="radio" data-control="input-physical" required>
                                      Masculino
                                    </label>
                                </div>
                                <div class="radio-inline">
                                    <label>
                                      <input name="client-genre" value="female" type="radio" data-control="input-physical" required>
                                      Feminino
                                    </label>
                                </div>
                                <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
                                <DIV CLASS="help-block with-errors"></DIV>            
                            </DIV>
                        </DIV>       
                    </div>
                    
                    <div class="row"> 
                        <DIV CLASS="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <DIV CLASS="form-group has-feedback">
                                <LABEL FOR="client-cpf">CPF:</LABEL>
                                <DIV CLASS="msg-validation">
                                    <input type="text" class="form-control" name="client-cpf" id="client-cpf" pattern="[0-9]{3}.?[0-9]{3}.?[0-9]{3}-?[0-9]{2}" data-control="mask-cpf">
                                </DIV>
                                <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
                                <DIV CLASS="help-block with-errors"></DIV>            
                            </DIV>
                        </DIV> 
                        <DIV CLASS="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <DIV CLASS="form-group has-feedback">
                                <LABEL FOR="client-rg">RG:</LABEL>
                                <DIV CLASS="msg-validation">
                                    <input type="text" class="form-control" name="client-rg" id="client-rg" pattern="[0-9]+$">
                                </DIV>
                                <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
                                <DIV CLASS="help-block with-errors"></DIV>            
                            </DIV>
                        </DIV>
                     </DIV>
                     
                     <div class="row"> 
                        <DIV CLASS="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <DIV CLASS="form-group has-feedback">
                                <LABEL FOR="client-marital-status"> Estado civil:</LABEL>
                                <DIV CLASS="msg-validation">
                                    <SELECT CLASS="form-control" NAME="client-marital-status" ID="client-marital-status" data-control="input-physical">
                                        <OPTION VALUE="">---</OPTION>
                                        <OPTION VALUE="single">Solteiro(a)</OPTION>
                                        <OPTION VALUE="married">Casado(a)</OPTION>
                                        <OPTION VALUE="separate">Separado(a)</OPTION>
                                        <OPTION VALUE="divorced">Divorciado(a)</OPTION>
                                        <OPTION VALUE="widower">Viúvo(a)</OPTION>
                                    </SELECT>
                                </DIV>
                                <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
                                <DIV CLASS="help-block with-errors"></DIV> 
                            </DIV>
                        </DIV>
                        
                        <DIV CLASS="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <DIV CLASS="form-group has-feedback">
                                <LABEL FOR="client-nationality"><SPAN CLASS="required">*</SPAN> Nacionalidade:</LABEL>
                                <DIV CLASS="msg-validation">
                                    <input type="text" class="form-control" name="client-nationality" id="client-nationality" data-control="input-physical" REQUIRED>
                                </DIV>
                                <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
                                <DIV CLASS="help-block with-errors"></DIV>            
                            </DIV>
                        </DIV>  
                     </DIV> <!-- Row -->

                     <div class="clearfix line-1 bg-gray"></div>
                     
                     <DIV CLASS="form-group has-feedback">
                        <LABEL FOR="client-is-employed"><SPAN CLASS="required">*</SPAN> Está trabalhando no momento?</LABEL>
                        <div class="clearfix"></div>
                        <div class="radio-inline">
                            <label>
                              <input name="client-is-employed" id="client-is-employed" value="S" type="radio" data-control="input-physical" required>
                              Sim
                            </label>
                        </div>
                        <div class="radio-inline">
                            <label>
                              <input name="client-is-employed" id="client-is-employed" value="N" type="radio" data-control="input-physical" required>
                              Não
                            </label>
                        </div>
                        <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
                        <DIV CLASS="help-block with-errors"></DIV>            
                    </DIV>
                        
                    <div class="row"> 
                        <DIV CLASS="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <DIV CLASS="form-group has-feedback">
                                <LABEL FOR="client-company-name">Nome da empresa:</LABEL>
                                <DIV CLASS="msg-validation">
                                    <input type="text" class="form-control" name="client-company-name" id="client-company-name" disabled>
                                </DIV>
                                <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
                                <DIV CLASS="help-block with-errors"></DIV>            
                            </DIV>
                        </DIV> 
                        <DIV CLASS="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <DIV CLASS="form-group has-feedback">
                                <LABEL FOR="client-company-position">Cargo:</LABEL>
                                <DIV CLASS="msg-validation">
                                    <input type="text" class="form-control" name="client-company-position" id="client-company-position" disabled>
                                </DIV>
                                <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
                                <DIV CLASS="help-block with-errors"></DIV>            
                            </DIV>
                        </DIV>
                     </DIV>
                     
                     <div class="row"> 
                        <DIV CLASS="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <DIV CLASS="form-group has-feedback">
                                <LABEL FOR="client-company-start-date">Data de início na empresa:</LABEL>
                                <DIV CLASS="msg-validation">
                                  <DIV CLASS="input-group date input-group-sm" data-control="datepicker">
                                      <SPAN CLASS="input-group-addon hidden-xs"><i class="fa fa-calendar" style="height: 10px !important;"></i></SPAN>
                                      <input type="text" class="form-control" name="client-company-start-date" id="client-company-start-date" disabled data-control="mask-date">
                                  </DIV>
                                </DIV>
                                <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
                                <DIV CLASS="help-block with-errors"></DIV>            
                            </DIV>
                        </DIV> 
                        <DIV CLASS="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <DIV CLASS="form-group has-feedback">
                                <LABEL FOR="client-company-contact">Telefone de contato da empresa:</LABEL>
                                <DIV CLASS="msg-validation">
                                    <input type="text" class="form-control" data-input-control="tel" name="client-company-contact" id="client-company-contact" data-control="mask-tel" disabled>
                                </DIV>
                                <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
                                <DIV CLASS="help-block with-errors"></DIV>            
                            </DIV>
                        </DIV>
                     </DIV>
                    
                </DIV>
            </DIV> <!--//.box -->  
            
  
        </DIV><!--//.col 6 left -->
    
    
        <DIV CLASS="col-lg-6 col-md-6 col-sm-6 col-xs-12">
             
            <DIV CLASS="box box-solid">
                <DIV CLASS="box-header ">
                    <STRONG>Endereço</STRONG>
                </DIV>
                <DIV CLASS="box-body">
                
                    <div class="row">   
                        <DIV CLASS="col-lg-9 col-md-9 col-sm-8 col-xs-12">
                            <DIV CLASS="form-group has-feedback">
                                <LABEL FOR="client-address-street">Rua:</LABEL>
                                <DIV CLASS="msg-validation">
                                    <input type="text" class="form-control" name="client-address-street" id="client-address-street">
                                </DIV>
                                <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
                                <DIV CLASS="help-block with-errors"></DIV>            
                            </DIV>
                        </DIV>
                        <DIV CLASS="col-lg-3 col-md-3 col-sm-4 col-xs-12">
                            <DIV CLASS="form-group has-feedback">
                                <LABEL FOR="client-address-street-number">Número:</LABEL>
                                <DIV CLASS="msg-validation">
                                    <input type="text" class="form-control" name="client-address-street-number" id="client-address-street-number">
                                </DIV>
                                <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
                                <DIV CLASS="help-block with-errors"></DIV>            
                            </DIV>
                        </DIV>
                    </DIV>
                    
                    <DIV CLASS="form-group has-feedback">
                        <LABEL FOR="client-address-neighborhood">Bairro:</LABEL>
                        <DIV CLASS="msg-validation">
                            <input type="text" class="form-control" name="client-address-neighborhood" id="client-address-neighborhood">
                        </DIV>
                        <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
                        <DIV CLASS="help-block with-errors"></DIV>            
                    </DIV>
                    
                    <div class="row">   
                        <DIV CLASS="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <DIV CLASS="form-group has-feedback">
                                <LABEL FOR="client-address-state">Estado:</LABEL>
                                <DIV CLASS="msg-validation">
                                    <SELECT CLASS="form-control" NAME="client-address-state" ID="client-address-state">
                                    </SELECT>
                                </DIV>
                                <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
                                <DIV CLASS="help-block with-errors"></DIV>            
                            </DIV>
                        </DIV>
                        
                        <DIV CLASS="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <DIV CLASS="form-group has-feedback">
                                <LABEL FOR="client-address-city">Cidade:</LABEL>
                                <DIV CLASS="msg-validation">
                                    <SELECT CLASS="form-control" NAME="client-address-city" ID="client-address-city">
                                    </SELECT> 
                                </DIV>
                                <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
                                <DIV CLASS="help-block with-errors"></DIV>            
                            </DIV>
                        </DIV>
                    </div>
                    
                    <div class="row">   
                        <DIV CLASS="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <DIV CLASS="form-group has-feedback">
                                <LABEL FOR="client-postal-code">CEP:</LABEL>
                                <DIV CLASS="msg-validation">
                                   <input type="text" class="form-control" name="client-postal-code" id="client-postal-code" pattern="[0-9]{5}-[0-9]{3}" data-control="mask-postal-code"> 
                                </DIV>
                                <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
                                <DIV CLASS="help-block with-errors"></DIV>            
                            </DIV>
                        </DIV>
                        
                        <DIV CLASS="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <DIV CLASS="form-group has-feedback">
                                <LABEL FOR="client-address-complement">Complemento:</LABEL>
                                <DIV CLASS="msg-validation">
                                    <SELECT CLASS="form-control" NAME="client-address-complement" ID="client-address-complement">
                                        <OPTION VALUE="">---</OPTION>
                                        <OPTION VALUE="house">Casa</OPTION>
                                        <OPTION VALUE="apartment">Apartamento</OPTION>
                                        <OPTION VALUE="loft">Sobrado</OPTION>
                                        <OPTION VALUE="commercial">Comercial</OPTION>
                                        <OPTION VALUE="condominium">Condomínio</OPTION>
                                        <OPTION VALUE="rural">Área rural</OPTION>
                                    </SELECT>
                                </DIV>
                                <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
                                <DIV CLASS="help-block with-errors"></DIV>            
                            </DIV>
                        </DIV>
                    </div>
                    
                    <DIV CLASS="form-group has-feedback">
                        <LABEL FOR="client-address-reference">Referência:</LABEL>
                        <DIV CLASS="msg-validation">
                           <input type="text" class="form-control" name="client-address-reference" id="client-address-reference"> 
                        </DIV>
                        <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
                        <DIV CLASS="help-block with-errors"></DIV>            
                    </DIV>

                    
                </DIV>
            </DIV> <!--//.box --> 
            
            <DIV CLASS="box box-solid">
                <DIV CLASS="box-header ">
                    <STRONG>Informações de contato</STRONG>
                </DIV>
                <DIV CLASS="box-body">
                
                    <div class="row"> 
                        <DIV CLASS="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <DIV CLASS="form-group has-feedback">
                                <LABEL FOR="client-contact-phone-1">Telefone 1:</LABEL>
                                <DIV CLASS="msg-validation">
                                    <input type="text" class="form-control" name="client-contact-phone-1" id="client-contact-phone-1" data-control="mask-tel">
                                </DIV>
                                <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
                                <DIV CLASS="help-block with-errors"></DIV>            
                            </DIV>
                        </DIV> 
                        <DIV CLASS="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <DIV CLASS="form-group has-feedback">
                                <LABEL FOR="client-contact-phone-2">Telefone 2:</LABEL>
                                <DIV CLASS="msg-validation">
                                    <input type="text" class="form-control" name="client-contact-phone-2" id="client-contact-phone-2" data-control="mask-tel">
                                </DIV>
                                <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
                                <DIV CLASS="help-block with-errors"></DIV>            
                            </DIV>
                        </DIV>
                     </DIV>
                     
                     <DIV CLASS="form-group has-feedback">
                        <LABEL FOR="client-contact-email">E-mail:</LABEL>
                        <DIV CLASS="msg-validation">
                           <input type="email" class="form-control" name="client-contact-email" id="client-contact-email"> 
                        </DIV>
                        <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
                        <DIV CLASS="help-block with-errors"></DIV>            
                    </DIV>
                
                </DIV>
            </DIV> <!--//.box -->
            
            <DIV CLASS="box box-solid">
                <DIV CLASS="box-header ">
                    <STRONG>Outras observações</STRONG>
                </DIV>
                <DIV CLASS="box-body"> 
                
                <DIV CLASS="form-group has-feedback">
                    <LABEL FOR="client-observations">Você pode colocar aqui informações que não estão no formulário</LABEL>
                    <DIV CLASS="msg-validation">
                       <textarea class="form-control count-caractere" DATA-MAX-CARACTERE="2048" name="client-observations" id="client-observations" style="resize:none; min-height:204px;"></textarea>
                       <DIV CLASS="restante-caractere"></DIV> 
                    </DIV>
                    <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
                    <DIV CLASS="help-block with-errors"></DIV>            
                </DIV>
                    
                </DIV>
            </DIV> <!--//.box -->       
      
      
        </DIV><!--//.col 6 right -->
   
        <DIV CLASS="clearfix"></DIV>
   
        <DIV CLASS="col-md-12">
            <button type="submit" class="btn btn-primary btn-flat">Cadastrar</button>
        </DIV>
      
    </form> 
    
    <DIV CLASS="clearfix space-20"></DIV>
    
  </DIV>
</SECTION> 

<script src="/plugins/bootstrap-validator-master/dist/validator.min.js"></script>
<script src="/plugins/input-mask/jquery.maskAll.js"></script>
<script src="/plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="/plugins/datepicker/locales/bootstrap-datepicker.pt-BR.js"></script>
<script>
$(document).ready(function() {
    $('[data-control="datepicker"]').datepicker({
        format: 'dd/mm/yyyy',
    });
});
</script>
<script src="/plugins/cidades-estados/estados-cidades.js"></script>
<script language="JavaScript" type="text/javascript" charset="utf-8">
  new dgCidadesEstados({
    cidade: document.getElementById('client-address-city'),
    estado: document.getElementById('client-address-state'),
  })
</script>
<script src="/app/javascript/clients.js"></script>
<script src="/app/javascript/control_forms.js"></script>
<?php
}
else
{
    echo '<DIV CLASS="error-page">',
    '<P CLASS=" headline text-yellow"> <I CLASS="fa fa-lock fa-2x" ARIA-HIDDEN="true"></I></P>',
    '<DIV CLASS="error-content">',
    '<H3><I CLASS="fa fa-warning text-yellow"></I> Oops! Você não tem permissão para acessar esta página.</H3>',
    'Retorne a página <a href="/app/index/home">inicial</a>.',
    '</P>',
    '</DIV> ',
    '</DIV>';
}
?>