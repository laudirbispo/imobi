<?php
if( $_SESSION['user_type'] == 'administrador' or $_SESSION['user_type'] == 'suporte' or isset($_SESSION['products_create']))
{
    require_once($_SERVER['DOCUMENT_ROOT'].'/app/modules/products/functions_products.php');
?>

<link href="/plugins/datepicker/datepicker3.css" rel="stylesheet" type="text/css">

<SECTION CLASS="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <H4 CLASS="text-darkgray"><STRONG>Adicionar Produtos</STRONG></H4>
            <OL CLASS="breadcrumb bg-white">
                <LI><a href="/app/admin/home"><I CLASS="fa fa-home"></I></a></LI>
                <LI><a href="/app/admin/properties"><i class="fa fa-shopping-cart"></i> Produtos</a></LI>
            </OL>
        </div>
    </div>
</SECTION>

<SECTION CLASS="container-fluid">
    <DIV CLASS="row">

        <form name="form-add-properties" method="POST" id="form-add-properties" action="/app/modules/properties/insert_properties.php" role="form" DATA-TOGGLE="validator" enctype="APPLICATION/X-WWW-FORM-URLENCODED" NOVALIDATE data-form-reset="reset" autocomplete="off">

            <input type="HIDDEN" name="user_id" value="<?php echo $_SESSION['user_id'] ?>">
            <input type="HIDDEN" name="form-token" value="<?php echo $_SESSION['secret_form_token'] ?>">
            
            <div class="col-md-6 col-sm-6 col-xs-12">
                
                <div class="box box-solid">
                    <div class="box-header">
                        <STRONG>Informações principais</STRONG>
                    </div>
                    <div class="box-body">
                       
                       <DIV CLASS="form-group has-feedback">
                            <LABEL FOR="product-name"><Span class="required">*</Span> Nome do Produto:</LABEL>
                            <DIV CLASS="msg-validation">
                                <input type="text" class="form-control" name="product-name" id="product-name" re>
                            </DIV>
                            <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
                            <DIV CLASS="help-block with-errors"></DIV>            
                        </DIV>
                        
                    </div>
                </div><!--//.box-->

            </div><!--//.col-left-->
            
            <div class="col-md-6 col-sm-6 col-xs-12">
                
                <div class="box box-solid">
                    <div class="box-header">
                        <STRONG>Categorias</STRONG>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-toggle="em-modal" data-page="layout" data-title="Editando categorias"><i class="fa fa-question-circle text-info"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <label><span class="required">*</span> Categoria do produto</label>
                        
                        <div class="clearfix space-20"></div>
                        
                        <p class="text-info"><i class="fa fa-info"></i> Use as opções abaixo para criar, editar e deletar categorias.</p>
                        <div class="input-group">
                            <input type="text" class="form-control" id="input-category-name" form="no-form">
                            <div class="input-group-btn">
                                <button type="button" class="btn btn-primary btn-flat" title="Adicionar categoria" id="new-category"><i class="fa fa-plus"></i></button>
                                <button type="button" class="btn btn-success btn-flat" title="Editar categoria selecionada" id="edit-category"><i class="fa fa-edit"></i></button>
                                <button type="button" class="btn btn-danger btn-flat" title="Deletar categoria selecionada" id="delete-category"><i class="fa fa-trash"></i></button>
                            </div>
                        </div>
                        
                        <div class="clearfix space-20"></div>
                        
                        <p class="text-info"><i class="fa fa-info"></i> Selecione a categoria</p>
                        
                        <div class="tree scrollbarCustom">
                             <?php
                                 echo recursiveListCategories($categoryId = '0', $ulInitialClass = 'first-treeview');
                             ?>
                        </div><!--//.tree-->
                    </div>
                </div><!--//.box-->
                
                
            </div><!--//.col-right-->
            
        </form>
        
    </DIV>
</SECTION>

<style>
.droppable-trash {
  background: transparent;
position: fixed;
    z-index: 999;
    display: none;
width: 150px;
height: 150px;
border-radius: 3.5px;
transition: box-shadow .2s ease;
right: 50px;
bottom: 40px;
    padding: 20px;


}
.droppable-trash:hover, 
.droppable-trash-hover {
  
}
.droppable-trash.droppable-trash-hover .lid,
.droppable-trash.droppable-trash-hover .lidcap,
.droppable-trash:hover .lid,
.droppable-trash:hover .lidcap {
  transform: rotate(30deg);
  margin-bottom: 13px;
    margin-left: 10px;
    transition: linear .5s ease;
}

.droppable-trash > .lidcap,
.droppable-trash > .lid,
.droppable-trash > .bin {
  position: absolute;
}

.droppable-trash > .lidcap,
.droppable-trash > .lid {
  border-top-left-radius: 2.8px;
  border-top-right-radius: 2.8px;
  background: #da2323;
  transition: transform .2s linear, margin .2s linear;
}

.droppable-trash > .lidcap {
  bottom: 74px;
left: 65px;
height: 7px;
width: 14px;

}

.droppable-trash > .lid {
  bottom: 65px;
left: 42.5px;
width: 60px;
height: 10px;

}

.droppable-trash > .bin {
  bottom: 15px;
    left: 48px;
    width: 50px;
    border-top: 50px solid #ee2626;
    border-left: 7px solid transparent;
    border-right: 7px solid transparent;

}
</style>

<div class="droppable-trash animated bounceInRight" id="droppable-trash">
  <div class="lid"></div>
  <div class="lidcap"></div>
  <div class="bin"></div>
</div>


<script src="/plugins/input-mask/jquery.maskMoney.js"></script>
<script src="/plugins/input-mask/jquery.maskAll.js"></script>
<script src="/app/javascript/products.js"></script>
<script async defer src="/app/javascript/control_forms.js"></script>
<script src="/plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="/plugins/datepicker/locales/bootstrap-datepicker.pt-BR.js"></script>
<script>
$(document).ready(function() {
    $('[data-control="datepicker"]').datepicker({
        format: 'dd/mm/yyyy',
    });
});
</script>

</script>
<script src="/plugins/tinymce/tinymce.min.js"></script>
<script>
tinymce.init({
    language : 'pt_BR',
    content_css : "/plugins/tinymce/css/properties.css",
	convert_urls: false,
    selector: ".tiny",
    constrain_menus : true,
	plugins: "textcolor",
    textcolor_rows: 5,
	textcolor_cols: 8,
    plugins: [
        'advlist autolink lists link image charmap print preview hr anchor pagebreak',
        'searchreplace wordcount visualblocks visualchars code fullscreen',
        'insertdatetime media nonbreaking save table contextmenu directionality',
        'emoticons template paste textcolor colorpicker textpattern imagetools'
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
    style_formats_merge: true,
    style_formats: [
        {
            title: 'Personalizado',
            items: [
                {title: 'Botão azul', inline: 'span', classes: 'botao'},
                {title: 'Botão verde', inline: 'span', classes: 'botao_verde'},
                {title: 'Título cinza', inline: 'h1', classes: 'titulo_cinza'}
            ]
        }
    ],
  
color_picker_callback: function(callback, value) {
        callback('#000')},
	
setup: function (editor) { editor.on('change', function () { tinymce.triggerSave(); }); }

});
</script>

<?php
}
else
{
    echo '<DIV CLASS="error-page">',
    '<P CLASS=" headline text-yellow"> <I CLASS="fa fa-lock fa-2x" ARIA-HIDDEN="true"></I></P>',
    '<DIV CLASS="error-content">',
    '<H3><I CLASS="fa fa-warning text-yellow"></I> Oops! Você não tem permissão para acessar esta página.</H3>',
    'Retorne a página <a href="/app/index/home">inicial</a>.',
    '</P>',
    '</DIV> ',
    '</DIV>';
}
?>