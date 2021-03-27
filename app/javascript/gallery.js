'use strict';

// função para selecionar capa de álbum
jQuery(document).on('click', '.define-capa-album', function(){
  var Tid = $(this).attr('data-album');
  var url = '/app/modules/gallery/select_capa.php';
  var img = $(this).attr('data-img');
  
  $.get(url, {img: img, id: Tid}, function(data){
    $('#alert-fix').hide().html( data ).slideDown('slow');
    setTimeout(function(){ $('#alert-fix').slideUp('slow');	}, 10000);
  }); 
  
});

//*******************************************************************
// seleciona todas as imagens para excluir
// enable input submit se existir algum checkbox marcado

$('[data-type="checkebox-all"]').on('ifChanged', function(){
  if( $(this).is(':checked')  )
  {     
    $(this).parent().parent().parent().addClass("active"); 
  }
  else
  {
    $('#submit-del-images').prop("disabled", true);
    $(this).parent().parent().parent().removeClass("active");
  }
  
  if( $('[data-type="checkebox-all"]').is(':checked')  )
   {
     $('#submit-del-images').prop("disabled", false); 
   }
   else
   {
    $('#select-all-images').iCheck('uncheck');
   }
});

$('#select-all-images').on('ifChanged', function(){
  if( $(this).is(':checked')  )
  {
    $('[data-type="checkebox-all"]').iCheck('check'); 
    $('[data-type="checkebox-all"]').parent().parent().parent().addClass("active");
  }
  else
  {
    $('[data-type="checkebox-all"]').iCheck('uncheck');
    $('[data-type="checkebox-all"]').parent().parent().parent().removeClass("active"); 
  }
});

//*********************************************************
$(document).on('click', '.add-legenda', function(){
  
  var subtitle = $(this).attr('data-subtitle');
  var id_image = $(this).attr('data-id');
  
  $.confirm({
    title: 'Legenda',
    closeIcon: true,
    cancelButton: false, // hides the cancel button.
    confirmButton: false, // hides the confirm button.
    content: 'url:/app/modules/gallery/subtitle_image.php?id-image='+id_image,
    confirm: function() {}
  });
});

//*********************************************************
//deleta o álbum
$(document).on("click", '[data-control="del-album"]', function(){
  var Aid = $(this).attr('data-album-id');
  var alb = $(this).parent().parent();

  $.confirm({
    icon: 'fa fa-warning',
    title: 'Você tem certeza?',
    content: 'Após o processo finalizado, o mesmo não poderá ser desfeito!',
    confirmButton: 'Sim',
    cancelButton: 'Não',
    backgroundDismiss: true,
    autoClose: 'cancel|10000',
    confirmButtonClass: 'btn-info',
    cancelButtonClass: 'btn-danger',
    confirm: function()
    {
      $.get('/app/modules/gallery/delete_album.php', {id: Aid}, function(data){
        
        if(data.status === 'success')
        {
          //$("#albuns").load(location.href+" #albuns>*","").fadeOut('slow').fadeIn('slow'); 
          $(alb).toggle("slide", { direction: "left" }, 500);            
        }
        else if(data.status === 'warning')
         {
             $('#alert-fix').hide().html('<div class="alert alert-warning alert-dismissible">' +
             '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>' +
             '<h4><i class="icon fa fa-exclamation-triangle"></i> Atenção!</h4>' + data.message +'</div>').slideDown('slow');	
             setTimeout(function(){ $('#alert-fix').slideUp('slow');	}, 10000);
         }
         else 
         {
             $('#alert-fix').hide().html('<div class="alert alert-danger alert-dismissible">' +
             '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>' +
             '<h4><i class="icon fa fa-ban"></i> Atenção!</h4>' + data.message +'</div>').slideDown('slow');	
             setTimeout(function(){ $('#alert-fix').slideUp('slow');	}, 10000);
         }
        
      });
    },
    cancel:function(){}
  });
  
}); 

