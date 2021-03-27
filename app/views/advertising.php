<?php
use config\connect_db;

if( ($_SESSION['user_master_perms'] !== 'administrador') )
{
    if( $_SESSION['advertising_read'] !== '1' )
    {
        die ('<script>location.href="/app/admin.php?page=access_denied";</script>');
    }
}

?>
<SECTION CLASS="row">
    <div class="container">
        <div class="col-md-12">
            <H4 CLASS="text-darkgray"><STRONG>Publicidade</STRONG></H4>
            <OL CLASS="breadcrumb bg-white">
                <LI><a href="admin.php?page=home"><I CLASS="fa fa-home" ARIA-HIDDEN="true"></I></a></LI>
    <LI><a href="admin.php?page=advertising"><I CLASS="fa fa-television" ARIA-HIDDEN="true"></I> Publicidade</a></LI>
            </OL>
        </div>
    </div>
</SECTION>

<DIV CLASS="space30"></DIV>

<SECTION CLASS="row">
  <DIV CLASS="container">
  
    <DIV CLASS="col-lg-4 col-md-4 col-sm-12 col-xs-12">
      
      <DIV CLASS="box box-primary">  
       <form name="form-publicidade" id="form-publicidade" method="POST" action="/app/modules/advertising/insert_advert.php" role="form"  enctype="multipart/form-data" data-post="FormData">      
        <DIV CLASS="box-body">
          
          <DIV CLASS="form-group">
            <LABEL FOR="publicidade-anunciante"><SPAN CLASS="required">*</SPAN> Anunciante</LABEL>
            <I CLASS="fa fa-question-circle pull-right color-verde" ARIA-HIDDEN="true" ROLE="button" DATA-PLACEMENT="auto" DATA-TOGGLE="popover" DATA-CONTAINER="body" DATA-HTML="true" DATA-TRIGGER="focus" TITLE="<strong>Ajuda</strong>" DATA-CONTENT="Nome da empresa ou anunciante para controle."></I>
            <DIV CLASS="msg-validation">
              <input type="text" class="form-control" id="publicidade-anunciante" name="publicidade-anunciante" REQUIRED>
              <DIV CLASS="help-block with-errors"></DIV>
            </div>
          </DIV>
                
          <DIV CLASS="form-group">
            <LABEL FOR="publicidade-link">Endereço do site ou facebook </LABEL>
            <I CLASS="fa fa-question-circle pull-right color-verde" ARIA-HIDDEN="true" ROLE="button" DATA-PLACEMENT="auto" DATA-TOGGLE="popover" DATA-CONTAINER="body" DATA-HTML="true" DATA-TRIGGER="focus" TITLE="Ajuda" DATA-CONTENT="Este é o endereço que será aberto quando o usuário clicar no anuncio! <br><strong>(Se não informado o endereço será seu próprio site!)<trong>"></I>
            <DIV CLASS="msg-validation">
              <input type="url" class="form-control" id="publicidade-link" name="publicidade-link">
              <DIV CLASS="help-block with-errors"></DIV>
            </div>
          </DIV>
          
          <DIV CLASS="form-group">
            <LABEL FOR="publicidade-local"><SPAN CLASS="required">*</SPAN> Local do anuncio no site</LABEL>
            <I CLASS="fa fa-question-circle pull-right color-verde" ARIA-HIDDEN="true" ROLE="button" DATA-PLACEMENT="auto" DATA-TOGGLE="popover" DATA-CONTAINER="body" DATA-HTML="true" DATA-TRIGGER="focus" TITLE="Ajuda" DATA-CONTENT="Os locais estão identificados em seu site com a tag <strong>PUBLICIDADE</strong> seguida do ID do banner.<br> Exemplo: PUBLICIDADE L1. "></I>
            <DIV CLASS="msg-validation">
              <SELECT CLASS="form-control" ID="publicidade-local" NAME="publicidade-local" REQUIRED>
                <OPTION VALUE="">Escolha um local</OPTION>
                <OPTION VALUE="l1">L1</OPTION>
                <OPTION VALUE="l2">L2</OPTION>
                <OPTION VALUE="l3">L3</OPTION>
                <OPTION VALUE="l4">L4</OPTION>
                <OPTION VALUE="l5">L5</OPTION>
                <OPTION VALUE="l6">L6</OPTION>
              </SELECT>
              <DIV CLASS="help-block with-errors"></DIV>
            </DIV>
          </DIV>
          
          <DIV CLASS="form-group">
            <LABEL FOR="publicidade-image"><SPAN CLASS="required">*</SPAN> Imagem do anuncio</LABEL>
              <DIV CLASS="msg-validation">
                <DIV CLASS="form-group">
                  <LABEL FOR="publicidade-image">
                    <a class="btn btn-primary btn-flat"><I CLASS="fa fa-upload" ARIA-HIDDEN="true"></I> Escolher Imagem</a>
                  </LABEL>
                  <input type="file" id="publicidade-image" name="publicidade-image" style="display:none" accept="image/jpg, image/jpeg, image/png, image/bmp, image/gif" class="input-preview">
                  <DIV CLASS="help-block with-errors"></DIV>
                </div>
            </DIV>
          </DIV>
          
          <p ID="images-ok"></p>
          <OUTPUT ID="previews-images"></OUTPUT>
          
          <DIV ID="resposta-publicidade"></DIV>
    
        </DIV><!--//.box-body-->
        
        <DIV CLASS="box-footer">
          <button type="submit" class="btn btn-danger btn-flat pull-left"> Publicar</button>  
        </DIV><!--//.box-footer -->
        
        </form>
      </DIV><!--//.box-->
      
      <DIV CLASS="callout callout-info">
        <H4>Importante!</H4>
        <P>Para criar ou alterar locais de publicidade em seu site, você deve entrar em contato com o suporte. <BR> Saiba mais na página de Ajuda & Suporte.</P>
        <a href="#"><STRONG>Ajuda e Suporte </STRONG><I CLASS="fa fa-arrow-right" ARIA-HIDDEN="true"></I>
</a>
      </DIV>
    
    </DIV> <!--//.col-->
    
    <DIV CLASS="col-lg-8 col-md-8 col-sm-12 col-xs-12">
    
      <TABLE CLASS="table table-striped" ID="table-publicidade"> 
        <THEAD> 
          <TR  CLASS="bg-primary"> 
            <TH STYLE="width:5% !important;">ID</TH> 
            <TH STYLE="width:15% !important;">DATA POST</TH>
            <TH STYLE="width:45% !important;">NOME DA EMPRESA</TH>
            <TH STYLE="width:20% !important;">LINK</TH> 
            <TH STYLE="width:15% !important;">VIEWS</TH> 
          </TR> 
        </THEAD>
        <?php 
          require_once ($_SERVER['DOCUMENT_ROOT'].'/config/public_functions.php');
    
          $con_db = new config\connect_db();
          $con = $con_db->connect();
        
          $publicidade = $con->query("SELECT * FROM `publicidade` ORDER BY `local` DESC ");
          $p = '<TBODY>';
        
          while( $reg = $publicidade->fetch_array() )
          {
              $p .= '<TR>';
              $p .= '  <TD>'.$reg['local'].'</TD> ';
              $p .= '  <TD>'.inverteData($reg['date']).'</TD> ';
              $p .= '  <TD>'.$reg['anunciante'].'</TD>';
              $p .= '  <TD>'.$reg['link'].'</TD> ';
              $p .= '  <TD>'.$reg['views'].'</TD> ';
              $p .= '</TR>';
          }
        
          $p .= '</TBODY> ';
        
          if( !$publicidade )
          {
              echo '<div class="alert alert-danger" role="alert">A conexão com o servidor falhou!</strong>';
          }
          else if( $publicidade and $publicidade->num_rows <= 0 )
          {
          echo '<div class="alert alert-danger" role="alert">Não encontramos nenhum registro!</strong>';
          }
          else if( $publicidade and $publicidade->num_rows > 0 )
          {
              echo $p;
          }
          else
          {
              echo '<div class="alert alert-warning" role="alert">Se você estiver vendo está mensagem, por favor informe o suporte!
          <br> suporte@grupositefacil.com.br</strong>';
          }

        ?>
      </TABLE>
      
       <NAV>
        <UL CLASS="pagination">
          <LI>
            <a href="#" aria-label="Previous">
              <SPAN ARIA-HIDDEN="true">&laquo;</SPAN>
            </a>
          </LI>
          <LI><a href="#">1</a></LI>
          <LI><a href="#">2</a></LI>
          <LI><a href="#">3</a></LI>
          <LI><a href="#">4</a></LI>
          <LI><a href="#">5</a></LI>
          <LI>
            <a href="#" aria-label="Next">
              <SPAN ARIA-HIDDEN="true">&raquo;</SPAN>
            </a>
          </LI>
        </UL>
      </NAV><!--//.pagination-->
    
    </DIV><!--//.col-->
     
  </DIV>  
</SECTION>

<script src="/app/javascript/generatePreviewsImages.js" DEFER ASYNC></script>
<script src="/plugins/bootstrap-validator-master/dist/validator.min.js"></script>
