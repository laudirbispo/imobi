<?php
if( $_SESSION['user_type'] == 'administrador' or $_SESSION['user_type'] == 'suporte' or isset($_SESSION['properties_create']))
{
    if(!isset($_GET['actionid']) or empty($_GET['actionid']))
    {
         echo '<div class="alert alert-warning">
              <h4><i class="icon fa fa-warning"></i> Atenção!</h4>
              Nenhum usuário identificado com essas credenciais.<br>
              Verifique se o endereço está correto, se essa mensagem continuar aparecendo, procure por ajuda na página de <a href="/app/admin/support">Ajuda & Suporte</a> ou entre em contato com o administrador.
              </div>'; 
    }
    else
    {
        $actionid = filterString(base64_decode($_GET['actionid']), 'INT');
        
?>
<link href="/plugins/lightbox/css/lightbox.css" rel="stylesheet" />
<SECTION CLASS="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <H4 CLASS="text-darkgray"><STRONG>Cadastrar Imóvel</STRONG></H4>
            <OL CLASS="breadcrumb bg-white">
                <LI><a href="/app/admin/home"><I CLASS="fa fa-home"></I></a></LI>
                <LI><a href="/app/admin/properties"><i class="fa fa-industry"></i> Imóveis</a></LI>
                <LI><a href="/app/admin/images_properties/<?php echo base64_encode($actionid) ?>"><i class="fa fa-eye"></i> Ref: <?php echo $actionid ?> </a></LI>
            </OL>
        </div>
    </div>
</SECTION>

<DIV CLASS="space30"></DIV>

<SECTION CLASS="container-fluid">
    <DIV CLASS="row">
   
        <div class="col-md-6 col-sm-6 col-xs-12">
            <DIV CLASS="box box-solid">  
                <DIV CLASS="box-header">
                    <STRONG>Fazer o upload de imagens</STRONG>
                </DIV>        
                <DIV CLASS="box-body"> 
                    
                    <DIV ID="upload-galeria" CLASS="upload-galeria" DATA-URL="/app/modules/properties/upload_images.php"></DIV> 
                    <DIV ID="status"></DIV> 
                </DIV>
            </DIV><!--//.box-->
        </div>

        <div class="col-md-6 col-sm-6 col-xs-12">
            <div class="callout callout-info">
                <h4>Instruções para o upload</h4>
                <ul style="padding:5px 15px;">
                    <li>Cada arquivo pode ter o tamanho máximo de 5MB cada.</li>
                    <li>Você pode enviar até 20 arquivos por vez.</li>
                    <li>Somente são permitidos aquivos com as seguintes extensões:</li>
                        <ul style="padding:5px 15px;">
                            <li>jpg</li>
                            <li>jpeg</li>
                            <li>png</li>
                            <li>bmp</li>
                            <li>gif</li>
                        </ul>
                </ul>
              </div>
        </div>
        
        <DIV CLASS="clearfix space30"></DIV>
        
        <div class="col-md-12"> 
            <DIV CLASS="selection-box">
                <input type="CHECKBOX" id="select-all-images"> 
                <LABEL for="select-all-images">Selecionar todas </LABEL>  
            </DIV> 
        </div>
        
  
        <DIV CLASS="clearfix space30"></DIV>
        
        <div class="">
            <form action="/app/modules/properties/delete_images_properties.php" id="form-delete-images-properties" name="form-delete-images-properties" data-reload="true" data-action="submit-ajax" data-form-reset="reset">

            <input type="HIDDEN" name="user_id" value="<?php echo $_SESSION['user_id'] ?>">
            <input type="HIDDEN" name="form-token" value="<?php echo $_SESSION['secret_form_token'] ?>">

            <DIV ID="reload-images" data-control="data-reload">
            <?php 

                $con_db = new config\connect_db();
                $con = $con_db->connect();

                $images_properties = $con->prepare("SELECT id, image, subtitle FROM images_properties WHERE id_properties = ? ORDER BY `id` DESC");
                $images_properties->bind_param('i', $actionid);
                $images_properties->execute();
                $images_properties->store_result();
                $images_properties->bind_result($id_image, $image, $subtitle);
                $total_reg = $images_properties->num_rows;

                $print_images = ''; 

                while( $reg = $images_properties->fetch() )
                {
                    $image_big = SUBDOMAIN_IMGS.'/docs/properties/'.$actionid.'/big/'.$image;
                    $image_small = SUBDOMAIN_IMGS.'/docs/properties/'.$actionid.'/small/'.$image;

                    $print_images .= '<div class="col-md-3 col-sm-4 col-xs-12">';
                    $print_images .= '<div class="card card-gallery">';
                    $print_images .= '<a href="'.$image_big.'" data-lightbox="roadtrip" data-title="'.$subtitle.'" class="full-screen ico-zoom animated fadeInRight pulse"><i class="fa fa-expand" aria-hidden="true"></i></a>';
                    $print_images .= '<div class="card-image">';
                    $print_images .= '<img class="img-responsive vertical-align" src="/app/images/loading-circle.gif" data-src="'.$image_small.'">';
                    $print_images .= '</div>';
                    $print_images .= '<div class="card-footer">';
                    $print_images .= '<input type="checkbox" class="pull-left" name="delete-imgs[]" data-control="checkebox-del" value="'.$id_image.'">';
                    $print_images .= '<div class="pull-right">';
                    $print_images .= '<a role="button" class="dropdown-toggle text-mediumgray" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-cogs"></i></a>';
                    $print_images .= '<ul class="dropdown-menu pull-right" role="menu">';
                    $print_images .= '<li><a HREF="javascript:;" tab-index="0" role="buttom" data-control="set-cover" data-tid="'.$actionid.'" data-img="'.$image.'" data-ajax-url="/app/modules/properties/set_cover.php" ><I CLASS="fa fa-thumb-tack text-green"></I>Definir como capa</a></li>';
                    $print_images .= '</ul>';
                    $print_images .= '</div>';
                    $print_images .= '</div>';
                    $print_images .= '</div>';
                    $print_images .= '</div><!-- //. Card item -->';
                }

                $images_properties->free_result();

                if( !$images_properties)
                {
                    echo 'errrr';
                }
                else if( $total_reg <= 0 )
                {
                    echo '<div class="col-md-12"><div class="callout callout-info">
                          <h4>Não há imagens para este imóvel.</h4>
                          <p>Adicione algumas imagens para este imóvel, com o recurso de arrastar e soltar fica fácil e prático fazer o upload de imagens.</p>
                          </div></div>'; 
                }
                else
                {
                    echo $print_images;
                }

            ?>

            </DIV><!--reload imagens-->

            <DIV CLASS="clearfix margin-bottom"></DIV>

            <DIV CLASS="col-md-12">    
              <button class="btn btn-flat btn-primary " DISABLED type="submit" data-control="submit-del-images"><I CLASS="fa fa-trash-o"></I> Excluir Ítens Selecionados</button>  
            </DIV> 

            </form>
  
        </div>
  </DIV>
</SECTION>

<script src="/plugins/bootstrap-validator-master/dist/validator.min.js"></script>
<script src="/app/javascript/generatePreviewsImages.js"></script>
<script src="/app/javascript/properties.js"></script>
<script src="/plugins/lightbox/js/lightbox.min.js"></script>
<script src="/app/javascript/jQuery_Uploader.js"></script>
<script>
$(document).ready(function(){

var url = $('.upload-galeria').attr('data-url');
var settings = {
	url: url,
	method: "POST",
    formData: {"id_properties": "<?php echo $actionid ?>", "form-token": "<?php echo $_SESSION['secret_form_token'] ?>", "user_id": "<?php echo $_SESSION['user_id'] ?>"},
	allowedTypes:"jpg,png,gif,jpeg",
	fileName: "images",
	multiple: true,
    showStatusAfterSuccess:true,
    showAbort:true,
	showDone:true,
    maxFileSize:1024*1024*10,
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
<?php
        
    }//.se existe $_GET['actionid']
    
}
else
{
    echo '<DIV CLASS="error-page">',
    '<P CLASS=" headline text-yellow"> <I CLASS="fa fa-lock fa-2x" ARIA-HIDDEN="true"></I></P>',
    '<DIV CLASS="error-content">',
    '<H3><I CLASS="fa fa-warning text-yellow"></I> Oops! Você não tem permissão para acessar esta página.</H3>',
    '<P>Você não tem privilégio suficiente para acessar esta página!<BR>
        Retorne a página <a href="/app/index/home">inicial</a>.',
    '</P>',
    '</DIV> ',
    '</DIV>';
}
?>