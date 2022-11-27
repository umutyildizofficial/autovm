<?php use yii\helpers\Html; use yii\helpers\Url;?>
<style type="text/css">
.table td{text-align:left;padding-left:10px!important;}
    .col-md-6{background:none!important;box-shadow:none!important;}
    .btn-primary,.btn-danger{margin-top:10px;}
</style>

<div class="pending" style="display:none;">
  <div class="row">
    <div class="col col-md-12">
      <p class="grey-text steps1"><i class="fa fa-check" style="margin-right:10px;color:green;display:none;"></i>İşletim Sistemi Dosyaları Hazırlanıyor</p>
      <p class="grey-text steps2"><i class="fa fa-check" style="margin-right:10px;color:green;display:none;"></i>Dosyalar Genişletiliyor</p>
      <p class="grey-text steps3"><i class="fa fa-check" style="margin-right:10px;color:green;display:none;"></i>Özellikler Yükleniyor</p>
      <p class="grey-text steps4"><i class="fa fa-check" style="margin-right:10px;color:green;display:none;"></i>Kurulum Tamamlanıyor</p>
    </div>
  </div>
</div>

<!-- content -->
<div class="content">

    <div class="col-md-6">
        <div style="float:left;width:100%;padding:30px;background:#fff;border-radius:6px;box-shadow:0 0 5px #ddd;">

            <table class="table table-bordered">

                <tr>
                    <td>Sunucu</td><td><a href="<?php echo Yii::$app->urlManager->createUrl(['/admin/server/edit', 'id' => isset($vps->server->id)?$vps->server->id:'']);?>"><?php echo Html::encode(isset($vps->server->name)?$vps->server->name:'');?></a></td>
                </tr>
                <?php foreach($vps->ips as $ip) {?>
                <tr>
                    <td>IP Adresleri</td><td><?php echo $ip->ip;?> <a href="<?php echo Url::toRoute(['vps/del', 'id' => $ip->id]);?>">Sil</a></td>
                </tr>
                <?php }?>
                <tr>
                    <td>İşletim Sistemleri</td><td><?php echo Html::encode(isset($vps->os->name) ? $vps->os->name : 'Yok');?></td>
                </tr>
               <tr>
                    <td>Kullanıcı Adı</td><td><?php echo Html::encode(isset($vps->os->username) ? $vps->os->username : 'Yok');?></td>
                </tr>
                <tr>
                    <td>Son Şifre</td><td><?php echo Html::encode($vps->password ? $vps->password : 'Yok');?></td>
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
            </table>

            <a href="<?php echo Yii::$app->urlManager->createUrl(['/admin/vps/edit', 'id' => $vps->id]);?>" class="btn btn-primary waves-effect waves-light" target="_blank">Düzenle</a>

<a href="<?php echo Yii::$app->urlManager->createUrl(['/admin/vps/start', 'id' => $vps->id]);?>" class="btn btn-primary waves-effect waves-light start">Başlat</a>
            <a href="<?php echo Yii::$app->urlManager->createUrl(['/admin/vps/stop', 'id' => $vps->id]);?>" class="btn btn-primary waves-effect waves-light stop">Durdur</a>
            <a href="<?php echo Yii::$app->urlManager->createUrl(['/admin/vps/restart', 'id' => $vps->id]);?>" class="btn btn-primary waves-effect waves-light restart">Yeniden Başlat</a>
            <a href="<?php echo Yii::$app->urlManager->createUrl(['/admin/vps/advanced-status', 'id' => $vps->id]);?>" class="btn btn-primary waves-effect waves-light status">Durum</a>

            <a href="<?php echo Yii::$app->urlManager->createUrl(['/admin/vps/update', 'id' => $vps->id]);?>" class="btn btn-primary waves-effect waves-light upgrade">Güncelle</a>
            <a href="<?php echo Yii::$app->urlManager->createUrl(['/admin/vps/terminate', 'id' => $vps->id]);?>" class="btn btn-danger waves-effect waves-light terminate">Sonlandır (!)</a>

            <?php echo Html::endForm();?>

            <div style="float:left;width:100%;margin-top:30px;"></div>

            <button type="button" class="btn btn-primary select-os">Kurulum / Format</button>

            <div style="display:none;" class="install-box">
            <?php echo Html::beginForm(Url::toRoute(['vps/install', 'id' => $vps->id]), 'POST', ['class' => 'install']);?>

            <div class="form-group">
                <select class="form-control" name="data[os]">
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
    </div>

       <div class="col-md-6">
        <div style="float:left;width:100%;padding:30px;background:#fff;border-radius:6px;box-shadow:0 0 5px #ddd;">

            <table class="table table-bordered">
                <tr>
                    <td width="150">ID</td><td><?php echo $vps->id;?></td>
                </tr>
                <tr>
                    <td>Kullanıcı</td> <td><?php echo ($vps->user->first_name);?> -  <a href="<?php echo \yii\helpers\Url::toRoute(['user/login', 'id' => $vps->user->id]);?>" target="_blank">Giriş Yap</a></td>
                </tr>
                <tr>
                    <td>Oluşturulma</td><td><?php echo date('d M Y - H:i', $vps->created_at);?></td>
                </tr>
                <tr>
                    <td>Güncellenme</td><td><?php echo date('d M Y - H:i', $vps->updated_at);?></td>
                </tr>
                <tr>
                    <td>Trafik kullanımı</td><td><p><?php if($vps->plan_type==VpsPlansTypeDefault) echo number_format($used_bandwidth/1024, 3) .' / '. number_format($vps->plan->band_width); else echo number_format($used_bandwidth/1024, 3) .' /'. number_format($vps->vps_band_width);?> GB</p></td>
                </tr>
            </table>

            <a href="<?php echo Yii::$app->urlManager->createUrl(['/admin/vps/reset-bandwidth', 'id' => $vps->id]);?>" class="btn btn-primary waves-effect waves-light">Trafiği sıfırla</a>

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
                <button type="submit" class="btn btn-primary">Yeni IP adresi ekle</button>
            </div>
</div>
<!-- END content -->
<?php

$url = Yii::$app->urlManager->createUrl(['/admin/vps/step', 'id' => $vps->id]);


$js = <<<EOT

$(".start").click(function(e) {

    e.preventDefault();

    self = $(this);

    new simpleAlert({title: 'Processing', content: 'Lütfen bekleyiniz, bu işlem birkaç dakika sürebilir'});

    $.getJSON(self.attr("href"), function(data) {

        if (data.ok) {
            new simpleAlert({title: 'Message', content: 'İsteğiniz başarıyla tamamlandı'});
        } else {
            new simpleAlert({title: 'Message', content: 'Bir hata oluştu, lütfen tekrar deneyiniz'});
        }
    });
});

$(".stop").click(function(e) {

    e.preventDefault();

    self = $(this);

    new simpleAlert({title: 'Processing', content: 'Lütfen bekleyiniz, bu işlem birkaç dakika sürebilir'});

    $.getJSON(self.attr("href"), function(data) {

        if (data.ok) {
            new simpleAlert({title: 'Message', content: 'İsteğiniz başarıyla tamamlandı'});
        } else {
            new simpleAlert({title: 'Message', content: 'Bir hata oluştu, lütfen daha sonra tekrar deneyiniz'});
        }
    });
});

$(".restart").click(function(e) {

    e.preventDefault();

    self = $(this);

    new simpleAlert({title: 'Processing', content: 'Lütfen bekleyiniz, bu işlem birkaç dakika sürebilir'});

    $.getJSON(self.attr("href"), function(data) {

        if (data.ok) {
            new simpleAlert({title: 'Message', content: 'İsteğiniz başarıyla tamamlandı'});
        } else {
            new simpleAlert({title: 'Message', content: 'Bir hata oluştu, lütfen daha sonra tekrar deneyiniz'});
        }
    });
});

$(".status").click(function(e) {

    e.preventDefault();

    self = $(this);

    new simpleAlert({title: 'Processing', content: 'Lütfen bekleyiniz, bu işlem birkaç dakika sürebilir'});

    $.getJSON(self.attr("href"), function(data) {

        if (data.ok) {
            new simpleAlert({title: 'Message', content: 'Sunucunuz ' + data.power + ' ve network durumunuz ' + data.network});
        } else {
            new simpleAlert({title: 'Message', content: 'Bir hata oluştu, lütfen daha sonra tekrar deneyiniz'});
        }
    });
});

$(".upgrade").click(function(e) {

    e.preventDefault();

    self = $(this);

    self.text("Please wait");

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

    ok = confirm('Sunucu sonlandırıldı');

    if (ok) {
        self.text("Please wait");

        $.getJSON(self.attr("href"), self.serialize(), function(data) {
            if (data.ok) {
                self.text("Tamamlandı");
            } else {
                self.text("Tekrar deneyin");
            }
        });
    }
});


function getStatus(password) {

    inter = setInterval(function() {

        $.getJSON("$url", function(data) {

            if (data.ok) {

                if (data.step >= 1) {
                    $(".steps1 i").css("display", "inline");
                }

                if (data.step >= 2) {
                    $(".steps2 i").css("display", "inline");
                }

                if (data.step >= 3) {
                    $(".steps3 i").css("display", "inline");
                }

                if (data.step >= 4) {
                    $(".steps4 i").css("display", "inline");

                    new simpleAlert({title: 'Kurulum Süreci', content: 'Sunucu kurulumu başarıyla tamamlandı <br> Şifreniz: ' + password});

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

            if (data.ok) {
                getStatus(data.password)
            } else {
                new simpleAlert({title: 'Durum', content: 'Bir hata oluştu, lütfen tekrar deneyiniz'});
            }
        });
    });
});

EOT;

$this->registerJs($js);
