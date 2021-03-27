<?php
session_name(SESSION_NAME);
session_start();
require_once ($_SERVER['DOCUMENT_ROOT'].'/config/autoload.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/config/config.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/config/public_functions.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/libs/WideImage/WideImage.php');

use config\connect_db;
use app\controls\errors;

$errors = new errors();

if( ($_SESSION['user_master_perms'] !== 'administrador') )
{
    if( $_SESSION['gallery_edit'] !== '1' )
    {
        die($errors->userNotAuthorized());
    }
}

if( empty($_GET['id-image']) or !isset($_GET['id-image']) )
{
    die ($errors->notReferenceId());
}
else
{
    $id_image = filterString($_GET['id-image'], 'INT');
}

$con_db = new config\connect_db();
$con = $con_db->connect();

$subtitle = $con->prepare("SELECT `legenda` FROM `images_gallery` WHERE `id` = ? ");
$subtitle->bind_param('i', $id_image);
$subtitle->execute();
$subtitle->store_result();
$subtitle->bind_result($legenda);
$subtitle->fetch();
$rows = $subtitle->num_rows;
$subtitle->free_result();
$subtitle->close();

if( !$subtitle or $rows <= 0 )
{
    die($con_db->serverFailure());
}

?>

<form action="/app/modules/gallery/update_subtitle.php" name="form-legenda-galeria" id="form-legenda-galeria" method="POST" enctype="APPLICATION/X-WWW-FORM-URLENCODED" data-reload="true" data-action="submit-ajax" data-form-reset="noreset">
  <input type="HIDDEN" name="id-image" value="<?php echo $id_image ?>">
  <TEXTAREA CLASS="form-control count-caractere" DATA-MAX-CARACTERE="1024" MAXLENGTH="1024" name="legenda" STYLE=" width:100% !important; min-height:150px !important; overflow:auto !important; resize:none;margin-bottom:5px; display:table"><?php echo $legenda; ?></TEXTAREA>
  <DIV CLASS="restante-caractere pull-right"></DIV>
  <button class="btn btn-primary" type="submit">Atualizar</button>
</form>

<div CLASS="clearfix space-20"></div>

<script src="/app/javascript/functions.js" DEFER ASYNC></script>
<script>
$('##form-legenda-galeria').submit(function(e){
  e.preventDefault(); 
  //alert('kkkk');

  var formDados = jQuery(this).serialize();
  var formUrl = $(this).attr('action');
  var buttonSubmit = $(this).find('button');
  var btnReset = $(this).find(':submit').html();
  buttonSubmit.html('<i class="fa fa-refresh fa-fw fa-spin aria-hidden="true"></i> Aguarde um momento... ');
  
  jQuery.ajax({
    type: "POST",
    url: formUrl,
    data: formDados,
    success: function( data )
    { 
      if(data === 'success')
      {       
        buttonSubmit.html('<i class="fa fa-check aria-hidden="true"></i> Legenda atualizada');
        setTimeout(function(){ buttonSubmit.html(btnReset);	}, 15000);   
         $("#reload-images").load(location.href+" #reload-images>*", function(){
          new EagerImageLoader(); 
        }).fadeOut('fast').fadeIn('fast');         
      }
      else if(data.status === 'warning')
     {
         buttonSubmit.html(btnReset);
         $('#alert-fix').hide().html('<div class="alert alert-warning alert-dismissible">' +
         '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>' +
         '<h4><i class="icon fa fa-exclamation-triangle"></i> Atenção!</h4>' + data.message +'</div>').slideDown('slow');	
         setTimeout(function(){ $('#alert-fix').slideUp('slow');	}, 10000);
     }
     else 
     {
         buttonSubmit.html(btnReset);
         $('#alert-fix').hide().html('<div class="alert alert-danger alert-dismissible">' +
         '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>' +
         '<h4><i class="icon fa fa-ban"></i> Atenção!</h4>' + data.message +'</div>').slideDown('slow');	
         setTimeout(function(){ $('#alert-fix').slideUp('slow');	}, 10000);
     }
        
    },
  }); 

});

</script>