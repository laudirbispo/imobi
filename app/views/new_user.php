<?php
if( $_SESSION['user_type'] == 'administrador' or $_SESSION['user_type'] == 'suporte' )
{
?>
<SECTION CLASS="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <H4 CLASS="text-darkgray"><STRONG>Novo Usuário</STRONG></H4>
            <OL CLASS="breadcrumb bg-white">
                <LI><a href="/app/admin/home"><I CLASS="fa fa-home"></I></a></LI>
                <LI><a href="/app/admin/users"><I CLASS="fa fa-users"></I> Usuários</a></LI>
                <LI><a href="/app/admin/new_user"><I CLASS="fa fa-user-plus"></I> Novo Usuário</a></LI>
            </OL>
        </div>
    </div>
</SECTION>

<DIV CLASS="space30"></DIV>

<SECTION CLASS="container-fluid">
  <DIV CLASS="row">

    <DIV CLASS="col-lg-8 col-md-8 col-sm-8 col-xs-12">
    
    <form name="form-new-user" method="POST" id="form-new-user" action="/app/modules/users/insert_user.php" role="form" DATA-TOGGLE="validator" enctype="APPLICATION/X-WWW-FORM-URLENCODED" NOVALIDATE  data-action="submit-ajax" data-form-reset="reset">
    
      <input type="HIDDEN" name="user_id" value="<?php echo $_SESSION['user_id'] ?>">
      <input type="HIDDEN" name="form-token" value="<?php echo $_SESSION['secret_form_token'] ?>">
    
      <DIV CLASS="box box-solid" STYLE="min-height:296px;">
        <DIV CLASS="box-header ">
          <STRONG>Cadastrar novo usuário</STRONG>
        </DIV>        
        <DIV CLASS="box-body">

            <DIV CLASS="col-lg-6 col-md-6 col-sm-6 col-xs-12">
              
              <DIV CLASS="form-group">
                <LABEL FOR="user-login"><SPAN CLASS="required">*</SPAN> Login </LABEL>
                <DIV CLASS="msg-validation">
                  <input type="text" class="form-control" name="user-login" id="user-login" REQUIRED maxlength="16" DATA-MINLENGTH="6" pattern="([\.a-z,0-9,A-Z,@_-]+)">
                  <DIV CLASS="help-block with-errors"></DIV>
                </DIV>
              </DIV>
              
              <DIV CLASS="form-group">
                <LABEL FOR="user-type"><SPAN CLASS="required">*</SPAN> Tipo de usuário</LABEL>
                <DIV CLASS="msg-validation">
                  <SELECT CLASS="form-control" NAME="user-type" ID="user-type" REQUIRED> 
                    <OPTION VALUE="">---</OPTION>
                    <OPTION VALUE="administrador">Administrador</OPTION>
                    <OPTION VALUE="auxiliar">Auxiliar de administrador</OPTION>
                    <OPTION VALUE="convidado">Convidado</OPTION>
                  </SELECT>
                  <DIV CLASS="help-block with-errors"></DIV>
                </DIV>
              </DIV>
              
              <DIV CLASS="checkbox">
                <LABEL>
                 <input type="checkbox" name="user-active" id="user-active"> Criar usuário mas mante-lo inativo
                </LABEL>
              </DIV>  
              
            </DIV><!--//.col-->
            
            <DIV CLASS="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            
               <DIV CLASS="form-group">
                <LABEL FOR="user-password"><SPAN CLASS="required">*</SPAN> Senha </LABEL>
                  <DIV CLASS="msg-validation">
                    <input type="password" autocomplete="OFF" class="form-control views-password" name="user-password" id="user-password" REQUIRED maxlength="16" DATA-MINLENGTH="8" >
                    <DIV CLASS="help-block with-errors"></DIV>
                  </DIV>
                </DIV>
                
                <DIV CLASS="form-group">
                  <LABEL FOR="user-confirm-password"><SPAN CLASS="required">*</SPAN> Confirme a senha </LABEL> 
                  <DIV CLASS="msg-validation">
                    <input type="password" class="form-control" name="user-confirm-password" id="user-confirm-password" REQUIRED  DATA-MINLENGTH="8" maxlength="16" DATA-MATCH="#user-password" DATA-MATCH-ERROR="Hoopss! A senha digitada não confere com o valor acima." >
                    <DIV CLASS="help-block with-errors"></DIV>
                  </DIV>
                </DIV>
              
              <DIV CLASS="progress " ID="progress-strongh-pass" STYLE="margin-bottom:2px !important">
                <DIV CLASS="progress-bar progress-bar-default" ROLE="progressbar" ARIA-VALUEMIN="0" ARIA-VALUEMAX="100" STYLE="width: 0%" ID="bar-strong-pass">
                </DIV>
              </DIV>
              <SPAN ID="output-strong-pass"></SPAN>
              
              <DIV CLASS="clearfix space-20"></DIV>
              
              <DIV CLASS="checkbox pull-right">
                <LABEL FOR="show-password">
                 <input type="checkbox" id="show-password"> Mostrar senha
                </LABEL>
              </DIV>  
              <a href="javascript:;" class="btn btn-flat btn-success" id="input-generate-password">Gerar Senha</a>
       
          </DIV>   <!--//.col 6--> 

         </DIV> <!--//.box-body--> 
      
        <DIV CLASS="box-footer">
          <button type="SUBMIT" class="btn btn-primary btn-flat"><I CLASS="fa fa-user-plus" ARIA-HIDDEN="true"></I> Criar Usuário</button>
        </DIV>      
      </DIV><!--//.box--> 
    </form>
  
    <DIV ID="return-insert-user"></DIV>
  
    </DIV><!--//.cl-8--> 
     
    <DIV CLASS="col-lg-4 col-md-4 col-sm-4 col-xs-12">
      <DIV CLASS="callout callout-warning">
        <H4><STRONG>Atenção</STRONG></H4>
        Tenha cuidado ao ceder permissão de administrador ao novo usuário, lembre-se que ele terá controle total ao sistema!
      </DIV>
      <DIV CLASS="callout callout-info">
        <H4><STRONG>Importante</STRONG></H4>
        <STRONG>Criando uma senha segura</STRONG>
        <UL>
          <LI>A senha deve conter entre 8 e 16 caracteres;</LI>
          <LI>Deve conter ao menos 1 número, 1 letra maiúscula, 1 letra minúscula e 1 caractere especial;</LI>
          <LI>Não use espaços nos campos de senha e de login.</LI>
        </UL>
      </DIV>        
    </DIV><!--//.col-->
      
    <DIV CLASS="clearfix space-30"></DIV>
     
    
  </DIV>
</SECTION>

<script src="/plugins/bootstrap-validator-master/dist/validator.min.js"></script>
<script src="/app/javascript/users.js"></script>
<?php
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