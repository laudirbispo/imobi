<?php
require_once ($_SERVER['DOCUMENT_ROOT'].'/config/config.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/config/autoload.php');
session_name(SESSION_NAME);
session_start();
$_SESSION['user_auth'] = 'N';

if(empty($_SESSION['user_id']) or !isset($_SESSION['user_id']))
{
    unset($_SESSION);
   	session_destroy();
    header("Location: /app/login");
}
$return = isset($_GET['return']) ? base64_decode($_GET['return']) : '/app/admin/home' ;

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pt-BR">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>loires:</title>
<link href="/app/images/logo-loires-ico.png"  rel="shortcut icon">
<meta name="author" content="Laudir Bispo">
<meta http-equiv="content-language" content="pt-br">
<!-- Tell the browser to be responsive to screen width -->
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport"/>
<!-- Bootstrap 3.3.6 -->
<link rel="stylesheet" href="/app/css/bootstrap.min.css">
<link rel="stylesheet" href="/app/css/font-awesome.min.css">
<link rel="stylesheet" href="/app/css/admin.css">
<link href="/app/css/AdminLTE.min.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="/app/css/_all-skins.min.css">
<META http-equiv="refresh" content="3600; url=/app/controls/logout.php">
<META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">    
<NOSCRIPT>
  <meta http-equiv="refresh" content="0; url=noscript.html">
</NOSCRIPT>
</head>
<body class="hold-transition bg-gradient-<?php echo mt_rand(1,10); ?>">
<!-- Automatic element centering -->
<div class="lockscreen-wrapper" style="background-color: #FFFFFFB3;padding: 30px 15px;border-radius: 5px;">
    
  <!-- User name -->
  <div class="lockscreen-name text-center"><?php echo $_SESSION['user_name'] ?></div>

  <!-- START LOCK SCREEN ITEM -->
  <div class="lockscreen-item">
    <!-- lockscreen image -->
    <div class="lockscreen-image">
      <img SRC="<?php echo $_SESSION['user_photo'] ?>" alt="User Image">
    </div>
    <form class="lockscreen-credentials" action="/app/controls/reAuthUser.php" method="post" name="form-reauth" id="form-reauth" enctype="application/x-www-form-urlencoded" autocomplete="off">
      <input type="hidden" name="secret-form-token" value="<?php echo $_SESSION['secret_form_token'] ?>"> 
      <div class="input-group">
        <input type="password" class="form-control" placeholder="password" name="password" id="password" autocomplete="off">

        <div class="input-group-btn">
          <button type="submit" class="btn"><i class="fa fa-arrow-right text-muted"></i></button>
        </div>
          
      </div>
        <a href="javascript:;" class="pull-right visible-xs" id="show-password" title="Mostrar/ocultar senha"><i class="fa fa-2x fa-eye"></i></a>
        
    </form>
    <!-- /.lockscreen credentials -->     
    </div>
    
  <!-- /.lockscreen-item -->
  <p class="text-danger text-center" id="resposta-auth"></p> 
  <div class="help-block text-center">
    Digite sua senha para recuperar sua sessão
  </div>
  <div class="text-center">
    <a href="/app/controls/logout.php">Ou faça login com um usuário diferente</a>
  </div>
    
    <p class="text-center"> <small>Notamos que você ficou um tempo ausente, para proteger seus dados, bloqueamos o acesso ao painel.<br>
      Você pode continuar suas tarefas normalmente após nos informar sua senha!</small>
    </p>
  <div class="lockscreen-footer text-center">
   <STRONG>Copyright &copy; 2016 <a href="http://grupositefacil.com.br">loires</a>.</STRONG> <br>Todos os direitos reservados.
  </div>
</div>
<!-- /.center -->

<!-- Retirar após atualizar o bootstrap usar o de baixo-->
<script src="/libs/jQuery/jquery-2.2.3.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="/app/javascript/bootstrap.min.js"></script>
<!-- EagerImageLoader  -->
<script src="/plugins/EagerImageLoader/eager-image-loader.min.js"></script>
<script> new EagerImageLoader();</script>
<script>
$(document).on('click', "#show-password", function(){
  if( $('#password').attr('type') === 'password' )
  {
      $('#password').attr('type', 'text'); 
      $(this).find('i').removeClass('fa-eye').addClass('fa-eye-slash');
  }
  else
  {
      $('#password').attr('type', 'password'); 
      $(this).find('i').removeClass('fa-eye-slash').addClass('fa-eye');
  }
});
</script>
<script>
$('#form-reauth').on('submit', function (e) {
    e.preventDefault();
    
    var formDados = jQuery(this).serialize();
    var formUrl = jQuery(this).attr('action');
    
    jQuery.ajax({
      type: "POST",
      async:true,
      cache:false,
      url: formUrl,
      dataType: 'json',
      data: formDados,
      success: function( data )
      {  
         if(data.status === 'success')
         {   
             location.href= '<?php echo $return ?>';
         }
         else 
         {
             $('.lockscreen-item').css('border','2px solid #a94442');
             $('.lockscreen-image').css('background','#a94442');
             $('#resposta-auth').html(data.message);
         }

      },
      error: function ()
      {
          $('#resposta-auth').html('Servidor não está respondendo.');
      }

    }); 
}); 
</script>
<script async defer>
$(document).ready(function(){
    
    setInterval(function() {$.get('/app/modules/users/users_status.php', {status: '2'} );
    }, 5000);
    
    
 })       
</script>
</body>
</html>
