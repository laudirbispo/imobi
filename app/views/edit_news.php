<?php
use config\connect_db;
use app\controls\errors;
use app\controls\perms;

$errors = new errors();
$user_perms   = new perms();

if( ($_SESSION['user_master_perms'] != 'administrador') )
{
    if( $_SESSION['news_read'] != '1' )
    {
       die ('<script>location.href="/app/admin.php?page=access_denied";</script>');
    }
}

$con_db = new connect_db();
$con = $con_db->connect();

if( empty($_GET['id']) or !isset($_GET['id']) )
{
    echo '<div class="alert alert-warning alert-dismissible" role="alert">
   <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><i class="fa fa-hand-paper-o fa-2x" aria-hidden="true"></i> <strong>AVISO!</strong> Selecione uma notícia para editar.</div>';
     $form_validate = "disabled";
}
else
{
    $form_validate = '';
  
    $id = base64_decode($_GET['id']);
    $id = filterString($id, 'INT');
    $edit = $con->query("SELECT * FROM `noticias` WHERE `id` = '$id' ");
    
    while( $reg = $edit->fetch_array() )
    {
        $titulo       = $reg['titulo'];
        $subtitulo    = $reg['subtitulo'];
        $capa         = $reg['capa'];
        $categoria    = $reg['categoria'];
        $subcategoria = $reg['subcategoria'];
        $destaque     = $reg['destaque'];
        $facebook     = $reg['facebook'];
        $ativa        = $reg['ativa'];
        $text_html    = $reg['text_html'];
        $tags         = $reg['tags'];
    }
  
    $facebook_check = ($facebook == '1') ? 'CHECKED' : '' ;
    $ativa_check = ($ativa == '1') ? 'CHECKED' : '' ;
    $destaque_check = ($destaque == '1') ? 'CHECKED' : '' ;
  
    if( !$edit and $edit->nun_rows <= 0 )
    {
        echo '<div class="alert alert-danger alert-dismissible" role="alert">
   <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><i class="fa fa-hand-paper-o fa-2x" aria-hidden="true"></i> <strong>ERROR!</strong> Falha ao buscar o registro selecionado.</div>' ;
    }
  
}

?>
<SECTION CLASS="content-header border-bottom">
  <H1 CLASS="text-danger"><STRONG>Editar Notícia</STRONG></H1>
  <OL CLASS="breadcrumb">
    <LI><a href="admin.php"><I CLASS="fa fa-home" ARIA-HIDDEN="true"></I></a></LI>
    <LI><a href="admin.php?page=noticias"><I CLASS="fa fa-newspaper-o" ARIA-HIDDEN="true"></I> Notícias</a></LI>
    <LI><a href="admin.php?page=aditar_noticia"><I CLASS="fa fa-pencil" ARIA-HIDDEN="true"></I> Notícia Edição</a></LI>
  </OL>
</SECTION>

<DIV CLASS="space30"></DIV>

<form name="form-noticia"   id="form-noticia" action="/app/modules/news/update_news.php" DATA-POST="noticia" role="form" DATA-TOGGLE="validator">

<input type="HIDDEN" form="form-noticia" name="noticia-id" value="<?php echo (isset($id)) ? $id : '' ; ?>">

<SECTION CLASS="row">
  <DIV CLASS="container">
  
  <DIV CLASS="callout callout-info">
    <H4><I CLASS="icon fa fa-info"></I> Importante!</H4>
    <P>Para saber mais sobre cada ferramenta do nosso sistema, criamos uma página de suporte com tutoriais e video aulas!</P>
    <a href="admin.php?page=suporte">Ir para a página de suporte</a>
  </DIV>

    <DIV CLASS="col-lg-6 col-md-6 col-sm-6 col-xs-12">
    
      <DIV CLASS="box box-primary">
        <DIV CLASS="box-body">
          <P>Faça o upload das imagens usadas na postagem.</P>
          <DIV ID="mulitplefileuploader" CLASS="div-upload" DATA-URL="/app/modulos/noticias/upload_images.php"></DIV>          
          <DIV ID="status"></DIV>
        </DIV>
      </DIV><!--//.box-->
      
      <DIV CLASS="box box-primary">       
        <DIV CLASS="box-body">
          <P>Configurar a postagem de acordo com sua necessidade!</P>
          <P CLASS="text-danger">Para melhor experiência de navegação do usuário, todos os campos são obrigatórios! </P>
          <BR>
          <DIV CLASS="form-group">
            <LABEL FOR="noticia-titulo"><SPAN CLASS="required">*</SPAN> Título da postagem </LABEL>
            <input type="text" form="form-noticia" name="noticia-titulo" class="form-control" id="noticia-titulo" maxlength="250" REQUIRED value="<?php  echo (isset($titulo)) ? $titulo : '' ; ?>">
          </DIV>
          
          <DIV CLASS="form-group">
            <LABEL FOR="noticia-subtitulo"><SPAN CLASS="required">*</SPAN> Subtítulo da postagem</LABEL>
            <TEXTAREA CLASS="form-control" NAME="noticia-subtitulo" FORM="form-noticia" ID="noticia-subtitulo" PLACEHOLDER="" STYLE="resize:none;" REQUIRED><?php echo (isset($subtitulo)) ? $subtitulo : '' ;  ?></TEXTAREA>
          </DIV>
          
          <DIV CLASS="form-group">
            <LABEL FOR="noticia-capa">Imagem de capa <I CLASS="fa fa-question-circle-o pull-right color-verde" ARIA-HIDDEN="true" ROLE="button" DATA-PLACEMENT="auto" DATA-TOGGLE="popover" DATA-TRIGGER="focus" TITLE="Ajuda" DATA-CONTENT="Copie o endereço da imagem no repositório de imagens. "></I></LABEL>
            <input type="url" form="form-noticia" name="noticia-capa" class="form-control" id="noticia-capa" placeholder="Exemplo: http://meusite.com.br/docs/images/noticias/1234.jpg" value="<?php echo (isset($capa)) ? $capa : '' ;  ?>">
          </DIV>
          
          <DIV CLASS="form-group">
            <LABEL FOR="noticia-categoria"><SPAN CLASS="required">*</SPAN> Categoria da postagem</LABEL>
            <SELECT CLASS="form-control" FORM="form-noticia" NAME="noticia-categoria" ID="noticia-categoria" REQUIRED>
              <OPTION VALUE="<?php echo (isset($categoria)) ? $categoria : '' ; ?>">Não alterar(<?php echo (isset($categoria)) ? $categoria : '' ; ?>)</OPTION>
              <OPTGROUP LABEL="Categorias principais">
              <OPTION VALUE="arte">Arte</OPTION>
              <OPTION VALUE="astronomia">Astronomia</OPTION>
              <OPTION VALUE="casa">Casa</OPTION>
              <OPTION VALUE="carreiras">Carreiras</OPTION>
              <OPTION VALUE="cidades">Cidades</OPTION>
              <OPTION VALUE="ciencia">Ciência</OPTION>
              <OPTION VALUE="cinema">Cinema</OPTION>
              <OPTION VALUE="carros">Carros</OPTION>
              <OPTION VALUE="cultura">Cultura</OPTION>
              <OPTION VALUE="casa">Casa</OPTION>
              <OPTION VALUE="cidades">Cidades</OPTION>
              <OPTION VALUE="ciencia">Ciência</OPTION>
              <OPTION VALUE="carreiras">Carreiras</OPTION>
              <OPTION VALUE="carros">Carros</OPTION>
              <OPTION VALUE="economia">Economia</OPTION>
              <OPTION VALUE="educacao">Educação</OPTION>
              <OPTION VALUE="esportes">Esportes</OPTION>
              <OPTION VALUE="famosos">Famosos</OPTION>
              <OPTION VALUE="gastronomia">Gastronomia</OPTION>
              <OPTION VALUE="justica">Justiça</OPTION>
              <OPTION VALUE="politica">Política</OPTION>
              <OPTION VALUE="religiao">Religião</OPTION>
              <OPTION VALUE="saude">Saúde</OPTION>
              <OPTION VALUE="sociedade">Sociedade</OPTION>
              <OPTION VALUE="moda">Moda</OPTION>
              <OPTION VALUE="mundo">Mundo</OPTION>
              <OPTION VALUE="natureza">Natureza</OPTION>
              <OPTION VALUE="policial">Policial</OPTION>
              </OPTGROUP>
              <OPTGROUP LABEL="Categorias secundárias">
                <OPTION VALUE="musica">Mundo Musical</OPTION>
                <OPTION VALUE="variedades">Variedades</OPTION>
                <OPTION VALUE="tecnologia">Técnologia</OPTION>
                <OPTION VALUE="famosos">Famosos</OPTION>
                <OPTION VALUE="empregos">Empregos</OPTION>
                <OPTION VALUE="falecimento">Notas de Falecimento</OPTION>
                <OPTION VALUE="loteria">Loteria</OPTION>
              </OPTGROUP>
            </SELECT>
          </DIV>
          
          <DIV CLASS="form-group">
            <LABEL FOR="noticia-subcategoria"><SPAN CLASS="required">*</SPAN> Subcategoria da postagem <I CLASS="fa fa-question-circle-o pull-right color-verde" ARIA-HIDDEN="true" ROLE="button" DATA-PLACEMENT="auto" DATA-TOGGLE="popover" DATA-TRIGGER="focus" TITLE="Ajuda" DATA-CONTENT="Por exemplo: Categoria: Economia > Subcategoria: Dólar "></I></LABEL>
            <input type="text" name="noticia-subcategoria" form="form-noticia" class="form-control" id="noticia-subcategoria" REQUIRED value="<?php echo (isset($subcategoria)) ? $subcategoria : '' ; ?>">
          </DIV>
          
        </DIV>
      </DIV><!--//.box-->
      
      
    </DIV><!--//.col-left-->
    
    <DIV CLASS="col-lg-6 col-md-6 col-sm-6 col-xs-12">
    
      <DIV CLASS="box box-success">
        <DIV CLASS="box-header ">
          <I CLASS="fa fa-archive" ARIA-HIDDEN="true"></I> <STRONG>Repositório</STRONG>
          <I CLASS="fa fa-question-circle-o pull-right balao color-verde" ARIA-HIDDEN="true" ROLE="button" DATA-PLACEMENT="left" DATA-TOGGLE="popover" DATA-TRIGGER="focus" TITLE="Ajuda" DATA-CONTENT="Este é o repositório onde ficam arquivadas as imagens usadas nas postagens!"></I>
        </DIV>
        <DIV CLASS="box-body">
          <P>Use esta ferramenta para adicionar imagens a sua postagem.</P>
          <DIV ID="load-images-repository" STYLE="height:400px; overflow-y:auto"></DIV>
        </DIV>
        <DIV CLASS="box-footer">
          <a class="btn    btn-primary btn  btn-flat" id="load-repository"><I CLASS="fa fa-plus-circle fa-fw" ID="load-images"></I> Carregar mais imagens</a>
          <a class="btn    btn-success" id="refresh-repository"><I CLASS="fa fa-refresh fa-fw"></I> Atualizar</a>
          
        </DIV>
      </DIV><!--//.box-->
      
      <DIV CLASS="box box-primary">
        <DIV CLASS="box-body">
          <P>Configurações alternativas para a postagem.</P>
          <DIV CLASS="checkbox">
            <LABEL>
              <input type="checkbox" name="noticia-destaque" form="form-noticia" value="<?php echo (isset($destaque)) ? $destaque : '' ; ?>" <?php echo (isset($destaque_check)) ? $destaque_check : '' ; ?>>
              Ver esta postagem por primeiro so meu site. <I CLASS="fa fa-question-circle-o pull-right color-verde" ARIA-HIDDEN="true" ROLE="button" DATA-PLACEMENT="auto" DATA-TOGGLE="popover" DATA-TRIGGER="focus" TITLE="Ajuda" DATA-CONTENT="Otimiza a postagem para aparecer primeiro nas páginas do site."></I>
            </LABEL>
          </DIV>    
          <DIV CLASS="checkbox">
            <LABEL>
              <input type="checkbox" form="form-noticia" name="noticia-facebook" value="<?php echo (isset($facebook)) ? $facebook : '' ; ?>" <?php echo (isset($facebook_check)) ? $facebook_check : '' ; ?>>
              Ativar comentários do Facebook na postagem? <I CLASS="fa fa-question-circle-o pull-right color-verde" ARIA-HIDDEN="true" ROLE="button" DATA-PLACEMENT="auto" DATA-TOGGLE="popover" DATA-TRIGGER="focus" TITLE="Ajuda" DATA-CONTENT="Ativa os botões de compartilhamento, curtir e a opção de comentários(padrão)."></I>
            </LABEL>
          </DIV>
          
          <DIV CLASS="checkbox">
            <LABEL>
              <input type="checkbox" form="form-noticia" name="noticia-hidden" value="<?php echo (isset($ativa)) ? $ativa : '' ; ?>" <?php echo (isset($ativa)) ? $ativa : '' ; ?>>
              Arquivar esta postagem mas não publicá-la no site <I CLASS="fa fa-question-circle-o pull-right color-verde" ARIA-HIDDEN="true" ROLE="button" DATA-PLACEMENT="auto" DATA-TOGGLE="popover" DATA-TRIGGER="focus" TITLE="Ajuda" DATA-CONTENT="Armazena a postagem mais não mostra no site(pode ser alterado futuramente!)"></I>
            </LABEL>
          </DIV>
          
          <P><STRONG>Utilize esta ferramenta para otimizar as buscas dos mecanismos de pesquisa.</STRONG><BR>
            Escreva até 20 palavras simples relacionadas ao conteúdo da postagem e separadas por vírgula.
          </P>
          
          <DIV CLASS="form-group">
            <LABEL FOR="noticias-tags"><I CLASS="fa fa-tags" ARIA-HIDDEN="true"></I> Palavras chaves</LABEL>
            <TEXTAREA CLASS="form-control" NAME="noticia-tags" FORM="form-noticia" ID="noticias-tags" STYLE="resize:none;"><?php echo (isset($tags)) ? $tags : '' ; ?></TEXTAREA>
          </DIV>
          
        </DIV>
      </DIV><!--//.x-->

        
    </DIV><!--//.col-right-->
  
  </DIV>
</SECTION>

<SECTION CLASS="row">
  <DIV CLASS="container">
    
    <DIV CLASS="box box-primary">
        <DIV CLASS="box-body">
          <P><SPAN CLASS="required">*</SPAN> Monte a conteúdo da postagem usando as ferramentas abaixo.</P>
          <BR>
          <TEXTAREA NAME="noticia-text" CLASS="tiny" FORM="form-noticia" ID="noticias-texto" STYLE="resize:none;"><?php echo (isset($text_html)) ? $text_html : '' ; ?></TEXTAREA>
        </DIV>
        <DIV CLASS="box-footer">
          <P>Tudo pronto? Agora é só fazer o post clicando no botão abaixo!</P>
          
            <button type="submit" <?php echo $form_validate; ?> class="btn    btn-primary btn  btn-flat" id="noticias-post" ><I CLASS="fa fa-check" ARIA-HIDDEN="true"></I>
 Publicar</button>
          
        </DIV>
    </DIV>
    
  </DIV>
</SECTION> 

</form>

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
