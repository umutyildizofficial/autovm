<?php
use yii\helpers\Html;
use app\models\Vps;
use app\models\Server;
use app\models\Ip;

$stats = new \stdClass;
        
$stats->totalVps = Vps::find()->count();
$stats->totalServer = Server::find()->count();
$stats->totalIp = Ip::find()->count();

$bundle = \app\modules\admin\assets\Asset::register($this);

$this->beginPage();

$baseUrl = rtrim(\yii\helpers\Url::to('/', true),'/') . Yii::$app->request->baseUrl . '/';
$this->registerJs("var baseUrl = \"{$baseUrl}\";", \yii\web\View::POS_END);


?>
<html lang="fa">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <?php echo Html::csrfMetaTags();?>
        
        <title><?php echo Html::encode(Yii::$app->setting->title);?> - Yönetici</title>
                
        <link rel="shortcut icon" href="<?php echo $bundle->baseUrl;?>/img/favicon.png">
        <!--<link href="<?=Yii::getAlias('@web')?>/strength-meter/css/strength-meter.min.css" media="all" rel="stylesheet" type="text/css" />-->
        
        <?php $this->head();?>

    </head>
    <body>
	
	
<div class="page-preloader js-page-preloader">
  <div class="page-preloader__desc">AutoVM</div>
  <div class="page-preloader__loader">
    <div class="page-preloader__loader-heading">Araçlar yükleniyor.</div>
    <div class="page-preloader__loader-desc">Lütfen Bekleyiniz.</div>
    <div class="progress progress-rounded page-preloader__loader-progress">
      <div id="page-loader-progress-bar" class="progress-bar bg-info" role="progressbar" style="width: 10%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
    </div>
  </div>
  <div class="page-preloader__copyright">SatanHosting TELEKOM WEB ÇÖZÜMLERİ</div>
</div>



  <div class="navbar navbar-light navbar-expand-lg">
  <button class="sidebar-toggler" type="button">
    <span class="ua-icon-sidebar-open sidebar-toggler__open"></span>
    <span class="ua-icon-alert-close sidebar-toggler__close"></span>
  </button>

  <span class="navbar-brand">
    <a href="/"><img src="<?php echo $bundle->baseUrl;?>/img/logo.png" alt="" class="navbar-brand__logo"></a>
  </span>


  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-collapse">
    <span class="ua-icon-navbar-open navbar-toggler__open"></span>
    <span class="ua-icon-alert-close navbar-toggler__close"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbar-collapse">
    <div class="navbar__menu">
      <ul class="navbar-nav">
        <li class="nav-item navbar__menu-item">
          <a class="nav-link text-white-navbar" href="<?php echo Yii::$app->urlManager->createUrl('/admin/default/setting');?>">AutoVM Ayarları</a>
        </li>
        <li class="nav-item navbar__menu-item">
          <a class="nav-link text-white-navbar" href="<?php echo Yii::$app->urlManager->createUrl('/admin');?>">Ana Sayfa</a>
        </li>
        <li class="nav-item navbar__menu-item">
          <a class="nav-link text-white-navbar" href="<?php echo Yii::$app->urlManager->createUrl('/site');?>">Kullanıcı Paneli</a>
        </li>
      </ul>
      <div class="navbar__menu-side">
        <div class="navbar__actions">
          <a href="<?php echo Yii::$app->urlManager->createUrl('/site/default/logout');?>" class="btn btn-danger icon-left subnav__header-side-item">
            Çıkış Yap <span class="btn-icon mdi mdi-cart"></span>
          </a>
        </div>
      </div>

    </div>
  </div>
</div>

<div class="page-wrap">


    <div class="sidebar-section">
  <div class="sidebar-section__scroll">

    <div>
      <div class="sidebar-section__separator"> AutoVM Admin</div>
      <ul class="sidebar-section-nav">
                    <?php $c = Yii::$app->controller->id; $a = Yii::$app->controller->action->id;?>
      <li class="sidebar-section-nav__item <?php echo ($c == 'default' && $a == 'index' ? 'is-active' : '');?>">
        <a class="sidebar-section-nav__link" href="<?php echo Yii::$app->urlManager->createUrl('/admin');?>">
          <span class="sidebar-section-nav__item-icon"><i class="fas fa-tachometer-alt"></i></span>
          <span class="sidebar-section-nav__item-text">Kontrol Merkezi</span>
        </a>
      </li>
        <li class="sidebar-section-nav__item <?php echo ($c == 'user' ? 'is-active' : '');?>">
          <a class="sidebar-section-nav__link sidebar-section-nav__link-dropdown" href="#">
          <span class="sidebar-section-nav__item-icon"><i class="fas fa-users-cog"></i></span>
            <span class="sidebar-section-nav__item-text">Kullanıcı İşlemleri</span>
          </a>
          <ul class="sidebar-section-subnav" <?php echo ($c == 'user' ? 'style="display:block;"' : '');?>>
            <li class="sidebar-section-subnav__item"><a class="sidebar-section-subnav__link" href="<?php echo Yii::$app->urlManager->createUrl('/admin/user/index');?>">Kullanıcıları Listele</a></li>
            <li class="sidebar-section-subnav__item"><a class="sidebar-section-subnav__link" href="<?php echo Yii::$app->urlManager->createUrl('/admin/user/create');?>">Kullanıcı Oluştur</a></li>
          </ul>
        </li>
        <li class="sidebar-section-nav__item <?php echo ($c == 'rdns' ? 'is-active' : '');?>">
          <a class="sidebar-section-nav__link sidebar-section-nav__link-dropdown" href="#">
          <span class="sidebar-section-nav__item-icon"><i class="fas fa-business-time"></i></span>
            <span class="sidebar-section-nav__item-text">Rdns İşlemleri</span>
            <span class="badge badge-danger sidebar-section-nav__badge">YENİ</span>
          </a>
          <ul class="sidebar-section-subnav" <?php echo ($c == 'rdns' ? 'style="display:block;"' : '');?>>
            <li class="sidebar-section-subnav__item"><a class="sidebar-section-subnav__link" href="<?php echo Yii::$app->urlManager->createUrl('/admin/rdns/index');?>">Rdns Kullanıcıları Listele</a></li>
            <li class="sidebar-section-subnav__item"><a class="sidebar-section-subnav__link" href="<?php echo Yii::$app->urlManager->createUrl('/admin/rdns/create');?>">Rdns Kullanıcı Oluştur</a></li>
            <li class="sidebar-section-subnav__item"><a class="sidebar-section-subnav__link" href="<?php echo Yii::$app->urlManager->createUrl('/admin/rdns/data');?>">Rdns Verileri</a></li>
            <li class="sidebar-section-subnav__item"><a class="sidebar-section-subnav__link" href="<?php echo Yii::$app->urlManager->createUrl('/admin/rdns/waitpending');?>">Rdns İşlem Bekleyenler</a></li>
            <li class="sidebar-section-subnav__item"><a class="sidebar-section-subnav__link" href="<?php echo Yii::$app->urlManager->createUrl('/admin/rdns/settings');?>">Rdns Ayarları</a></li>
          </ul>
        </li>
        <li class="sidebar-section-nav__item <?php echo ($c == 'plan' ? 'is-active' : '');?>">
          <a class="sidebar-section-nav__link sidebar-section-nav__link-dropdown" href="#">
          <span class="sidebar-section-nav__item-icon"><i class="fas fa-shapes"></i></span>
            <span class="sidebar-section-nav__item-text">Paket İşlemleri</span>
          </a>
          <ul class="sidebar-section-subnav"  <?php echo ($c == 'plan' ? 'style="display:block;"' : '');?>>
            <li class="sidebar-section-subnav__item"><a class="sidebar-section-subnav__link" href="<?php echo Yii::$app->urlManager->createUrl('/admin/plan/index');?>" >Paketleri Listele</a></li>
            <li class="sidebar-section-subnav__item"><a class="sidebar-section-subnav__link" href="<?php echo Yii::$app->urlManager->createUrl('/admin/plan/create');?>" >Paket Oluştur</a></li>
          </ul>
        </li>
        <li class="sidebar-section-nav__item <?php echo ($c == 'ip' ? 'is-active' : '');?>">
          <a class="sidebar-section-nav__link sidebar-section-nav__link-dropdown" href="#">
          <span class="sidebar-section-nav__item-icon"><i class="fas fa-location-arrow"></i></span>
            <span class="sidebar-section-nav__item-text">IP Adres</span>
            <span class="badge badge-lasur sidebar-section-nav__badge"><?=$stats->totalIp;?></span>
          </a>
          <ul class="sidebar-section-subnav"  <?php echo ($c == 'ip' ? 'style="display:block;"' : '');?>>
            <li class="sidebar-section-subnav__item"><a class="sidebar-section-subnav__link" href="<?php echo Yii::$app->urlManager->createUrl('/admin/ip/index');?>" >Ip Adreslerini Listele</a></li>
            <li class="sidebar-section-subnav__item"><a class="sidebar-section-subnav__link" href="<?php echo Yii::$app->urlManager->createUrl('/admin/ip/empty');?>" >Boş Ip Listele</a></li>
            <li class="sidebar-section-subnav__item"><a class="sidebar-section-subnav__link" href="<?php echo Yii::$app->urlManager->createUrl('/admin/server/index');?>" >Ip Adresi Oluştur</a></li>
          </ul>
        </li>
        <li class="sidebar-section-nav__item <?php echo ($c == 'server' ? 'is-active' : '');?>">
          <a class="sidebar-section-nav__link sidebar-section-nav__link-dropdown" href="#">
          <span class="sidebar-section-nav__item-icon"><i class="fas fa-server"></i></span>
            <span class="sidebar-section-nav__item-text">Fiziksel Sunucu</span>
            <span class="badge badge-warning sidebar-section-nav__badge"><?=$stats->totalServer;?></span>
          </a>
          <ul class="sidebar-section-subnav"  <?php echo ($c == 'server' ? 'style="display:block;"' : '');?>>
            <li class="sidebar-section-subnav__item"><a class="sidebar-section-subnav__link" href="<?php echo Yii::$app->urlManager->createUrl('/admin/server/index');?>" >Fiziksel Sunucuları Listele</a></li>
            <li class="sidebar-section-subnav__item"><a class="sidebar-section-subnav__link" href="<?php echo Yii::$app->urlManager->createUrl('/admin/server/create');?>" >Fiziksel Sunucu Oluştur</a></li>
          </ul>
        </li>
        <li class="sidebar-section-nav__item <?php echo ($c == 'datastore' ? 'is-active' : '');?>">
          <a class="sidebar-section-nav__link sidebar-section-nav__link-dropdown" href="#">
          <span class="sidebar-section-nav__item-icon"><i class="fas fa-hdd"></i></span>
            <span class="sidebar-section-nav__item-text">Disk Yönetimi</span>
          </a>
          <ul class="sidebar-section-subnav"  <?php echo ($c == 'datastore' ? 'style="display:block;"' : '');?>>
            <li class="sidebar-section-subnav__item"><a class="sidebar-section-subnav__link" href="<?php echo Yii::$app->urlManager->createUrl('/admin/datastore/index');?>" >Diskleri Listele</a></li>
            <li class="sidebar-section-subnav__item"><a class="sidebar-section-subnav__link" href="<?php echo Yii::$app->urlManager->createUrl('/admin/server/index');?>" >Disk Oluştur</a></li>
          </ul>
        </li>
        <li class="sidebar-section-nav__item <?php echo ($c == 'os' ? 'is-active' : '');?>">
          <a class="sidebar-section-nav__link sidebar-section-nav__link-dropdown" href="#">
          <span class="sidebar-section-nav__item-icon"><i class="fab fa-redhat"></i></span>
            <span class="sidebar-section-nav__item-text">İşletim Sistemleri</span>
          </a>
          <ul class="sidebar-section-subnav"  <?php echo ($c == 'os' ? 'style="display:block;"' : '');?>>
            <li class="sidebar-section-subnav__item"><a class="sidebar-section-subnav__link" href="<?php echo Yii::$app->urlManager->createUrl('/admin/os/index');?>" >İşletim Sistemlerini Listele</a></li>
            <li class="sidebar-section-subnav__item"><a class="sidebar-section-subnav__link" href="<?php echo Yii::$app->urlManager->createUrl('/admin/os/create');?>" >İşletim Sistemi Oluştur</a></li>
          </ul>
        </li>
        <li class="sidebar-section-nav__item <?php echo ($c == 'iso' ? 'is-active' : '');?>">
          <a class="sidebar-section-nav__link sidebar-section-nav__link-dropdown" href="#">
          <span class="sidebar-section-nav__item-icon"><i class="fas fa-compact-disc"></i></span>
            <span class="sidebar-section-nav__item-text">İSO Kalıp İşlemleri</span>
          </a>
          <ul class="sidebar-section-subnav"  <?php echo ($c == 'iso' ? 'style="display:block;"' : '');?>>
            <li class="sidebar-section-subnav__item"><a class="sidebar-section-subnav__link" href="<?php echo Yii::$app->urlManager->createUrl('/admin/iso/index');?>" >İSO Kalıplarını Listele</a></li>
            <li class="sidebar-section-subnav__item"><a class="sidebar-section-subnav__link" href="<?php echo Yii::$app->urlManager->createUrl('/admin/iso/create');?>" >İSO Kalıbı Oluştur</a></li>
          </ul>
        </li>
        <li class="sidebar-section-nav__item <?php echo ($c == 'vps' ? 'is-active' : '');?>">
          <a class="sidebar-section-nav__link sidebar-section-nav__link-dropdown" href="#">
          <span class="sidebar-section-nav__item-icon"><i class="fab fa-linux"></i></span>
            <span class="sidebar-section-nav__item-text">Sanal Sunucu</span>
            <span class="badge badge-danger sidebar-section-nav__badge"><?=$stats->totalVps;?></span>
          </a>
          <ul class="sidebar-section-subnav"  <?php echo ($c == 'vps' ? 'style="display:block;"' : '');?>>
            <li class="sidebar-section-subnav__item"><a class="sidebar-section-subnav__link" href="<?php echo Yii::$app->urlManager->createUrl('/admin/vps/index');?>" >Sanal Sunucuları Listele</a></li>
            <li class="sidebar-section-subnav__item"><a class="sidebar-section-subnav__link" href="<?php echo Yii::$app->urlManager->createUrl('/admin/user/index');?>" >Sanal Sunucu Oluştur</a></li>
          </ul>
        </li>
      <li class="sidebar-section-nav__item <?php echo ($c == 'default' && $a == 'login' ? 'is-active' : '');?>">
        <a class="sidebar-section-nav__link" href="<?php echo Yii::$app->urlManager->createUrl('admin/default/login');?>">
          <span class="sidebar-section-nav__item-icon"><i class="fas fa-history"></i></span>
          <span class="sidebar-section-nav__item-text">Giriş Geçmişi</span>
        </a>
      </li>
      <li class="sidebar-section-nav__item <?php echo ($c == 'api' ? 'is-active' : '');?>">
        <a class="sidebar-section-nav__link" href="<?php echo Yii::$app->urlManager->createUrl('admin/api/index');?>">
          <span class="sidebar-section-nav__item-icon"><i class="fas fa-robot"></i></span>
          <span class="sidebar-section-nav__item-text">Api Ayarları</span>
        </a>
      </li>
      <li class="sidebar-section-nav__item <?php echo ($c == 'default' && $a == 'setting' ? 'is-active' : '');?>">
        <a class="sidebar-section-nav__link" href="<?php echo Yii::$app->urlManager->createUrl('admin/default/setting');?>">
          <span class="sidebar-section-nav__item-icon"><i class="fas fa-tools"></i></span>
          <span class="sidebar-section-nav__item-text">Genel Ayarlar</span>
        </a>
      </li>
      <li class="sidebar-section-nav__item <?php echo ($c == 'default' && $a == 'mail' ? 'is-active' : '');?>">
        <a class="sidebar-section-nav__link" href="<?php echo Yii::$app->urlManager->createUrl('admin/default/mail');?>">
          <span class="sidebar-section-nav__item-icon"><i class="fas fa-envelope"></i></span>
          <span class="sidebar-section-nav__item-text">Mail Ayarları</span>
        </a>
      </li>
		
	</ul>

    </div>
  </div>

    <!--<div class="sidebar-section-nav__footer">
      <ul class="sidebar-section-nav">
        <li class="sidebar-section-nav__item sidebar-section-nav__item-btn mb-4">
          <a href="#" class="btn btn-info btn-block">Create project</a>
        </li>
      </ul>
      <div class="sidebar__collapse">
        <span class="icon ua-icon-collapse-left-arrows"></span>
      </div>
    </div>
  </div>
  -->
</div>


<div class="page-content">
    
<div class="container-fluid">
    
            <?php $this->beginBody();?>
                <?php echo $content;?>
            <?php $this->endBody();?>
            <!-- footer -->
			
</div>

</div>

</div>





    </body>
</html>
<?php $this->endPage();?>
