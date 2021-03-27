'use strict';
// Deleta um imóvel
$(document).on("click", '[data-action="del-contract"]', function(){
    var actionid = $(this).attr('data-actionid');
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
        content: 'Esta ação excluirá este modelo do banco de dados e NÂO afetará em contratos já feitos e salvos.',
        confirm: function()
        {  
            $.get('/app/modules/contracts/delete_templante_contracts.php', {actionid: actionid}, function(data){
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

// use modelo de contrato
$(document).on('click', '[data-action="open-contract"]', function(){
    var modalTitle = $('#modal-contract-title');
    var modalBody = $('#modal-contract-body');
    var actionId = $(this).attr('data-actionid');
    var inputModel = $('#contract-model');
    var textContract = $('#contract-text');
    var cModel = null;
    var cText = null;
    
    jQuery.get("/app/modules/contracts/open_contracts.php", {actionid: actionId}, function(data){
        if(data.status === 'success')
        {
            cModel = data.model;
            cText = data.html;
            modalTitle.html(data.model);
            modalBody.html(data.html);
            tinymce.triggerSave();
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
    
    $('#modal-contract').modal('show');
    $(document).on('click', '#use-contract-model', function(){
        tinymce.get('contract-text').setContent(cText);
        tinymce.triggerSave();
        inputModel.val(cModel);
        $('#modal-contract').modal('hide');
    });
});

/**
 * Functions para info de clientes
 *
 */
$(function(){

    $(document).on('click', '[data-action="load-info-client-owner"]', function(){
        var idClient = $('#client-owner').find(':selected').val();
        var topPosition  = $(document).scrollTop();
        var currentZindex = parseInt($('.client-tenant').css('z-index'));
        $('aside.client-owner').fadeOut(300, function() { $(this).remove(); });
        
        if(idClient === '' || idClient === undefined) 
        {
            return show_alert('warning','Atenção','Selecione um cliente para continuar!','fa fa-info',false);
        }
        else
        {   
            jQuery.get("/app/modules/contracts/get_info_client.php", {actionid: idClient}, function(data){
                
                if (data.status === 'success')
                {
                    
                    var boxClientInfo = '<aside class="box-resize client-owner" id="resize">' +
                        '<div class="box-header" id="drag-user-info">' +
                        '<i class="fa fa-user"></i> Informações do cliente 1' +
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
                        '<i class="resize-ico ui-icon ui-icon-gripsmall-diagonal-se" style="z-index: 9999;"></i>' +
                        '</aside>';

                        $(boxClientInfo).appendTo('body').fadeIn(4000);
                        $('.client-owner').css('top', topPosition + 60);
                        $('.client-owner').css('z-index', currentZindex+1);
                        $('.client-owner').resizable({ grid: [10, 10]}).draggable({ handle: '.box-header', scroll: 'true' });
                        // remove aside draggable
                        $(document).on('click', '[data-action="close-box"]', function(){
                            $(this).closest('.box-resize').remove();
                        });
                    }
                    else 
                    {

                    }
            });
        }
        
    });
    
});

$(function(){

    $(document).on('click', '[data-action="load-info-client-tenant"]', function(){
        var idClient = $('#client-tenant').find(':selected').val();
        var topPosition  = $(document).scrollTop();
        var currentZindex = parseInt($('.client-owner').css('z-index'));
        
        $('aside.client-tenant').fadeOut(300, function() { $(this).remove(); });
        
        if(idClient === '' || idClient === undefined) 
        {
            return show_alert('warning','Atenção','Selecione um cliente para continuar!','fa fa-info',false);
        }
        else
        {   
            jQuery.get("/app/modules/contracts/get_info_client.php", {actionid: idClient}, function(data){
                
                if (data.status === 'success')
                {
                    
                    var boxClientInfo = '<aside class="box-resize client-tenant" id="resize">' +
                        '<div class="box-header" id="drag-user-info">' +
                        '<i class="fa fa-user"></i> Informações do cliente 2' +
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
                        '<i class="resize-ico ui-icon ui-icon-gripsmall-diagonal-se" style="z-index: 9999;"></i>' +
                        '</aside>';

                        $(boxClientInfo).appendTo('body').fadeIn(4000);
                        $('.client-tenant').css('top', topPosition + 90);
                        $('.client-tenant').css('left', '60px');
                        $('.client-tenant').css('z-index', currentZindex+1);
                        $('.client-tenant').resizable({ grid: [10, 10]}).draggable({ handle: '.box-header', scroll: 'true' });
                        // remove aside draggable
                        $(document).on('click', '[data-action="close-box"]', function(){
                            $(this).closest('.box-resize').remove();
                        });
                    }
                    else 
                    {

                    }
            });
        }
        
    });
    
});

var rContainer, totalRows, rRows, lastRow, grossValue, rowNumber, discountValue, discountCause, additionValue, additionCause, dueDate;

function createRowsReceipts()
{
    var q;
    rContainer = $('#container-receipts');
    q = parseInt($('#quantidade-receipts').val());
    rRows = $('#container-receipts').find('.receipts-itens');
    totalRows = rRows.length;
    lastRow = rRows.eq(-1);
     
    rowNumber = parseInt(lastRow.find('.receipts-number').html());
    grossValue = lastRow.find('[name="r-gross-value[]"]').val();
    discountValue = lastRow.find('[name="r-discount[]"]').val();
    discountCause = lastRow.find('[name="discount-cause[]"]').val();
    additionValue = lastRow.find('[name="r-addition[]"]').val();
    additionCause = lastRow.find('[name="additions-cause[]"]').val();
    dueDate = lastRow.find('[name="receipts-due[]"]').val();
    
    var i, newDate, newCol, colNumber;

    for (i = 1; i <= q; i++)
    {
        
        newCol = lastRow.clone();
        newCol.find('.receipts-number').html(rowNumber+i);
        var parcelas = 1;
        newDate = calcularParcelas(parcelas, dueDate);
        newCol.find('[name="receipts-due[]"]').val(newDate);
        dueDate = newDate;
        newCol.find('[data-control="mask-money"]').maskMoney({
            symbol:'R$ ', 
            showSymbol:false,
            thousands:'.', 
            decimal:',', 
            symbolStay: false,
        });
        newCol.find('[data-control="mask-date"]').mask("99/99/9999",{placeholder:"mm/dd/yyyy"});
        newCol.find('[data-control="datepicker"]').datepicker({format: 'dd/mm/yyyy',});
        $('#container-receipts').append(newCol);
    }
    
}

function removeRowsReceipts()
{
    var i;
    var q = parseInt($('#quantidade-receipts').val());
    
    for (i = 1; i <= q; i++)
    {   
        if($('#container-receipts').find('.receipts-itens').length <= 1){ continue; }
        $('#container-receipts').find('.receipts-itens').eq(-1).remove();
    }
    
}


/*
 * gera sequencia de datas de vencimento de recibos
 */

function correcaoDia(dia) {
    if (isNaN(dia)) {return false; }
    
    return dia < 10 ? "0" + dia : dia ;
}

function correcaoMes(mes) {
    if (isNaN(mes)) {return false;} 
    return mes < 10 ? "0" + mes : mes ;
}

function calcularParcelas(parcelas, stringData) {
    var ano = parseInt(stringData.substring(6,10));
    var mes = parseInt(stringData.substring(3,5));
    var dia = parseInt(stringData.substring(0,2));
    var defaultDia = dia;

    if(dia >= 29 && mes === 2 && leapYear(ano)) {dia = 1; mes = 1; alert('kkkk');}
    var dataInicial = new Date(ano,mes,dia);
    var dataParcela = new Date();
    var resultado = "";
    var novoMes = 0;
    var novoAno = 0;
    
    for ( var p = 0 ; p < parcelas ; p++ ) {
        
        novoMes =  dataInicial.getMonth() + p;
        if(novoMes === 13 )
        {
            novoMes = 1;
            novoAno = dataInicial.getFullYear() + 1;
        }
        else
        {
            novoAno = dataInicial.getFullYear();
        }

        dataParcela.setDate(dia);
        dataParcela.setMonth(novoMes);
        dataParcela.setYear(novoAno);
        
        resultado = correcaoDia(dataParcela.getDate())+"/"+correcaoMes(dataParcela.getMonth() + 1)+"/"+dataParcela.getFullYear();
        if(dia >= 29 && mes === 2 && leapYear(ano)) {p--;mes = 2;dia = defaultDia;}
    }
    
    return resultado;
}

function leapYear(year)
{
    return ((year % 4 === 0) && (year % 100 !== 0)) || (year % 400 === 0);
}

// Deleta um usuário
$(document).on("click", '[data-action="write-dow-receipts"]', function(){
    
    var modalId = $('#modal-write-dow-receipts');
    var contractId = $(this).attr('data-contract-id');
    var receiptId = $(this).attr('data-receipt-id');
    var vDiscount = $(this).attr('data-discount');
    var vDiscountCause = $(this).attr('data-discount-cause');
    var vAddiiton = $(this).attr('data-addition');
    var vAddiitonCause = $(this).attr('data-addition-cause');
    var vObservations = $(this).attr('data-observations');
    
    modalId.find('form').each(function(){this.reset();});

    $('#receipt-id').val(receiptId);
    $('#contract-id').val(contractId);
    $('#receipt-discount').val(vDiscount);
    $('#receipt-discount-cause').val(vDiscountCause);
    $('#receipt-addition').val(vAddiiton);
    $('#receipt-addition-cause').val(vAddiitonCause);
    $('#receipt-observations').val(vObservations);
    
    modalId.modal('show');
}); 


$(document).on('click', '[data-action="print-receipt"]', function(){
    
    var contractId = $(this).attr('data-contract-id');
    var receiptId = $(this).attr('data-receipt-id');
    var modalContainer = $('#modal-print-receipts');
    
    $.get('/app/modules/contracts/generate_receipt.php', {contract_id: contractId, receipt_id: receiptId}, function(data){
        if(data.status === 'success')
        {
            modalContainer.find('.modal-body').html(data.message);    
        }
        else if(data.status === 'warning')
         {
             modalContainer.find('.modal-body').html(data.message);
         }
         else if(data.status === 'info')
         {
             modalContainer.find('.modal-body').html(data.message);
         }
         else 
         {
             modalContainer.find('.modal-body').html(data.message);
         }
    });
    
    modalContainer.modal('show');
    
});

$(document).on("click", '[data-action="low-receipt"]', function(){
    
    var receiptId = $(this).attr('data-receipt-id');
    var modalContainer = $('#modal-low-r');
    modalContainer.find('form').find('#write-receipt-id').val(receiptId);
    
    modalContainer.modal('show');
    
});

$(document).on("click", '[data-control="del-contract"]', function(){
    
    var contractId = $(this).attr('data-contract-id');
    var modalContainer = $('#modal-del-contract');
    modalContainer.find('form').find('#contract-id').val(contractId);
    
    modalContainer.modal('show');
    
});