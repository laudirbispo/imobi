<?php
use config\connect_db;

if( ($_SESSION['user_master_perms'] !== 'administrador') )
{
    if( $_SESSION['news_create'] !== '1' )
    {
        die ('<script>location.href="/app/admin.php?page=access_denied";</script>');
    }
}
?>

  <DIV CLASS="callout callout-info">
    <H4><I CLASS="icon fa fa-info"></I> Importante!</H4>
    <P> Está página deve ser refeita.</P>
    <p>ATT: Laudir</p>
    <a href="admin.php?page=suporte">Ir para a página de suporte</a>
  </DIV>

    
<script src="/plugins/bootstrap-validator-master/dist/validator.min.js"></script>
<!-- TinyMCE Editor-->
<script src="/plugins/tinymce/tinymce.min.js"></script>
<script>
tinymce.init({
	convert_urls: false,
    selector: ".tiny",
	plugins: "textcolor",
    textcolor_rows: 5,
	textcolor_cols: 8,
	
    plugins: [
        "advlist autolink lists link image charmap print preview anchor imagetools",
        "searchreplace visualblocks code fullscreen",
		"image",
		"textcolor",
        "insertdatetime media table contextmenu paste"
    ],
    toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | forecolor backcolor ",
	image_prepend_url: "", // caminho para visualizar no editor
	 image_advtab: true,
	 image_dimensions: true,
	 paste_data_images: true,
	 images_upload_url: "",
	 images_upload_base_path: "", // caminho para visualização no site
	 images_upload_handler: function(blobInfo, success, failure) {
        console.log(blobInfo.blob());
        success('url');
	 },
	textcolor_map: [
    "000000", "Black",
    "993300", "Burnt orange",
    "333300", "Dark olive",
    "003300", "Dark green",
    "003366", "Dark azure",
		"FF0000", "Red",
		"0066FF", "Blue",
		"FFFF00", "Yellow",
		"FF6600", "Orange",
		"666666", "Gray Medium",
    ],
  
color_picker_callback: function(callback, value) {
        callback('#000')},
	
setup: function (editor) { editor.on('change', function () { tinymce.triggerSave(); }); }

});
</script>

<script>
$(window).load(function() {
carregaRepository();
});
</script>

