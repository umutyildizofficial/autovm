<?php
use yii\helpers\Html;
use app\models\Rdnsdurum;

$bundle = \app\modules\site\assets\Asset::register($this);

Yii::$app->setting->title .= ' - sunucu kontrol merkezi';

$this->registerJs("
    var vpsId = " . $vps->id . ";

    $(document).ready(function () {
    \"use strict\";

});

    $.ajax({
        url:baseUrl + '/site/vps/bandwidth',
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
                lineColors: ['#189C7E'],
            });
        }
    });
	
	$(\"html, body\").animate({ scrollTop: $('#bodyVirtualServer-'+vpsId).offset().top - 40 }, 1000);
");

?>



<style type="text/css">
.view-vps-table{
    box-shadow:none;
}

.title-vm2{
	text-align:center;
	font-weight:bold;
	font-size:14px;
	margin-top:10px;
}

.detail-vm {
    text-align: center;
    background: #f2f2f2;
    padding: 9px;
}

.info-title-1{
	background:#4caf50;
	color:#fff;
	padding:5px;
	text-align:center;
}

.info-title-2{
	background:#189c7e;
	color:#fff;
	padding:5px;
	text-align:center;
}

.vm-button{
	color:#0e4658;
	text-align:center;
	display:block
}

.vm-icon {
    display: block;
    margin-bottom: 7px;
    font-size: 29px;
    text-align: center;
    margin-top: 29px;
}

.nav-justified{
	margin-top:15px;
}

.margin-15{
	margin-top:15px;
	margin-bottom:15px;
}


</style>
<?= Yii::$app->session->set('username', $vps->server->username); ?>

<?php

	if(count($VpsIpleri) > 0){

?>

<ul class="nav nav-pills nav-justified">
  <li class="active"><a data-toggle="tab" href="#sunucu-genel-islemler-<?php echo $vps->id;?>"><i class="fas fa-server"></i> Sunucu Genel İşlemler</a></li>
  <li><a data-toggle="tab" href="#rdns-islemleri-<?php echo $vps->id;?>">RDNS İşlemleri</a></li>
</ul>

	<?php } ?>

<div class="tab-content">
<div id="sunucu-genel-islemler-<?php echo $vps->id;?>" class="tab-pane fade in active">
						<div id="chart<?php echo $vps->id;?>" style="width:100%;height:150px"></div>

						
							<div class="row-vm">
							
							<div class="col-md-6 col-xs-12 col-sm-12">
								<div class="info-title-1">Server Bilgileri</div>
								
								<div class="row">
								
									<div class="col-md-6 col-xs-12 col-sm-12">
									
										<div class="title-vm2"><?php echo Yii::t('app', 'IP Address');?></div>
										<div class="detail-vm"><?php foreach($vps->ips as $ip) {?>
												<div class="ip"><?php echo Html::encode($ip->ip);?></div>
											<?php }?>
											</div>
									
									</div>
									
									<div class="col-md-6 col-xs-12 col-sm-12">
									
										<div class="title-vm2"><?php echo Yii::t('app', 'Server');?></div>
										<div class="detail-vm"><?php echo Html::encode(isset($vps->server)?$vps->server->name:'');?></div>
										
									</div>
									
								</div>
									
								<div class="row">
									
									
									<div class="col-md-6 col-xs-12 col-sm-12">
									
										<div class="title-vm2"><?php echo Yii::t('app', 'Username');?></div>
										<div class="detail-vm"><?php echo Html::encode(isset($vps->os->username)?$vps->os->username:'');?></div>
									
									</div>
									<div class="col-md-6 col-xs-12 col-sm-12">
									
										<div class="title-vm2"><?php echo Yii::t('app', 'Disk');?></div>
										<div class="detail-vm"><?php echo Html::encode(isset($vps->disk)?$vps->disk:'');?></div>
									
									</div>
									
								
								</div>
								
							</div>
							
							<div class="col-md-6 col-xs-12 col-sm-12">
								<div class="info-title-2">Server Özellikleri</div>
								
								<div class="row">
								
									<div class="col-md-4 col-xs-12 col-sm-12">
									
										<div class="title-vm2"><?php echo Yii::t('app', 'Operating System');?></div>
										<div class="detail-vm"><?php echo Html::encode(isset($vps->os->name)?$vps->os->name:'');?></div>
									
									</div>
								
									<div class="col-md-4 col-xs-12 col-sm-12">
									
										<div class="title-vm2"><?php echo Yii::t('app', 'Memory');?></div>
										<div class="detail-vm"><?php
											//var_dump($vps);exit;
											if($vps->plan_type==VpsPlansTypeDefault) {
												echo $vps->plan->ram;
											}
											else {
												echo $vps->vps_ram;
											}
											?>
											</div>
									
									</div>
									<div class="col-md-4 col-xs-12 col-sm-12">
									
										<div class="title-vm2"><?php echo Yii::t('app', 'CPU Cores');?></div>
										<div class="detail-vm"><?php
											//var_dump($vps);exit;
											if($vps->plan_type==VpsPlansTypeDefault) {
												echo $vps->plan->cpu_core;
											}
											else {
												echo $vps->vps_cpu_core;
											}
											?> Çekirdek
											</div>
										
									</div>
					
							</div>
							
							<div class="row">
                    <div class="col-lg-4 col-sm-4">
					
						<div class="title-vm2"><?php echo Yii::t('app', 'CPU MHZ');?></div>
						<div class="detail-vm"><?php
                            //var_dump($vps);exit;
                            if($vps->plan_type==VpsPlansTypeDefault) {
                                echo $vps->plan->cpu_mhz;
                            }
                            else {
                                echo $vps->vps_cpu_mhz;
                            }
                            ?> MHZ
							</div>
					
					</div>
                    <div class="col-lg-4 col-sm-4">
					
						<div class="title-vm2"><?php echo Yii::t('app', 'Disk Space');?></div>
						<div class="detail-vm"><?php
                            //var_dump($vps);exit;
                            if($vps->plan_type==VpsPlansTypeDefault) {
                                echo $vps->plan->hard;
                            }
                            else {
                                echo $vps->vps_hard;
                            }
                            ?> GB
							</div>
					
					</div>
                    <div class="col-lg-4 col-sm-4">
					
						<div class="title-vm2"><?php echo Yii::t('app', 'Bandwidth');?></div>
						<div class="detail-vm"><?php
                            if($vps->plan_type==VpsPlansTypeDefault)
                                echo number_format($used_bandwidth/1024, 1) .' /'. ($vps->plan->band_width + $vps->extra_bw);
                            else
                                echo number_format($used_bandwidth/1024, 1) .' / '. ($vps->vps_band_width + $vps->extra_bw);?>
                            GB
							</div>
					
					</div>
					
						</div>
								
							</div>
						
						</div>
						
												
							<div class="col-md-12 vm-buttons-detail">
							<div class="col-lg-1 col-md-3 col-sm-6 col-xs-6"><a href="javascript:void(0);" class="vm-button" onclick="loadItem(<?=$vps->id;?>, 1)"><span class="vm-icon"><i class="fas fa-desktop server"></i></span> <?php echo Yii::t('app', 'Change Os');?></a></div>
							<div class="col-lg-1 col-md-3 col-sm-6 col-xs-6"><a href="javascript:void(0);" class="vm-button" onclick="loadItem(<?=$vps->id;?>, 10)"><span class="vm-icon"><i class="fas fa-compact-disc"></i></span> <?php echo Yii::t('app', 'ISO');?></a></div>
							<div class="col-lg-1 col-md-3 col-sm-6 col-xs-6"><a href="javascript:void(0);" class="vm-button" onclick="loadItem(<?=$vps->id;?>, 2)"><span class="vm-icon"><i class="fas fa-sync"></i></span> <?php echo Yii::t('app', 'Restart');?></a></div>
							<div class="col-lg-1 col-md-3 col-sm-6 col-xs-6"><a href="javascript:void(0);" class="vm-button" onclick="loadItem(<?=$vps->id;?>, 3)"><span class="vm-icon"><i class="fas fa-stop"></i></span> <?php echo Yii::t('app', 'Stop');?></p></a></div>
							<div class="col-lg-1 col-md-3 col-sm-6 col-xs-6"><a href="javascript:void(0);" class="vm-button" onclick="loadItem(<?=$vps->id;?>, 4)"><span class="vm-icon"><i class="fas fa-play"></i></span> <?php echo Yii::t('app', 'Start');?></a></div>
							<div class="col-lg-1 col-md-3 col-sm-6 col-xs-6"><a href="javascript:void(0);" class="vm-button" onclick="loadItem(<?=$vps->id;?>, 5)"><span class="vm-icon"><i class="fas fa-wifi"></i></span> <?php echo Yii::t('app', 'VM Status');?></a></div>
							<div class="col-lg-1 col-md-3 col-sm-6 col-xs-6"><a href="javascript:void(0);" class="vm-button" onclick="loadItem(<?=$vps->id;?>, 6)"><span class="vm-icon"><i class="fas fa-chart-line"></i></span> <?php echo Yii::t('app', 'Monitor');?></a></div>
							<div class="col-lg-1 col-md-3 col-sm-6 col-xs-6"><a href="javascript:void(0);" class="vm-button" onclick="loadItem(<?=$vps->id;?>, 7)"><span class="vm-icon"><i class="fas fa-hdd"></i></span> <?php echo Yii::t('app', 'Extend hard');?></a></div>
							<div class="col-lg-1 col-md-3 col-sm-6 col-xs-6"><a href="javascript:void(0);" class="vm-button" onclick="loadItem(<?=$vps->id;?>, 8)"><span class="vm-icon"><i class="fas fa-history"></i></span> <?php echo Yii::t('app', 'Action logs');?></a></div>
							<div class="col-lg-1 col-md-3 col-sm-6 col-xs-6"><a href="javascript:void(0);" class="vm-button" onclick="loadItem(<?=$vps->id;?>, 9)"><span class="vm-icon"><i class="fas fa-terminal"></i></span> <?php echo Yii::t('app', 'Console');?></a></div>
							<div class="col-lg-1 col-md-3 col-sm-6 col-xs-6"><a href="javascript:void(0);" class="vm-button" onclick="loadItem(<?=$vps->id;?>, 11)"><span class="vm-icon"><i class="fas fa-cloud"></i></span> <?php echo Yii::t('app', 'SnapShot');?></a></div>
							<div class="col-lg-1 col-md-3 col-sm-6 col-xs-6"><a href="javascript:void(0);" class="vm-button" onclick="loadItem(<?=$vps->id;?>, 13)"><span class="vm-icon"><i class="fas fa-key"></i></span> <?php echo Yii::t('app', 'Şifre İşlemleri');?></a></div>
							
							</div>
							

						
						
						<div class="clear"></div>
						
						</div>
						
						<?php if(count($VpsIpleri) > 0){ ?>
						
						 <div id="rdns-islemleri-<?php echo $vps->id;?>" class="tab-pane fade">
						 
						 <div id="rdns-result-<?php echo $vps->id;?>">
						 
						 <a class="btn btn-warning margin-15" onclick="rdns_create(<?=$vps->id;?>)"><i class="fas fa-plus"></i> RDNS Oluştur</a>
						 
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
											<a class=\"btn btn-primary\" onclick=\"rdns_pending_edit({$rdnsWaitInsert->id})\"><i class=\"fas fa-edit\"></i></a>
											<a class=\"btn btn-danger\" onclick=\"rdns_pending_delete({$rdnsWaitInsert->id})\"><i class=\"fas fa-trash\"></i></a> 
											</td>
											</tr>";
										}
									
										foreach($rdns_datas as $rdnsdata){
											
											echo "<tr>
											<td>{$rdnsdata->surec}</td>
											<td>{$rdnsdata->ana_ip}</td>
											<td>{$rdnsdata->type}</td>
											<td>{$rdnsdata->content}</td>
											<td>{$rdnsdata->priority}</td>
											<td>{$rdnsdata->ttl}</td>
											<td>
											<a class=\"btn btn-primary\" onclick=\"rdns_edit({$rdnsdata->id})\"><i class=\"fas fa-edit\"></i></a>
											<a class=\"btn btn-danger\" onclick=\"rdns_delete({$rdnsdata->id})\"><i class=\"fas fa-trash\"></i></a> 
											</td>
											</tr>";
										}
									?>
								
								</tbody>
								
							
							</table>
						 
						 </div>
						 
						 </div>
						 
						<?php } ?>
						
						</div>



