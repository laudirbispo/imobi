'use strict';

// função para selecionar capa do carro
jQuery(document).on('click', '.define-capa-car', function(){
    var id = $(this).attr('data-id-veiculo');
    var cat = $(this).attr('data-categoria');
    var url = '/app/modules/vehicles/select_capa.php';
    var img = $(this).attr('data-img');
  
    $.get(url, {img: img, id: id, categoria: cat}, function(data){
        if(data.status === 'success')
         {   
             $('#alert-fix').hide().html('<div class="alert alert-success alert-dismissible">' +
             '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>' +
             '<h4><i class="icon fa fa-exclamation-triangle"></i> Atenção!</h4>' + data.message +'</div>').slideDown('slow');	
             setTimeout(function(){ $('#alert-fix').slideUp('slow');}, 10000);
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
             '<h4><i class="icon fa fa-ban"></i> Atenção!</h4>Ocorreu uma falha não identificada. Tente novamente, se o erro persistir entre em contato com o suporte técnico.</div>').slideDown('slow');	
             setTimeout(function(){ $('#alert-fix').slideUp('slow');	}, 10000);
         }
    }); 
});

// Insert car
// função universal para submit de formulários
$('#form-car, #form-motorcycle').validator().on('submit', function (e) {
    
  var thisForm = $(this); 
 
  if (e.isDefaultPrevented()) {
    // handle the invalid form...
  } 
  else 
  {
    e.preventDefault();
    var formDados = jQuery(this).serialize();
    var formUrl = thisForm.attr('action');
    var buttonSubmit = thisForm.find(':submit');
    var btnReset = buttonSubmit.html();
    buttonSubmit.html('<i class="fa fa-refresh fa-fw fa-spin aria-hidden="true"></i> Aguarde...');
    buttonSubmit.prop('disabled', true);
    
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
             buttonSubmit.html(btnReset);
             thisForm.each(function(){this.reset();});
             buttonSubmit.removeClass('btn-primary').addClass('btn-success');
             $('#return-ajax-insert-car').hide().html('<div class="alert alert-success alert-dismissible">' +
             '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>' +
             '<h4><i class="icon fa fa-check"></i>' + data.message + ' </h4><a href="'+data.link+'" class="btn btn-primary" role="button">Deseja adicionar imagens agora?</></div>').slideDown('slow');         
             setTimeout(function(){   
                buttonSubmit.removeClass('btn-success').addClass('btn-primary');
                $('#return-ajax-insert-car').slideUp('slow');
             }, 8000); 
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
             '<h4><i class="icon fa fa-ban"></i> Atenção!</h4>Ocorreu uma falha não identificada. Tente novamente, se o erro persistir entre em contato com o suporte técnico.</div>').slideDown('slow');	
             setTimeout(function(){ $('#alert-fix').slideUp('slow');	}, 10000);
         }
    
      },
      error: function ()
      {
        buttonSubmit.html(btnReset);
        jQuery('#alert-fix').html('<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Um script parou de funcionar, verefique sua conexão com a internet ou tente novamente!</div>');
        setTimeout(function(){ $('#alert-fix').slideUp('slow');	}, 10000);
      }
       
    }); 

    buttonSubmit.prop('disabled', false);
    
  }
});


//********************************************************************

$(document).on('change', '#veiculo-categoria-2', function(){
    var dataAction = $(this).attr('data-action');
    var valueSelected = $(this).find(':selected').val();
    var url = '/app/modules/vehicles/generate_options.php';
    
    $.get(url, {action: dataAction, option: valueSelected }, function(data){
        $('#veiculo-marca-2').html(data);
    });
});

$(document).on('change', '#car-marca', function(){
    var dataAction = 'select_model';
    var valueSelected = $(this).find(':selected').val();
    var categoria = 'car';
    var url = '/app/modules/vehicles/generate_options.php';
    
    $.get(url, {action: dataAction, option: valueSelected, category: categoria }, function(data){
        $('#car-modelo').html(data);
    });
});

$(document).on('change', '#motorcycle-marca', function(){
    var dataAction = 'select_model';
    var valueSelected = $(this).find(':selected').val();
    var categoria = 'motorcycle';
    var url = '/app/modules/vehicles/generate_options.php';
    
    $.get(url, {action: dataAction, option: valueSelected, category: categoria }, function(data){
        $('#motorcycle-modelo').html(data);
    });
});

//*******************************************************************
// seleciona todas as imagens para excluir
// enable input submit se existir algum checkbox marcado

$(document).on('change', '[data-type="checkebox-all"]', function(){
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
    $('#submit-del-images').prop("disabled", true); 
   }
});

//****************************************************************
// input range portas
$(document).on('change', '#car-portas', function(e){
  e.preventDefault();
 var portas = $(this).val();
  $('#range-car-portas').html(portas+' portas');
});

//****************************************************************
// seleciona todos os opcionais
var inputs = $('#checkbox-opcionais').find('input[type=checkbox]');
$(document).on('change', '#check-all-opcionais', function(){
  if( $(this).is(':checked') )
  {
    $(inputs).prop('checked', true);
  }
  else
  {
    $(inputs).prop('checked', false);
  }
});
$(document).on('change', inputs, function(){
  if( $(inputs).is(':checked') )
  {
  }
  else
  {
    $('#check-all-opcionais').prop('checked', false);
  }
});

//********************************************************************
// deleta carro separadamente
$(document).on('click', '[data-control="delete-vehicles"]', function(e){
    
    e.preventDefault();
    var tr  = $(this).parent().parent();
    var id  = $(this).attr('data-id');
    var ct = $(this).attr('data-categoria');
    
    $.confirm({
        title: 'Confirmar exclusão',
        content: 'Você tem certeza que deseja excluir este veículo?',
        confirmButtonClass: 'btn-primary',
        cancelButtonClass: 'btn-default',
        confirmButton: 'Sim',
        cancelButton: 'Cancelar',
        autoClose: 'cancel|10000',
        confirm: function(){
            $.get( "/app/modules/vehicles/delete_vehicles.php", { id: id, categoria: ct }, function(data){
                if(data.status === 'success')
                {
                    $('#table-cars').load(location.href+" #table-cars>*", function(){});
                }
                else
                {  
                    $('#alert-fix').hide().html('<div class="alert alert-danger alert-dismissible">' +
                     '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>' +
                     '<h4><i class="icon fa fa-ban"></i> Atenção!</h4>Ocorreu uma falha não identificada. Tente novamente, se o erro persistir entre em contato com o suporte técnico.</div>').slideDown('slow');	
                     setTimeout(function(){ $('#alert-fix').slideUp('slow');	}, 10000);
                }
            });
        },
        cancel: function(){}
    });
 
});
//****************************************************************************
$(document).on("click", '[data-control="featured-car"]', function(){
  var id = $(this).attr('data-id');
  var action = $(this).attr('data-action');
  var cat = $(this).attr('data-categoria');
  $.get('/app/modules/vehicles/featured_car.php', {id_car: id, action: action, categoria: cat}, function(data){    
      if(data.status === 'success')
      {
          $('#table-cars').load(location.href+" #table-cars>*", function(){});
      }
      else
      {  
          $('#alert-fix').hide().html('<div class="alert alert-danger alert-dismissible">' +
         '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>' +
         '<h4><i class="icon fa fa-ban"></i> Atenção!</h4>Ocorreu uma falha não identificada. Tente novamente, se o erro persistir entre em contato com o suporte técnico.</div>').slideDown('slow');	
         setTimeout(function(){ $('#alert-fix').slideUp('slow');	}, 10000);
      }
  }); 
  
});
//********************************************************************

jQuery('#form-delete-images-vehicles').submit(function(e){

    e.preventDefault(); 
    var formDados = jQuery(this).serialize();
    var formUrl = $(this).attr('action');
    var buttonSubmit = $(this).find('button');
    var btnReset = $(this).find(':submit').html();
    buttonSubmit.html('<i class="fa fa-refresh fa-fw fa-spin aria-hidden="true"></i> Excluindo... ');
    buttonSubmit.prop('disabled', true);
  
    jQuery.ajax({
    type: "POST",
    url: formUrl,
    data: formDados,
    success: function( data )
    { 
        if(data.status === 'success')
        {     
            buttonSubmit.html('<i class="fa fa-check aria-hidden="true"></i>' + data.message );
            buttonSubmit.removeClass('btn-primary').addClass('btn-success');  
            $("#reload-images").load(location.href+" #reload-images>*", function(){
                buttonSubmit.html(btnReset);
                buttonSubmit.removeClass('btn-success').addClass('btn-primary');
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
             '<h4><i class="icon fa fa-ban"></i> Atenção!</h4>Ocorreu uma falha não identificada. Tente novamente, se o erro persistir entre em contato com o suporte técnico.</div>').slideDown('slow');	
             setTimeout(function(){ $('#alert-fix').slideUp('slow');	}, 10000);
         }
            
    },
     
	});  
    buttonSubmit.html(btnReset);
    buttonSubmit.prop('disabled', false);
});

