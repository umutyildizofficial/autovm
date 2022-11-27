<?php use yii\helpers\Html;?>

<!-- content -->
  <div class="page-content__header">
    <div>
      <h2 class="page-content__header-heading"><i class="fas fa-search"></i> <?=$server->name;?> Adlı Fiziksel Sunucuyu Görüntülüyorsunuz </h2>
    </div>
    </div>
	
<div class="main-container">     
    <div class="col-md-8">
		<table class="table table-bordered">
            <tbody>
                <tr>
                    <td>Sunucu Adı</td>
                    <td><?php echo Html::encode($server->name);?></td>
                </tr>
                <tr>
                    <td>Host Adresi</td>
                    <?php if($api->host) {?>
                    <td><?php echo Html::encode($api->host);?></td>
                    <?php } else {?>
                    <td>Yok</td>
                    <?php }?>
                </tr>
                <tr>
                    <td>Host Bağlantısı</td>
                    <?php if(empty($result)) {?>
                    <td><font color="red">Bağlantı başarısız</font></td>
                    <?php } else {?>
                    <td><font color="green">Bağlandı</font></td>
                    <?php }?>
                </tr>
                <?php if(!empty($result)) {?>
                <tr>
                    <td>ESXi Bağlantısı</td>
                    <?php if($result->server) {?>
                    <td><font color="green">Bağlandı</font></td>
                    <?php } else {?>
                    <td><font color="red">Bağlantı başarısız</font></td>
                    <?php }?>
                </tr>
                <tr>
                    <td>Vcenter Bağlantısı</td>
                    <?php if($result->center) {?>
                    <td><font color="green">Bağlandı</font></td>
                    <?php } else {?>
                    <td><font color="red">Bağlantı başarısız</font></td>
                    <?php }?>
                </tr>
                <tr>
                    <td>SSH Bağlantısı</td>
                    <?php if($result->ssh) {?>
                    <td><font color="green">Bağlandı</font></td>
                    <?php } else {?>
                    <td><font color="red">Bağlantı başarısız</font></td>
                    <?php }?>
                </tr>
                <tr>
                    <td>Diskler</td>
                    <?php if($result->storage) {?>
                    <td><font color="green">Doğrulandı</font></td>
                    <?php } else {?>
                    <td><font color="red">Doğrulama başarısız</font></td>
                    <?php }?>
                </tr>
                <?php }?>
                <tr>
                    <td colspan="2">Fiziksel sunuculara ilgili işletim sistemleri'nin (.ova) imajlarını yüklemeniz gerekmektedir.</td>
                </tr>
			</tbody>
		</table>
    </div>
</div>
<!-- / content -->
