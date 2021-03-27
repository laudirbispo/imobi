<?php 
require_once ($_SERVER['DOCUMENT_ROOT'].'/config/config.php'); 
require_once ($_SERVER['DOCUMENT_ROOT'].'/config/public_functions.php');
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>loires LOGIN</title>
<link href="/app/css/bootstrap.min.css" rel="stylesheet" type="text/css">
<link href="/app/css/font-awesome.min.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="/app/css/AdminLTE.min.css">
<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700,600,800' rel='stylesheet' type='text/css'>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<NOSCRIPT><meta http-equiv="refresh" content="0; url=noscript.html"></NOSCRIPT>
<META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">
<style>
.login-box-body{background-color:rgba(0,0,0,0.6);color:#FFF!important}.login-logo{background-color:#FFF;color:#FFF!important;margin-bottom:0!important;padding:20px 0!important}.login-logo a{color:#FFF!important}a{color:#FFF!important}a:hover{text-decoration:underline}.response-login{min-height:42px}.logo-color-1{color: #2376F2}
</style>
</head>

<?php 

$bg = array();

$bg[0] = '/app/images/backgrounds/vintage-wallpaper1.jpg';
$bg[1] = '/app/images/backgrounds/vintage-wallpaper2.jpg';
$bg[2] = '/app/images/backgrounds/vintage-wallpaper3.jpg';
$bg[3] = '/app/images/backgrounds/vintage-wallpaper4.jpg';
$bg[4] = '/app/images/backgrounds/vintage-wallpaper5.jpg';

$i = array_rand($bg, 1);

?>

<body class="hold-transition login-page" style="background:url(<?php echo $bg[$i]; ?>) no-repeat center center; overflow-y:hidden;">

<div class="login-box">
      <div class="login-logo">
          <img src="/assets/images/loires.png" class="img-responsive center-block">
      </div>
      <div class="login-box-body">
          <p class="login-box-msg">Iniciar a sessão</p>
          <form action="/app/controls/login.php" method="post" name="form-auth" id="form-auth" enctype="application/x-www-form-urlencoded">       
              <div class="form-group has-feedback">
                  <input class="form-control" placeholder="Login" type="text" name="login" required>
                  <span class="glyphicon glyphicon-user form-control-feedback"></span>
              </div>
              <div class="form-group has-feedback">
                  <input class="form-control" placeholder="Password" type="password" name="password" id="password" required>
                  <span class="glyphicon glyphicon-lock form-control-feedback"></span>
              </div>
              <div class="row">
                  <div class="col-xs-6 ">
                      <button type="submit" name="submit-login" class="btn btn-primary btn-block btn-flat">Entrar</button>
                  </div>
                  <div class="col-xs-6">
                      <div class="checkbox icheck hidden">
                          <label style="padding-left: 20px;">
                              <input type="checkbox" name="remember" class="no-padding">
                              Mantenha-me conectado
                          </label>
                      </div>
                      <a href="javascript:;" class="pull-right" id="show-password" title="Mostrar/ocultar senha"><i class="fa fa-2x fa-eye"></i></a>
                  </div>
              </div>
          </form>
          <a href="#" class="hidden">Esqueci minha senha</a><br>
          <div class="clearfix"></div>
          
          <div class="response-login"></div>
          <p class="text-center no-margin"><small><?php echo auto_copyright(2015); ?></small></p> 
    </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<script src="/libs/jQuery/jquery-2.2.3.js" CROSSORIGIN="anonymous"></script>
<script src="/app/javascript/bootstrap.min.js" CROSSORIGIN="anonymous"></script>
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
$('#form-auth').on('submit', function (e) {
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
             location.href="/app/admin";
         }
         else if(data.status === 'info')
         {   
             $('.response-login').html('<div class="alert alert-info alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>' + data.message + '</div>');
         }
         else if(data.status === 'warning')
         {   
             $('.response-login').html('<div class="alert alert-warning alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>' + data.message + '</div>');
         }
         else 
         {
             $('.response-login').html('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>' + data.message + '</div>');
         }

      },
      error: function ()
      {
          $('.response-login').html('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>O servidor não está respondendo.</div>');
      }

    }); 
}); 
</script>
</body>
</html>
    