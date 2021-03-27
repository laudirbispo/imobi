'use strict';

// esta função ativa as animações - animateCss
$.fn.extend({
    animateCss: function (animationName) {
        var animationEnd = 'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend';
        this.addClass('animated ' + animationName).one(animationEnd, function() {
            $(this).removeClass('animated ' + animationName);
        });
    }
});

// mostra a imagem carregada no input file escondido
$(document).on('change', '[data-control="input-file"]', function(){
    var value = $(this).val();
    $('#image-loaded').html('<i class="fa fa-picture-o"></i>' + value);
});

// Desativa envio de forms com ENTER
$(document).ready(function () {
    $('input').keypress(function (e) {
        var code = null;
        code = (e.keyCode ? e.keyCode : e.which);                
        return (code === 13) ? false : true;
    });
});


//------------------------------------------------------------------------

(function ($) {
    $('[DATA-CONTROL="popover-hover"]').popover({
        html: true,
        trigger: 'hover'
    });

    $('[DATA-CONTROL="popover-focus"]').popover({
        html: true,
        trigger: 'focus'
    });
})(jQuery);

(function ($) {
  $('.spinner .btn:first-of-type').on('click', function() {
    $('.spinner input').val( parseInt($('.spinner input').val(), 10) + 1);
  });
  $('.spinner .btn:last-of-type').on('click', function() {
    $('.spinner input').val( parseInt($('.spinner input').val(), 10) - 1);
  });
})(jQuery);
//****************************************************************
// transforma decimal em moeda
//n = numero a converter
//c = numero de casas decimais
//d = separador decimal
//t = separador milhar 
function numeroParaMoeda(n, c, d, t)
{
    c = isNaN(c = Math.abs(c)) ? 2 : c, d = d === undefined ? "," : d, t = t === undefined ? "." : t, s = n < 0 ? "-" : "", i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "", j = (j = i.length) > 3 ? j % 3 : 0;
    return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
}

//****************************************************************
// enable input submit se existir algum checkbox marcado
$(document).on('change', '[data-control="select-all"]', function(){   
    if( $(this).is(':checked') )
    { 
        $('[data-control="checkebox-del"]').prop("checked", true); 
        $('[data-control="submit-button"]').prop('disabled', false);
        $('[data-control="checkebox-del"]').parent().parent().addClass("active"); 
    }
    else
    {
        $('[data-control="checkebox-del"]').prop("checked", false); 
        $('[data-control="submit-button"]').prop('disabled', true);
        $('[data-control="checkebox-del"]').parent().parent().removeClass("active");
    }
    
});

$(document).on('change', '[data-control="checkebox-del"]', function(){
    var checados = $('[data-control="checkebox-del"]:checked').length;
    
    if( checados > 0)
    {
        $('[data-control="submit-button"]').prop('disabled',false);   
    }
    else
    {
        $('[data-control="submit-button"]').prop('disabled', true);
        $('[data-control="select-all"]').prop('checked', false);
    }
    
    if( $(this).is(':checked') )
    {
        $(this).parent().parent().addClass("active");    
    }
    else
    {
        $(this).parent().parent().removeClass("active");
    }
});

//****************************************************************
// filtra registros em divs
$(function(){
    var elemSearch = '[data-control="elem-filter"]';
    $(document).on('keyup', '[data-control="search-filter"]', function(){
        var stringPesquisa = $(this).val();
        $(elemSearch).find('.animated').removeClass('animated');
        if( stringPesquisa !== ""){
            $(elemSearch).hide();
            $(elemSearch+':containsi('+stringPesquisa+')').show();
        } 
        else{
            $(elemSearch+':containsi('+stringPesquisa+')').show();
        } 
        
    });  
});
$.extend($.expr[':'], {
  'containsi': function(elem, i, match, array)
  {
    return (elem.textContent || elem.innerText || '').toLowerCase()
    .indexOf((match[3] || "").toLowerCase()) >= 0;
  }
});
// função para filtrar registros de tabela 
$(function(){
    $("#filter-table").keyup(function(){
        var tabela = $(this).attr('data-table');       
        if( $(this).val() !== ""){
            $("#"+tabela+" tbody>tr").hide();
            $("#"+tabela+" td:contains-ci('" + $(this).val() + "')").parent("tr").show();           
        } else{
            $("#"+tabela+" tbody>tr").show();
        }
    }); 
});
$.extend($.expr[":"], {
    "contains-ci": function(elem, i, match, array) {
        return (elem.textContent || elem.innerText || $(elem).text() || "").toLowerCase().indexOf((match[3] || "").toLowerCase()) >= 0;
    }
});
//***************************************************************************************
//seleciona imagens para excluir
$(document).on('change', '[data-control="checkebox-del"]', function(){
    
    var div = $(this).parent().parent();
    
    if( $(this).is(':checked') )
    {
      $(div).addClass("selected");
    }
    else
    {
      $(div).removeClass("selected");
      $('#select-all-images').prop('checked', false);
    }

});

$(document).on("change", "#select-all-images", function(){    
    if( $(this).is(':checked') )
    { 
        $('[data-control="checkebox-del"]').prop("checked", true); 
        $('[data-control="submit-del-images"]').prop('disabled', false);
        $('[data-control="checkebox-del"]').parent().parent().addClass("selected"); 
    }
    else
    {
        $('[data-control="checkebox-del"]').prop("checked", false); 
        $('[data-control="submit-del-images"]').prop('disabled', true);
        $('[data-control="checkebox-del"]').parent().parent().removeClass("selected");
    }
});

$(document).on("change", '[data-control="checkebox-del"]', function(){
    var checados = $('[data-control="checkebox-del"]:checked').length;
    if( checados > 0)
    {
        $('[data-control="submit-del-images"]').prop('disabled',false);   
    }
    else
    {
        $('[data-control="submit-del-images"]').prop('disabled', true);
        $('#select-all-images').prop('checked', false);
    }
});    

//***************************************************************************************
// o número de caracteres restantes
$(document).ready(function (){
    $(".count-caractere").bind("input keyup paste", function (){
        var maximo = $(this).attr('data-max-caractere');
        var disponivel = maximo - $(this).val().length;
        if(disponivel < 0) 
        {
            var texto = $(this).val().substr(0, maximo); 
            $(this).val(texto);
            disponivel = 0;
        }
        $(this).closest('.form-group').find(".restante-caractere").html('<span class="text-danger">'+disponivel+ '</span> caracteres restantes');
    });
});


//****************************************************************
// altera o tipo do input para ver a senha
$(document).on('change', '#show-password', function(){
  if( $(this).is(':checked') )
  {
    $('.views-password').attr('type', 'text'); 
  }
  else
  {
    $('.views-password').attr('type', 'password'); 
  }
});
//****************************************************************
// Gera uma senha rândomica
$(document).on("click", "#input-generate-password", function(e){
  e.preventDefault();
  $.get("/app/modules/users/generate_password.php", function(data) {
    $("#user-password").val(data);
    $("#user-confirm-password").val(data);
    stronghPass();
  });
});
//****************************************************************
// função universal para submit de formulários
$('[data-action="submit-ajax"]').validator().on('submit', function (e) {
  var thisForm = $(this); 
  if (e.isDefaultPrevented()) {
    //alert('invalid form');
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
             buttonSubmit.html('<i class="fa fa-check aria-hidden="true"></i>' + data.message );
             buttonSubmit.removeClass('btn-primary').addClass('btn-success');         
             setTimeout(function(){ 
                buttonSubmit.html(btnReset);
                buttonSubmit.removeClass('btn-success').addClass('btn-primary');      
             }, 5000); 
             
             if( tableReload === 'true' )
             {
                 $('[data-control="data-reload"]').load(location.href+' [data-control="data-reload"]>*', function(){
                     new EagerImageLoader();
                 }).fadeOut('slow').fadeIn('slow'); 
             }

             
         }
         else if(data.status === 'warning')
         {
             buttonSubmit.html(btnReset);
             show_alert('warning','Atenção',data.message,'fa fa-exclamation-triangle',false);
         }
         else if(data.status === 'info')
         {
             buttonSubmit.html(btnReset);
             show_alert('info','Atenção',data.message,'fa fa-info',false);
         }
         else 
         {
             buttonSubmit.html(btnReset);
             show_alert('error','Atenção',data.message,'fa fa-meh-o',false);
         }
    
      },
      error: function ()
      {
        buttonSubmit.html(btnReset);
        show_alert('error','Atenção','O servidor não está respondendo','fa fa-meh-o',false);
      }
       
    }); 
    buttonSubmit.html(btnReset);
    buttonSubmit.prop('disabled', false);
    
  }
});


//****************************************************************
function setProgressBar()
{
    $('#image-loaded').html('');
    $("#status-progress").html('');
    $("#bar-progress").css('width','0%');
    $("#bar-progress").removeClass('progress-bar-success').addClass('progress-bar-info');
    $('#div-progress').addClass('hidden');
}
//****************************************************************

// ativa os botões e inputs se o usuário aceitar os termos
$(document).on('change', '[data-action="accept-terms"]', function() {
    if( $(this).is(':checked') )
    { 
        $('[data-form-control="accept-terms"]').prop("disabled", false); 
    }
    else
    {
        $('[data-form-control="accept-terms"]').prop("disabled", true); 
    }
});

function show_alert(type,title,text,icon,desktop) {
    var opts = {
        type: type,
        title: title,
        text: text,
        addclass: "stack-topright",
        icon: icon,
        animate_speed: "fast",
        shadow: false,
        styling:"bootstrap3",
        desktop: { desktop: desktop},
        animate: {
            animate: true,
            in_class: 'fadeInDown',
            out_class: 'fadeInUp',
        }
    };
    new PNotify(opts);
}

(function($){
    //draggable e resizable no mesmo elemento
    $('[data-indentification="resizable-draggable"]').resizable({handle: '.box', minHeight: 200, minWidth: 300}).draggable({ handle: '.box-header', scroll: 'true' });
    // somente resizable
    $('[data-indentification="resizable"]').resizable({ minHeight: 200, minWidth: 300});
    //somente draggable
    $('[data-indentification="draggable"]').draggable({ handle: '.box-header', scroll: 'true' });
     
})(jQuery);

// Remove element do dom draggable and droppable 
(function($){
    
    $('.draggable').draggable({
        connectToSortable: "#droppable-trash",
        handle: '.box-header', 
        scroll: true,
        snap:true,
        start: function() {
            $('.droppable-trash').show().addClass('droppable-trash-hover').removeClass('fadeOutRight').addClass('fadeInRight'); 
        },
        drag: function() {
        },
        stop: function() {
            $('.droppable-trash').removeClass('droppable-trash-hover').removeClass('fadeInRight').addClass('fadeOutRight').hide('slow');
        }
    });
    $('.resizable').resizable({ minHeight: 200, minWidth: 200});
    
    $('#droppable-trash').droppable({
        revert: "invalid",
        hoverClass: "droppable-trash-hover",
        drop: function(event, ui) {
             ui.draggable.css({'height': '50px', 'width': '50px', 'margin-top': '80px', 'margin-left': '100px'});
             ui.draggable.addClass('animated, fadeOutRight');
             $(ui.draggable).fadeOut(100, function () {   
                $('.droppable-trash').removeClass('droppable-trash-hover').removeClass('fadeInRight').addClass('fadeOutRight').hide('slow');
                $(this).remove();
             });
        },
        deactivate: function() {
            $('.droppable-trash').removeClass('droppable-trash-hover').removeClass('fadeInRight').addClass('fadeOutRight').hide('slow');
        },

    });
     
    function playAudio(soundUrl)
    {
        var audioElement = document.createElement('audio');
        audioElement.setAttribute('src', soundUrl);
        audioElement.setAttribute('autoplay', 'autoplay');
        //audioElement.load()
        $.get();
        audioElement.addEventListener("load", function() {
        audioElement.play();
        });
    };
    
})(jQuery);

