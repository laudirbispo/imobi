<?php
if( $_SESSION['user_type'] === 'administrador' or $_SESSION['user_type'] === 'suporte')
{
    
    /*

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
            <H4 CLASS="text-darkgray"><STRONG>Configuralções do meu Site</STRONG></H4>
            <OL CLASS="breadcrumb bg-white">
                <LI><a href="/app/admin/home"><I CLASS="fa fa-home"></I></a></LI>
                <LI><a href="/app/admin/clients"><i class="fa fa-cogs"></i> Configurações</a></LI>
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
        
        <DIV CLASS="space-20"></DIV>

        <div class="col-md-6 col-sm-6 col-xs-12">

            <div class="box box-solid">
                <div class="box-header">
                    <STRONG>SEO - Search Engine Optimization</STRONG>
                    <div CLASS="pull-right ico-help icons_sys sprites-support" tabindex= "0" role= "button" DATA-CONTROL="popover-focus"  DATA-PLACEMENT="top" DATA-TOGGLE="popover" DATA-CONTAINER="body" DATA-HTML="true" DATA-TRIGGER="focus" TITLE="Ajuda" DATA-CONTENT="<b>SEO</b> significa Search Engine Optimization. É um conjunto de técnicas que possuem o objetivo de otimizar o seu site para obter melhores resultados orgânicos no Google e outros mecânismos de pesquisa."></div>
                </div>
                <div class="box-body">

                    <form name="form-add-clients" method="POST" id="form-add-clients" action="/app/modules/clients/update_clients.php" role="form" DATA-TOGGLE="validator" enctype="APPLICATION/X-WWW-FORM-URLENCODED" data-action="submit-ajax" data-form-reset="noreset" autocomplete="off">

                        <input type="HIDDEN" name="user_id" value="<?php echo $_SESSION['user_id'] ?>">
                        <input type="HIDDEN" name="form-token" value="<?php echo $_SESSION['secret_form_token'] ?>">
                        
                        <DIV CLASS="form-group has-feedback">
                            <LABEL FOR="settings-site-title"><SPAN CLASS="required">*</SPAN> Nome do Site</LABEL>
                            <div CLASS="pull-right ico-help icons_sys" id="sprites-support" tabindex= "0" role= "button" DATA-CONTROL="popover-focus"  DATA-PLACEMENT="top" DATA-TOGGLE="popover" DATA-CONTAINER="body" DATA-HTML="true" DATA-TRIGGER="focus" TITLE="Ajuda" DATA-CONTENT="Título da página, este é o texto que é exibido na aba do navegador.<br> Utilize no máximo <b>60</b> caracteres."></div>
                            <DIV CLASS="msg-validation">
                              <DIV CLASS="input-group">
                                  <SPAN CLASS="input-group-addon bg-aqua">T</SPAN>
                                  <input class="form-control count-caractere" maxlength="60" DATA-MAX-CARACTERE="60" type="text" name="settings-site-title" id="settings-site-title" placeholder="Exemplo: Easy Mobi - Tecnologia Web" data-form-control="accept-terms" required disabled data-validate="true">
                              </DIV>
                            </DIV>
                            <DIV CLASS="restante-caractere pull-right"></DIV>
                            <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
                            <DIV CLASS="help-block with-errors"></DIV> 
                        </DIV>
                        
                        <DIV CLASS="form-group has-feedback">
                            <LABEL for="settings-site-description"><SPAN CLASS="required">*</SPAN> Descrição</LABEL>
                            <div CLASS="pull-right ico-help icons_sys sprites-support" tabindex= "0" role= "button" DATA-CONTROL="popover-focus"  DATA-PLACEMENT="top" DATA-TOGGLE="popover" DATA-CONTAINER="body" DATA-HTML="true" DATA-TRIGGER="focus" TITLE="Ajuda" DATA-CONTENT="Uma breve descrição sobre o site.<br> Utilize no máximo <b>150</b> caracteres."></div>
                            <DIV CLASS="msg-validation">
                                <TEXTAREA CLASS="form-control count-caractere" maxlength="150" DATA-MAX-CARACTERE="150" NAME="settings-site-description" ID="settings-site-description" STYLE="resize:none; min-height:60px !important" data-form-control="accept-terms" required disabled></TEXTAREA>
                            </DIV>
                            <DIV CLASS="restante-caractere pull-right"></DIV>
                            <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
                            <DIV CLASS="help-block with-errors"></DIV>  
                        </DIV>
                        
                        <DIV CLASS="form-group has-feedback">
                            <LABEL for="settings-site-keywords"><SPAN CLASS="required">*</SPAN> Palavras Chaves(KEYWORDS)</LABEL>
                            <div CLASS="pull-right ico-help icons_sys sprites-support" tabindex= "0" role= "button" DATA-CONTROL="popover-focus"  DATA-PLACEMENT="top" DATA-TOGGLE="popover" DATA-CONTAINER="body" DATA-HTML="true" DATA-TRIGGER="focus" TITLE="Ajuda" DATA-CONTENT="Keywords são nada mais do que certas palavras colocadas em uma determinada ordem que são utilizadas para fazer pesquisas nos mecanismos de pesquisa (Google, Bing, Yahoo, etc.).<br> Utilize no máximo <b>20</b> palavras, dando <b>ênfase</b> para as <b>5 primeiras</b> palavras.<br> Separe as palavras por vírgulas."></div>
                            <DIV CLASS="msg-validation">
                                <TEXTAREA CLASS="form-control count-caractere" maxlength="150" DATA-MAX-CARACTERE="150" NAME="settings-site-keywords" ID="settings-site-keywords" STYLE="resize:none; min-height:60px !important" data-form-control="accept-terms" required disabled></TEXTAREA>
                            </DIV>
                            <DIV CLASS="restante-caractere pull-right"></DIV>
                            <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
                            <DIV CLASS="help-block with-errors"></DIV>  
                        </DIV>
                        
                        <div class="clearfix space-30"></div>
                        <button type="submit" class="btn btn-primary btn-flat" data-form-control="accept-terms" disabled>Salvar Mudanças</button>
                        
                    </form> 

                </div>
            </div><!--//.box SEO & Social Shares-->

        </div><!--//.col -->

    
    <DIV CLASS="clearfix space-20"></DIV>
    
  </DIV>
</SECTION> 

<script src="/plugins/bootstrap-validator-master/dist/validator.min.js"></script>

<?php
   /* }
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