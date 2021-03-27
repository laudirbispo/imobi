<?php require_once('header.php'); ?>
  
  <div class="bg-top">
	<div class="container">
		<ol class="breadcrumb">
		  <li><a href="/home">Início</a></li>
		  <li class="active">Contato</li>
		</ol>
	</div>
</div>
  <div class="space-20 clearfix"></div>
  <div class="container">
    <div class="row">
        <h2>ENTRE EM CONTATO</h2>
        <div class="space-20 clearfix"></div>
        <div class="row">
        	<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
				<div class="contact-form">
					<form name="sentMessage-tirar" id="contactForm" enctype="application/x-www-form-urlencoded" method="post" action="/envia2.php">
						<div CLASS="form-group row">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-bottom:20px;">
								<input type="text" class="form-control" id="nome" name="nome" placeholder="Nome*" required>
							</div>
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-bottom:20px;">
								<input type="email" class="form-control" id="email" name="email" placeholder="Email*" required>
							</div>
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-bottom:20px;">
								<input type="tel" class="form-control" id="telefone" name="telefone" placeholder="Telefone*" required>
							</div>
							<div CLASS="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<textarea style="resize:none; height:100px;" type="text" class="form-control" id="mensagem" name="mensagem" placeholder="Mensagem*" required></textarea>
							</div>
						</div>
						<button type="submit" class="btn btn-primary">Enviar Mensagem</button>


					</form>
					<div class="space-20"></div>
					<div id="resposta"></div>
				</div>
       	
       			<div class="space-20 clearfix"></div>
       			
       			<div class="t24"><span class="fa fa-envelope-o text-iguacu"></span> - bolinhabohn@hotmail.com</div><br>
       			<div class="t24"><span class="fa fa-envelope-o text-iguacu"></span> - bolinhabohn@gmail.com</div><br>
       			<div class="t24"><span class="fa fa-whatsapp text-iguacu"></span> - (46) 99901-7566</div><br>
       			<div class="t24"><span class="fa fa-phone text-iguacu"></span> - (46) 3543-4405</div><br>
       			<div class="t24"><span class="fa fa-map-marker text-iguacu"></span> - Rua Belém, 2522, Centro Cívico - Realeza/PR</div>
       			
        	</div>
        	<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        		<div  id="map" style="height:550px; width:100%;" class="center-block"  ></div> 
        	</div>
        </div>
        
        
        
		<div class="space-40 clearfix"></div>  
       
		  
    </div> 
  </div>
	



<?php require_once('footer.php'); ?>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAIFWg-3hOXabs6hpgQRZvIoP_BV-yFm0A&"></script>
<script type="text/javascript">
$(document).ready(function(){
    
initMap();
var map;
function initMap() {
    var styleArray = [
    {
      featureType: "all",
      stylers: [
       { hue: "#ED3237" },
       { saturation: -20 }
      ]
    }
  ];
  
   var contentString = '<div id="content" class="text-center">'+
      '<div id="siteNotice">'+
      '</div>'+
      '<b>BOHN IMÓVEIS</b><br>Rua Belém, 2522, Centro Cívico <br> Realeza/PR'+
      '</div>'+
      '</div>';


  map = new google.maps.Map(document.getElementById('map'), {
    center: {lat: -25.774295, lng: -53.532434},
    scrollwheel: false,
    styles: styleArray,
    zoom: 18
  });
  
  var infowindow = new google.maps.InfoWindow({
    content: contentString
  });
  
   var marker = new google.maps.Marker({
    map: map,
    position: {lat: -25.774295, lng: -53.532434},
    title: 'BOHN IMÓVEIS'
  });
  $(document).ready(function(e) {
    infowindow.open(map, marker);
  });



 
}


});
    </script>
    
     <script type="text/javascript"  async defer>
	jQuery(document).ready(function(e){

		jQuery('#contactForm').submit(function(e){
			e.preventDefault();
			var dados = jQuery( this ).serialize();
e.preventDefault();
e.preventDefault();
			jQuery.ajax({
				type: "POST",
				url: "/envia2.php",
				data: dados,
				success: function( data )
				{

					$("#resposta").html(data);
					document.getElementById('contactForm').reset();						
				}
			});



		});

	});
</script>
