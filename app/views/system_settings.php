<?php
if( $_SESSION['user_type'] === 'administrador' or $_SESSION['user_type'] === 'suporte')
{
    /*
    $actionid = filterString(base64_decode($_GET['actionid']), 'INT');

    $con_db = new config\connect_db();
    $con = $con_db->connect();

    $clients = $con->prepare("SELECT client_type, client_social_name, client_cnpj, client_fantasy_name, client_responsible, client_name, client_last_name, client_birth_date, client_nationality, client_genre, client_cpf, client_rg, client_marital_status, client_is_employed, client_company_name, client_company_position, client_company_start_date, client_company_contact, client_address_street, client_address_street_number, client_address_neighborhood, client_address_city, client_address_state, client_postal_code, client_address_complement, client_address_reference, client_contact_phone_1, client_contact_phone_2, client_contact_email, client_observations FROM clients WHERE id = ?");
    $clients->bind_param('i', $actionid);
    $clients->execute();
    $clients->store_result();
    $clients->bind_result($type, $social_name, $cnpj, $fantasy_name, $responsible, $name, $last_name, $birth_date, $nationality, $genre, $cpf, $rg, $marital_status, $employed, $company_name, $company_position, $company_start_date, $company_contact, $street, $street_number, $neighborhood, $city, $state, $postal_code, $complement, $reference, $phone_1, $phone_2, $email, $observations);
    $clients->fetch();
    $rows = $clients->affected_rows;
    $clients->free_result();
    $clients->close();

    if($clients and $rows > 0)
    {
    */

?>
<link href="/plugins/datepicker/datepicker3.css" rel="stylesheet" type="text/css">

<SECTION CLASS="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <H4 CLASS="text-darkgray"><STRONG>Editar Clientes</STRONG></H4>
            <OL CLASS="breadcrumb bg-white">
                <LI><a href="/app/admin/home"><I CLASS="fa fa-home"></I></a></LI>
                <LI><a href="/app/admin/clients"><i class="fa fa-users"></i> Clientes</a></LI>
                <LI><a href="/app/admin/edit_clients/<?php echo base64_encode($actionid) ?>"><i class="fa fa-user"></i> <?php echo $actionid ?></a></LI>
            </OL>
        </div>
    </div>
</SECTION>

<DIV CLASS="space30"></DIV>

<SECTION CLASS="container-fluid">
    <DIV CLASS="row">
  
    <form name="form-add-clients" method="POST" id="form-add-clients" action="/app/modules/clients/update_clients.php" role="form" DATA-TOGGLE="validator" enctype="APPLICATION/X-WWW-FORM-URLENCODED" NOVALIDATE data-action="submit-ajax" data-form-reset="reset" autocomplete="off">
        
        <input type="HIDDEN" name="actionid" value="<?php echo base64_encode($actionid) ?>">
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
                                <OPTION VALUE="physical" <?php echo ($type === 'physical') ? 'selected' : ''; ?> >Pessoa Fisica</OPTION>
                                <OPTION VALUE="juridical" <?php echo ($type === 'juridical') ? 'selected' : ''; ?> >Pessoa Jurídica</OPTION>
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
            
             <DIV CLASS="box box-solid" id="inputs-person-juridical" <?php echo ($type === 'physical') ? 'style="cursor: not-allowed; opacity: 0.5;"' : ''; ?>>
                <DIV CLASS="box-header">
                    <STRONG>Caso o cliente seja pessoa jurídica, preencha aqui</STRONG>
                </DIV>
                <DIV CLASS="box-body">
                    
                    <div class="row">   
                        <DIV CLASS="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <DIV CLASS="form-group has-feedback">
                                <LABEL FOR="client-social-name">Razão social:</LABEL>
                                <DIV CLASS="msg-validation">
                                    <input type="text" class="form-control" name="client-social-name" id="client-social-name" value="<?php echo $social_name ?>" <?php echo ($type === 'physical') ? 'disabled' : ''; ?> >
                                </DIV>
                                <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
                                <DIV CLASS="help-block with-errors"></DIV>            
                            </DIV>
                        </DIV>
                        <DIV CLASS="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <DIV CLASS="form-group has-feedback">
                                <LABEL FOR="client-cnpj">CNPJ:</LABEL>
                                <DIV CLASS="msg-validation">
                                    <input type="text" class="form-control" name="client-cnpj" id="client-cnpj" pattern="[0-9]{2}.?[0-9]{3}.?[0-9]{3}/?[0-9]{4}-?[0-9]{2}" data-control="mask-cnpj" value="<?php echo $cnpj ?>" <?php echo ($type === 'physical') ? 'disabled' : ''; ?> >
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
                                    <input type="text" class="form-control" name="client-fantasy-name" id="client-fantasy-name" data-control="input-juridical" value="<?php echo $fantasy_name ?>" REQUIRED <?php echo ($type === 'physical') ? 'disabled' : ''; ?> >
                                </DIV>
                                <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
                                <DIV CLASS="help-block with-errors"></DIV>            
                            </DIV>
                        </DIV>
                        <DIV CLASS="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <DIV CLASS="form-group has-feedback">
                                <LABEL FOR="client-responsible">Nome do responsável:</LABEL>
                                <DIV CLASS="msg-validation">
                                    <input type="text" class="form-control" name="client-responsible" id="client-responsible" value="<?php echo $responsible ?>" <?php echo ($type === 'physical') ? 'disabled' : ''; ?> >
                                </DIV>
                                <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
                                <DIV CLASS="help-block with-errors"></DIV>            
                            </DIV>
                        </DIV>
                     </DIV>
                    
                    
                </DIV>
            </DIV> <!--//.box -->
            
            <DIV CLASS="box box-solid" id="inputs-person-physical" <?php echo ($type === 'juridical') ? 'style="cursor: not-allowed; opacity: 0.5;"' : ''; ?>>
                <DIV CLASS="box-header ">
                    <STRONG>Caso o cliente seja pessoa fisica, preencha aqui</STRONG>
                </DIV>
                <DIV CLASS="box-body">
                    
                    <div class="row">   
                        <DIV CLASS="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <DIV CLASS="form-group has-feedback">
                                <LABEL FOR="client-name"><SPAN CLASS="required">*</SPAN> Nome:</LABEL>
                                <DIV CLASS="msg-validation">
                                    <input type="text" class="form-control" name="client-name" id="client-name" data-control="input-physical" value="<?php echo $name ?>" REQUIRED <?php echo ($type === 'juridical') ? 'disabled' : ''; ?> >
                                </DIV>
                                <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
                                <DIV CLASS="help-block with-errors"></DIV>            
                            </DIV>
                        </DIV>
                        
                        <DIV CLASS="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <DIV CLASS="form-group has-feedback">
                                <LABEL FOR="client-last-name"><SPAN CLASS="required">*</SPAN> Sobrenome:</LABEL>
                                <DIV CLASS="msg-validation">
                                    <input type="text" class="form-control" name="client-last-name" id="client-last-name" data-control="input-physical" value="<?php echo $last_name ?>" REQUIRED <?php echo ($type === 'juridical') ? 'disabled' : ''; ?> >
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
                                <DIV CLASS=" input-group msg-validation">
                                <SPAN CLASS="input-group-addon hidden-xs"><I CLASS="fa fa-calendar"></I></SPAN>
                                    <input type="text" class="form-control date" name="client-birth-date" id="client-birth-date" data-control="mask-date" value="<?php echo inverteData($birth_date) ?>" <?php echo ($type === 'juridical') ? 'disabled' : ''; ?> >
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
                                      <input name="client-genre" value="male" type="radio" data-control="input-physical" <?php echo ($genre === 'male') ? 'checked' : '' ; ?> required <?php echo ($type === 'juridical') ? 'disabled' : ''; ?> >
                                      Masculino
                                    </label>
                                </div>
                                <div class="radio-inline">
                                    <label>
                                      <input name="client-genre" value="female" type="radio" data-control="input-physical" <?php echo ($genre === 'female') ? 'checked' : '' ;?> required <?php echo ($type === 'juridical') ? 'disabled' : ''; ?> >
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
                                    <input type="text" class="form-control" name="client-cpf" id="client-cpf" pattern="[0-9]{3}.?[0-9]{3}.?[0-9]{3}-?[0-9]{2}" data-control="mask-cpf" value="<?php echo $cpf ?>" <?php echo ($type === 'juridical') ? 'disabled' : ''; ?> >
                                </DIV>
                                <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
                                <DIV CLASS="help-block with-errors"></DIV>            
                            </DIV>
                        </DIV> 
                        <DIV CLASS="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <DIV CLASS="form-group has-feedback">
                                <LABEL FOR="client-rg">RG:</LABEL>
                                <DIV CLASS="msg-validation">
                                    <input type="text" class="form-control" name="client-rg" id="client-rg" pattern="[0-9]{2}.?[0-9]{3}.?[0-9]{3}-?[0-9]{1}" data-control="mask-rg" value="<?php echo $rg ?>" <?php echo ($type === 'juridical') ? 'disabled' : ''; ?> >
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
                                    <SELECT CLASS="form-control" NAME="client-marital-status" ID="client-marital-status" data-control="input-physical" <?php echo ($type === 'juridical') ? 'disabled' : ''; ?> >
                                        <OPTION VALUE="" <?php echo ($marital_status === '') ? 'selected' : ''; ?>---</OPTION>
                                        <OPTION VALUE="single" <?php echo ($marital_status === 'single') ? 'selected' : ''; ?> >Solteiro(a)</OPTION>
                                        <OPTION VALUE="married" <?php echo ($marital_status === 'married') ? 'selected' : ''; ?> >Casado(a)</OPTION>
                                        <OPTION VALUE="separate" <?php echo ($marital_status === 'separate') ? 'selected' : ''; ?> >Separado(a)</OPTION>
                                        <OPTION VALUE="divorced" <?php echo ($marital_status === 'divorced') ? 'selected' : ''; ?> >Divorciado(a)</OPTION>
                                        <OPTION VALUE="widower" <?php echo ($marital_status === 'widower') ? 'selected' : ''; ?> >Viúvo(a)</OPTION>
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
                                    <input type="text" class="form-control" name="client-nationality" id="client-nationality" data-control="input-physical" value="<?php echo $nationality ?>" REQUIRED <?php echo ($type === 'juridical') ? 'disabled' : ''; ?> >
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
                              <input name="client-is-employed" id="client-is-employed" value="S" type="radio" data-control="input-physical" <?php echo ($employed === 'Y') ? 'checked' : '' ; ?> required <?php echo ($type === 'juridical') ? 'disabled' : ''; ?> >
                              Sim
                            </label>
                        </div>
                       
                        <div class="radio-inline">
                            <label>
                              <input name="client-is-employed" id="client-is-employed" value="N" type="radio" data-control="input-physical" <?php echo ($employed === 'N') ? 'checked' : '' ; ?> required <?php echo ($type === 'juridical') ? 'disabled' : ''; ?> >
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
                                    <input type="text" class="form-control" name="client-company-name" id="client-company-name" value="<?php echo $company_name ?>" <?php echo ($employed === 'N') ? 'disabled' : '' ; ?> <?php echo ($type === 'juridical') ? 'disabled' : ''; ?> >
                                </DIV>
                                <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
                                <DIV CLASS="help-block with-errors"></DIV>            
                            </DIV>
                        </DIV> 
                        <DIV CLASS="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <DIV CLASS="form-group has-feedback">
                                <LABEL FOR="client-company-position">Cargo:</LABEL>
                                <DIV CLASS="msg-validation">
                                    <input type="text" class="form-control" name="client-company-position" id="client-company-position" value="<?php echo $company_position ?>" <?php echo ($employed === 'N') ? 'disabled' : '' ; ?> <?php echo ($type === 'juridical') ? 'disabled' : ''; ?> >
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
                                      <input class="form-control" type="text" name="client-company-start-date" id="client-company-start-date" value="<?php echo inverteData($company_start_date) ?>" <?php echo ($employed === 'N') ? 'disabled' : '' ; ?> data-control="mask-date" <?php echo ($type === 'juridical') ? 'disabled' : ''; ?>>
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
                                    <input type="text" class="form-control" data-input-control="tel" name="client-company-contact" id="client-company-contact" data-control="mask-tel" value="<?php echo $company_contact ?>" required <?php echo ($employed === 'N') ? 'disabled' : '' ; ?> <?php echo ($type === 'juridical') ? 'disabled' : ''; ?> >
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
                                    <input type="text" class="form-control" name="client-address-street" id="client-address-street" value="<?php echo $street ?>">
                                </DIV>
                                <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
                                <DIV CLASS="help-block with-errors"></DIV>            
                            </DIV>
                        </DIV>
                        <DIV CLASS="col-lg-3 col-md-3 col-sm-4 col-xs-12">
                            <DIV CLASS="form-group has-feedback">
                                <LABEL FOR="client-address-street-number">Número:</LABEL>
                                <DIV CLASS="msg-validation">
                                    <input type="text" class="form-control" name="client-address-street-number" id="client-address-street-number" value="<?php echo $street_number ?>">
                                </DIV>
                                <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
                                <DIV CLASS="help-block with-errors"></DIV>            
                            </DIV>
                        </DIV>
                    </DIV>
                    
                    <DIV CLASS="form-group has-feedback">
                        <LABEL FOR="client-address-neighborhood">Bairro:</LABEL>
                        <DIV CLASS="msg-validation">
                            <input type="text" class="form-control" name="client-address-neighborhood" id="client-address-neighborhood" value="<?php echo $neighborhood ?>">
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
                                     <option value="<?php echo $state ?>"><?php echo $state ?></option>
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
                                        <option value="<?php echo $city ?>"><?php echo $city ?></option>
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
                                   <input type="text" class="form-control" name="client-postal-code" id="client-postal-code" pattern="[0-9]{5}-[0-9]{3}" data-control="mask-postal-code" value="<?php echo $postal_code ?>" > 
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
                                        <OPTION VALUE="" <?php echo ($complement === '') ? 'selected' : '' ; ?></OPTIO>>---</OPTION>
                                        <OPTION VALUE="house" <?php echo ($complement === 'house') ? 'selected' : '' ; ?>>Casa</OPTION>
                                        <OPTION VALUE="apartment" <?php echo ($complement === 'apartment') ? 'selected' : '' ; ?>>Apartamento</OPTION>
                                        <OPTION VALUE="loft" <?php echo ($complement === 'loft') ? 'selected' : '' ; ?>>Sobrado</OPTION>
                                        <OPTION VALUE="commercial" <?php echo ($complement === 'commercial') ? 'selected' : '' ; ?>>Comercial</OPTION>
                                        <OPTION VALUE="condominium" <?php echo ($complement === 'condominium') ? 'selected' : '' ; ?>>Condomínio</OPTION>
                                        <OPTION VALUE="rural" <?php echo ($complement === 'rural') ? 'selected' : '' ; ?>>Área rural</OPTION>
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
                           <input type="text" class="form-control" name="client-address-reference" id="client-address-reference" value="<?php echo $reference ?>"> 
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
                                    <input type="text" class="form-control" name="client-contact-phone-1" id="client-contact-phone-1" data-control="mask-tel" value="<?php echo $phone_1 ?>" >
                                </DIV>
                                <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
                                <DIV CLASS="help-block with-errors"></DIV>            
                            </DIV>
                        </DIV> 
                        <DIV CLASS="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <DIV CLASS="form-group has-feedback">
                                <LABEL FOR="client-contact-phone-2">Telefone 2:</LABEL>
                                <DIV CLASS="msg-validation">
                                    <input type="text" class="form-control" name="client-contact-phone-2" id="client-contact-phone-2" data-control="mask-tel" value="<?php echo $phone_2 ?>" >
                                </DIV>
                                <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
                                <DIV CLASS="help-block with-errors"></DIV>            
                            </DIV>
                        </DIV>
                     </DIV>
                     
                     <DIV CLASS="form-group has-feedback">
                        <LABEL FOR="client-contact-email">E-mail:</LABEL>
                        <DIV CLASS="msg-validation">
                           <input type="email" class="form-control" name="client-contact-email" id="client-contact-email" value="<?php echo $email ?>"> 
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
                       <textarea class="form-control count-caractere" DATA-MAX-CARACTERE="2048" name="client-observations" id="client-observations" style="resize:none; min-height:204px;"><?php echo $observations ?></textarea>
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
            <button type="submit" class="btn btn-primary btn-flat">Atualizar</button>
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
    estadoVal: 'PR',
    cidadeVal: 'Realeza'
  })
</script>
<script src="/app/javascript/clients.js"></script>
<script src="/app/javascript/control_forms.js"></script>
<?php
  /*  }
    else
    {
    echo '<div class="alert alert-warning">
         <h4><i class="icon fa fa-warning"></i> Atenção!</h4>
         Nenhum cliente identificado com essas credenciais.<br>
         Verifique se o endereço está correto, se essa mensagem continuar aparecendo, procure por ajuda na página de <a href="/app/admin/support">Ajuda & Suporte</a> ou entre em contato com o administrador.
         </div>';    
    }//. Se o select encontrou o usuário      
    */
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