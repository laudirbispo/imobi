'use strict';

//-------------------------------------------

$(document).on('change', '#client-type', function(){
    
    var tipoCliente = $(this).find(':selected').val();
    var boxClienteFisico = $('#inputs-person-physical');
    var boxClienteJuridico = $('#inputs-person-juridical');
    
    if( tipoCliente === 'physical')
    {
       boxClienteJuridico.css('cursor', 'not-allowed');
       boxClienteFisico.css('cursor', 'default');
       boxClienteJuridico.css('opacity', '0.5');
       boxClienteFisico.css('opacity', '1');
       boxClienteFisico.find('input, select').prop('disabled', false); 
       boxClienteJuridico.find('input, select').prop('disabled', true);
       boxClienteFisico.find('[data-control="input-physical"]').prop('required', true); 
       boxClienteJuridico.find('[data-control="input-juridical"]').prop('required', false);   
    }
    else if( tipoCliente === 'juridical')
    {
       boxClienteJuridico.css('cursor', 'default');
       boxClienteFisico.css('cursor', 'not-allowed');
       boxClienteFisico.css('opacity', '0.5');
       boxClienteJuridico.css('opacity', '1');
       boxClienteJuridico.find('input, select').prop('disabled', false); 
       boxClienteFisico.find('input, select').prop('disabled', true);
       boxClienteJuridico.find('[data-control="input-juridical"]').prop('required', true); 
       boxClienteFisico.find('[data-control="input-physical"]').prop('required', false);
    }
    else
    {
        boxClienteJuridico.css('cursor', 'default');
        boxClienteFisico.css('cursor', 'default');
        boxClienteJuridico.css('opacity', '1');
        boxClienteFisico.css('opacity', '1');
        boxClienteJuridico.find('input, select').prop('disabled', false); 
        boxClienteFisico.find('input, select').prop('disabled', false);
        boxClienteJuridico.find('[data-control="input-juridical"]').prop('required', false); 
        boxClienteFisico.find('[data-control="input-physical"]').prop('required', false);
    }
    $('#form-add-clients').validator('update');
});

//-------------------------------------------

$(document).on('change', '#client-is-employed', function(){
    
    var valueSelected = $(this).val(); 
    
    if( valueSelected === 'S' )
    {
        $('#client-company-name').prop('disabled', false); 
        $('#client-company-position').prop('disabled', false); 
        $('#client-company-start-date').prop('disabled', false); 
        $('#client-company-contact').prop('disabled', false); 
    }
    else
    {
        $('#client-company-name').prop('disabled', true); 
        $('#client-company-position').prop('disabled', true); 
        $('#client-company-start-date').prop('disabled', true); 
        $('#client-company-contact').prop('disabled', true);
    }
    
   $('#form-add-clients').validator('update');
    
});

// Deleta um usuário
$(document).on("click", '[data-action="del-client"]', function(){
    var actionid = $(this).attr('data-client-id');
    var container = $(this).closest('tr');
    $.confirm({
        icon: 'fa fa-warning text-yellow',
        columnClass: 'col-md-6 col-md-offset-3 col-xs-6 col-xs-offset-3',
        title: 'Esta ação requer autenticação!',
        btnClass: 'btn-flat',
        confirmButton: 'Continuar',
        cancelButton: 'Cancelar',
        backgroundDismiss: true,
        autoClose: 'cancel|45000',
        confirmButtonClass: 'btn-primary',
        cancelButtonClass: 'btn-danger',
        content: '<p>Os dados que você esta prestes a excluir, podem ter relações com outros dados e ferramentas.<br>' +
        'Após o fim do processo, nenhum dado poderá ser recuperado.</p>' +
        '<p><strong>Você tem certeza que deseja continuar?</strong></p>' +
        '<p>Informe sua senha para continuar</p>' +
        '<input type="password" name="password" id="del-password" placeholder="Password" class="name form-control" required />',
        confirm: function()
        {
            var password = this.$content.find('#del-password').val();
            $.get('/app/modules/clients/delete_clients.php', {actionid: actionid, user_password: password}, function(data){
                if(data.status === 'success')
                {
                    container.remove();
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

$(function(){
    $(document).on('click', '[data-action="load-info-client"]', function(){
        $('.box-resize').remove();
        var idClient = $(this).attr('data-client-id');
        var topPosition  = $(document).scrollTop();
        
        if(idClient === '' || idClient === undefined) 
        {
            return show_alert('warning','Atenção','Selecione um cliente para continuar!','fa fa-info',false);
        }
        else
        {   
            jQuery.get("/app/modules/clients/info_client.php", {actionid: idClient}, function(data){
                
                if (data.status === 'success')
                {
                    
                    var boxClientInfo = '<aside class="box-resize" id="resize">' +
                        '<div class="box-header" id="drag-user-info">' +
                        '<i class="fa fa-user"></i> Informações do cliente' +
                        '<a role="button" tabindex="1" class="label label-danger pull-right" data-action="close-box"><i class="fa fa-times"></i></a>' +
                        '<i class="fa fa-arrows pull-right"></i>' +
                        '</div>' +
                        '<div class="box-body" style="line-height:15px">' +
                        '<table class="table table-striped"><tbody>' +
                        '<tr>' +
                        '<td><strong class="text-blue">Nome: </strong></td><td><span class="text-mediumgray">'+data.name+'</span></td>' +
                        '</tr>' +
                        '<tr>' +
                        '<td><strong class="text-blue">Razão Social: </strong></td><td><span class="text-mediumgray">'+data.social+'</span></td>' +
                        '</tr>' +
                        '<tr>' +
                        '<td><strong class="text-blue">Nome Fantasia: </strong></td><td><span class="text-mediumgray">'+data.fantasy+'</span></td>' +
                        '</tr>' +
                        '<tr>' +
                        '<td><strong class="text-blue">CNPJ: </strong></td><td><span class="text-mediumgray">'+data.cnpj+'</span></td>' +
                        '</tr>' +
                        '<tr>' +
                        '<td><strong class="text-blue">CPF: </strong></td><td><span class="text-mediumgray">'+data.cpf+'</span></td>' +
                        '</tr>' +
                        '<tr>' +
                        '<td><strong class="text-blue">RG: </strong></td><td><span class="text-mediumgray">'+data.rg+'</span></td>' +
                        '</tr>' +
                        '<tr>' +
                        '<td><strong class="text-blue">Tipo: </strong></td><td><span class="text-mediumgray">'+data.type+'</span></td>' +
                        '<tr>' +
                        '<td><strong class="text-blue">Nacionalidade: </strong></td><td><span class="text-mediumgray">'+data.nationality+'</span></td>' +
                        '</tr>' +
                        '<tr>' +
                        '<td><strong class="text-blue">Endereço: </strong></td><td><span class="text-mediumgray">'+data.address+'</span></td>' +
                        '</tr>' +
                        '<tr>' +
                        '<td><strong class="text-blue">Telefones: </strong></td><td><span class="text-mediumgray">'+data.phones+'</span></td>' +
                        '</tr>' +
                        '<tr>' +
                        '<td><strong class="text-blue">E-mail: </strong></td><td><span class="text-mediumgray">'+data.email+'</span></td>' +
                        '</tr>' +
                        '<tr>' +
                        '<td><strong class="text-blue">Estado Civil: </strong></td><td><span class="text-mediumgray">'+data.marital+'</span></td>' +
                        '</tr>' +
                        '<tr>' +
                        '<td><strong class="text-blue">Observações: </strong></td><td><span class="text-mediumgray">'+data.obs+'</span></td>' +
                        '</tr>' +
                        '</tbody></table>' +
                        '</div>' +
                        '<div class="box-footer">' +
                        '<a href="/app/admin/edit_clients/'+data.client_id+'" class="btn btn-primary btn-sm btn-flat text-uppercase "><i class="fa fa-edit"></i> editar informações</a>' +
                        '</div>' +
                        '<div class="resize-ico ui-resizable-handle ui-resizable-se ui-icon ui-icon-gripsmall-diagonal-se" style="z-index: 90;"></div>' +
                        '</aside>';

                        $(boxClientInfo).appendTo('body').fadeIn(4000);
                        $('#resize').css('top', topPosition + 10);
                        $('#resize').css('z-index', '9999');
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



