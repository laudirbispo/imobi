'use strict';
var helpMeModal;

helpMeModal = function(pageOpen, modalType, modalTitle) {
    
    this.pageOpen = pageOpen;
    this.modalType = modalType;
    this.modalId = helpMeModal.generateId();
    this.modalTitle = modalTitle;       
};

helpMeModal.defaultOptions = {
    title: 'Ajuda & Suporte',
};

helpMeModal.generateId = function() {
    var ts = +new Date();
    var tsStr = ts.toString();
    var arr = tsStr.split('');
    var rev = arr.reverse();
    var filtered = rev;
    return filtered.join(''); 
};

helpMeModal.prototype.constructModal = function()
{
    var stringHtml = '<div class="em-modal box box-solid draggable resizable animated slideInUp" tabindex="-1" role="dialog" id="em-modal_'+this.modalId+'">' +
    '<div class="box-header with-border">' +
    '<b><i class="fa fa-support"></i> '+this.modalTitle+'</b>' +
    '<div class="box-tools pull-right">' +
    '<button type="button" class="btn btn-box-tool hidden" data-widget="collapse"><i class="fa fa-minus"></i></button>' +
    '<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times text-white"></i></button>' +
    '</div>' +
    '</div>' +
    '<div class="box-body no-padding">' +
    '<div class="overlay"><i class="fa fa-refresh fa-3x fa-spin"></i><br> Carregando...</div>' +
    '</div>' +
    '</div>';
    return stringHtml;
};

helpMeModal.prototype.show = function() {
   $('body').append(this.constructModal()); 
   this.loadPage();
};

helpMeModal.prototype.loadPage = function() {
       /*
    $('#em-modal_'+this.modalId+' > .box-body').load('/app/views/support.php?pageHelp='+this.pageOpen, function(statusTxt){
       if(statusTxt === "error")
       {
           $(this).html('<div class="alert alert-danger"><h4><i class="icon fa fa-ban"></i> Error!</h4>Tivemos um problema ao carregar a página.</div>');
       }
    });
 */
    $('#em-modal_'+this.modalId+' > .box-body').html('<iframe src="/app/support/'+this.pageOpen+'" width="100%" height="100%" style="overflow:hidden; border:none;"></iframe>');
};


(function ($){

    $(document).on('click', '[data-toggle="em-modal"]', function(){

        var pageOpen = $(this).attr('data-page');
        var modalTitle = $(this).attr('data-title');
        new helpMeModal(pageOpen, 'help', modalTitle).show();
        
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
        
    }); 
    
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
            playAudio('/app/sounds/alerts/recycle.wav');
        },
        deactivate: function() {
            $('.droppable-trash').removeClass('droppable-trash-hover').removeClass('fadeInRight').addClass('fadeOutRight').hide('slow');
        },

    });

})(jQuery);