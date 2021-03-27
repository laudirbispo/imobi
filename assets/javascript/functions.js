// JavaScript Document

$(document).ready(function() {
  'use strict';
	$('#open-sidebar').click(function (event) { 
		event.preventDefault();  
         $('.sidebar-categorias').animate({right: '0'}, 600, "linear");
	});
	
	$('#close-sidebar').click(function (event) { 
		event.preventDefault(); 
		$('.sidebar-categorias').animate({right: '-100%'}, 600, "linear");
	});

});
//****************************************************************************

//************************************************
// função universal para submit de formulários



//********************************************************************************
// news slide vertical

function abrir(URL) 
{
  'use strict';
  var width = 370;
  var height = 80;
  var left = 99;
  var top = 99;
 
  window.open(URL,'janela', 'width='+width+', height='+height+', top='+top+', left='+left+', scrollbars=no, status=no, toolbar=no, location=no, directories=no, menubar=no, resizable=no, fullscreen=no');
 
}


//********************************************************************************

$(function() { 
'use strict'; 
  var nav = $('.menu-principal');
  var nocols = $('.hidden-scroll');
    $(window).scroll(function () {
        if ($(this).scrollTop() > 101) {
           nocols.addClass("hidden-xs");
            nav.addClass("menu-fixo");
        } else {
            nocols.removeClass("hidden-xs");
            nav.removeClass("menu-fixo");
        }
  });
 });
 
//****************************************************************************
$(document).on('click', '.social-share', function(event){
  'use strict';
  event.preventDefault();
  var url = $(this).attr('data-url');
  var width = $(this).attr('data-width');
  var height = $(this).attr('data-height');
  var left = 99;
  var top = 99;
  
 window.open(url,'janela', 'width='+width+', height='+height+', top='+top+', left='+left+', scrollbars=no, status=no, toolbar=no, location=no, directories=no, menubar=no, resizable=no, fullscreen=no'); 
  
});

//********************************************************************************

