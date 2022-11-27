<?php 

use yii\helpers\Html;

$this->registerJsFile(
    '@web/adminassets/vendor/raphael/raphael.min.js'
);
$this->registerJsFile(
    '@web/adminassets/vendor/tui-chart/tui-code-snippet.js'
);
$this->registerJsFile(
    '@web/adminassets/vendor/tui-chart/tui-chart.min.js'
);

$this->registerCssFile(
    '@web/adminassets/vendor/tui-chart/tui-chart.min.css'
);



?>
<!-- content -->

  <div class="page-content__header">
    <div>
      <h2 class="page-content__header-heading">Kontrol Merkezi</h2>
      <div class="page-content__header-description">AutoVM Kontrol Merkezine Hoşgeldin.</div>
    </div>
  </div>
  
  
<div class="row">
    <div class="col-lg-3">
      <div class="ecommerce-widget ecommerce-widget-a ecommerce-widget-a--info">
        <div class="ecommerce-widget-a__currency"><i class="fas fa-server"></i></div>
        <div class="ecommerce-widget-a__content">
          <div class="ecommerce-widget-a__title">Fiziksel Sunucu</div>
          <div class="ecommerce-widget-a__price"><?php echo $stats->totalServer;?></div>
		  <a class="btn btn-outline-info btn-block" href="<?php echo Yii::$app->urlManager->createUrl(['/admin/server/index']);?>">Tüm Serverları Gör</a>
        </div>
      </div>
    </div>
    <div class="col-lg-3">
      <div class="ecommerce-widget ecommerce-widget-a ecommerce-widget-a--danger">
        <div class="ecommerce-widget-a__currency"><i class="fab fa-linux"></i></div>
        <div class="ecommerce-widget-a__content">
          <div class="ecommerce-widget-a__title">Toplam Sunucu</div>
          <div class="ecommerce-widget-a__price"><?php echo $stats->totalVps;?></div>
		  <a class="btn btn-outline-danger btn-block"  href="<?php echo Yii::$app->urlManager->createUrl(['/admin/vps/index']);?>">Tüm Sunucuları Gör</a>
        </div>
      </div>
    </div>
    <div class="col-lg-3">
      <div class="ecommerce-widget ecommerce-widget-a ecommerce-widget-a--purple">
        <div class="ecommerce-widget-a__currency"><i class="fas fa-cubes"></i></div>
        <div class="ecommerce-widget-a__content">
          <div class="ecommerce-widget-a__title">Toplam Paket</div>
          <div class="ecommerce-widget-a__price"><?php echo $stats->totalPlan;?></div>
		  <a class="btn btn-outline-purple btn-block"  href="<?php echo Yii::$app->urlManager->createUrl(['/admin/plan/index']);?>">Tüm Paketleri Gör</a>
        </div>
      </div>
    </div>
    <div class="col-lg-3">
      <div class="ecommerce-widget ecommerce-widget-a ecommerce-widget-a--success">
        <div class="ecommerce-widget-a__currency"><i class="fab fa-redhat"></i></div>
        <div class="ecommerce-widget-a__content">
          <div class="ecommerce-widget-a__title">Toplam İşletim Sistemi</div>
          <div class="ecommerce-widget-a__price"><?php echo $stats->totalOs;?></div>
		  <a class="btn btn-outline-success btn-block"  href="<?php echo Yii::$app->urlManager->createUrl(['/admin/os/index']);?>">Tüm İşletim Sistemlerini Gör</a>
        </div>
      </div>
    </div>
  </div>

  
  <div class="row">
  
	<div class="col-md-6">
	
        <div class="chart-widget tui-chart-widget">
          <div id="paket-sanal-sunucular" class="chart-widget__chart"></div>
        </div>
	
	</div>
	
	<div class="col-md-6">
	
        <div class="chart-widget tui-chart-widget">
          <div id="isletim-sistemi-sanal-sunucular" class="chart-widget__chart"></div>
        </div>
	
	</div>
  
  </div>
  
<div class="row">
	<div class="col-md-3">
	
      <div class="ecommerce-widget ecommerce-widget-a ecommerce-widget-a--warning">
        <div class="ecommerce-widget-a__currency"><i class="fas fa-users"></i></div>
        <div class="ecommerce-widget-a__content">
          <div class="ecommerce-widget-a__title">Toplam Hesap</div>
          <div class="ecommerce-widget-a__price"><?php echo $stats->totalUsers;?></div>
		  <a class="btn btn-outline-warning btn-block"  href="<?php echo Yii::$app->urlManager->createUrl(['/admin/user/index']);?>">Tüm Hesapları Gör</a>
        </div>
      </div>
      <div class="ecommerce-widget ecommerce-widget-a ecommerce-widget-a--purple">
        <div class="ecommerce-widget-a__currency"><i class="fas fa-play"></i></div>
        <div class="ecommerce-widget-a__content">
          <div class="ecommerce-widget-a__title">Toplam Eylem</div>
          <div class="ecommerce-widget-a__price"><?php echo $stats->totalVpsAction;?></div>
        </div>
      </div>
      <div class="ecommerce-widget ecommerce-widget-a ecommerce-widget-a--danger">
        <div class="ecommerce-widget-a__currency"><i class="fas fa-chart-line"></i></div>
        <div class="ecommerce-widget-a__content">
          <div class="ecommerce-widget-a__title">Kullanılan Trafik / Aylık</div>
          <div class="ecommerce-widget-a__price"><?php echo number_format($stats->bandwidth/1024, 2);?> <span class="ecommerce-widget-a__price-sub">GB</span></div>
		  <a class="btn btn-outline-danger btn-block"  href="<?php echo Yii::$app->urlManager->createUrl(['/admin/vps/index']);?>">Tüm Sunucuları Gör</a>
        </div>
      </div>
      <div class="ecommerce-widget ecommerce-widget-a ecommerce-widget-a--info">
        <div class="ecommerce-widget-a__currency"><i class="fas fa-globe"></i></div>
        <div class="ecommerce-widget-a__content">
          <div class="ecommerce-widget-a__title">Toplam IP</div>
          <div class="ecommerce-widget-a__price"><?php echo $stats->totalIp;?></div>
		  <a class="btn btn-outline-info btn-block"  href="<?php echo Yii::$app->urlManager->createUrl(['/admin/ip/index']);?>">Tüm İPleri Gör</a>
        </div>
      </div>
      <div class="ecommerce-widget ecommerce-widget-a ecommerce-widget-a--success">
        <div class="ecommerce-widget-a__currency"><i class="fas fa-location-arrow"></i></div>
        <div class="ecommerce-widget-a__content">
          <div class="ecommerce-widget-a__title">Boşta Olan IP</div>
          <div class="ecommerce-widget-a__price"><?php echo $stats->emptyIp;?></div>
		  <a class="btn btn-outline-success btn-block"  href="<?php echo Yii::$app->urlManager->createUrl(['/admin/ip/empty']);?>">Boşta Olan Ipleri Gör</a>
        </div>
      </div>
	
	  </div>
	  
	  <div class="col-md-9">
	  	  
<div class="widget widget-controls widget-table widget-billing">
        <div class="widget-controls__header">
          <div>
            <i class="fas fa-play"></i> Geçmiş Sunucu Eylemleri</span>
          </div>
        </div>
        <div class="widget-controls__content js-scrollable" data-simplebar="init"><div class="simplebar-track vertical"><div class="simplebar-scrollbar"></div></div><div class="simplebar-track horizontal" style="visibility: visible;"><div class="simplebar-scrollbar" ></div></div><div class="simplebar-scroll-content" style="padding-right: 15px; margin-bottom: -30px;"><div class="simplebar-content">
          <table class="table table-no-border">
            <thead>
                <th>Sanal Sunucu</th>
                <th>Eylem</th>
                <th>Zaman</th>
                <th>İşlem</th>
            </thead>
            <tbody>
            <?php foreach($stats->vpsActions as $action) {?>
                <tr>
                    <td><?php echo Html::encode($action->vps->ip ? $action->vps->ip->ip : ' IP Yok');?></td>
                    <td>
                    <?php if($action->getIsStart()) {?>
                        <label class="badge badge-success"><i class="fas fa-play"></i> Başlat</label>
                    <?php } elseif ($action->getIsStop()) {?>
                        <label class="badge badge-danger"><i class="fas fa-stop"></i> Durdur</label>
                    <?php } elseif ($action->getIsRestart()) {?>
                        <label class="badge badge-info"><i class="fas fa-redo-alt"></i> Yeniden Başlat</label>
                    <?php } elseif ($action->getIsInstall()) {?>
                        <label class="badge badge-warning"><i class="fab fa-linux"></i> Format / Kurulum</label>
                    <?php } elseif ($action->getIsSuspend()) {?>
                        Askıya Al <?php echo ($action->description ? ' - ' . Html::encode($action->description) : null);?>
                    <?php } elseif ($action->getIsUnsuspend()) {?>
                        Askıdan Çıkar <?php echo ($action->description ? ' - ' . Html::encode($action->description) : null);?>
                    <?php }?>
                    </td>
                    <td><?php echo date('d M Y - H:i', $action->created_at);?></td>
					<td><a href="<?php echo Yii::$app->urlManager->createUrl(['/admin/vps/view', 'id' => $action->vps_id]);?>" class="btn btn-primary"> <i class="fas fa-arrow-right"></i> Sunucuya Git</a> </td>
                </tr>
            <?php }?>
            </tbody>
          </table>
        </div></div></div>
      </div>	  
	  
	          <div class="chart-widget tui-chart-widget">
          <div id="ip-istatistikleri" class="chart-widget__chart"></div>
        </div>

	  
	  </div>
	  
  </div>
  
  
  
<div class="panel-widget">
          <div class="panel-widget__header">
            <div>
              <span class="panel-widget__header-icon fas fa-history"></span> Son Girişler
            </div>
          </div>
          <div class="panel-widget__body">
              <table class="table table-no-border panel-widget__body-table">
            <thead>
				<th width="10">Sıra</th>
                <th>İsim</th>
                <th>IP Adresi</th>
                <th>Tarayıcı</th>
                <th>Tarih/Zaman</th>
                <th>Eylem Durumu</th>
            </thead>
            <tbody>




            <?php
						$i=0;
						foreach($stats->logins as $login) {
						$i++;
				?>
                <tr>
					<td > <span class=" round round-success"><?php echo $i; ?> </span> </td>
                    <td><a href="<?php echo Yii::$app->urlManager->createUrl(['/admin/user/edit', 'id' => $login->user->id]);?>"><?php echo Html::encode($login->user->getFullName());?></a></td>
                    <td><?php echo Html::encode($login->ip);?></td>
                    <td><?php echo Html::encode($login->browser_name); ?></td>
                    <td><?php echo date('d M Y - H:i:s', $login->created_at);?></td>
                    <td><?php echo ($login->getIsSuccessful() ? ' <label class="badge badge-success">Başarılı</label>' : ' <label class="badge badge-danger">Başarısız</label>');?></td>
                </tr>
            <?php }?>
            </tbody>
              </table>
          </div>
        </div>
		
		
<?php

$js = <<<EOT

(function ($) {
  'use strict';

  $(document).ready(function() {
    (function() {
		
    $.ajax({
        url:baseUrl + 'admin/vps/plan-chart',
        type:'POST',
        dataType:'JSON',
        success:function(back){
		  var container = document.getElementById('paket-sanal-sunucular');
		  var data = back.data;
		  var options = {
			chart: {
			  width: container.getBoundingClientRect().width,
			  height: 350,
			  title: 'Paket İstatistikleri',
			  format: '1,000'
			},
			yAxis: {
			  title: 'Toplam Sanal Sunucu',
			  min: 0,
			  max: back.toplam
			},
			xAxis: {
			  title: 'Paketleri kullanan sanal sunucular'
			},
			legend: {
			  align: 'top'
			}
		  };
		  tui.chart.columnChart(container, data, options);
        }
    });
		
    })();
	
    (function() {
		
    $.ajax({
        url:baseUrl + 'admin/vps/os-chart',
        type:'POST',
        dataType:'JSON',
        success:function(back){
		  var container = document.getElementById('isletim-sistemi-sanal-sunucular');
		  var data = back.data;
		  var options = {
			chart: {
			  width: container.getBoundingClientRect().width,
			  height: 350,
			  title: 'İşletim Sistemi İstatistikleri',
			  format: '1,000'
			},
			yAxis: {
			  title: 'Toplam Sanal Sunucu',
			  min: 0,
			  max: back.toplam
			},
			xAxis: {
			  title: 'İşletim sistemlerini kullanan sanal sunucular'
			},
			legend: {
			  align: 'top'
			}
		  };
		  tui.chart.columnChart(container, data, options);
        }
    });
		
    })();
	
    (function() {
		
    $.ajax({
        url:baseUrl + 'admin/vps/ip-chart',
        type:'POST',
        dataType:'JSON',
        success:function(back){
		  var container = document.getElementById('ip-istatistikleri');
		  var data = back.data;
		  var options = {
			chart: {
			  width: container.getBoundingClientRect().width,
			  height: 350,
			  title: 'Ip İstatistikleri',
			  format: '1,000'
			},
			yAxis: {
			  title: 'Toplam Ip',
			  min: 0,
			  max: back.toplam
			},
			legend: {
			  align: 'top'
			}
		  };
		  tui.chart.columnChart(container, data, options);
        }
    });
		
    })();
	

  });
})(jQuery);


EOT;

$this->registerJs($js);

?>
		
