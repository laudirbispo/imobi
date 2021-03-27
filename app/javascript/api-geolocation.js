'use strict';

    
var geocoder;
var map;
var marker;
var position = null;
var infowindow;
var latlng;
var google;
var lat;
var lon;
var marker;
var latitude;
var longitude;
var status;

    
$(document).on('click', '#getLocation', function(){
    
    $('#mapa').append('<div class="loading-overlay text-center"><br><i class="fa fa-refresh fa-spin"></i> Buscando sua localização ...</div>');
    
    var options = {
      enableHighAccuracy: true,
      timeout: 15000,
      maximumAge: 0
    };
    if(navigator.geolocation)
    {
        navigator.geolocation.getCurrentPosition(geocodeLatLng,showError,options); 
    }
    else
    {
        show_alert('warning','Atenção!','Seu navegador não suporta Geolocation.','fa fa-exclamation-triangle',false);
    }
});
  

function initialize(position) 
{

    if(position === null)
    {
         lat = -25.77599645469469;
         lon = -53.53503098095092;
         $('#txtEndereco').val('Pesquisar endereço');
         latlng = new google.maps.LatLng(lat, lon);
    }
    else
    {
        lat = position.coords.latitude;
        lon = position.coords.longitude;
         latlng = new google.maps.LatLng(lat, lon);
    }

    var options = {
        zoom: 16,
        center: latlng,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
		scrollwheel: false,
    };
    var image = '/app/images/icons/marker-custom.png';
    map = new google.maps.Map(document.getElementById("mapa"), options);
    geocoder = new google.maps.Geocoder();
    
    geocoder.geocode({'location': latlng}, function(results, status) {
        if (status === google.maps.GeocoderStatus.OK) {
            if (results[1]) {
                $('#txtEndereco').val(results[1].formatted_address);
            }
        }
    });

    $('#txtLatitude').val(lat);
    $('#txtLongitude').val(lon); 

    marker = new google.maps.Marker({
        map: map,
        draggable: true,
        icon: image,
        animation: google.maps.Animation.BOUNCE,
    });
    marker.setPosition(latlng);

    setTimeout(function(){
        marker.setAnimation(google.maps.Animation.DROP);
    }, 5000); 
}

function geocodeLatLng(position) {
         lat = position.coords.latitude;
         lon = position.coords.longitude;
         latlng = {lat: lat, lng: lon};

      geocoder.geocode({'location': latlng}, function(results, status) {
        if (status === google.maps.GeocoderStatus.OK) {
          if (results[1]) {
            map.setZoom(16);
            var location = new google.maps.LatLng(lat, lon);
            marker.setPosition(location);
            map.setCenter(location);
            map.setZoom(16);			
            //infowindow.setContent(results[1].formatted_address);
              $('#txtEndereco').val(results[1].formatted_address);
              $('#txtLatitude').val(lat);
              $('#txtLongitude').val(lon);
              $('#mapa').find('.loading-overlay').remove();

          } else {
            window.alert('No results found');
              $('#mapa').append('<div class="loading-overlay text-center"><br>Não conseguimos obter sua localização atual. Tente novamente se o erro continuar entre em contato com a nossa equipe de suporte.</div>');
          }
        } else {
          $('#mapa').append('<div class="loading-overlay text-center"><br>Não conseguimos obter sua localização atual. Tente novamente se o erro continuar entre em contato com a nossa equipe de suporte.</div>');
            
        }
      });

}

function carregarNoMapa(endereco) {
    geocoder.geocode({ 'address': endereco + ', Brasil', 'region': 'BR' }, function (results, status) {
        if (status === google.maps.GeocoderStatus.OK) {
            if (results[0]) {
                 latitude = results[0].geometry.location.lat();
                 longitude = results[0].geometry.location.lng();
                $('#mapa').find('.loading-overlay').remove();
                $('#txtEndereco').val(results[0].formatted_address);
                $('#txtLatitude').val(latitude);
                $('#txtLongitude').val(longitude);

                var location = new google.maps.LatLng(latitude, longitude);
                marker.setPosition(location);
                map.setCenter(location);
                map.setZoom(16);
            }
        }
    });
}

    
$("#btnEndereco").click(function() {
    if($(this).val() !== "")
        carregarNoMapa($("#txtEndereco").val());
});

$("#txtEndereco").blur(function() {
    if($(this).val() !== "")
        carregarNoMapa($(this).val());
});

function markerDrag ()
{  
    google.maps.event.addListener(marker, 'drag', function () {
    geocoder.geocode({ 'latLng': marker.getPosition() }, function (results, status) {
        if (status === google.maps.GeocoderStatus.OK) {
            if (results[0]) {  
                $('#txtEndereco').val(results[0].formatted_address);
                $('#txtLatitude').val(marker.getPosition().lat());
                $('#txtLongitude').val(marker.getPosition().lng());   
            }
        }
    });
    });
}

function autoComplete (){

    $("#txtEndereco").autocomplete({
        source: function (request, response) {
            geocoder.geocode({ 'address': request.term + ', Brasil', 'region': 'BR' }, function (results, status) {
                response($.map(results, function (item) {
                    return {
                        label: item.formatted_address,
                        value: item.formatted_address,
                        latitude: item.geometry.location.lat(),
                        longitude: item.geometry.location.lng(),
                    };
                }));
            });
        },
        select: function (event, ui) {
            $("#txtLatitude").val(ui.item.latitude);
            $("#txtLongitude").val(ui.item.longitude);
            var location = new google.maps.LatLng(ui.item.latitude, ui.item.longitude);
            marker.setPosition(location);
            map.setCenter(location);
            map.setZoom(16);
        }
    });
}


function showError(error)
{
  var x = document.getElementById("mapa");
  switch(error.code) 
    {
    case error.PERMISSION_DENIED:
      x.innerHTML="<div class='text-center'><br><i class='fa fa-frown-o fa-5x'></i><br>Usuário rejeitou a solicitação de Geolocalização.</div>";
      break;
    case error.POSITION_UNAVAILABLE:
      x.innerHTML="<div class=' text-center'><br><i class='fa fa-frown-o fa-5x'></i><br>Localização indisponível.</div>";
      break;
    case error.TIMEOUT:
      x.innerHTML="<div class='text-center'><br><i class='fa fa-frown-o fa-5x'></i><br>O tempo da requisição expirou.</div>";
      break;
    case error.UNKNOWN_ERROR:
      x.innerHTML="<div class='text-center'><br><i class='fa fa-frown-o fa-5x'></i><br>Algum erro desconhecido aconteceu.</div>";
      break;
    }
}




