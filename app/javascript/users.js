'use strict';

// Bloqueia o usuário
$(document).on("click", '[data-control="user-bloq"]', function(){
  var elem = $(this);
  var user_id = $(this).attr('data-user-id');
  var action = $(this).attr('data-action');
  var stateLabel = $(this).parent().parent().parent().parent().parent().parent().find('[data-control="user-state"]');
  $.get('/app/modules/users/user_bloq.php', {user_id: user_id, action: action}, function(data){
    
    if(data.status === 'success')
    {
        if (action === 'user-lock')
        {
            stateLabel.removeClass('label-success').addClass('label-danger');
            stateLabel.html('Bloqueado');
            elem.attr('data-action', 'user-unlock');
            elem.html('<I CLASS="fa fa-unlock text-green"></I> Desbloquear este usuário');
        }
        else if (action === 'user-unlock')
        {
            stateLabel.removeClass('label-danger').addClass('label-success');
            stateLabel.html('Ativo');
            elem.attr('data-action', 'user-lock');
            elem.html('<I CLASS="fa fa-lock text-red"></I> Bloquear este usuário');
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
         show_alert('info','Aviso',data.message,'fa fa-info-circle',false);
    }
    else 
    {
         show_alert('error','Atenção','O servidor não está respondendo','fa fa-meh-o',false);
    }
    
  }); 
  
});
//*********************************************************

// Deleta um usuário
$(document).on("click", '[data-function="del-user"]', function(){
    var container = $(this).closest('.box');
    var actionid = $(this).attr('data-user-id');
    $.confirm({
        icon: 'fa fa-warning',
        title: 'Você tem certeza?',
        btnClass: 'btn-flat',
        confirmButton: 'Continuar',
        cancelButton: 'Cancelar',
        backgroundDismiss: true,
        autoClose: 'cancel|30000',
        confirmButtonClass: 'btn-primary',
        cancelButtonClass: 'btn-default',
        content: '' +
        '<p>Informe sua senha para continuar</p>' +
        '<input type="password" name="password" id="del-password" placeholder="Password" class="name form-control" required />',
        confirm: function()
        {
            var password = this.$content.find('#del-password').val();
            $.get('/app/modules/users/user_delete.php', {actionid: actionid, user_password: password}, function(data){
                if(data.status === 'success')
                {
                    container.parent().remove();
                    show_alert('success','Sucesso!',data.message,'fa fa-check',false);       
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

//***************************************************************
// Verefica força da senha
$(document).on('keyup', '#user-password', function(e) {
  'use strict';
  e.preventDefault();
  stronghPass();
});

function stronghPass(){
   
  var score   = 0;
  var bar = $('#progress-strongh-pass'); 
  var inputPass  = jQuery('#user-password').val(); 
  var barProgress = jQuery('.progress-bar');
  var outputText  = jQuery('#output-strong-pass');
  var ico = '<i class="fa fa-times text-red"></i>';
  
  jQuery('#progress-strongh-pass').removeClass('hidden');
  
  // medir lenght 
  if(inputPass.length < 8)
  {
    score += 0;
  }
  else if((inputPass.length >= 8) && (inputPass.length <= 12))
  {
    score += 30;
  }
  else if((inputPass.length >= 13) && (inputPass.length <= 16))
  {
    score += 40;
  }
  
  
  if(inputPass.match(/[a-z]+/))
  {
    score += 15;
  }
  if(inputPass.match(/[A-Z]+/))
  {
    score += 15;
  }
  if(inputPass.match(/[0-9]/))
  {
    score += 15;
  }
  if(inputPass.match(/.*[!,@,#,$,%,^,&,*,.,?,-,_,~]/))
  {
    score += 15;
  }
  
  //se tiver letras minuscúlas, maiuscúlas, números e letras
  if ( (inputPass.match(/.*[!,@,#,$,%,^,&,*,.,?,-,_,~]/)) && (inputPass.match(/[0-9]/)) && (inputPass.match(/[A-Z]+/)) && (inputPass.match(/[a-z]+/)) && (inputPass.length >= 8) && (score > 75))
  {
    ico = '<i class="fa fa-check text-green"></i>';
  }

  if(bar.hasClass("hidden"))
  {
      bar.removeClass('hidden');
  }
  
  if(score >= 80)
  {
    barProgress.removeClass('progress-bar-warning');
    barProgress.removeClass('progress-bar-info');
    barProgress.removeClass('progress-bar-danger');
    barProgress.addClass('progress-bar-success');
    barProgress.css('width', score+'%');
    outputText.html('Muito Forte' +ico);
  }
  else if ( score < 80 && score >= 60)
  {
    barProgress.removeClass('progress-bar-danger');
    barProgress.removeClass('progress-bar-warning');
    barProgress.removeClass('progress-bar-success');
    barProgress.addClass('progress-bar-info');
    barProgress.css('width', score+'%');
    outputText.html('Forte ' +ico);
  }
  else if ( score <= 59 && score > 40)
  {
    barProgress.removeClass('progress-bar-danger');
    barProgress.addClass('progress-bar-warning');
    barProgress.css('width', score+'%');
    outputText.html('Boa '+ico);
  }
  else if ( score < 40 && score > 20)
  {
    barProgress.addClass('progress-bar-danger');
    barProgress.css('width', score+'%');
    outputText.html('Regular ' +ico);
  }
  else if ( score < 20)
  {
    barProgress.addClass('progress-bar-danger');
    barProgress.css('width', score+'%');
    outputText.html('Fraca ' +ico);
  }
  else
  {
    return false;
  }
 
}
//***********************************************************

  $(document).on('click', "#show-password", function(){
    if( $('#user-password').attr('type') === 'password' )
    {
        $('#user-password').attr('type', 'text'); 
        $(this).find('i').removeClass('fa-eye').addClass('fa-eye-slash');
    }
    else
    {
        $('#user-password').attr('type', 'password'); 
        $(this).find('i').removeClass('fa-eye-slash').addClass('fa-eye');
    }
  }); 
