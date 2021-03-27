'use strict';

// retorna o tupo de imóvel de acordo com os segmento escolhido
$(document).on('change', '#properties-segment', function() {
    
    var segmento =  $(this).find(':selected').val();
    var propertiesType = $('#properties-type');
    
    if( segmento === 'residencial')
    {
        propertiesType.html('<OPTION VALUE="apartamento">Apartamento</OPTION>' +
                            '<OPTION VALUE="casa">Casa</OPTION>' +
                            '<OPTION VALUE="cobertura">Cobertura</OPTION>' +
                            '<OPTION VALUE="sobrado">Sobrado</OPTION>' +
                            '<OPTION VALUE="flat">Flat</OPTION>' +
                            '<OPTION VALUE="loft">Loft</OPTION>' +
                            '<OPTION VALUE="pousada">Pousada</OPTION>' +
                            '<OPTION VALUE="terreno">Terreno</OPTION>' +
                            '<OPTION VALUE="prédio">Prédio</OPTION>' +
                            '<OPTION VALUE="quarto">Quarto</OPTION>' +
                            '<OPTION VALUE="quitinete">Quitinete</OPTION>' +
                            '<OPTION VALUE="studio">Studio</OPTION>' + 
                            '<OPTION VALUE="outro">Outro</OPTION>');
    }
    else if( segmento === 'comercial' )
    {
        propertiesType.html('<OPTION VALUE="barracão">Barracão/Galpão/Depósito/Armazém</OPTION>' +
                            '<OPTION VALUE="casa-comercial">Casa comercial</OPTION>' +
                            '<OPTION VALUE="sala-comercial">Sala Comercial</OPTION>' +
                            '<OPTION VALUE="loja">Loja</OPTION>' +
                            '<OPTION VALUE="shopping">Loja no Shopping</OPTION>' +
                            '<OPTION VALUE="ponto-comercial">Pontos Comerciais</OPTION>' +
                            '<OPTION VALUE="prédio-comercial">Prédio Comercial</OPTION>' +
                            '<OPTION VALUE="pousada">Pousada</OPTION>' +
                            '<OPTION VALUE="prédio">Prédio</OPTION>' +
                            '<OPTION VALUE="box">Box/Garagem</OPTION>' +
                            '<OPTION VALUE="studio">Studio</OPTION>' +
                            '<OPTION VALUE="outro">Outro</OPTION>');
    }
	else if( segmento === 'residencialcomercial' )
    {
        propertiesType.html('<OPTION VALUE="barracão">Barracão/Galpão/Depósito/Armazém</OPTION>' +
                            '<OPTION VALUE="casa-comercial">Casa comercial</OPTION>' +
                            '<OPTION VALUE="sala-comercial">Sala Comercial</OPTION>' +
                            '<OPTION VALUE="loja">Loja</OPTION>' +
                            '<OPTION VALUE="shopping">Loja no Shopping</OPTION>' +
                            '<OPTION VALUE="ponto-comercial">Pontos Comerciais</OPTION>' +
                            '<OPTION VALUE="prédio-comercial">Prédio Comercial</OPTION>' +
                            '<OPTION VALUE="pousada">Pousada</OPTION>' +
                            '<OPTION VALUE="prédio">Prédio</OPTION>' +
                            '<OPTION VALUE="box">Box/Garagem</OPTION>' +
                            '<OPTION VALUE="studio">Studio</OPTION>' +
                            '<OPTION VALUE="outro">Outro</OPTION>' +
							'<OPTION VALUE="apartamento">Apartamento</OPTION>' +
                            '<OPTION VALUE="casa">Casa</OPTION>' +
                            '<OPTION VALUE="cobertura">Cobertura</OPTION>' +
                            '<OPTION VALUE="sobrado">Sobrado</OPTION>' +
                            '<OPTION VALUE="flat">Flat</OPTION>' +
                            '<OPTION VALUE="loft">Loft</OPTION>' +
                            '<OPTION VALUE="pousada">Pousada</OPTION>' +
                            '<OPTION VALUE="terreno">Terreno</OPTION>' +
                            '<OPTION VALUE="prédio">Prédio</OPTION>' +
                            '<OPTION VALUE="quarto">Quarto</OPTION>' +
                            '<OPTION VALUE="quitinete">Quitinete</OPTION>' +
                            '<OPTION VALUE="studio">Studio</OPTION>' + 
                            '<OPTION VALUE="outro">Outro</OPTION>');
    }
    else if( segmento === 'rural' )
    {
        propertiesType.html('<OPTION VALUE="chácara">Chácara</OPTION>' +
                            '<OPTION VALUE="fazenda">Fazenda</OPTION>' +
                            '<OPTION VALUE="haras">Haras</OPTION>' +
                            '<OPTION VALUE="sítio">Sítio</OPTION>' +
                            '<OPTION VALUE="outro">Outro</OPTION>');
    }
    else
    {
        propertiesType.html('<OPTION VALUE="">Escolha um segmento</OPTION>');
    }
    
});

//---------------------------------------------------------------------------------

// altera o status do imóvel - vendido/alugado/nenhum
jQuery(document).on('click', '[data-control="change-status-properties"]', function(){
    var status = $(this).attr('data-control-value');
    var tid = $(this).attr('data-tid');
    var container = $(this).closest('.card');
    
    jQuery.get("/app/modules/properties/change_status.php", {status: status, tid: tid}, function(data){
         if(data.status === 'success')
         {   
            if( status === 'vendido')
            {
                container.find('.sale-status').remove();
                container.prepend('<span class="sale-status text-capitalize">vendido</span>');
            }
            else if( status === 'alugado')
            {
                container.find('.sale-status').remove();
                container.prepend('<span class="sale-status text-capitalize">alugado</span>');
            }
            else if( status === 'remove')
            {
                container.find('.sale-status').remove();
            }
            else
            {
                return false;
            }
             
         }
         else if(data.status === 'warning')
         {
             show_alert('warning','Atenção',data.message,'fa fa-exclamation-triangle',false);
         }
         else if(data.status === 'info')
         {
             show_alert('info','Atenção',data.message,'fa fa-info',false);
         }
         else 
         {
             show_alert('error','Atenção',data.message,'fa fa-meh-o',false);
         }
    });
    
});

//---------------------------------------------------------------------------------

$(document).on('change', '[data-control="remove-required"]', function(){  
    var inputAction = $(this).attr('data-action');
    
    if( $(this).is(':checked') )
    { 
        $(inputAction).prop("required", false); 
    }
    else
    {
        $(inputAction).prop("required", true); 
    }
      
});

//------------------------------------------------------------------------
$(document).on('change', '[data-control="near"]', function(){      
    if( $(this).is(':checked') )
    {
        $(this).parent().addClass('checked');
    }
    else
    {
        $(this).parent().removeClass('checked');
    }
});

//------------------------------------------------------------------------
// Deleta um imóvel
$(document).on("click", '[data-control="del-properties"]', function(){
    var actionid = $(this).attr('data-tid');
    var container = $(this).closest('.card');

    $.confirm({
        icon: 'fa fa-warning',
        title: 'Você tem certeza?',
        btnClass: 'btn-flat',
        confirmButton: 'Continuar',
        cancelButton: 'Cancelar',
        backgroundDismiss: true,
        autoClose: 'cancel|10000',
        confirmButtonClass: 'btn-primary',
        cancelButtonClass: 'btn-danger',
        content: 'Esta ação deletará todas as imagens, videos, localização, estatísticas, do servior e do banco de dados. Esta ação não poderá ser desfeita.',
        confirm: function()
        {  
            $.get('/app/modules/properties/delete_properties.php', {tid: actionid}, function(data){
                if(data.status === 'success')
                {
                    container.parent().remove();
                    show_alert('success',data.message,false,'fa fa-check',false);       
                }
                else if(data.status === 'warning')
                 {
                     show_alert('warning','Atenção',data.message,'fa fa-exclamation-triangle',false);
                 }
                 else if(data.status === 'info')
                 {
                     show_alert('info','Atenção',data.message,'fa fa-info',false);
                 }
                 else 
                 {
                     show_alert('error','Atenção',data.message,'fa fa-meh-o',false);
                 }
            });
        },
        cancel:function(){}
      
    }); 
}); 

//----------------------------------------------------------------------------------------
$('#form-add-properties').validator().on('submit', function (e) {
  var thisForm = $(this); 
 
  if (e.isDefaultPrevented()) {
    // handle the invalid form...
  } 
  else 
  {
    e.preventDefault();
    var formDados = jQuery(this).serialize();
    var formUrl = thisForm.attr('action');
    var formReset = thisForm.attr('data-form-reset');
    var tableReload = thisForm.attr('data-reload');
    var buttonSubmit = thisForm.find(':submit');
    var btnReset = buttonSubmit.html();
    buttonSubmit.html('<i class="fa fa-refresh fa-fw fa-spin"></i> Aguarde...');
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
             if(formReset === 'reset'){thisForm.each(function(){this.reset();});}
             buttonSubmit.html('<i class="fa fa-check></i>' + data.message );
             buttonSubmit.removeClass('btn-primary').addClass('btn-success');         
             setTimeout(function(){ 
                buttonSubmit.html(btnReset);
                buttonSubmit.removeClass('btn-success').addClass('btn-primary');
                
             }, 5000); 
             resetValidator(thisForm);
         
             $('#response-add').html('<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><h4>Imóvel cadastrado. O que deseja fazer agora?</h4><a href="/app/admin/images_properties/' + data.link + '" class="btn btn-flat btn-warning" role="button"><i class="fa fa-picture-o"></i> Adicionar imagens</a><a href="/app/admin/preview_property/' + data.link + '" class="btn btn-flat btn-primary margin-left" role="button"><i class="fa fa-external-link-square"></i> Prever</a></div>');
             
         }
         else if(data.status === 'warning')
         {
             buttonSubmit.html(btnReset);
             show_alert('warning','Atenção',data.message,'fa fa-2x fa-exclamation-triangle',false);
         }
         else if(data.status === 'info')
         {
             buttonSubmit.html(btnReset);
             show_alert('info','Atenção',data.message,'fa fa-info',false);
         }
         else 
         {
             buttonSubmit.html(btnReset);
             show_alert('error','Atenção',data.message,'fa fa-2x fa-meh-o',false);
         }
    
      },
      error: function ()
      {
        buttonSubmit.html(btnReset);
        show_alert('error','Atenção','O servidor não está respondendo','fa fa-meh-o',false);
      }
       
    }); 
    buttonSubmit.prop('disabled', false);
    
  }
});

//----------------------------------------------------------------------------------------

//  function to define layers
jQuery(document).on('click', '[data-control="set-cover"]', function(){
  var tid = $(this).attr('data-tid');
  var url = $(this).attr('data-ajax-url');
  var img = $(this).attr('data-img');

  $.get(url, {img: img, tid: tid}, function(data){
    if(data.status === 'success')
     {   
        show_alert('success',false,data.message,'fa fa-check',false); 
     }
     else if(data.status === 'warning')
     {
         show_alert('warning','Atenção',data.message,'fa fa-exclamation-triangle',false);
     }
     else if(data.status === 'info')
     {
         show_alert('info','Atenção',data.message,'fa fa-info',false);
     }
     else 
     {
         show_alert('error','Atenção',data.message,'fa fa-meh-o',false);
     }
  }).fail(function() { show_alert('error','Atenção','Ocorreu um erro inesperado ao executar está ação. Tente novamente.','fa fa-meh-o',false); }); 
  
});
//----------------------------------------------------------------------------------------


$(document).on('click', '.featured', function(e){
    e.preventDefault();
    var action = $(this).attr('data-action');
    var tid = $(this).attr('data-tid');
    var el = $(this);
    
    if(action != 'featured' && action != 'unfeatured')
    {
        show_alert('warning','Atenção','Está ação não é possível.','fa fa-exclamation-triangle',false);
        return false;
    }
    
    $.get('/app/modules/properties/featured_properties.php', {action: action, tid: tid}, function(data){
        if(data.status === 'success')
        {   
            el.find('i').animateCss('rotateIn');
            
            if(action == 'featured')
            {
                el.find('i').removeClass('fa-star-o').addClass('fa-star');
                el.attr('data-action', 'unfeatured');
                el.attr('title', 'Remover dos destaques');
            }
            else if(action == 'unfeatured')
            {
                el.find('i').removeClass('fa-star').addClass('fa-star-o');
                el.attr('data-action', 'featured');
                el.attr('title', 'Marcar como destaque');
            }
            else
            {
                show_alert('warning','Atenção','Está ação não é possível.','fa fa-exclamation-triangle',false);
            }
                
        }
        else if(data.status === 'warning')
        {
            show_alert('warning','Atenção',data.message,'fa fa-exclamation-triangle',false);
        }
        else if(data.status === 'info')
        {
            show_alert('info','Atenção',data.message,'fa fa-info',false);
        }
        else 
        {
            show_alert('error','Atenção',data.message,'fa fa-meh-o',false);
        }
    }).fail(function() { show_alert('error','Atenção','Ocorreu um erro inesperado ao executar está ação. Tente novamente.','fa fa-meh-o',false); }); 
});


//----------------------------------------------------------------------------------------


$(document).on('click', '.view', function(e){
    e.preventDefault();
    var action = $(this).attr('data-action');
    var tid = $(this).attr('data-tid');
    var el = $(this);
    
    if(action != 'hidden' && action != 'show')
    {
        show_alert('warning','Atenção','Está ação não é possível.','fa fa-exclamation-triangle',false);
        return false;
    }
    
    $.get('/app/modules/properties/change_visibility_properties.php', {action: action, tid: tid}, function(data){
        if(data.status === 'success')
        {  
            el.find('i').animateCss('rotateIn');
            
            if(action == 'show')
            {
                el.find('i').removeClass('fa-eye-slash').addClass('fa-eye');
                el.attr('data-action', 'hidden');
                el.attr('title', 'Oculto no meu site/tornar visível');
            }
            else if(action == 'hidden')
            {
                el.find('i').removeClass('fa-eye').addClass('fa-eye-slash');
                el.attr('data-action', 'show');
                el.attr('title', 'Visível no meu site/tornar invisível');
            }
            else
            {
                show_alert('warning','Atenção','Está ação não é possível.','fa fa-exclamation-triangle',false);
            }
                
        }
        else if(data.status === 'warning')
        {
            show_alert('warning','Atenção',data.message,'fa fa-exclamation-triangle',false);
        }
        else if(data.status === 'info')
        {
            show_alert('info','Atenção',data.message,'fa fa-info',false);
        }
        else 
        {
            show_alert('error','Atenção',data.message,'fa fa-meh-o',false);
        }
    }).fail(function() { show_alert('error','Atenção','Ocorreu um erro inesperado ao executar está ação. Tente novamente.','fa fa-meh-o',false); }); 
});
 

$('#form-settings-logomarca').validator().on('submit', function (e) {
    var thisForm = $(this); 
    
    if (e.isDefaultPrevented()) 
    {
    // handle the invalid form...
    } 
    else 
    {
        e.preventDefault();
        var formData = new FormData(this); 
        var formUrl = thisForm.attr('action');
        var formReset = thisForm.attr('data-form-reset');
        var buttonSubmit = thisForm.find(':submit');
        var btnReset = buttonSubmit.html();
        buttonSubmit.html('<i class="fa fa-refresh fa-fw fa-spin aria-hidden="true"></i> Aguarde...');
        buttonSubmit.prop('disabled', true);
        $('#div-progress').removeClass('hidden');
        
        jQuery.ajax({
            xhr: function()
            {
                var xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener("progress", function(evt){
                if (evt.lengthComputable) {
                    var percentComplete = Math.ceil(((evt.loaded) / evt.total) * 100);
                    $("#status-progress").html(percentComplete + "% completo");
                    $("#bar-progress").css('width', percentComplete+'%');
                }
                }, false);          
                xhr.addEventListener("load", function (evt) {
                    $("#status-progress").html("Upload completo");
                    $("#bar-progress").removeClass('progress-bar-info').addClass('progress-bar-success');
                    $("#status-progress").html('');
                    $("#bar-progress").css('width', '0%');
                    $('#div-progress').addClass('hidden');
                }, false);
               return xhr;          
            },
            type: 'POST',
            url:formUrl,
            dataType: 'json',
            async:true,
    	    data: formData,
    	    mimeType:"multipart/form-data",
    	    contentType: false,
    	    cache: false,
    	    processData:false,
            success: function(data)
            {  
                 if(data.status === 'success')
                 {   
                    setTimeout(function(){ 
                        buttonSubmit.html(btnReset);
                        buttonSubmit.removeClass('btn-success').addClass('btn-primary');   
                     }, 5000);
                     show_alert('success',data.message,false,'fa fa-check',false);
                 }
                 else 
                 {
                     show_alert('warning','Atenção',data.message,'fa fa-meh-o',false);
                 }
            },
            error: function() 
            {
                setTimeout(function(){ 
                    buttonSubmit.html(btnReset);
                    buttonSubmit.removeClass('btn-success').addClass('btn-primary');   
                 }, 5000); 
                show_alert('error','Atenção','Ocorreu um erro inesperado ao executar está ação. Tente novamente.','fa fa-meh-o',false);
                
            } 
       
        }); 
        
        
    }//if form valid
    buttonSubmit.html(btnReset);
    buttonSubmit.prop('disabled', false);
    $("#bar-progress").removeClass('progress-bar-success').addClass('progress-bar-info');
});

$(function(){

    $(document).on('click', '[data-action="load-info-client"]', function(){
        var idClient = $(this).attr('data-client-id');
        var topPosition  = $(document).scrollTop();
        $('#resize').fadeOut(300, function() { $(this).remove(); });
        
        if(idClient === '' || idClient === undefined) 
        {
            return show_alert('warning','Atenção','Selecione um cliente para continuar!','fa fa-info',false);
        }
        else
        {   
            jQuery.get("/app/modules/contracts/get_info_client.php", {actionid: idClient}, function(data){
                
                if (data.status === 'success')
                {
                    
                    var boxClientInfo = '<aside class="box-resize" id="resize">' +
                        '<div class="box-header" id="drag-user-info" alt="Mantenha clicado e me arraste">' +
                        '<i class="fa fa-user"></i> Informações do cliente' +
                        '<div class="box-tools pull-right">' +
                        '<button type="button" class="btn btn-box-tool" data-action="close-box"><i class="fa fa-times"></i></button>' +
                        '</div>' +
                        '</div>' +
                        '<div class="box-body" style="line-height:15px">' +
                        '<p><strong class="text-blue">Nome: </strong><span class="text-mediumgray">'+data.name+'</span></p>' +
                        '<p><strong class="text-blue">Razão Social: </strong><span class="text-mediumgray">'+data.social+'</span></p>' +
                        '<p><strong class="text-blue">Nome Fantasia: </strong><span class="text-mediumgray">'+data.fantasy+'</span></p>' +
                        '<p><strong class="text-blue">CNPJ: </strong><span class="text-mediumgray">'+data.cnpj+'</span></p>' +
                        '<p><strong class="text-blue">CPF: </strong><span class="text-mediumgray">'+data.cpf+'</span></p>' +
                        '<p><strong class="text-blue">RG: </strong><span class="text-mediumgray">'+data.rg+'</span></p>' +
                        '<p><strong class="text-blue">Tipo: </strong><span class="text-mediumgray">'+data.type+'</span></p>' +
                        '<p><strong class="text-blue">Endereço: </strong><span class="text-mediumgray">'+data.address+'</span></p>' +
                        '<p><strong class="text-blue">Telefones: </strong><span class="text-mediumgray">'+data.phones+'</span></p>' +
                        '<p><strong class="text-blue">E-mail: </strong><span class="text-mediumgray">'+data.email+'</span></p>' +
                        '<p><strong class="text-blue">Estado Civil: </strong><span class="text-mediumgray">'+data.marital+'</span></p>' +
                        '<p><strong class="text-blue">Observações: </strong><span class="text-mediumgray">'+data.obs+'</span></p>' +
                        '</div>' +
                        '</aside>';

                        $(boxClientInfo).appendTo('body').fadeIn(4000);
                        $('#resize').css('top', topPosition + 10);
                        $('#resize').resizable({ grid: [10, 10]}).draggable({ handle: '.box-header', scroll: 'true' });
                        // remove aside draggable
                        $(document).on('click', '[data-action="close-box"]', function(){
                            $(this).closest('.box-resize').remove();
                        });
                    }
                    else 
                    {
                        return show_alert(data.status,'Atenção',data.message,'fa fa-info',false);
                    }
            });
        }
        
    });
    
});