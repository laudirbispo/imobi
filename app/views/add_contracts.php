<?php
if( $_SESSION['user_type'] == 'administrador' or $_SESSION['user_type'] == 'suporte' or isset($_SESSION['contracts_create']))
{
?>

<link href="/plugins/carouseller/css/carouseller.css" rel="stylesheet" type="text/css"> 
<link href="/plugins/datepicker/datepicker3.css" rel="stylesheet" type="text/css">

<SECTION CLASS="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <H4 CLASS="text-darkgray"><STRONG>Novo Contrato</STRONG></H4>
            <OL CLASS="breadcrumb bg-white">
                <LI><a href="/app/admin/home"><I CLASS="fa fa-home"></I></a></LI>
                <LI><a href="/app/admin/contracts"><i class="fa fa-file-text-o"></i> Contratos</a></LI>
            </OL>
        </div>
    </div>
</SECTION>

<SECTION CLASS="container-fluid">
    <DIV CLASS="row">

        <form name="form-add-contracts" method="POST" id="form-add-contracts" action="/app/modules/contracts/insert_contracts.php" role="form" DATA-TOGGLE="validator" enctype="APPLICATION/X-WWW-FORM-URLENCODED" NOVALIDATE data-action="submit-ajax" data-form-reset="reset" autocomplete="off" data-editor="true">

            <input type="HIDDEN" name="user_id" value="<?php echo $_SESSION['user_id'] ?>">
            <input type="HIDDEN" name="form-token" value="<?php echo $_SESSION['secret_form_token'] ?>">
            
            <div class="col-md-12">
               
                <div class="box box-solid">
                    <div class="box-header">
                        <STRONG>Informações principais</STRONG>
                    </div>
                    <div class="box-body">
                        <div class="row">
                           
                            <div class="col-md-4 col-sm-6 col-xs-12">
                                <DIV CLASS="form-group has-feedback">
                                    <LABEL FOR="contract-type"><SPAN CLASS="required">*</SPAN> Tipo de contrato:</LABEL>
                                    <DIV CLASS="msg-validation">
                                      <SELECT CLASS="form-control" NAME="contract-type" id="contract-type" REQUIRED>
                                        <OPTION VALUE="">Escolha uma oção</OPTION>
                                        <OPTION VALUE="rent">Aluguel</OPTION>
                                        <OPTION VALUE="sale">Venda</OPTION>
                                        <OPTION VALUE="management">Administração</OPTION>
                                        <OPTION VALUE="other">Outro</OPTION>
                                      </SELECT>
                                    </DIV>
                                    <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
                                    <DIV CLASS="help-block with-errors"></DIV> 
                                </DIV>
                            </div>
                           
                            <div class="col-md-4 col-sm-6 col-xs-12">
                                <DIV CLASS="form-group has-feedback">
                                    <LABEL FOR="client-owner"><SPAN CLASS="required">*</SPAN> Cliente proprietário:</LABEL>
                                    <DIV CLASS="msg-validation">
                                      <SELECT CLASS="form-control" NAME="client-owner" id="client-owner" REQUIRED>
                                        <?php echo  listClients(); ?>
                                      </SELECT>
                                    </DIV>
                                    <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
                                    <DIV CLASS="help-block with-errors"></DIV> 
                                </DIV>
                            </div>
                            
                            <div class="col-md-4 col-sm-6 col-xs-12">
                                <DIV CLASS="form-group has-feedback">
                                    <LABEL FOR="client-tenant">Cliente (comprador ou locatário):</LABEL>
                                    <DIV CLASS="msg-validation">
                                      <SELECT CLASS="form-control" NAME="client-tenant" id="client-tenant">
                                        <?php echo  listClients(); ?>
                                      </SELECT>
                                    </DIV>
                                    <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
                                    <DIV CLASS="help-block with-errors"></DIV> 
                                </DIV>
                            </div>
                            
                            <div class="col-md-4 col-sm-6 col-xs-12">
                                <DIV CLASS="form-group has-feedback">
                                    <LABEL FOR="contract-property">Imóvel:</LABEL>
                                    <DIV CLASS="msg-validation">
                                      <SELECT CLASS="form-control" NAME="contract-property" id="contract-property" REQUIRED>
                                        <?php echo  listProperties(); ?>
                                      </SELECT>
                                    </DIV>
                                    <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
                                    <DIV CLASS="help-block with-errors"></DIV> 
                                </DIV>
                            </div>
                            
                            <div class="col-md-4 col-sm-6 col-xs-12">
                                <DIV CLASS="form-group has-feedback">
                                    <LABEL FOR="contract-start-date"><SPAN CLASS="required">*</SPAN> Início da vigência do contrato:</LABEL>
                                    <DIV CLASS="msg-validation">
                                      <DIV CLASS="input-group date" data-control="datepicker">
                                          <SPAN CLASS="input-group-addon hidden-xs"><i class="fa fa-calendar"></i></SPAN>
                                          <input class="form-control" type="text" name="contract-start-date" id="contract-start-date" data-control="mask-date" required>
                                      </DIV>
                                    </DIV>
                                    <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
                                    <DIV CLASS="help-block with-errors"></DIV> 
                                </DIV>
                            </div>
                            
                            <div class="col-md-4 col-sm-6 col-xs-12">
                                <DIV CLASS="form-group has-feedback">
                                    <LABEL FOR="contract-end-date"> Fim da vigência do contrato:</LABEL>
                                    <div CLASS="pull-right ico-help icons_sys sprites-support" tabindex= "0" role= "button" DATA-CONTROL="popover-focus"  DATA-PLACEMENT="left" DATA-TOGGLE="popover" DATA-CONTAINER="body" DATA-HTML="true" DATA-TRIGGER="focus" TITLE="Ajuda" DATA-CONTENT="Deixe em branco caso a validade deste contrato seja indeterminada."></div>
                                    <DIV CLASS="msg-validation">
                                      <DIV CLASS="input-group date" data-control="datepicker">
                                          <SPAN CLASS="input-group-addon hidden-xs"><i class="fa fa-calendar"></i></SPAN>
                                          <input class="form-control" type="text" name="contract-end-date" id="contract-end-date" data-control="mask-date">
                                      </DIV>
                                    </DIV>
                                    <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
                                    <DIV CLASS="help-block with-errors"></DIV> 
                                </DIV>
                            </div>
                            
                        </div> 
                    </div>
                </div><!--//. box  -->
                
            </div><!--//.col-md-12  -->
            
            <div class="clearfix space-30"></div>
            <?php
            
            $con_db = new config\connect_db();
            $con = $con_db->connect();
            
            $contracts = $con->query("SELECT id, model, description, text FROM tempantes_contracts ORDER BY id DESC");
    
            $html = '<div class="col-md-12">';
            $html .= '<p>Escolha um modelo de contrato pré definido <a href="/app/admin/new_template_contracts">ou crie um novo modelo.</a></p>';
            $html .= '<div class="carouseller row-fluid for-car"> ';
            $html .= '<div class="carousel-wrapper">'; 
            $html .= '<div class="carousel-items">'; 
            
            while ($reg = $contracts->fetch_array())
            {
                $html .= '<div class="span3 carousel-block">';
                $html .= '<div class="card">';
                $html .= '<div class="card-content" style="min-height: 120px;">';
                $html .= '<img src="/app/images/contract-ico.png" class="contract-ico">';
                $html .= '<p class="text-mediumgray t22">'.$reg['model'].'</p>';
                $html .= '</div>';
                $html .= '<div class="card-footer">';
                $html .= '<a role="button" class="pull-left label label-danger" title="Excluir este modelo" data-actionid="'.base64_encode($reg['id']).'" data-action="del-contract"><i class="fa fa-trash"></i></a>';
                $html .= '<a role="button" class="pull-right label label-primary" data-actionid="'.base64_encode($reg['id']).'" data-action="open-contract" title="Pré visualizar modelo"><i class="fa fa-eye"></i></a>';
                $html .= '<a class="pull-right label label-success" href="/app/admin/edit_template_contracts/'.base64_encode($reg['id']).'" title="Editar modelo"><i class="fa fa-edit"></i></a>';
                $html .= '</div>';
                $html .= '</div>';
                $html .= '</div>';
            }    
                          
            $html .= '</div>';
            $html .= '</div>';
            $html .= '<div class="carousel-control-block">';
            $html .= '<div class="carousel-button-left shadow"><a href="javascript:void(0)"><i class="fa fa-chevron-left"></i></a></div>'; 
            $html .= '<div class="carousel-button-right shadow"><a href="javascript:void(0)"><i class="fa fa-chevron-right"></i></a></div>'; 
            $html .= '</div>';
            $html .= '</div>';
            $html .= '</div><!--.// col-md-6-->';
    
            if($contracts and $contracts->num_rows > 0)
            {
                echo $html;
            }
    
            ?>
            <div class="clearfix"></div>
            
            <div class="col-md-12">                              
                 <a class="btn btn-info btn-flat pull-right margin-left" data-action="load-info-client-tenant" role="button" tabindex="1">Dados cliente 2</a>
                 <a class="btn btn-info btn-flat pull-right margin-left" data-action="load-info-client-owner" role="button" tabindex="1">Dados cliente 1</a>  
                 <a class="btn btn-info btn-flat pull-right hidden" data-action="load-info-client" role="button" tabindex="1">Dados da minha empresa</a>
                  <div class="clearfix space-30"></div>
            	<div class="box box-solid">
                    <div class="box-header">
                        <STRONG>Preencha o contrato com as informações necessárias</STRONG>
                    </div>
                    <div class="box-body no-padding">
                        <TEXTAREA NAME="contract-text" CLASS="tiny" ID="contract-text" STYLE="resize:none; min-height: 800px;"></TEXTAREA>
                        <div class="clearfix"></div>
                    </div>
                </div><!--//. box --> 
            </div>
            
            <div class="clearfix space-30"></div>
            
            <DIV CLASS="col-md-12">
                <button type="submit" class="btn btn-primary btn-flat">Publicar</button>
            </DIV>
            
        </form>
        
        <div class="clearfix space-30"></div>
        
        <DIV CLASS="col-md-12" id="response-add">
        </DIV>
        
    </DIV>
</SECTION>

<DIV CLASS="clearfix space-30"></DIV>

<!-- Modal -->
<div class="modal fade bs-example-modal-lg" id="modal-contract" tabindex="-1" role="dialog" aria-labelledby="bs-example-modal-lg">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="modal-contract-title"></h4>
      </div>
      <div class="modal-body" id="modal-contract-body">
        <div class="overlay text-center">
          <br>
          <i class="fa fa-refresh fa-spin"></i> Aguarde um momento...
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
        <button type="button" class="btn btn-primary" id="use-contract-model">Usar este modelo</button>
      </div>
    </div>
  </div>
</div><!--// modal modelo de contrato-->



<script src="/plugins/bootstrap-validator-master/dist/validator.min.js"></script>
<script src="/plugins/input-mask/jquery.maskMoney.js"></script>
<script src="/plugins/input-mask/jquery.maskAll.js"></script>
<script src="/plugins/carouseller/js/carouseller.min.js"></script>
<script type="text/javascript">
$(function() {
    carouseller = new carousel('.carouseller');
});
</script>
<script src="/plugins/tinymce/tinymce.min.js"></script>
<script>
tinymce.init({
    language : 'pt_BR',
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
  
color_picker_callback: function(callback, value) {callback('#000')},	
setup: function (editor) { editor.on('change', function () { tinymce.triggerSave(); }); },
});
</script>
<script src="/app/javascript/control_forms.js"></script>
<script src="/app/javascript/contracts.js"></script>
<script src="/plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="/plugins/datepicker/locales/bootstrap-datepicker.pt-BR.js"></script>
<script>
$(document).ready(function() {
    $('[data-control="datepicker"]').datepicker({
        format: 'dd/mm/yyyy',
    });
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