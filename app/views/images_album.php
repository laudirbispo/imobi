<?php
use config\connect_db;

if( ($_SESSION['user_master_perms'] !== 'administrador') )
{
    if( $_SESSION['gallery_read'] !== '1' )
    {
        die ('<script>location.href="/app/admin.php?page=access_denied";</script>');
    }
}

if( empty($_GET['id']) or !isset($_GET['id']) )
{
    die ('<script>location.href="/app/admin.php?page=404";</script>');
}
else
{
     $id_album = filterString(base64_decode($_GET['id']), 'INT');
}

if( empty($_GET['album']) or !isset($_GET['album']) )
{
    die ('<script>location.href="/app/admin.php?page=404";</script>');
    
}
else
{
     $album = filterString(base64_decode($_GET['album']), 'CHAR');
}

?>
<link href="/plugins/lightbox/css/lightbox.css" rel="stylesheet" />
<SECTION CLASS="row">
    <div class="container">
        <div class="col-md-12">
            <H4><strong>Álbum: <?php echo str_replace('-', ' ', $album) ?></STRONG></H4>
            <OL CLASS="breadcrumb bg-white">
                <LI><a href="admin.php"><I CLASS="fa fa-home"></I></a></LI>
                <LI><a href="admin.php?page=gallery"><I CLASS="fa fa-picture-o" ></I> Galeria</a></LI>
                <LI><a href="admin.php?page=images_album&album=<?php echo base64_encode($album )?>&id=<?php echo $id_album  ?>"> <?php echo @$album ?></a></LI>
            </OL>
        </div>
    </div>
</SECTION>

<DIV CLASS="space30"></DIV>

<SECTION CLASS="row">
  <DIV CLASS="container">
   
    <div class="col-md-12">
    
        <DIV CLASS="box box-primary">  
          <DIV CLASS="box-header">
            <STRONG>Fazer o upload de imagens</STRONG>
          </DIV>        
          <DIV CLASS="box-body"> 
            <DIV ID="upload-galeria" CLASS="upload-galeria" DATA-URL="/app/modules/gallery/upload_images.php"></DIV>
            <DIV ID="status"></DIV>  
          </DIV>
        </DIV><!--//.box-->
    

        <DIV CLASS="container" STYLE="padding:0 !important">
          <DIV CLASS="btn btn-group" ROLE="group">
            <LABEL>
              <input type="CHECKBOX" id="select-all-images"> Selecionar todas as imagens  
            </LABEL> 
          </DIV> 
        </DIV>
  
    <DIV CLASS="clearfix space30"></DIV>
  
        <form action="/app/modules/gallery/delete_images_gallery.php" id="form-delete-images-galeria" name="form-delete-images-galeria" data-reload="true" data-action="submit-ajax" data-form-reset="reset">
        
        <input type="HIDDEN" name="user_id" value="<?php echo $_SESSION['user_id'] ?>">
        <input type="HIDDEN" name="form-token" value="<?php echo$_SESSION['secret_form_token '] ?>">
        
        <DIV ID="reload-images" data-control="data-reload">
          <?php 
        
            $con_db = new config\connect_db();
            $con = $con_db->connect();
                    
            $images_gallery = $con->prepare("SELECT `id`, `image`, `dir`, `legenda` FROM `images_gallery` WHERE `id_album` = ? ORDER BY `id` DESC");
            $images_gallery->bind_param('i', $id_album);
            $images_gallery->execute();
            $images_gallery->store_result();
            $images_gallery->bind_result($id_image, $image, $dir, $legenda);
            $total_reg = $images_gallery->num_rows;
            
            $print_images = ''; 
                
            while( $reg = $images_gallery->fetch() )
            {
                $print_images .= '<div CLASS="col-lg-2 col-md-2 col-sm-3 col-xs-6 ">'; 
                $print_images .= '  <div CLASS="container-imagem">'; 
                $print_images .= '    <a href="'.$image.'" data-lightbox="roadtrip" data-title="'.$legenda.'" class="full-screen"><i class="fa fa-expand" aria-hidden="true"></i></a>'; 
                $print_images .= '     <IMG data-src="'.$image.'" SRC="/plugins/EagerImageLoader/ajax_loader.gif" CLASS="img-responsive">'; 
                $print_images .= '    <div class="add-legenda" data-id="'.$id_image.'" data-subtitle="'.$legenda.'" TITLE="Clique para editar a legenda da imagem.">'; 
                $print_images .= '      <i CLASS="fa fa-comments-o"></i>'; 
                $print_images .= '    </div>'; 
                $print_images .= '    <div class="checkbox-del">'; 
                $print_images .= '      <input type="checkbox" name="delete-imgs[]" data-control="checkebox-del" value="'.$id_image.'">';
                $print_images .= '    </div>'; 
                $print_images .= '    <div class="select-capa define-capa-album" data-img="'.$image.'" data-album="'.$id_album.'" TITLE="Clique para definir esta imagem como capa.">'; 
                $print_images .= '      <i CLASS="fa fa-check"></i>'; 
                $print_images .= '    </div>'; 
                $print_images .= '  </div>'; 
                $print_images .= '</div><!--//.item-->'; 
            }
            
            $images_gallery->free_result();
            
            if( !$images_gallery )
            {
                echo ($con_db->serverFailure());
            }
            else if( $total_reg <= 0 )
            {
                echo '<div class="callout callout-info">
                      <h4>:(</h4>
                      <p>Não há imagens neste álbum.</p>
                     </div>'; 
            }
            else
            {
                echo $print_images;
            }
            
           ?>
        
        </DIV><!--reload imagens-->
          
        <DIV CLASS="clearfix margin-bottom"></DIV>
        
        <DIV CLASS="tfoot nav navbar-nav">    
          <button class="btn btn-flat btn-danger " DISABLED type="submit" data-control="submit-del-images"><I CLASS="fa fa-trash-o"></I> Excluir Ítens Selecionados</button>  
        </DIV> 
        
        </form>
  
  </div>
  
  </DIV>
</SECTION>

<script src="/plugins/bootstrap-validator-master/dist/validator.min.js"></script>
<script src="/app/javascript/generatePreviewsImages.js"></script>
<script src="/app/javascript/gallery.js"></script>
<script src="/plugins/lightbox/js/lightbox.min.js"></script>
<script src="/app/javascript/jQuery_Uploader.js"></script>
<script>
$(document).ready(function(){

var url = $('.upload-galeria').attr('data-url');
var settings = {
	url: url,
	method: "POST",
    formData: {"dir":"<?php echo @$album ?>", "id_album": "<?php echo @$id_album ?>"},
	allowedTypes:"jpg,png,gif,jpeg",
	fileName: "images",
	multiple: true,
    showStatusAfterSuccess:true,
    showAbort:true,
	showDone:true,
    maxFileSize:1024*1024*5,
    multiDragErrorStr: "Drag & Drop! Soltar arquivos não está permitido!",
	extErrorStr:"Extensão não permitida:",
	sizeErrorStr:"não é permitido. O tamanho máximo permitido é de: ",
	uploadErrorStr:"Upload não está liberado!",
	onSuccess:function(files,data,xhr)
	{
		$("#status").append(data); 
	},
    afterUploadAll:function()
	{
		$("#reload-images").load(location.href+" #reload-images>*", function(){ 
      new EagerImageLoader();
    }).fadeIn('slow');
	},
	onError: function(files,status,errMsg)
	{		
		$("#status").html(errMsg);
	}
}
$("#upload-galeria").uploadFile(settings);

});
</script>