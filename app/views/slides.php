<?php
use config\connect_db;

if( ($_SESSION['user_master_perms'] !== 'administrador') )
{
    if( $_SESSION['slide_read'] !== '1' )
    {
        die ('<script>location.href="/app/admin.php?page=access_denied";</script>');
    }
}
?>
<link href="/plugins/lightbox/css/lightbox.css" rel="stylesheet" />
<SECTION CLASS="row">
    <div class="container">
        <div class="col-md-12">
            <H4><strong>Slideshows</STRONG></H4>
            <OL CLASS="breadcrumb bg-white">
                <LI><a href="admin.php"><I CLASS="fa fa-home"></I></a></LI>
                <LI><a href="admin.php?page=slides"><I CLASS="fa fa-desktop"></I> Slides</a></LI>
            </OL>
        </div>
    </div>
</SECTION>

<DIV CLASS="space30"></DIV>


<SECTION CLASS="row">
    <DIV CLASS="container">
        
            <div class="col-md-6 col-sm-6 col-xs-12">
                <form name="form-slides" method="POST" action="/app/modules/slides/insert_slide_image.php" id="form-slides" role="form" DATA-TOGGLE="validator" enctype="multipart/form-data" data-form-reset="reset" data-reload="true">
  
                <input type="HIDDEN" name="user_id" value="<?php echo $_SESSION['user_id'] ?>">
                <input type="HIDDEN" name="form-token" value="<?php echo$_SESSION['secret_form_token '] ?>">
  
                <DIV CLASS="box box-primary">  
                    <DIV CLASS="box-header">
                        <STRONG>Fazer o upload de imagens</STRONG>
                    </DIV>        
                    <DIV CLASS="box-body">
                    
                        <DIV CLASS="form-group has-feedback">
                            <LABEL for="slide-identificacao"><SPAN CLASS="required">*</SPAN> Identificação do banner</LABEL>
                            <DIV CLASS="msg-validation">
                              <input type="text" name="slide-identificacao" class="form-control" id="slide-identificacao" required>
                            </DIV>
                            <SPAN CLASS="fa form-control-feedback" ARIA-HIDDEN="true"></SPAN>
                            <DIV CLASS="help-block with-errors"></DIV> 
                        </DIV>
                        
                        <DIV CLASS="form-group has-feedback">
                            <LABEL for="slide-link">Link</LABEL>
                            <img src="/app/images/icons/pupover-ico.png" CLASS="pull-right ico-help" ARIA-HIDDEN="true"  DATA-PLACEMENT="left" DATA-TOGGLE="popover" DATA-CONTAINER="body" DATA-HTML="true" DATA-TRIGGER="focus" TITLE="<strong>Ajuda</strong>" DATA-CONTENT="Endereço URL para onde o usuário será redirecionado ao clicar no banner.">
                            <DIV CLASS="msg-validation">
                              <input type="url" name="slide-link" class="form-control">
                            </DIV>
                            <SPAN CLASS="fa form-control-feedback"></SPAN>
                            <DIV CLASS="help-block with-errors"></DIV> 
                        </DIV>
                        
                        <DIV CLASS="form-group has-feedback">
                            <LABEL>Texto</LABEL>
                            <DIV CLASS="msg-validation">
                              <TEXTAREA CLASS="form-control count-caractere" DATA-MAX-CARACTERE="1024" NAME="slide-texto" ID="slide-texto" STYLE="resize:none; min-height:100px !important"></TEXTAREA>
                            </DIV>
                            <SPAN CLASS="glyphicon form-control-feedback fa" ARIA-HIDDEN="true"></SPAN>
                            <DIV CLASS="help-block with-errors"></DIV> 
                            <DIV CLASS="restante-caractere">1024 caracteres permitidos</DIV>
                        </DIV>
                        
                        <label for="slide-image">
                            <a class="btn btn-primary btn-flat"><i class="fa fa-upload"></i> Selecionar imagem</a>
                            <input type="file" name="images" id="slide-image" data-control="input-file" class="hidden" required>
                            <p id="image-loaded"></p>
                        </label>
                             
                        <div class="progress progress-md active hidden" id="div-progress">
                            <div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" id="bar-progress" style="width: 0%">
                              <span id="status-progress"></span>
                            </div>
                        </div>
                        
                    </DIV>
                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary btn-flat">Publicar</button>
                    </div>
                </DIV><!--//.box-->
                
                </form>
            </div><!--//.col-left -->
            
            <div class="col-md-6 col-sm-6 col-xs-12">
            
                <DIV CLASS="box box-default">  
                    <DIV CLASS="box-header">
                        <STRONG>Ajuda</STRONG>
                    </DIV>        
                    <DIV CLASS="box-body">
                        <p>Nosso sistema gera de maneira automática, 1 imagem para cada resolução de telas mais usadas atualmente.<br> O ideal, é que na hora de produzir uma imagem para o slide, você se baseie na maior resolução listada abaixo.</p>
                        <p>Configuração dos slides:</p>
                        <dl class="dl-horizontal">
                            <dt><i class="fa fa-television"></i> <?php echo $_SESSION['config']['slide-lg'] ?> ou menor:</dt>
                            <dd>Dispositivos grandes: Televisores, telas em alta definição, etc.</dd>
                            <dt><i class="fa fa-desktop"></i> <?php echo $_SESSION['config']['slide-md'] ?> ou menor:</dt>
                            <dd>Dispositivos médios: Desktops, notebooks, etc.</dd>
                            <dt><i class="fa fa-tablet"></i> <?php echo $_SESSION['config']['slide-sm'] ?> ou menor:</dt>
                            <dd>Tablets, tablets em modo paisagem e alguns celulares em modo paisagem.</dd>
                            <dt><i class="fa fa-mobile"></i> <?php echo $_SESSION['config']['slide-xs'] ?> ou menor:</dt>
                            <dd>Celulares em geral.</dd>
                        </dl>
                        <p><strong>Qualidade das imagens:</strong> <?php echo $_SESSION['config']['slide_image_quality'] ?>% da original</p>
                    </DIV>
                    <div class="box-footer">
                        <a href="#" class="pull-right">Alterar configurações</a>
                    </div>
                </DIV>
                
            </div><!--//.col-right-->
            
    </DIV>
</SECTION>

<SECTION CLASS="row">
  <DIV CLASS="container">
        <div class="col-md-12">
            <div class="callout callout-info">
                <h4><i class="fa fa-info"></i> Dica</h4>
                <p>Arraste & Solte</p>
                <p>Altere a ordem de exibição dos slides mantendo o ponteiro do mouse clicado sobre os itens abaixo. O recurso de arrastar e soltar será ativado e você poderá move-lô para a posição que escolher.</p>
                <p><strong>Nota:</strong> Necessita da permissão de edição.</p>
            </div>
        </div>
    </DIV>
</SECTION>
    
<SECTION CLASS="row">
  <DIV CLASS="container">
    
        <DIV CLASS="clearfix "></DIV>
        
        <DIV ID="reload-slides" data-control="data-reload">

            <?php
            
            $con_db = new config\connect_db();
            $con = $con_db->connect();
            
            $slides = $con->query(" SELECT * FROM `slides` ORDER BY `ordered` ASC ");
            $rows = $slides->num_rows;
            
            $item = '<ul id="sortable" class="list-unstyled">';
            
            while( $reg = $slides->fetch_assoc() )
            {
                $item .= '<li class="col-md-4 col-sm-6 col-xs-12" id="id_'.$reg['id'].'">';
                $item .= '    <DIV CLASS="box box-primary"> ';      
                $item .= '      <DIV CLASS="box-body no-padding"> ';
                $item .= '          <DIV class="container-imagem-slide bg-cover" style="background-image:url(/plugins/EagerImageLoader/l.gif);" data-background="/docs/slides/xs/'.$reg['image'].'">';
                $item .= '          </DIV>';
                $item .= '      </DIV>';
                $item .= '      <div class="box-footer">';
                $item .= '          <span title="'.$reg['name'].'">'.substr_replace($reg['name'], (strlen($reg['name']) > 30 ? '...' : ''), 30).'</span>';
                $item .= '          <i class="fa fa-trash text-red btn-anime-1 pull-right" data-control="delete-slides" data-id="'.$reg['id'].'" title="Excluir este slide?"></i>';
                $item .= '      </div>';
                $item .= '    </DIV><!--//.box-->';
                $item .= '</li>';
            }
            
            $item .= '</ul>';
            
            if($slides and $rows  > 0)
            {
              echo $item;
            }
            else if($rows  <= 0)
            {
              echo '<div class="col-md-12">',
              '<div class="alert alert-warning">',
              '<h4><i class="fa fa-exclamation-triangle"></i> Nenhum slide adicionado!</h4>',
              'Um website fica muito mais atrativo e dinâmico, quando possui um Slideshow bem montado e com belas imagens.',
              '</div></div>';
            }
            else if(!$slides)
            {
              echo ($con_db->serverFailure());
            }
                
           ?>
        
        </DIV><!--reload imagens-->
          
        <DIV CLASS="space30"></DIV>
       

    </DIV>
</SECTION>

<script src="/plugins/lightbox/js/lightbox.min.js"></script>
<script src="/plugins/bootstrap-validator-master/dist/validator.min.js"></script>
<script src="/app/javascript/slides.js"></script>

<?php
if( ($_SESSION['user_master_perms'] === 'administrador') )
{
    echo '<script>$(document).ready(function() {sortableSlides();});</script>';
}
else
{
    if( $_SESSION['slide_read'] === '1' )
    {
        echo '<script>$(document).ready(function() {sortableSlides();});</script>';
    }
}
?>
