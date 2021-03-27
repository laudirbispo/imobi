'use strict';

$('#form-slides').validator().on('submit', function (e) {
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
        var tableReload = thisForm.attr('data-reload');
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
                //Do something with upload progress
                    $("#status-progress").html(percentComplete + "% completo");
                    $("#bar-progress").css('width', percentComplete+'%');
                }
                }, false);
            
                xhr.addEventListener("load", function (evt) {
                    $("#status-progress").html("Upload completo");
                    $("#bar-progress").removeClass('progress-bar-info').addClass('progress-bar-success');
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
                     if(formReset === 'reset'){thisForm.each(function(){this.reset();});}
                     buttonSubmit.html('<i class="fa fa-check aria-hidden="true"></i>' + data.message );
                     buttonSubmit.removeClass('btn-primary').addClass('btn-success');         
                     setTimeout(function(){ 
                        buttonSubmit.html(btnReset);
                        buttonSubmit.removeClass('btn-success').addClass('btn-primary');
                        
                     }, 5000); 
                    
                     setProgressBar();
                     resetValidator(thisForm);
                     
                     if( tableReload === 'true' )
                     {
                         $('[data-control="data-reload"]').load(location.href+' [data-control="data-reload"]>*', function(){
                             new EagerImageLoader();
                             sortableSlides();
                         }).fadeIn('slow'); 
                     }
                 }
                 else if(data.status === 'warning')
                 {
                     buttonSubmit.html(btnReset);
                     $('#alert-fix').hide().html('<div class="alert alert-warning alert-dismissible">' +
                     '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>' +
                     '<h4><i class="icon fa fa-exclamation-triangle"></i> Atenção!</h4>' + data.message +'</div>').slideDown('slow');	
                     setTimeout(function(){ $('#alert-fix').slideUp('slow');}, 10000);
                     setProgressBar();
                 }
                 else 
                 {
                     buttonSubmit.html(btnReset);
                     $('#alert-fix').hide().html('<div class="alert alert-danger alert-dismissible">' +
                     '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>' +
                     '<h4><i class="icon fa fa-ban"></i> Atenção!</h4>Ocorreu uma falha não identificada. Tente novamente, se o erro persistir entre em contato com o suporte técnico.</div>').slideDown('slow');	
                     setTimeout(function(){ $('#alert-fix').slideUp('slow');	}, 10000);
                     setProgressBar();
                 }
            },
            error: function() 
            {
                buttonSubmit.html(btnReset);
                jQuery('#alert-fix').html('<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Um script parou de funcionar, verefique sua conexão com a internet ou tente novamente!</div>');
                setTimeout(function(){ $('#alert-fix').slideUp('slow');	}, 10000);
                
            } 
       
        }); 
        
        buttonSubmit.html(btnReset);
        buttonSubmit.prop('disabled', false);
    }//if form valid
});
//=======================================================================================================================

function sortableSlides()
{
    $("#sortable").sortable({
        opacity: 0.5,
        cursor: "move",
        update: function() {
            var order   = $('#sortable').sortable('serialize');
            $.get( "/app/modules/slides/reordered_slides.php?"+order, {  }, function(data){
                if( data.status !== 'success')
                {
                    $('#alert-fix').hide().html('<div class="alert alert-danger alert-dismissible">' +
                     '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>' +
                     '<h4><i class="icon fa fa-ban"></i> Atenção!</h4>Ocorreu uma falha não identificada. Tente novamente, se o erro persistir entre em contato com o suporte técnico.</div>').slideDown('slow');	
                     setTimeout(function(){ $('#alert-fix').slideUp('slow');	}, 10000);
                }
            });
        }
    });
    $("#sortable").disableSelection();
}

//=======================================================================================================================
// deleta slides separadamente
$(document).on('click', '[data-control="delete-slides"]', function(e){
    
    e.preventDefault();
    var id = $(this).attr('data-id');
    var it = $(this).parent().parent();
    
    $.confirm({
        icon: 'fa fa-trash',
        title: 'Deseja continuar?',
        content: 'Após o processo finalizado o mesmo não poderá ser desfeito.',
        confirmButtonClass: 'btn-primary',
        cancelButtonClass: 'btn-danger',
        confirmButton: 'Sim',
        cancelButton: 'Cancelar',
        autoClose: 'cancel|10000',
        confirm: function(){
            $.get( "/app/modules/slides/delete_slide.php", { id: id }, function(data){
                if(data.status === 'success')
                {               
                    $("#reload-slides").load(location.href+" #reload-slides>*", function(){
                        new EagerImageLoader();
                    }).fadeIn('fast');             
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
        },
        cancel: function(){}
    });
 
});