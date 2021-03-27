<?php
use config\connect_db;
use app\controls\errors;
use app\controls\perms;

require_once ($_SERVER['DOCUMENT_ROOT'].'/config/public_functions.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/app/controls/adminFunctions.php');

$errors = new errors();
$user_perms   = new perms();

if( ($_SESSION['user_master_perms'] !== 'administrador') )
{
    if( $_SESSION['news_read'] !== '1' )
    {
        die ('<script>location.href="/app/admin.php?page=access_denied";</script>');
    }
}
?>

<SECTION CLASS="content-header border-bottom">
    <H1 CLASS="text-danger"><STRONG>Notícias</STRONG></H1>
    <OL CLASS="breadcrumb">
        <LI><a href="admin.php?page=home"><I CLASS="fa fa-home" ARIA-HIDDEN="true"></I></a></LI>
        <LI><a href="admin.php?page=noticias"><I CLASS="fa fa-newspaper-o" ARIA-HIDDEN="true"></I> Notícias</a></LI>
    </OL>
</SECTION>
<DIV CLASS="space30"></DIV>
<SECTION CLASS="row">
    <DIV CLASS="container">
        <NAV CLASS="navbar">
            <DIV CLASS="container-fluid"> 
                <!-- Brand and toggle get grouped for better mobile display -->
                <DIV CLASS="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" DATA-TOGGLE="collapse" DATA-TARGET="#bs-example-navbar-collapse-1" aria-expanded="false"> <SPAN CLASS="sr-only">Toggle navigation</SPAN> <SPAN CLASS="icon-bar"></SPAN> <SPAN CLASS="icon-bar"></SPAN> <SPAN CLASS="icon-bar"></SPAN> </button>
                </DIV>
                
                <!-- Collect the nav links, forms, and other content for toggling -->
                <DIV CLASS="collapse navbar-collapse" ID="bs-example-navbar-collapse-1">
                    <UL CLASS="nav navbar-nav">
                        <LI CLASS="dropdown"> <a href="#" class="dropdown-toggle" DATA-TOGGLE="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Ordernar Por: <SPAN CLASS="caret"></SPAN></a>
                            <UL CLASS="dropdown-menu">
                                <LI><a href="?page=noticias&order=date-desc"><I CLASS="fa fa-sort-numeric-desc" ARIA-HIDDEN="true"></I> Data mais recente</a></LI>
                                <LI><a href="?page=noticias&order=date-asc"><I CLASS="fa fa-sort-numeric-asc" ARIA-HIDDEN="true"></I> Data mais antiga</a></LI>
                                <LI><a href="?page=noticias&order=views-desc"><I CLASS="fa fa-sort-amount-desc" ARIA-HIDDEN="true"></I> Mais acessadas</a></LI>
                                <LI><a href="?page=noticias&order=views-asc"><I CLASS="fa fa-sort-amount-asc" ARIA-HIDDEN="true"></I> Menos acessadas</a></LI>
                                <LI><a href="?page=noticias&order=favorite"><I CLASS="fa fa-star" ARIA-HIDDEN="true"></I> Marcadas como favoritas</a></LI>
                                <LI><a href="?page=noticias&order=clear"><I CLASS="fa fa-bars" ARIA-HIDDEN="true"></I> Padrão</a></LI>
                            </UL>
                        </LI>
                        <LI CLASS="dropdown"> <a href="#" class="dropdown-toggle" DATA-TOGGLE="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Número de Registros: <SPAN CLASS="caret"></SPAN></a>
                            <UL CLASS="dropdown-menu">
                                <LI><a href="?page=noticias&limite=25" title="Valor Recomendado!">25</a></LI>
                                <LI><a href="?page=noticias&limite=50">50</a></LI>
                                <LI><a href="?page=noticias&limite=100">100</a></LI>
                                <LI><a href="?page=noticias&limite=250">250</a></LI>
                                <LI><a href="?page=noticias&limite=all" title="O sistema pode ficar lento!">Todas</a></LI>
                            </UL>
                        </LI>
                    </UL>
                    <form class="navbar-form navbar-right" role="search">
                        <DIV CLASS="form-group">
                            <input type="text" class="form-control" id="filter-table" DATA-TABLE="table-noticias" placeholder="Buscar registros" style="width:400px">
                        </DIV>
                    </form>
                </DIV>
                <!-- /.navbar-collapse --> 
            </DIV>
            <!-- /.container-fluid --> 
        </NAV>
        <DIV>
            <TABLE CLASS="table table-striped table-responsive" ID="table-noticias">
                <THEAD>
                    <TR CLASS="bg-primary">
                        <TH STYLE="width:5% !important;"><input type="checkbox" name="del-all" id="del-all"></TH>
                        <TH STYLE="width:10% !important;">ID</TH>
                        <TH STYLE="width:13% !important;">DATA POST</TH>
                        <TH STYLE="width:50% !important;">TÍTULO</TH>
                        <TH STYLE="width:2% !important;"><I CLASS="fa fa-star" ARIA-HIDDEN="true" TITLE="Favoritar"></I></TH>
                        <TH STYLE="width:10% !important;">VIEWS</TH>
                        <TH STYLE="width:10% !important;">AÇÕES</TH>
                    </TR>
                </THEAD>
                <?php
    
        $con_db = new config\connect_db();
        $con = $con_db->connect();
        
        $pag = ( empty($_GET['pag']) or !isset($_GET['pag'])  ) ? 0 : $_GET['pag'] ; 
        
        if(empty($_GET['order']) == true or !isset($_GET['order']))
        {
          $order = (!isset($_SESSION['order-noticias']) ) ? "ORDER BY `id` DESC " : $_SESSION['order-noticias'] ;
        }
        else
        {
           if($_GET['order'] == 'date-asc') { $order = "ORDER BY `date_post` ASC" ; }
           else if($_GET['order'] == 'date-desc') { $order = "ORDER BY `date_post` DESC" ; }
           else if($_GET['order'] == 'views-desc') { $order = "ORDER BY `views` DESC" ; } 
           else if($_GET['order'] == 'views-asc') { $order = "ORDER BY `views` ASC" ; } 
           else if($_GET['order'] == 'favorite') { $order = "ORDER BY `favorite` DESC" ; } 
           else if($_GET['order'] == 'clear') { $order = "ORDER BY `id` DESC" ; } 
           else { $order = "ORDER BY `id` DESC" ; } 
           $_SESSION['order-noticias'] = $order;
        }
        
        if(empty($_GET['limite']) == true or !isset($_GET['limite']))
        {
          $limite = (!isset($_SESSION['limite-noticia']) ) ? '25' : $_SESSION['limite-noticia'] ;
        }
        else if ($_GET['limite'] == 'all')
        {
          $inicio = 0;
          $limite = 99999;
        }
        else 
        {
          $limite = filter_var($_GET['limite'], FILTER_SANITIZE_NUMBER_INT);
          $_SESSION['limite-noticia'] = $limite;
        }
           
        if ($pag and $pag != '')
        {
          $inicio = ($pag - 1) * $limite;
        }
        else
        {
          $inicio = 0;
        }
            
        $noticias = $con->query("SELECT * FROM `noticias`  $order LIMIT $inicio,$limite");
        $total_reg = $noticias->num_rows;
        
        $print_noticias = '<TBODY>'; 
        
        while($reg = $noticias->fetch_array())
        {
            $favorite = ($reg['favorite'] == '1') ? 'favorite' : '' ;
            $strong = ($reg['favorite'] == '1') ? 'class="strong"' : '' ;
          
            $print_noticias .= '<TR '.$strong.'>';
            $print_noticias .= '  <TD><input type="checkbox" name="del[]" value="'.$reg['id'].'" class="del" form="form-noticias"></TD>';
            $print_noticias .= '  <TD>'.$reg['id'].'</TD> ';
            $print_noticias .= '  <TD>'.inverteData(substr($reg['date_post'], 0, 10)).'</TD>'; 
            $print_noticias .= '  <TD>'.$reg['titulo'].'</TD>';
            $print_noticias .= '  <TD><I CLASS="fa fa-star star-marked '.$favorite.'" ARIA-HIDDEN="true" TITLE="Favoritar" DATA-STAR-ID="'.$reg['id'].'"></I></TD>';
            $print_noticias .= '  <TD>'.$reg['views'].'</TD>'; 
            $print_noticias .= '  <TD><a href="?page=editar_noticia&id='.base64_encode($reg['id']).'" title="Editar"><I CLASS="fa fa-edit color-verde"></I></a><I CLASS="fa fa-trash-o color-red delete" data-id="'.$reg['id'].'" data-table="noticias"></I></TD>'; 
            $print_noticias .= '</TR>'; 
        }
        
        $print_noticias .= '</TBODY>';
        
        if( $noticias and $total_reg > 0 )
        {
            echo $print_noticias;
        }
        else if( $total_reg <= 0 )
        {
            echo '<div class="alert alert-danger" role="alert">Não encontramos nenhum registro!</strong>';
        }
        else if( !$noticias )
        {
            echo '<div class="alert alert-danger" role="alert">O servidor não está respondendo!</div>';
        }
        
        // paginação----------
        $busca_total = $con->query("SELECT COUNT(*) as `id` FROM `noticias`  ");
        $total = $busca_total->fetch_array();
        $total = $total['id'];

        $prox = $pag + 1;
        $ant = $pag - 1;
        $ultima_pag = ceil($total / $limite);
        $penultima = $ultima_pag - 1;  
        @$adjacentes = 2;
             
        if( $pag>1 )
        {
            $paginacao = '<li><a href="?page=noticias&pag='.$ant.'"><i class="fa fa-arrow-left"></i></a>';
        }
          
        if( $ultima_pag <= 5 )
        {
            for ($i=1; $i< $ultima_pag+1; $i++)
            {
                if( $i == $pag )
                {
                    @$paginacao .= '<li><a class="atual" href="?page=noticias&pag='.$i.'">'.$i.'</a>';        
                }
                else 
                {
                    @$paginacao .= '<li><a href="?page=noticias&pag='.$i.'">'.$i.'</a>';  
                }
            }
        }
        
        if( $ultima_pag > 5 )
        {
            if( $pag < 1 + (2 * $adjacentes) )
            {
                for ($i=1; $i< 2 + (2 * $adjacentes); $i++)
                {
                    if( $i == $pag )
                    {
                        @$paginacao .= '<li><a class="atual" href="?page=noticias&pag='.$i.'">'.$i.'</a>';        
                    } 
                    else 
                    {
                        @$paginacao .= '<li><a href="?page=noticias&pag='.$i.'">'.$i.'</a>';  
                    }
                }
                $paginacao .= '<li><a href="javascript:;">...</a></li>';
                $paginacao .= '<li><a href="?page=noticias&pag='.$penultima.'">'.$penultima.'</a></li>';
                $paginacao .= '<li><a href="?page=noticias&pag='.$ultima_pag.'">'.$ultima_pag.'</a></li>';
            }
          
            elseif( $pag > (2 * $adjacentes) && $pag < $ultima_pag - 3 )
            {
                $paginacao .= '<li><a href="?page=noticias&pag=1">1</a></li>';        
                $paginacao .= '<li><a href="javascript:;">...</a></li>';
                for ($i = $pag-$adjacentes; $i<= $pag + $adjacentes; $i++)
                {
                    if ($i == $pag)
                    {
                        $paginacao .= '<li><a class="atual" href="?page=noticias&pag='.$i.'">'.$i.'</a></li>';        
                    } 
                    else 
                    {
                        $paginacao .= '<li><a href="?page=noticias&pag='.$i.'">'.$i.'</a></li>';  
                    }
                }
                $paginacao .= '<li><a href="javascript:;">...</a></li>';
                $paginacao .= '<li><a href="?page=noticias&pag='.$penultima.'">'.$penultima.'</a></li>';
                $paginacao .= '<li><a href="?page=noticias&pag='.$ultima_pag.'">'.$ultima_pag.'</a></li>';
          }
          else 
          {
              $paginacao .= '<li><a href="?page=noticias&pag=1">1</a></li>';        
              $paginacao .= '<li><a href="?page=noticias&pag=1">2</a></li>';  
              for ($i = $ultima_pag - (2 + (2 * $adjacentes)); $i <= $ultima_pag; $i++)
              {
                  if ($i == $pag)
                  {
                      $paginacao .= '<li><a class="atual" href="?page=noticias&pag='.$i.'">'.$i.'</a></li>';        
                  } 
                  else 
                  {
                      $paginacao .= '<li><a href="?page=noticias&pag='.$i.'">'.$i.'</a></li>';  
                  }
              }
          }
        }
        
        if( $prox <= $ultima_pag && $ultima_pag > 2 )
        {
            $paginacao .= '<li><a href="?page=noticias&pag='.$prox.'"><i class="fa fa-arrow-right"></i></a></li>';
        }
        
        ?>
            </TABLE>
            <DIV CLASS="tfoot nav navbar-nav">
                <form action="/app/modules/news/delete_news.php" id="form-noticias" name="FORM-NOTICIAS" DATA-ACTION="delete-all">
                    <button class="btn btn-flat btn-danger navbar-left" id="submit-del-all" DISABLED type="submit"><I CLASS="fa fa-trash-o"></I> Excluir Ítens Selecionados</button>
                </form>
            </DIV>
        </DIV>
        <!--//.table-noticias-->
        
        <NAV>
            <UL CLASS="pagination">
                <?php echo @$paginacao  ?>
            </UL>
        </NAV>
        <!--//.pagination--> 
        
    </DIV>
</SECTION>