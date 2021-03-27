<?php
if( $_SESSION['user_type'] == 'administrador' or $_SESSION['user_type'] == 'suporte' or isset($_SESSION['contracts_edit']))
{
    if(!isset($_GET['actionid']) or empty($_GET['actionid']))
    {
         echo '<div class="alert alert-warning">
              <h4><i class="icon fa fa-warning"></i> Atenção!</h4>
              Nenhum imóvel identificado com essas credenciais.<br>
              Verifique se o endereço está correto, se essa mensagem continuar aparecendo, procure por ajuda na página de <a href="/app/admin/support">Ajuda & Suporte</a> ou entre em contato com o administrador.
              </div>'; 
    }
    else
    {
        $actionid = filterString(base64_decode($_GET['actionid']), 'INT');

        $con_db = new config\connect_db();
        $con = $con_db->connect();
        
        $templante_contracts = $con->prepare("SELECT id, model, description, text FROM tempantes_contracts WHERE id = ? LIMIT 1");
        $templante_contracts->bind_param('i', $actionid);
        $templante_contracts->execute();
        $templante_contracts->store_result();
        $templante_contracts->bind_result($id, $model, $description, $text);
        $templante_contracts->fetch();
        $rows = $templante_contracts->num_rows;
        $templante_contracts->free_result();
        $templante_contracts->close();
        
        if($templante_contracts and $rows > 0)
        {    
            // Purifica saída html
            require_once($_SERVER['DOCUMENT_ROOT'].'/libs/HTMLPurifier/HTMLPurifier.auto.php');
            $config = HTMLPurifier_Config::createDefault();
            $purifier = new HTMLPurifier($config);
            $text = $purifier->purify($text);      

?>

<style>
<?php 
echo file_get_contents($_SERVER['DOCUMENT_ROOT'].'/plugins/datepicker/datepicker3.css'); 
?>
</style>

<SECTION CLASS="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <H4 CLASS="text-darkgray"><STRONG>Editar Modelo de Contrato</STRONG></H4>
            <OL CLASS="breadcrumb bg-white">
                <LI><a href="/app/admin/home"><I CLASS="fa fa-home"></I></a></LI>
                <LI><a href="/app/admin/contracts"><i class="fa fa-files-o"></i> Contratos</a></LI>
            </OL>
        </div>
    </div>
</SECTION>

<SECTION CLASS="container-fluid">
    <DIV CLASS="row">

        <form name="form-edit-templante_contracts" method="POST" id="form-edit-templante_contracts" action="/app/modules/contracts/update_templante_contracts.php" role="form" DATA-TOGGLE="validator" enctype="APPLICATION/X-WWW-FORM-URLENCODED" NOVALIDATE data-action="submit-ajax" data-form-reset="noreset" autocomplete="off">

            <input type="HIDDEN" name="user_id" value="<?php echo $_SESSION['user_id'] ?>">
            <input type="HIDDEN" name="form-token" value="<?php echo $_SESSION['secret_form_token'] ?>">
            <input type="HIDDEN" name="actionid" value="<?php echo base64_encode($actionid) ?>">
            
            <div class="col-md-6 col-sm-6 col-xs-12">
                
                <div class="box box-solid">
                    <div class="box-header">
                        <STRONG>Informações principais</STRONG>
                    </div>
                    <div class="box-body">
                           
                       <DIV CLASS="form-group has-feedback">
                            <LABEL FOR="contracts-model-name"><SPAN CLASS="required">*</SPAN> Nome do modelo:</LABEL>
                            <DIV CLASS="msg-validation">
                                <input class="form-control" type="text" name="contracts-model-name" id="contracts-model-name" value="<?php echo $model ?>">
                            </DIV>
                            <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
                            <DIV CLASS="help-block with-errors"></DIV> 
                        </DIV>
                        
                        <DIV CLASS="form-group has-feedback">
                            <LABEL>Descrição:</LABEL>
                            <DIV CLASS="msg-validation">
                                <TEXTAREA CLASS="form-control count-caractere" DATA-MAX-CARACTERE="1024" NAME="contracts-description" ID="contracts-description" STYLE="resize:none; min-height:120px !important"><?php echo $description ?></TEXTAREA>
                            </DIV>
                            <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
                            <DIV CLASS="help-block with-errors"></DIV> 
                            <p CLASS="restante-caractere"></p>
                       </DIV>

                    </div>
                </div><!--//. box  -->
                
            </div><!--//. col left principal -->
            
            <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="callout callout-info">
                    <h4><i class="fa fa-info"></i> Dicas</h4>
                    <p><strong>LEI Nº 11.785, DE  22 DE SETEMBRO DE 2008</strong><br>Os contratos de adesão escritos serão redigidos em termos claros e com caracteres ostensivos e legíveis, cujo tamanho da fonte não será inferior ao corpo doze, de modo a facilitar sua compreensão pelo consumidor.</p>
                    <p>É aconselhável usar umas das formas da fonte Arial.</p>
                </div>
            </div><!--//. col-right principal -->
            
            <div class="clearfix space-30"></div>
            
            <div class="col-md-12">
            	<div class="box box-solid">
                    <div class="box-header">
                        <STRONG>Digitalize aqui o contrato</STRONG>
                    </div>
                    <div class="box-body no-padding">
                        <TEXTAREA NAME="contracts-text" CLASS="tiny"  ID="contracts-text" STYLE="resize:none; min-height: 500px !important;"><?php echo $text ?></TEXTAREA>
                        <div class="clearfix"></div>
                    </div>
                </div><!--//. box --> 
            </div>
            
            <div class="clearfix space-30"></div>
            
            <DIV CLASS="col-md-12">
                <button type="submit" class="btn btn-primary btn-flat">Atualizar</button>
            </DIV>
            
        </form>
        
        <div class="clearfix space-30"></div>
        
    </DIV>
</SECTION>

<DIV CLASS="clearfix space-30"></DIV>

<script src="/plugins/bootstrap-validator-master/dist/validator.min.js"></script>
<script src="/plugins/input-mask/jquery.maskMoney.js"></script>
<script src="/plugins/input-mask/jquery.maskAll.js"></script>
<script src="/plugins/datepicker/bootstrap-datepicker.js"></script>
<script>
$(document).ready(function() {
$('[data-control="datepicker"]').datepicker();
});
</script>

<script src="/plugins/tinymce/tinymce.min.js"></script>
<script>
tinymce.init({
    language : 'pt_BR',
    content_css : "/app/tinymce/css/contracts.css",
	convert_urls: false,
    selector: ".tiny",
    constrain_menus : true,
	plugins: "textcolor",
    textcolor_rows: 5,
    forced_root_block_attrs: { "style": "margin: 2px 0;" },
	textcolor_cols: 8,
    plugins: [
        'advlist autolink lists link image charmap print preview hr anchor pagebreak',
        'searchreplace wordcount visualblocks visualchars code fullscreen',
        'insertdatetime media nonbreaking save table contextmenu directionality',
        'emoticons template paste textcolor colorpicker textpattern imagetools'
    ],
    toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | forecolor backcolor | fontsizeselect | fontselect",
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
    fontsize_formats: '8pt 9pt 10pt 11pt 12pt 14pt 16pt 18pt 24pt 36pt 48pt',
    Theme_advanced_fonts: "Andale Mono = andale mono, times;" + 
      "Arial = arial, helvetica, sans-serif;" + 
      "Arial Black = arial black, avant garde;" + 
      "Livro Antiqua = livro antiqua, palatino;" + 
      "Comic Sans MS = comic sem ms, sans-serif;" + 
      "Courier New = courier new, courier;" + 
      "Geórgia = geórgia, palatino;" + 
      "Helvetica = helvetica;" + 
      "Impacto = impacto, chicago;" + 
      "Símbolo = símbolo;" + 
      "Tahoma = tahoma, arial, helvetica, sans-serif;" + 
      "Terminal = terminal, monaco;" + 
      "Times New Roman = tempos novos romanos, tempos;" + 
      "Trebuchet MS = trebuchet ms, genebra;" + 
      "Verdana = verdana, geneva;" + 
      "Webdings = webdings;" + 
      "Wingdings = wingdings, dingbats do zapf",
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
<script src="/app/javascript/control_forms.js"></script>

<?php
        }
        else
        {
            echo '<div class="alert alert-warning">
                 <h4><i class="icon fa fa-warning"></i> Atenção!</h4>
                 Nenhum imóvel identificado com essas credenciais.<br>
                 Verifique se o endereço está correto, se essa mensagem continuar aparecendo, procure por ajuda na página de <a href="/app/admin/support">Ajuda & Suporte</a> ou entre em contato com o administrador.
                 </div>';    
        }//. Se o select encontrou o usuário
        
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