<?php
use config\connect_db;

if( ($_SESSION['user_master_perms'] !== 'administrador') )
{
    if( $_SESSION['vehicles_edit'] !== '1' )
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
     @$id_motorcycle = filterString(base64_decode($_GET['id']), 'INT');  
}

?>
<link href="/plugins/lightbox/css/lightbox.css" rel="stylesheet" />

<SECTION CLASS="row">
    <div class="container">
        <div class="col-md-12">
            <H4 CLASS="text-darkgray"><STRONG>Imagens</STRONG></H4>
            <OL CLASS="breadcrumb bg-white">
                <LI><a href="admin.php"><I CLASS="fa fa-home"></I></a></LI>
                <LI><a href="admin.php?page=vehicles"><I CLASS="fa fa-car"></I> Veículos</a></LI>
                <LI><a href="admin.php?page=images_motorcycle&id=<?php echo (isset($id_motorcycle)) ? $id_motorcycle : '' ; ?>"><?php echo (isset($id_motorcycle)) ? $id_motorcycle : '' ; ?></a></LI>
            </OL>
        </div>
    </div>
</SECTION>


<DIV CLASS="space30"></DIV>

<SECTION CLASS="container" >
  <DIV CLASS="row">
  
    <DIV CLASS="box box-primary">  
      <DIV CLASS="box-header ">
        <STRONG>Fazer o upload de imagens</STRONG>
      </DIV>        
      <DIV CLASS="box-body"> 
        <DIV ID="upload-cars" CLASS="upload-cars" DATA-URL="/app/modules/vehicles/upload_images_motorcycles.php"></DIV>
        <DIV ID="status"></DIV>  
      </DIV>
    </DIV><!--//.box-->

    <DIV CLASS="container-fluid" STYLE="padding:0 !important">
      <DIV CLASS="btn btn-group" ROLE="group">
        <LABEL>
          <input type="CHECKBOX" id="select-all-images"> Selecionar todas as imagens  
        </LABEL> 
      </DIV> 
    </DIV>
  
  <DIV CLASS="clearfix space30"></DIV>
  
  <form action="/app/modules/vehicles/delete_images_vehicles.php" id="form-delete-images-vehicles" name="form-delete-images-vehicles">
    
    <input type="HIDDEN" name="user_id" value="<?php echo $_SESSION['user_id'] ?>">
    <input type="HIDDEN" name="form-token" value="<?php echo$_SESSION['secret_form_token '] ?>">
    <input type="hidden" name="categoria" value="images_motorcycles">
  
    <DIV ID="reload-images">

      <?php 
 
        $con_db = new config\connect_db();
        $con = $con_db->connect();
                
        $images = $con->prepare("SELECT `id`, `image` FROM `images_motorcycles` WHERE `id_motorcycle` = ? ORDER BY `id` DESC");
        $images->bind_param('i', $id_motorcycle);
        $images->execute();
        $images->store_result();
        $images->bind_result($id, $image);
        $total_reg = $images->num_rows; 
        
        $print_images = ''; 
            
        while( $reg = $images->fetch() )
        {
            $print_images .= '<div CLASS="col-lg-2 col-md-2 col-sm-3 col-xs-6 container-itens-images">'; 
            $print_images .= '  <div CLASS="container-imagem">'; 
            $print_images .= '    <a href="'.$image.'" data-lightbox="roadtrip" class="full-screen"><i class="fa fa-expand" aria-hidden="true"></i></a>'; 
            $print_images .= '     <IMG data-src="'.$image.'" SRC="/plugins/EagerImageLoader/ajax_loader.gif" CLASS="img-responsive">'; 
            $print_images .= '    <div class="checkbox-del">'; 
            $print_images .= '      <input type="checkbox" name="delete-imgs[]" data-control="checkebox-del" value="'.$id.'">';
            $print_images .= '    </div>'; 
            $print_images .= '    <div class="select-capa define-capa-car" data-categoria="motorcycles" data-img="'.$image.'" data-id-veiculo="'.$id_motorcycle.'" TITLE="Clique para definir esta imagem como capa.">'; 
            $print_images .= '      <i CLASS="fa fa-check"></i>'; 
            $print_images .= '    </div>'; 
            $print_images .= '  </div>'; 
            $print_images .= '</div><!--//.item-->'; 
        }
        
        $images->free_result();
        $images->close();
        
        if( !$images )
        {
            echo ($con_db->serverFailure());
        }
        else if( $total_reg <= 0 )
        {
            echo '<div class="callout callout-info">
                 <h4><i class="icon fa fa-info"></i> Atenção</h4>
                 <p>Nenhuma imagem cadastrada até o momento para este veículo.</p>
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
      <button class="btn btn-flat btn-danger" type="submit" data-control="submit-del-images" DISABLED><I CLASS="fa fa-trash-o"></I> Excluir Ítens Selecionados</button>  
    </DIV> 
  
  </form>
  
  </DIV>
</SECTION>

<script src="/plugins/bootstrap-validator-master/dist/validator.min.js"></script>
<script src="/app/javascript/generatePreviewsImages.js"></script>
<script src="/app/javascript/vehicle.js"></script>
<script src="/plugins/lightbox/js/lightbox.min.js"></script>
<script src="/app/javascript/jQuery_Uploader.js"></script>
<SCRIPT>
$(document).ready(function(){

var url = $('.upload-cars').attr('data-url');
var settings = {
	url: url,
	method: "POST",
    formData: {"id-motorcycle":"<?php echo $id_motorcycle ?>"},
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
    $('input').iCheck('update');
	},
  afterUploadAll:function()
	{
		$("#reload-images").load(location.href+" #reload-images>*", function(){ 
            new EagerImageLoader();
        }).fadeOut('slow').fadeIn('slow');
	},
	onError: function(files,status,errMsg)
	{		
		$("#status").html(errMsg);
	}
}
$("#upload-cars").uploadFile(settings);

});
</script>