<?php
use config\connect_db;

if( ($_SESSION['user_master_perms'] !== 'administrador') )
{
    if($_SESSION['vehicles_read'] !== '1')
    {
        die ('<script>location.href="/app/admin.php?page=access_denied";</script>');
    }
}
?>
<SECTION CLASS="row">
    <div class="container">
        <div class="col-md-12">
            <H4 CLASS="text-darkgray"><STRONG>Estatísticas</STRONG></H4>
            <OL CLASS="breadcrumb bg-white">
                <LI><a href="admin.php"><I CLASS="fa fa-home" ARIA-HIDDEN="true"></I></a></LI>
                <LI><a href="admin.php?page=vehicles"><I CLASS="fa fa-car"></I> Veículos</a></LI>
                <LI><a href="admin.php?page=statistics_vehicles"><I CLASS="fa fa-pie-chart"></I> Estatísticas</a></LI>
            </OL>
        </div>
    </div>
</SECTION>

<DIV CLASS="clearfix"></DIV>

<SECTION CLASS="row">
  <DIV CLASS="container">
  
      <div class="col-md-4 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-green"><i class="fa fa-car"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Total de carros</span>
              <span class="info-box-number"><?php echo countRegTables('cars'); ?></span>
              <span class="info-box-number">R$ <?php echo decimalMoeda(sumMoneyTable('cars')); ?></span>
            </div>
          </div>
      </div>
      
      <div class="col-md-4 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-aqua"><i class="fa fa-motorcycle"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Total de motos</span>
              <span class="info-box-number"><?php echo countRegTables('motorcycles'); ?></span>
              <span class="info-box-number">R$ <?php echo decimalMoeda(sumMoneyTable('motorcycles')); ?></span>
            </div>
          </div>
      </div>
      
      <div class="col-md-4 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-red"><i class="fa fa-eye"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Carros</span>
              <span class="info-box-number"><?php echo countViews('cars') + countViews('motorcycles'); ?></span>
            </div>
          </div>
      </div>
  
    
    </DIV>  
</SECTION>

<script src="/plugins/bootstrap-validator-master/dist/validator.min.js"></script>
<script src="/app/javascript/vehicle.js"></script>