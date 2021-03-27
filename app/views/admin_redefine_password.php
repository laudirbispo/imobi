<?php
if( $_SESSION['user_type'] == 'administrador' or $_SESSION['user_type'] == 'suporte' )
{
    if(!isset($_GET['actionid']) or empty($_GET['actionid']))
    {
         echo '<div class="alert alert-warning">
              <h4><i class="icon fa fa-warning"></i> Atenção!</h4>
              Nenhum usuário identificado com essas credenciais.<br>
              Verifique se o endereço está correto, se essa mensagem continuar aparecendo, procure por ajuda na página de <a href="/app/admin/support">Ajuda & Suporte</a> ou entre em contato com o administrador.
              </div>'; 
    }
    else if(base64_decode($_GET['actionid']) == $_SESSION['user_id'])
    {
        echo '<div class="alert alert-danger">
              <h4><i class="icon fa fa-ban"></i> ação bloqueada!</h4>
              Você não pode alterar sua senha por aqui!<br>Caso queirá alterar sua senha <a href="/app/admin/my_account#box-password">clique aqui</a>.
              </div>';
    }
    else
    {
        $actionid = filterString(base64_decode($_GET['actionid']), 'INT');

        $con_db = new config\connect_db();
        $con = $con_db->connect();
        
        $user = $con->prepare("SELECT su.type, su.login, pro.user_name, pro.user_profile_photo FROM sec_users su LEFT JOIN user_profile pro ON (pro.user_id = su.id) WHERE su.id = ?");
        $user->bind_param('i', $actionid);
        $user->execute();
        $user->store_result();
        $user->bind_result($type, $login, $user_name, $user_profile_photo);
        $user->fetch();
        $rows = $user->affected_rows;
        $user->free_result();
        $user->close();
       
        if($user and $rows > 0)
        {              
            if (empty($user_profile_photo) || null === $user_profile_photo)
            {
                $user_profile_photo = SUBDOMAIN_IMGS.'/app/images/default-user.png';
            }
            else
            {
               $user_profile_photo = (fileRemoteExist(SUBDOMAIN_IMGS.$user_profile_photo) === true) ? SUBDOMAIN_IMGS.$user_profile_photo : SUBDOMAIN_IMGS.'/defaults/default-user.png';
            }

?>
<SECTION CLASS="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <H4 CLASS="text-darkgray"><STRONG>Redefinir Senha</STRONG></H4>
            <OL CLASS="breadcrumb bg-white">
                <LI><a href="/app/admin/home"><I CLASS="fa fa-home"></I></a></LI>
                <LI><a href="/app/admin/users"><I CLASS="fa fa-users"></I> Usuários</a></LI>
                <LI><a href="/app/admin/admin_redefine_password"><I CLASS="fa fa-key"></I> Redefinir senha</a></LI>
            </OL>
        </div>
    </div>
</SECTION>

<DIV CLASS="space30"></DIV>

<SECTION CLASS="container-fluid">
  <DIV CLASS="row">
  
    <form name="form-reset-password" method="POST" id="form-reset-password" action="/app/modules/users/reset_password.php" role="form" DATA-TOGGLE="validator" enctype="APPLICATION/X-WWW-FORM-URLENCODED" NOVALIDATE data-action="submit-ajax" data-form-reset="reset" autocomplete="off">

       <input type="HIDDEN" name="actionid" value="<?php echo @$actionid ?>">
       <input type="HIDDEN" name="user_id" value="<?php echo $_SESSION['user_id'] ?>">
       <input type="HIDDEN" name="form-token" value="<?php echo $_SESSION['secret_form_token'] ?>">
        
        <DIV CLASS="col-md-4 col-sm-6 col-xs-12">

            <DIV CLASS="box box-solid" STYLE="min-height:296px;">
              <DIV CLASS="box-header">
                <STRONG>Redefinir senha</STRONG>
              </DIV>        
              <DIV CLASS="box-body">

                 <DIV CLASS="form-group">
                  <LABEL FOR="user-password"><SPAN CLASS="required">*</SPAN> Nova Senha </LABEL>
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

                <DIV CLASS="progress hidden" ID="progress-strongh-pass" STYLE="margin-bottom:2px !important">
                  <DIV CLASS="progress-bar progress-bar-default" ROLE="progressbar" ARIA-VALUEMIN="0" ARIA-VALUEMAX="100" STYLE="width: 0%" ID="bar-strong-pass">
                  </DIV>
                </DIV>
                <SPAN ID="output-strong-pass"></SPAN>

                <DIV CLASS="clearfix space-20"></DIV>

                <a href="javascript:;" class="pull-right" id="show-password" title="Mostrar/ocultar senha"><i class="fa fa-2x fa-eye"></i></a> 

                <a href="javascript:;" class="btn btn-flat btn-success" id="input-generate-password">Gerar Senha</a>

              </DIV> <!--//.box-body--> 
     
            </DIV><!--//.box-->

        </DIV><!--//.col left-->
        
        <div class="col-md-8 col-sm-6 cl-xs-12">
            <div class="media">
              <div class="media-left">
                <a href="#">
                  <img class="media-object img-circle" src="<?php echo $user_profile_photo ?>" alt="Imagem de perfil" width="64">
                </a>
              </div>
              <div class="media-body">
                <h4 class="media-heading"><?php echo (empty($user_name)) ? $login : $user_name ; ?></h4>
                <div class="form-group">
                    <div class="checkbox">
                      <label>
                         <input type="checkbox" name="user_confirm" required> Este é o usuário que estou alterando a senha
                      </label>
                      <div class="help-block with-errors"></div>
                    </div>
                  </div>
               </div>
            </div>
            <button type="SUBMIT" class="btn btn-primary btn-flat"><I CLASS="fa fa-key"></I> Redefina a senha</button>
        </div>
        
    </form>
    
    <DIV CLASS="clearfix"></DIV>

  </DIV>
</SECTION>

<script src="/plugins/bootstrap-validator-master/dist/validator.min.js"></script>
<script src="/app/javascript/users.js"></script>
<?php
        }
        else
        {
            echo '<div class="alert alert-warning">
                 <h4><i class="icon fa fa-warning"></i> Atenção!</h4>
                 Nenhum usuário identificado com essas credenciais.<br>
                 Verifique se o endereço está correto, se essa mensagem continuar aparecendo, procure por ajuda na página de <a href="/app/admin/support">Ajuda & Suporte</a> ou entre em contato com o administrador.
                 </div>';    
        }//. Se o select encontrou o usuário
        
    }//.se existe $_GET['actionid']
    
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