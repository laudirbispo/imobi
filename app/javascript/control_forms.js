'use strict';

(function( $ ) {
   $(document).ready(function(){ 
    $('[data-control="mask-tel"]').mask("(99) ?9-9999-9999");
    $('[data-control="mask-cpf"]').mask("999.999.999-99");
    $('[data-control="mask-rg"]').mask("99.999.999-9");
    $('[data-control="mask-cnpj"]').mask("99.999.999/9999-99");
    $('[data-control="mask-postal-code"]').mask("99999-999");
    $('[data-control="mask-date"]').mask("99/99/9999",{placeholder:"mm/dd/yyyy"});
   });
})(jQuery);