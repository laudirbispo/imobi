'use strict';

$(document).ready(function() {
  
  $(document).on('change', '.input-preview', function(){
      if (typeof (FileReader) !== "undefined") {
          var dvPreview = $("#previews-images");
          dvPreview.html("");
          var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.jpg|.jpeg|.gif|.png|.bmp)$/;
          $($(this)[0].files).each(function () {
              var file = $(this);
              if (regex.test(file[0].name.toLowerCase())) {
                  var reader = new FileReader();
                  reader.onload = function (e) {
                      var img = $("<img />");
                      img.attr("style", "height:60px;width: auto;margin:5px");
                      img.attr("src", e.target.result);
                      dvPreview.append(img);
                  };
                  reader.readAsDataURL(file[0]);
                  $('#up_resposta').html('');
                  $('#images-ok').html('Pré visualização: As imagens a seguir estão prontas para o upload.');
                  
              } 
              else 
              {
                  $('#images-ok').html(file[0].name + " imagem inválida.");
                  dvPreview.html("");
                  return false;
              }
          });
      } 
      else 
      {
          $('#images-ok').html("Seu navegador não suporta preview de imagens.");
      }
  });
  
});
      