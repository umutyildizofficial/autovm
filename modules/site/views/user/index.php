<?php
use yii\helpers\Url;
use yii\helpers\Html;
use app\models\Bandwidth;

Yii::$app->setting->title .= ' - sanal sunucular';
?>

<h3 class="title-vm"><div class="container"><i class="fab fa-linux"></i> <?php echo Yii::t('app', 'Virtual Machines');?><p><?php echo Yii::t('app', 'List of your active virtual servers');?></p></div></h3>


<div class="container">

<?php echo \app\widgets\Alert::widget();?>

		<div class="row flex">

		 <?php foreach($virtualServers as $vps) {?>
		 
			<div id="server-<?=$vps->id;?>" class="col-md-4 col-sm-12 col-xs-12">
			<div class="VpsServer">
                    <?php if($vps->getIsOnline()){
                                   echo('<div class="green statusVps"> Online</div>');
                           }
                             else{
									echo('<div class="red statusVps"> Offline </div>');
                            }
                           ?>
					<div class="row">
					<div class="vps_item col-lg-12" id="vpsid-<?=$vps->id;?>">#<?=$vps->id;?></div>
					<div class="vps_item col-lg-12" id="vpsip-<?=$vps->id;?>"> <i class="fas fa-location-arrow"></i> <?php echo Html::encode($vps->ip ? $vps->ip->ip : 'yok');?></div>
					<div class="vps_item col-lg-12" id="vpshostname-<?=$vps->id;?>"> <i class="fas fa-info"></i> <?php echo Html::encode(!empty($vps->hostname) ? $vps->hostname : '');?></div>
					<div class="vps_item col-lg-12" id="vpsos-<?=$vps->id;?>"> <i class="fas fa-cubes"></i>  <?php echo Html::encode(isset($vps->os->name)?$vps->os->name:'yok');?></div>
					<div class="col-lg-12" id="vpsactions-<?=$vps->id;?>">
					<center>
					<div class="buttons_vps">
					<div class="btn-group">
						
                                    <a href="javascript:void(0);" onclick="loadItem(<?=$vps->id;?>, 12)" class="btn btn-warning vps-stop" style="padding:5px;"><i class="fas fa-info"></i> Hostname</a>
                                    <a href="javascript:void(0);" onclick="loadItem(<?=$vps->id;?>, 2)" class="btn btn-primary" style="padding:5px;"><i class="fas fa-redo"></i></a>
                                    <a href="javascript:void(0);" onclick="loadItem(<?=$vps->id;?>, 3)" class="btn btn-danger " style="padding:5px;"><i class="fas fa-stop"></i> Durdur</a>
                                    <a href="javascript:void(0);" onclick="loadItem(<?=$vps->id;?>, 4)" class="btn btn-success vps-start" style="padding:5px;"><i class="fas fa-play"></i> Başlat</a>
					</div>		
					</div>		
					</center>
					</div>
					
						
						</div>
						
					<a data-toggle="collapse" data-target="#bodyVirtualServer-<?=$vps->id;?>" class="btn btn-default btn-block server" data-id="<?=$vps->id;?>" href="#bodyVirtualServer-<?=$vps->id;?>"><i class="fab fa-linux"></i> Detaylar</a>
						
						<div id="bodyVirtualServer-<?=$vps->id;?>" class="collapse"></div>					
				</div>
			</div>
		 
		 <?php } ?>
		 
		 
		 </div>


</div>
