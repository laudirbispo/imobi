<?php
if( $_SESSION['user_type'] == 'administrador' or $_SESSION['user_type'] == 'suporte')
{ 
    $url = new app\controls\securePage();
    $system_id = SYSTEM_ID;
    $con_db = new config\connect_db();
    $con = $con_db->connect();
    
    $settings = $con->prepare("SELECT company_social_name, company_creci, company_cnpj, company_street, company_street_number, company_neighborhood, company_state, company_city, company_postal_code, company_logo FROM settings_properties WHERE unique_id = ?");
    $settings->bind_param('s', $system_id);
    $settings->execute();
    $settings->store_result();
    $settings->bind_result($social_name, $creci, $cnpj, $street, $street_number, $neighborhood, $state, $city, $postal_code, $logo);
    $settings->fetch();
    $rows = $settings->affected_rows;
    $settings->free_result();
    $settings->close();
    
    if($settings and $rows > 0)
    {
        
    
?>
<link href="/plugins/datepicker/datepicker3.css" rel="stylesheet" type="text/css">

<SECTION CLASS="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <H4 CLASS="text-darkgray"><STRONG>Imóveis - Configurações</STRONG></H4>
            <OL CLASS="breadcrumb bg-white">
                <LI><a href="/app/admin/home"><I CLASS="fa fa-home"></I></a></LI>
                <LI><a href="/app/admin/properties"><i class="fa fa-industry"></i> Imóveis</a></LI>
            </OL>
        </div>
    </div>
</SECTION>

<SECTION CLASS="container-fluid">
    <DIV CLASS="row">
       
        <div class="col-md-12">
            <div class="alert alert-info">
                <h4><i class="icon fa fa-info"></i> Atenção!</h4>
                <p>As informações a seguir foram pré estabelecidas durante o desenvolvimento do sistema de acordo com
                a necessidade da sua empresa.<br>
                Não nos responsabilizamos pelas alterações feitas por usuários, as quais poderão sutir efeitos nas ferramentas disponíveis neste sistema.</p>
                <div class="checkbox">
                  <label>
                    <input type="checkbox" data-action="accept-terms"><strong>Estou ciente e gostaria de alterar as configurações</strong>
                  </label>
                </div>
            </div>
        </div>
        
        <div class="clearfix space-30"></div>
        
        <DIV CLASS="col-lg-6 col-md-6 col-sm-6 col-xs-12 hidden">
            
            <div class="box box-solid">
                <div class="box-header">
                    <strong>Logomarca da empresa</strong>
                </div>
                <div class="box-body">
                   <p class="text-info"><i class="icon fa fa-info"></i> A imagem deve ter o tamanho máximo de 283px de largura por 94px de altura: </p>
                   <form name="form-settings-logomarca" method="POST" id="form-settings-logomarca" action="/app/modules/properties/upload_logo.php" role="form" DATA-TOGGLE="validator" enctype="APPLICATION/X-WWW-FORM-URLENCODED" NOVALIDATE data-form-reset="reset" autocomplete="off">
                   
                       <input type="HIDDEN" name="user_id" value="<?php echo $_SESSION['user_id'] ?>">
                       <input type="HIDDEN" name="form-token" value="<?php echo $_SESSION['secret_form_token'] ?>">
                       
                        <DIV CLASS="form-group has-feedback">
                            <label for="logo">
                                <a class="btn btn-primary btn-flat"><i class="fa fa-upload"></i> Escolher imagem</a>
                                <input type="file" name="logo" id="logo" data-control="input-file" class="hidden" accept=".jpej,.jpeg,.png" data-form-control="accept-terms" required disabled>
                                <p id="image-loaded"></p>
                            </label>
                        </DIV>

                        <button type="submit" class="btn btn-primary btn-flat" data-form-control="accept-terms" disabled>Upload</button>
                        
                    </form> 
                    
                    <div class="clearfix space-20"></div>                
                    <div class="progress progress-md active hidden" id="div-progress">
                        <div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" id="bar-progress" style="width: 0%">
                            <span id="status-progress"></span>
                        </div>
                    </div>
                </div>
            </div>
            
            <?php 
            if(file_exists($_SERVER['DOCUMENT_ROOT'].'/docs/company/logomarca/md/'.$logo))
            {
               echo '<div class="col-md-12"><div class="card"><img src="/docs/company/logomarca/lg/'.$logo.'"></div></div>';
            }
            ?>      
            
        </DIV><!--//col-left-->
        
        <DIV CLASS="col-lg-6 col-md-6 col-sm-6 col-xs-12">
           
            <form name="form-settings-properties" method="POST" id="form-settings-properties" action="/app/modules/properties/update_settings_properties.php" role="form" DATA-TOGGLE="validator" enctype="APPLICATION/X-WWW-FORM-URLENCODED" NOVALIDATE data-action="submit-ajax" data-form-reset="noreset" autocomplete="off">

                <input type="HIDDEN" name="user_id" value="<?php echo $_SESSION['user_id'] ?>">
                <input type="HIDDEN" name="form-token" value="<?php echo $_SESSION['secret_form_token'] ?>">
                
                <div class="box box-solid">
                    <div class="box-header">
                        <STRONG>Dados da empresa</STRONG>
                    </div>
                    <div class="box-body">
                       
                        <DIV CLASS="form-group has-feedback">
                            <LABEL FOR="settings-properties-social-name"><span class="required">*</span> Razão social</LABEL>
                            <DIV CLASS="msg-validation">
                                <input class="form-control" type="text" name="settings-properties-social-name" id="settings-properties-social-name" data-form-control="accept-terms" required disabled value="<?php echo $social_name ?>">
                            </DIV>
                            <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
                            <DIV CLASS="help-block with-errors"></DIV> 
                        </DIV>
                        <div class="row">
                            <DIV CLASS="col-lg-6 col-md-6 col-sm-12 col-xs-12"> 
                                <DIV CLASS="form-group has-feedback">
                                    <LABEL FOR="settings-properties-creci"><span class="required">*</span> CRECI</LABEL>
                                    <DIV CLASS="msg-validation">
                                        <input class="form-control" type="text" name="settings-properties-creci" id="ssettings-properties-creci" data-form-control="accept-terms" data-form-control="accept-terms" required disabled value="<?php echo $creci ?>">
                                    </DIV>
                                    <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
                                    <DIV CLASS="help-block with-errors"></DIV> 
                                </DIV>
                            </DIV>
                            
                            <DIV CLASS="col-lg-6 col-md-6 col-sm-12 col-xs-12"> 
                                <DIV CLASS="form-group has-feedback">
                                    <LABEL FOR="settings-properties-cnpj"><span class="required">*</span> CNPJ:</LABEL>
                                    <DIV CLASS="msg-validation">
                                        <input type="text" class="form-control" name="settings-properties-cnpj" id="settings-properties-cnpj" pattern="[0-9]{2}.?[0-9]{3}.?[0-9]{3}/?[0-9]{4}-?[0-9]{2}" data-control="mask-cnpj" data-form-control="accept-terms" required disabled value="<?php echo $cnpj ?>">
                                    </DIV>
                                    <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
                                    <DIV CLASS="help-block with-errors"></DIV>            
                                </DIV> 
                            </DIV>

                            <DIV CLASS="col-lg-9 col-md-9 col-sm-8 col-xs-12">
                                <DIV CLASS="form-group has-feedback">
                                    <LABEL FOR="settings-properties-address-street"><span class="required">*</span> Rua:</LABEL>
                                    <DIV CLASS="msg-validation">
                                        <input type="text" class="form-control" name="settings-properties-address-street" id="settings-properties-address-street" data-form-control="accept-terms" required disabled value="<?php echo $street ?>">
                                    </DIV>
                                    <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
                                    <DIV CLASS="help-block with-errors"></DIV>            
                                </DIV>
                            </DIV>
                            <DIV CLASS="col-lg-3 col-md-3 col-sm-4 col-xs-12">
                                <DIV CLASS="form-group has-feedback">
                                    <LABEL FOR="settings-properties-address-street-number">Número:</LABEL>
                                    <DIV CLASS="msg-validation">
                                        <input type="text" class="form-control" name="settings-properties-address-street-number" id="settings-properties-address-street-number" data-form-control="accept-terms" disabled value="<?php echo $street_number ?>">
                                    </DIV>
                                    <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
                                    <DIV CLASS="help-block with-errors"></DIV>            
                                </DIV>
                            </DIV>                      
                        
                            <DIV CLASS="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <DIV CLASS="form-group has-feedback">
                                    <LABEL FOR="settings-properties-address-neighborhood"><span class="required">*</span> Bairro:</LABEL>
                                    <DIV CLASS="msg-validation">
                                        <input type="text" class="form-control" name="settings-properties-address-neighborhood" id="settings-properties-address-neighborhood" data-form-control="accept-terms" required disabled value="<?php echo $neighborhood ?>">
                                    </DIV>
                                    <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
                                    <DIV CLASS="help-block with-errors"></DIV>            
                                </DIV>
                            </DIV>

                            <DIV CLASS="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <DIV CLASS="form-group has-feedback">
                                    <LABEL FOR="settings-properties-address-state"><span class="required">*</span> Estado:</LABEL>
                                    <DIV CLASS="msg-validation">
                                        <SELECT CLASS="form-control" NAME="settings-properties-address-state" ID="settings-properties-address-state" data-form-control="accept-terms" required disabled placeholder="ff">
                                        <OPTION VALUE="<?php echo $state ?>"><?php echo $state ?></OPTION>
                                        </SELECT>
                                    </DIV>
                                    <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
                                    <DIV CLASS="help-block with-errors"></DIV>            
                                </DIV>
                            </DIV>

                            <DIV CLASS="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <DIV CLASS="form-group has-feedback">
                                    <LABEL FOR="settings-properties-address-city"><span class="required">*</span> Cidade:</LABEL>
                                    <DIV CLASS="msg-validation">
                                        <SELECT CLASS="form-control" NAME="settings-properties-address-city" ID="settings-properties-address-city" data-form-control="accept-terms" required disabled>
                                        <OPTION VALUE="<?php echo $city ?>"><?php echo $city ?></OPTION>
                                        </SELECT> 
                                    </DIV>
                                    <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
                                    <DIV CLASS="help-block with-errors"></DIV>            
                                </DIV>
                            </DIV>

                            <DIV CLASS="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <DIV CLASS="form-group has-feedback">
                                    <LABEL FOR="settings-properties-postal-code"><span class="required">*</span> CEP:</LABEL>
                                    <DIV CLASS="msg-validation">
                                       <input type="text" class="form-control" name="settings-properties-postal-code" id="settings-properties-postal-code" pattern="[0-9]{5}-[0-9]{3}" data-control="mask-postal-code" data-form-control="accept-terms" required disabled value="<?php echo $postal_code ?>"> 
                                    </DIV>
                                    <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
                                    <DIV CLASS="help-block with-errors"></DIV>            
                                </DIV>
                            </DIV>
                        </div>
                          
                    </div>
                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary btn-flat" data-form-control="accept-terms" disabled>Alterar dados</button> 
                    </div>
                </div> <!--//box-solid-->  
                 
            </form>   
             
        </DIV><!--//col-right-->
        
    </DIV>    
</SECTION>

<DIV CLASS="clearfix space-30"></DIV>

<script src="/plugins/bootstrap-validator-master/dist/validator.min.js"></script>
<script src="/plugins/cidades-estados/estados-cidades.js"></script>
<script type="text/javascript" charset="utf-8">
  new dgCidadesEstados({
    cidade: document.getElementById('settings-properties-address-city'),
    estado: document.getElementById('settings-properties-address-state'),
    estadoVal: '<?php echo $state ?>',
    cidadeVal: '<?php echo $city ?>'
  })
</script>
<script src="/plugins/input-mask/jquery.maskMoney.js"></script>
<script src="/plugins/input-mask/jquery.maskAll.js"></script>
<script src="/app/javascript/properties.js"></script>
<script src="/plugins/datepicker/bootstrap-datepicker.js"></script>
<script>
$(document).ready(function() {
$('[data-control="datepicker"]').datepicker();
});
</script>
<script src="/app/javascript/control_forms.js"></script>
<?php
    }
    else
    {
        echo '<div class="alert alert-warning">
             <h4><i class="icon fa fa-warning"></i> Atenção!</h4>
             Ocorreu um problema ao carregar as configurações.<br>
             Se essa mensagem continuar aparecendo, entre em contato com o administrador.
             </div>';    
    }//. Se o select encontrou o usuário
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