<?php use yii\helpers\Html; use yii\helpers\Url; use app\models\SatanHosting; use app\models\SatanHostingSettings; 

$this->registerJsFile(
    '@web/adminassets/js/raphael.js',
    ['depends' => [\yii\web\JqueryAsset::className()]]
);

$this->registerJsFile(
    '@web/adminassets/vendor/morris/morris.js',
    ['depends' => [\yii\web\JqueryAsset::className()]]
);

$this->registerJsFile(
    '@web/adminassets/js/renderajax.js',
    ['depends' => [\yii\web\JqueryAsset::className()]]
);

$this->registerCssFile(
    '@web/adminassets/vendor/morris/morris.css'
);

$this->registerJs("
    var vpsId = " . $vps->id . ";

    $(document).ready(function () {
    \"use strict\";

});

    $.ajax({
        url:baseUrl + 'admin/vps/bandwidth',
        type:'POST',
        dataType:'JSON',
        data:{vpsId:vpsId},
        success:function(data){
            Morris.Line({
                element: 'chart{$vps->id}',
                data: data,
                xkey: 'date',
                ykeys: ['total'],
                labels: ['Trafik MB'],
                smooth:false,
                lineWidth:2,
                lineColors: ['#0094f2', '#000'],
				gridTextColor: '#000',
				gridTextWeight: 'bold'
            });
        }
    });
");


?>



<div class="pending" style="display:none;">
  <div class="row">
    <div class="col col-md-12">
      <p style="text-align:center;font-size:20px;font-weight:bold;"><span class="percent">0</span>%</p>
      <p class="grey-text steps1">İşletim Sistemi Dosyaları Hazırlanıyor</p>
      <p class="grey-text steps2">Dosyalar Genişletiliyor</p>
      <p class="grey-text steps3">Özellikler Yükleniyor</p>
      <p class="grey-text steps4">Kurulum Tamamlanıyor</p>
    </div>
  </div>
</div>

    
<?php if(!empty($result->log)) {?>
    <p class="alert alert-danger">Birşeyler yanlış gitti <a href="https://wiki.autovm.net/index.php/Logs" target="_blank">soruna wikiden bakmak için tıklayınız</a> Hata kodu: <b><?php echo $result->log;?></b>.</p>
<?php }?>

<?php if($vps->suspends) {?>
    <div class="col-md-12">
        <div style="float:left;width:100%;padding:30px;background:#fff;border-radius:6px;box-shadow:0 0 5px #ddd;">
            <table class="table table2">
                <thead>
                    <th>Id</th> <th>Eylem</th> <th>Açıklama</th> <th>Oluşturulma</th>
                </thead>
                <tbody>
            <?php foreach($vps->suspends as $action) {?>
                <tr>
                    <td><?php echo $action->id;?></td> <td>Askı Durumu</td> <td><?php echo $action->description;?></td> <td><?php echo date('d M Y - H:i', $action->created_at);?></td>
                </tr>
            <?php }?>
                </tbody>
            </table>
        </div>
    </div>
<?php }?>


<div class="row">

<div class="col-md-2">

    <h3><i class="fas fa-server"></i> <?php echo Html::encode(isset($vps->hostname)?$vps->hostname:'');?></h3>
	  
            <a href="<?php echo Yii::$app->urlManager->createUrl(['/admin/vps/edit', 'id' => $vps->id]);?>" class="btn btn-info btn-block"><i class="fas fa-edit"></i> Düzenle</a>

            <a href="<?php echo Yii::$app->urlManager->createUrl(['/admin/vps/start', 'id' => $vps->id]);?>" class="btn btn-success mr-3 btn-block start"><i class="fas fa-play"></i> Başlat</a>
            <a href="<?php echo Yii::$app->urlManager->createUrl(['/admin/vps/stop', 'id' => $vps->id]);?>" class="btn btn-danger mr-3 btn-block stop"><i class="fas fa-pause"></i> Durdur</a>
            <a href="<?php echo Yii::$app->urlManager->createUrl(['/admin/vps/restart', 'id' => $vps->id]);?>" class="btn btn-warning mr-3 btn-block restart"><i class="fas fa-redo"></i>  Yeniden Başlat</a>
            <a href="<?php echo Yii::$app->urlManager->createUrl(['/admin/vps/advanced-status', 'id' => $vps->id]);?>" class="btn btn-info mr-3 btn-block status"><i class="fas fa-wifi"></i> Durum</a>
            <a href="<?php echo Yii::$app->urlManager->createUrl(['/admin/vps/console', 'id' => $vps->id]);?>" class="btn btn-purple mr-3 btn-block console"><i class="fas fa-terminal"></i> Konsol (VNC)</a>

            <a href="<?php echo Yii::$app->urlManager->createUrl(['/admin/vps/update', 'id' => $vps->id]);?>" class="btn btn-secondary mr-3 btn-block upgrade"><i class="fas fa-save"></i> Güncelle</a>
            <a href="<?php echo Yii::$app->urlManager->createUrl(['/admin/vps/terminate', 'id' => $vps->id]);?>" class="btn btn-danger mr-3 btn-block terminate"><i class="fas fa-trash"></i> Sonlandır (!)</a>
			
            <button type="button" class="btn btn-primary select-os btn-block">Format / Kurulum</button>
			
            <div style="display:none;" class="install-box">
            <?php echo Html::beginForm(Url::toRoute(['vps/install', 'id' => $vps->id]), 'POST', ['class' => 'install']);?>

			<input type="hidden" name="vpsId" value="<?=$vps->id;?>">
			<input type="hidden" name="password" value="<?=SatanHostingSettings::autoPassword();?>">

            <div class="form-group">
                <select class="form-control" name="osId">
                    <?php foreach($os as $o) {?>
                    <option value="<?php echo $o->id;?>"><?php echo Html::encode($o->name);?></option>
                    <?php }?>
                </select>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">Yükle</button>
            </div>

            <?php echo Html::endForm();?>
            </div>
				  
  
  </div>
  
  <div class="col-md-10">
  
  <div class="main-container">
    <div class="container-tabs">
      <ul class="nav nav-tabs">
        <li class="nav-item">
          <a class="nav-link active" data-toggle="tab" href="#genel-bilgiler">Sunucu Genel Bilgiler</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" data-toggle="tab" href="#tab-2">Trafik / IP / Kullanıcı İşlemleri</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" data-toggle="tab" href="#tab-3">RDNS İşlemleri</a>
        </li>
      </ul>
	<div class="tab-content">	
	 <div class="tab-pane active" id="genel-bilgiler">
            <table class="table table-bordered">

                <tr>
                    <td>Sunucu</td><td><a href="<?php echo Yii::$app->urlManager->createUrl(['/admin/server/edit', 'id' => isset($vps->server->id)?$vps->server->id:'']);?>"><?php echo Html::encode(isset($vps->server->name)?$vps->server->name:'');?></a></td>
                </tr>
                <?php 
				
				foreach($vps->ips as $ip) {?>
                <tr>
                    <td>IP Adresi</td><td><?php echo $ip->ip;?> <a href="<?php echo Url::toRoute(['vps/del', 'id' => $ip->id]);?>">Sil</a>
					<?php
						if($ip->ip != $ipObject->ip){
							?>
								<a style="color:green;" href="<?php echo Url::toRoute(['vps/changeip', 'id' => $ip->id]);?>">Değiştir</a>
							<?php
						}
					?>
					</td>
                </tr>
                <?php }?>
                <tr>
                    <td>İşletim Sistemi</td><td><?php echo Html::encode(isset($vps->os->name) ? $vps->os->name : 'Yok');?></td>
                </tr>
               <tr>
                    <td>Kullanıcı Adı</td><td><?php echo Html::encode(isset($vps->os->username) ? $vps->os->username : 'Yok');?></td>
                </tr>
                <tr>
                    <td>Şifre</td><td><?php echo Html::encode($vps->password ? $vps->password : 'Yok');?></td>
                </tr>
                <tr>
                    <td>Paket</td><td><a href="<?php echo isset($vps->plan)? Yii::$app->urlManager->createUrl(['/admin/plan/edit', 'id' => $vps->plan->id]):'' ; ?>"><?php echo isset($vps->plan)?Html::encode($vps->plan->name):'';?></a></td>
                </tr>
                <tr>
                    <td>Loglar</td><td><a href="<?php echo Yii::$app->urlManager->createUrl(['/admin/vps/log', 'id' => $vps->id]);?>"><i class="fa fa-search"></i></a></td>
                </tr>
                <tr>
                    <td>Erişim</td><td><?php echo ($vps->getIsActive() ? ' <b class="text-success"> Var</b>' : ' <b class="text-danger"> Yok</b>');?></td>
                </tr>
                <tr>
                    <td>Format Limitini Değiştir</td><td><?php echo $vps->change_limit;?>/<?php echo Yii::$app->setting->change_limit;?> <a style="color:#e91e63;" href="<?php echo Yii::$app->urlManager->createUrl(['admin/vps/reset-limit', 'id' => $vps->id]);?>">Sıfırla</a></td>
                </tr>
            </table>
			
			<div id="chart<?php echo $vps->id;?>" style="width:100%;height:150px"></div>
			
		</div>
		 <div class="tab-pane" id="tab-2">
		 
            <table class="table table-bordered">
                <tr>
                    <td width="150">ID</td><td><?php echo $vps->id;?></td>
                </tr>
                <tr>
                    <td>Kullanıcı</td> <td><?php echo ($vps->user->first_name);?> -  <a href="<?php echo \yii\helpers\Url::toRoute(['user/login', 'id' => $vps->user->id]);?>">Giriş yap</a></td>
                </tr>
                <tr>
                    <td>Oluşturulma</td><td><?php echo date('d M Y - H:i', $vps->created_at);?></td>
                </tr>
                <tr>
                    <td>Güncellenme</td><td><?php echo date('d M Y - H:i', $vps->updated_at);?></td>
                </tr>
                <?php if($vps->notify_at) {?>
                <tr>
                    <td>Şuradan bildir:</td><td><?php echo date('d M Y - H:i', $vps->notify_at);?></td>
                </tr>
                <?php }?>
                <tr>
                    <td>Trafik kullanımı</td><td><p><?php if($vps->plan_type==VpsPlansTypeDefault) echo number_format($used_bandwidth/1024, 1) .' / '. number_format(($vps->plan->band_width + $vps->extra_bw)); else echo number_format($used_bandwidth/1024, 1) .' /'. number_format(($vps->vps_band_width + $vps->extra_bw));?> GB</p></td>
                </tr>
            </table>

            <a href="<?php echo Yii::$app->urlManager->createUrl(['/admin/vps/reset-bandwidth', 'id' => $vps->id]);?>" class="btn btn-danger waves-effect waves-light">Trafiği sıfırla</a>

            <div style="float:left;width:100%;margin-top:30px;"></div>

            <?php echo Html::beginForm(Url::toRoute(['vps/add', 'id' => $vps->id]));?>

            <div class="form-group">
                <select class="form-control" name="data[ip]">
                    <?php foreach($ips as $ip) {?>
                    <option value="<?php echo $ip->id;?>"><?php echo $ip->ip;?></option>
                    <?php }?>
                </select>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-success">Yeni IP adresi ekle</button>
            </div>
		 
		 </div>
		 
		  <div class="tab-pane" id="tab-3">
		  
						 <a class="btn btn-warning margin-15 rdns-create" target="_blank" href="<?php echo Yii::$app->urlManager->createUrl(['/admin/vps/rdns-create-view', 'id' => $vps->id]);?>"><i class="fas fa-plus"></i> RDNS Oluştur</a>
						 
							<table class="table">
							
								<thead>
									<tr>
										<th>Süreç</th>
										<th>Ip Adresi</th>
										<th>Type</th>
										<th>Content</th>
										<th>Priority</th>
										<th>TTL</th>
										<th>İşlem</th>
									</tr>
								</thead>
								
								<tbody>
								
									<?php
									
										foreach($rdnsWaitPendingInsert as $rdnsWaitInsert){
											
											echo "<tr id=\"waitpending-{$rdnsWaitInsert->id}\">
											<td><label class=\"label label-success\"> Eklenme Sürecinde</label></td>
											<td>{$rdnsWaitInsert->ana_ip}</td>
											<td>{$rdnsWaitInsert->type}</td>
											<td>{$rdnsWaitInsert->content}</td>
											<td>{$rdnsWaitInsert->priority}</td>
											<td>{$rdnsWaitInsert->ttl}</td>
											<td>
											<a class=\"btn btn-primary\" href=\"javascript:;\" onclick=\"rdns_pending_edit({$rdnsWaitInsert->id})\"><i class=\"fas fa-edit\"></i></a>
											<a class=\"btn btn-danger\" href=\"javascript:;\" onclick=\"rdns_pending_delete({$rdnsWaitInsert->id})\"><i class=\"fas fa-trash\"></i></a> 
											</td>
											</tr>";
										}
									
										foreach($rdns_datas as $rdnsdata){
											
											echo "<tr id=\"rdnsdata-{$rdnsdata->id}\">
											<td>{$rdnsdata->surec}</td>
											<td>{$rdnsdata->ana_ip}</td>
											<td>{$rdnsdata->type}</td>
											<td>{$rdnsdata->content}</td>
											<td>{$rdnsdata->priority}</td>
											<td>{$rdnsdata->ttl}</td>
											<td>
											<a class=\"btn btn-primary\" onclick=\"rdns_edit({$rdnsdata->id})\" href=\"javascript:;\"><i class=\"fas fa-edit\"></i></a>
											<a class=\"btn btn-danger\" onclick=\"rdns_delete({$rdnsdata->id})\" href=\"javascript:;\"><i class=\"fas fa-trash\"></i></a> 
											</td>
											</tr>";
										}
									?>
								
								</tbody>
								
							
							</table>
		  
		  </div>
		 
			
		</div>

	
  </div>
</div>




</div>
</div>



<div class="modal show fade" id="myModal"><div class="modal-header"><a class="close" data-dismiss="modal">×</a><h3>Modal header</h3></div><div class="modal-body"><p>One fine body…</p></div></div>


<!-- END content -->
<?php

$url = Yii::$app->urlManager->createUrl(['/admin/vps/step', 'id' => $vps->id]);

$osUrl = Yii::$app->urlManager->createUrl(['/site/vps/os', 'id' => '']);



$js = <<<EOT

$(".rdns-create").click(function(e) {

    e.preventDefault();

    self = $(this);
	
    new simpleAlert({title: 'İşleniyor', content: 'Lütfen bekleyiniz, bu işlem birkaç dakika sürebilir'});
	
    $.get(self.attr("href"), function(data) {
		new simpleAlert({title: 'Rdns Oluştur', content: data});
    });
	
});	
	
$(".start").click(function(e) {

    e.preventDefault();

    self = $(this);

    new simpleAlert({title: 'İşleniyor', content: 'Lütfen bekleyiniz, bu işlem birkaç dakika sürebilir'});

    $.getJSON(self.attr("href"), function(data) {

        if (data.ok) {
            new simpleAlert({title: 'Mesaj', content: 'İstediğiniz tamamlandı'});
        } else {
            new simpleAlert({title: 'Mesaj', content: 'Bir hata oluştu, lütfen daha sonra tekrar deneyiniz'});
        }
    });
});

$(".stop").click(function(e) {

    e.preventDefault();

    self = $(this);

    new simpleAlert({title: 'İşleniyor', content: 'Lütfen bekleyiniz, bu işlem birkaç dakika sürebilir'});

    $.getJSON(self.attr("href"), function(data) {

        if (data.ok) {
            new simpleAlert({title: 'Mesaj', content: 'İstediğiniz tamamlandı'});
        } else {
            new simpleAlert({title: 'Mesaj', content: 'Bir hata oluştu, lütfen daha sonra tekrar deneyiniz'});
        }
    });
});

$(".restart").click(function(e) {

    e.preventDefault();

    self = $(this);

    new simpleAlert({title: 'İşleniyor', content: 'Lütfen bekleyiniz, bu işlem birkaç dakika sürebilir'});

    $.getJSON(self.attr("href"), function(data) {

        if (data.ok) {
            new simpleAlert({title: 'Mesaj', content: 'İstediğiniz tamamlandı'});
        } else {
            new simpleAlert({title: 'Mesaj', content: 'Bir hata oluştu, lütfen daha sonra tekrar deneyiniz'});
        }
    });
});

$(".status").click(function(e) {

    e.preventDefault();

    self = $(this);

    new simpleAlert({title: 'İşleniyor', content: 'Lütfen bekleyiniz, bu işlem birkaç dakika sürebilir'});

    $.getJSON(self.attr("href"), function(data) {

        if (data.ok) {
            new simpleAlert({title: 'Mesaj', content: 'Sunucunuz ' + data.power + ' ve network durumu ' + data.network});
        } else {
            new simpleAlert({title: 'Mesaj', content: 'Bir hata oluştu, lütfen tekrar deneyin'});
        }
    });
});

$(".console").click(function(e) {

    e.preventDefault();

    self = $(this);

    new simpleAlert({title: 'Konsol', content: 'Lütfen bekleyiniz, konsol erişimi için sunucu yeniden başlatılıyor'});

    $.getJSON(self.attr("href"), function(data) {
		
        if (data.ok) {
            new simpleAlert({title: 'Konsol', content: '<a target="_blank" href="' + data.address + ':' + data.port + '/vnc_lite.html?password=' + data.password + '">Konsolu açmak için buraya tıklayınız</a>'});
        } else {
            new simpleAlert({title: 'Konsol', content: 'Bir hata oluştu, lütfen tekrar deneyin'});
        }
    });
});

$(".upgrade").click(function(e) {

    e.preventDefault();

    self = $(this);

    self.text("Lütfen Bekleyiniz");

    $.getJSON(self.attr("href"), self.serialize(), function(data) {
        if (data.ok) {
            self.text("Tamamlandı");
        } else {
            self.text("Tekrar deneyin");
        }
    });
});

$(".terminate").click(function(e) {

    e.preventDefault();

    self = $(this);

    ok = confirm('Sunucuyu sonlandırmak istediğinizden emin misiniz?');

    if (ok) {
        self.text("Lütfen bekleyiniz");

        $.getJSON(self.attr("href"), self.serialize(), function(data) {
            if (data.ok) {
                self.text("Tamamlandı");
            } else {
                self.text("Tekrar deneyin");
            }
        });
    }
});

function getStatus(password, osId) {
	
	urlOs = "{$osUrl}" + osId;

    inter = setInterval(function() {

        $.getJSON("$url", function(data) {

            if (data.ok) {

                if (data.percent) {
                    $(".percent").text(data.percent);
                }

                if (data.step >= 1) {
                    $(".steps1").css("color", "green");
                }

                if (data.step >= 2) {
                    $(".steps2").css("color", "green");
                }

                if (data.step >= 3) {
                    $(".steps3").css("color", "green");
                }

                if (data.step >= 4) {
					
                   $(".steps4").css("color", "green");

                }
				if (data.step >= 5 && data.percent == 100){
					
					var vpsId = "{$vps->id}"; 
					
					$.getJSON(urlOs, function(dataOs) {

						if (dataOs.ok && dataOs.status == 1) {
							_username = "administrator";
						} else if (dataOs.ok && dataOs.status == 2) {
							_username = "root";
						} else {
							_username = "administrator (OR) root";
						}
						
						
EOT;
$js .= <<<EOT
					});
					setTimeout(function(){
						new simpleAlert({title: 'Kurulum Süreci', content: 'Sunucu kurulumu başarıyla tamamlandı <br> Şifreniz: ' + password});
					 }, 6000);
					clearInterval(inter);
				}
            }
        });

    }, 5000);
}

$(".select-os").click(function() {

    data = $(".install-box").html();

    new simpleAlert({title: 'İşletim Sistemi Seçimi', content: data});

    $(".install").submit(function(e) {

        e.preventDefault();

        self = $(this);

        data = $(".pending").html();

        new simpleAlert({title: 'Yükleniyor..', content: data});

        $.post(self.attr("action"), self.serialize(), function(data) {

            if (data.error == "none") {
                getStatus(data.password, data.osid)
            }
			else if(data.error == "os"){
                new simpleAlert({title: 'Durum', content: 'İşletim sistemi bulunamadı.'});
			}
			else {
                new simpleAlert({title: 'Durum', content: 'Bir hata oluştu, lütfen daha sonra tekrar deneyiniz'});
            }
        });
    });
});

EOT;

$this->registerJs($js);
