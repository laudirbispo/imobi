<?php
$con_db = new config\connect_db();
$con = $con_db->connect();

$profile_info = $con->prepare("SELECT id, user_id, user_name, user_profile_about, user_profile_photo, user_profile_facebook, user_profile_google, user_profile_twitter, user_profile_linkedin FROM user_profile WHERE user_id = ? ");
$profile_info->bind_param('i', $_SESSION['user_id']);
$profile_info->execute();
$profile_info->store_result();
$profile_info->bind_result($id, $user_id, $user_name, $user_profile_about, $user_profile_photo, $user_profile_facebook, $user_profile_google, $user_profile_twitter, $user_profile_linkedin);
$profile_info->fetch();
$profile_info_rows = $profile_info->affected_rows;
$profile_info->free_result();
$profile_info->close();

if( $profile_info or $profile_info_rows > 0 )
{
    
?>
<SECTION CLASS="container-fluid">
  <DIV CLASS="row">
  
    <DIV CLASS="col-lg-4 col-md-4 col-sm-12 col-xs-12">
    
      <DIV CLASS="box box-widget widget-user-2">
        <!-- Add the bg color to the header using any of the bg-* classes -->
        <DIV CLASS="widget-user-header bg-lite bg-cover" STYLE="position:relative; background-image:url('/docs/users/456593/cover-images/123242343443.jpg');">
          <DIV CLASS="widget-user-image" id="widget-user-image">
            <IMG CLASS="img-circle"  data-toggle="modal" data-target="#change-photo" SRC="<?php echo $_SESSION['user_photo']?>" ALT="User Avatar">
          </DIV>
          <!-- /.widget-user-image -->
          <H2 CLASS="widget-user-username text-capitalize"><?php echo $_SESSION['user_name']?></H2>
          <br>
        </DIV>
        <DIV CLASS="box-footer no-padding">
          <UL CLASS="nav nav-stacked">
            <LI><a href="javascript:;" class="link"  data-toggle="modal" data-target="#change-photo"><I CLASS="fa fa-camera"></I> Alterar imagem de perfil</a></LI>
            <LI CLASS="hidden"><a href="#">Completed Projects <SPAN CLASS="pull-right badge bg-green">12</SPAN></a></LI>
            <LI CLASS="hidden"><a href="#">Followers <SPAN CLASS="pull-right badge bg-red">842</SPAN></a></LI>
          </UL>
        </DIV>
      </DIV>
      
      <DIV CLASS="box box-solid hidden">
          <DIV CLASS="box-header">
            <STRONG>E-mail</STRONG>
          </DIV>        
          <DIV CLASS="box-body">
              <form name="form-" method="POST" id="form-change-password" action="/app/modules/users/user_change_password.php" role="form" DATA-TOGGLE="validator" enctype="APPLICATION/X-WWW-FORM-URLENCODED" autocomplete="OFF" data-action="submit-ajax" data-form-reset="reset">
                  
                  <p class="text-success"><i class="fa fa-check-circle"></i> E-mail verificado</p>
                  <DIV CLASS="form-group has-feedback">
                      <DIV CLASS="msg-validation">
                          <input type="email" autocomplete="OFF" class="form-control" name="user-email" id="user-email" REQUIRED placeholder="E-mail">
                      </DIV>
                      <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
                      <DIV CLASS="help-block with-errors"></DIV>
                  </DIV>
                  <p><a href="">Reenviar e-mail de autenticação</a>  </p>
                 <button type="SUBMIT" class="btn btn-primary btn-flat"> Atualizar e-mail</button> 
              </form>  
          </DIV>
      </DIV>
      
     <form name="form-change-password" method="POST" id="form-change-password" action="/app/modules/users/user_change_password.php" role="form" DATA-TOGGLE="validator" enctype="APPLICATION/X-WWW-FORM-URLENCODED" autocomplete="OFF" data-action="submit-ajax" data-form-reset="reset">
 
      <DIV CLASS="box box-solid" STYLE="min-height:296px;" id="box-password">
        <DIV CLASS="box-header">
          <STRONG>Redefinir senha</STRONG>
        </DIV>        
        <DIV CLASS="box-body">
          
            <input type="HIDDEN" name="user_id" value="<?php echo $_SESSION['user_id']  ?>">
            <input type="HIDDEN" name="form-token" value="<?php echo $_SESSION['secret_form_token'] ?>">
            
            <DIV CLASS="form-group">
              <LABEL FOR="user-password-current" CLASS="control-label"><SPAN CLASS="required">*</SPAN> Senha atual </LABEL>
              <input type="password" autocomplete="OFF" class="form-control" name="user-password-current" id="user-password-current" REQUIRED maxlength="16" DATA-MINLENGTH="8" >
              <DIV CLASS="help-block with-errors"></DIV>
             </DIV>

             <DIV CLASS="form-group has-feedback">
               <LABEL FOR="user-password" CLASS="control-label"><SPAN CLASS="required">*</SPAN> Nova Senha </LABEL>
               <DIV CLASS="msg-validation">
                 <input type="password" autocomplete="OFF" class="form-control views-password" name="user-password" id="user-password" REQUIRED maxlength="16" DATA-MINLENGTH="8" >
               </DIV>
               <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
               <DIV CLASS="help-block with-errors"></DIV>
             </DIV>
            
             <DIV CLASS="form-group has-feedback">
               <LABEL FOR="user-confirm-password" CLASS="control-label"><SPAN CLASS="required">*</SPAN> Confirme a senha </LABEL> 
               <DIV CLASS="msg-validation">
                 <input type="password" class="form-control" name="user-confirm-password" id="user-confirm-password" REQUIRED  DATA-MINLENGTH="8" maxlength="16" DATA-MATCH="#user-password" DATA-MATCH-ERROR="Hoopss! A senha digitada não confere com o valor acima." >
               </DIV>
               <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
               <DIV CLASS="help-block with-errors"></DIV>  
             </DIV>
          
             <DIV CLASS="progress hidden" ID="progress-strongh-pass" STYLE="margin-bottom:2px !important">
              <DIV CLASS="progress-bar progress-bar-default" ROLE="progressbar" ARIA-VALUEMIN="0" ARIA-VALUEMAX="100" STYLE="width: 0%" ID="bar-strong-pass"></DIV>
             </DIV>
             <SPAN ID="output-strong-pass"></SPAN>
          
             <DIV CLASS="clearfix space-20"></DIV>
          
             <a href="javascript:;" class="pull-right" id="show-password" title="Mostrar/ocultar senha"><i class="fa fa-2x fa-eye"></i></a> 
             
            <a href="javascript:;" class="btn btn-flat btn-success btn-flat" id="input-generate-password">Gerar Senha</a>

            <DIV CLASS="clearfix"></DIV>
            
            <DIV ID="return-change-password"></DIV> 
             
          </DIV> <!--//.box-body--> 
        
          <DIV CLASS="box-footer">
            <button type="SUBMIT" class="btn btn-primary btn-flat"><I CLASS="fa fa-key" ARIA-HIDDEN="true"></I> Redefina a senha</button>
          </DIV>      
        </DIV><!--//.box-->
      
      </form>

    </DIV><!--//.COL-->
    
    
    <DIV CLASS="col-lg-8 col-md-8 col-sm-12 col-xs-12">
    
      <form action="/app/modules/users/user_update_profile.php" name="form-update-profile" id="form-update-profile" enctype="APPLICATION/X-WWW-FORM-URLENCODED" NOVALIDATE  role="form" DATA-TOGGLE="validator" autocomplete="OFF" data-action="submit-ajax" data-form-reset="noreset">
        
        <input type="HIDDEN" name="user_id" value="<?php echo $_SESSION['user_id']  ?>">
        <input type="HIDDEN" name="form-token" value="<?php echo $_SESSION['secret_form_token'] ?>">
        
        <DIV CLASS="box box-widget">
          <DIV CLASS="box-header">
            <H4><STRONG>Informações básicas</STRONG></H4>
          </DIV>
          <DIV CLASS="box-body">
        
            <DIV CLASS="form-group has-feedback">
              <LABEL FOR="profile-name" CLASS="control-label">Nome completo:</LABEL>             
                <input type="text" class="form-control" name="profile-name" id="profile-name" REQUIRED maxlength="50" DATA-MINLENGTH="6" value="<?php echo $user_name ?>">              
              <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
              <DIV CLASS="help-block with-errors"></DIV>
            </DIV>
            
            <DIV CLASS="form-group has-feedback">
              <LABEL FOR="profile-facebook" CLASS="control-label">Facebook</LABEL>
              <DIV CLASS="input-group">
                <SPAN CLASS="input-group-addon hidden-xs"><I CLASS="fa fa-facebook"></I></SPAN>
                <input type="URL" class="form-control" name="profile-facebook" id="profile-facebook" value="<?php echo $user_profile_facebook ?>">
              </DIV>
              <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
              <DIV CLASS="help-block with-errors"></DIV>
            </DIV>
            
            <DIV CLASS="form-group has-feedback">
              <LABEL FOR="profile-google" CLASS="control-label">Google+</LABEL>
              <DIV CLASS="input-group">
                <SPAN CLASS="input-group-addon hidden-xs"><I CLASS="fa fa-google-plus"></I></SPAN>
                <input type="URL" class="form-control" name="profile-google" id="profile-google" value="<?php echo $user_profile_google ?>">
              </DIV>
              <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
              <DIV CLASS="help-block with-errors"></DIV>
            </DIV>
            
            <DIV CLASS="form-group has-feedback">
              <LABEL FOR="profile-twitter" CLASS="control-label">Twitter</LABEL>
              <DIV CLASS="input-group">
                <SPAN CLASS="input-group-addon hidden-xs"><I CLASS="fa fa-twitter"></I></SPAN>
                <input type="URL" class="form-control" name="profile-twitter" id="profile-twitter" value="<?php echo $user_profile_twitter ?>">
              </DIV>
              <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
              <DIV CLASS="help-block with-errors"></DIV>
            </DIV>
            
            <DIV CLASS="form-group has-feedback">
              <LABEL FOR="profile-linkedin" CLASS="control-label">LinkedIn</LABEL>
              <DIV CLASS="input-group">
                <SPAN CLASS="input-group-addon hidden-xs"><I CLASS="fa fa-linkedin"></I></SPAN>
                <input type="URL" class="form-control" name="profile-linkedin" id="profile-linkedin" value="<?php echo $user_profile_linkedin ?>">
              </DIV>
              <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
              <DIV CLASS="help-block with-errors"></DIV>
            </DIV>
            
            <DIV CLASS="form-group has-feedback">
              <LABEL FOR="profile-about" CLASS="control-label">Sobre você</LABEL>
              <DIV CLASS="input-group">
                <TEXTAREA CLASS="form-control formitem crollbar-custom" NAME="profile-about" ID="profile-about" MAXLENGTH="2048" STYLE="min-width:100% !important; resize:none; display:block !important" COLS="100" ROWS="8"><?php echo $user_profile_about ?></TEXTAREA>
              </DIV>
              <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
              <DIV CLASS="help-block with-errors"></DIV>
            </DIV>
            
          </DIV>
          <DIV CLASS="box-footer">
            <button type="SUBMIT" class="btn btn-primary btn-flat"><I CLASS="fa fa-floppy-o" ARIA-HIDDEN="true"></I> Salvar informações</button>
          </DIV>
        </DIV>
      
      </form>
     
    </DIV><!--//.COL-->
     
  </DIV>  
</SECTION>


<div class="modal fade" tabindex="-1" role="dialog" id="change-photo">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><i class="fa fa-picture-o"></i> Escolha uma foto nova para seu perfil</h4>
      </div>
      <div class="modal-body">
       
       <span class="text-info"><i class="fa fa-info-circle"> </i> Imagens com fundo transparente não são aceitas.</span>
       
        <div class="clearfix space-20"></div>
        <div class="image-editor">
          <div class="clearfix"></div>
          <div class="cropit-preview"></div>
          <div class="image-size-label"></div>
          
          <div class="clearfix space-20"></div>
          
          <div class="col-md-2 col-sm-2 col-xs-3">
              <i class="fa fa-picture-o text-mediumgray pull-right"></i>
          </div>
          <div class="col-md-8 col-sm-8 col-xs-6" id="zoom-image-profile">
            <input type="range" class="cropit-image-zoom-input" title="Zoom">
          </div>
          <div class="col-md-2 col-sm-2 col-xs-3">
              <i class="fa fa-picture-o fa-2x text-mediumgray" style="margin-top: -5px !important;"></i>
          </div>
          
          <div class="clearfix space-20"></div>
          
          <div class="col-md-4 col-sm-4 col-xs-12">
              <label for="input-change-photo-profile" class="center-block">
                <a class="btn btn-primary btn-flat"><i class="fa fa-upload"></i> Escolher imagem</a>
              </label>
              <input type="file" id="input-change-photo-profile" class="cropit-image-input" style="visibility:hidden; display:none">     
          </div>
          
          <div class="col-md-8 col-sm-8 col-xs-12">
              <button class="rotate-ccw btn btn-flat no-margin" title="Girar para esquerda"><i class="fa fa-rotate-left"></i></button>
              <button class="rotate-cw btn btn-flat " title="Girar para a direita"><i class="fa fa-rotate-right"></i></button>
              <button class="export btn btn-flat btn-success" id="export-image"><i class="fa fa-save"></i> Recortar e salvar</button>
              <button type="button" class="btn btn-flat btn-danger" data-dismiss="modal">Cancelar</button>
          </div>
          
        </div>

        <div class="clearfix space-20"></div>
        <div id="error-crop" ></div>
        
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script src="/plugins/bootstrap-validator-master/dist/validator.min.js"></script>
<script src="/plugins/Crop/dist/jquery.cropit.js"></script>
<script src="/app/javascript/users.js"></script>

<script>
$(document).ready(function() {
$('.image-editor').cropit({
  exportZoom: 1.25,
  imageBackground: true,
  imageBackgroundBorderWidth: 30,
  allowDragNDrop: true,        
});

$('.rotate-cw').click(function() {
  $('.image-editor').cropit('rotateCW');
});

$('.rotate-ccw').click(function() {
  $('.image-editor').cropit('rotateCCW');
});

$('.export').click(function() {
  var imageData = $('.image-editor').cropit('export');
  var btnCrop = $('#export-image').html('<i class="fa fa-spinner fa-pulse fa-fw"></i>Aguarde <span class="sr-only">carregando...</span>');
  jQuery.ajax({
    type: "POST",
    async: true,
    cache:false,
    url: '/app/modules/users/upload_profile_image.php',
    data: {data_img: imageData},
    dataType: 'json',
    success: function(data)
    { 
        if(data.status === 'success')
        {                     
           location.reload();                   
        }
        else
        {
            show_alert('error','Atenção',data.message,'fa fa-exclamation-triangle',false);
        }

    },
    error: function ()
    {
        show_alert('error','Atenção','A requisição falhou','fa fa-exclamation-triangle',false);
    },

   });
});

});
</script>
<?php
}
else
{
    echo '<div class="alert alert-warning">
         <h4><i class="icon fa fa-warning"></i> Atenção!</h4>
         No momento não foi possível carregar suas informações.<br>
         Se essa mensagem continuar aparecendo, procure por ajuda na página de <a href="/app/admin/support">Ajuda & Suporte</a> ou entre em contato com o administrador.
         </div>';    
}//. Se o select encontrou o usuário
?>


