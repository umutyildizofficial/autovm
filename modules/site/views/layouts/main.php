<?php
use yii\helpers\Html;

$this->beginPage();

$bundle = \app\modules\site\assets\Asset::register($this);

// website base url
//$baseUrl = Yii::$app->request->baseUrl . '/';
$baseUrl = rtrim(\yii\helpers\Url::to('/', true),'/') . Yii::$app->request->baseUrl . '/';
$this->registerJs("var baseUrl = \"{$baseUrl}\";", \yii\web\View::POS_END);

?>

<?php $this->beginPage();?>

<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="author" content="nikivm">
        <title>SatanHosting Sunucu Kontrol Paneli</title>
        <!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>-->
        <!--<script type="text/javascript" src="<?= \yii\helpers\Url::base() ?>/js/pwstrength.js"></script>-->
        <link rel="shortcut icon" href="<?php echo $bundle->baseUrl;?>/img/favicon.png">
        <?php echo Html::csrfMetaTags();?>
        <?php $this->head();?>
    </head>
    <body><?php echo (Yii::$app->lang->getIsRtl() ? ' class="rtl"' : '');?>
    <?php $this->beginBody();?>
        <?php if(!Yii::$app->user->isGuest) {echo \app\modules\site\widgets\UserVpsList::widget();}?>


<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
		<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
              <span class="sr-only"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>	
      <a class="navbar-brand" href="#"><img src="<?php echo $bundle->baseUrl;?>/img/logo.png" style="margin-top: -18px;
    width: 153px;" width="150"></a>
    </div>
	<div id="navbar" class="navbar-collapse collapse">
    <ul class="nav navbar-nav navbar-right">
      <?php $controller = Yii::$app->controller->id; $action = Yii::$app->controller->action->id;?>
                    <?php if(Yii::$app->user->isGuest) {?>
					
					
					<li class="dropdown">
							<a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="fas fa-globe"></i> <?php echo Yii::t('app', 'Language');?>
							<span class="caret"></span></a>
							<ul class="dropdown-menu">
					<?php foreach(Yii::$app->lang->langs as $id => $name) {?>
				  		<li><a href="<?php echo Yii::$app->urlManager->createUrl(['/site/default/lang', 'id' => $id, 'now' => time()]);?>"><?php echo $name;?></a></li>
					<?php }?>
							</ul>
						  </li>

						<li><a href="<?php echo Yii::$app->urlManager->createUrl('/site');?>"><i class="fas fa-home"></i> <?php echo Yii::t('app', 'Home');?></a></li>
                        <li<?php echo $controller == 'site' && $action == 'login' ? ' class="active"' : '';?>><a href="<?php echo Yii::$app->urlManager->createUrl(['/site/default/login']);?>"><i class="fas fa-user"></i> <?php echo Yii::t('app', 'Login');?></a></li>
                        <li<?php echo $controller == 'site' && $action == 'lost-password' ? ' class="active"' : '';?>><a href="<?php echo Yii::$app->urlManager->createUrl(['site/default/lost-password']);?>"><i class="fas fa-lock"></i> <?php echo Yii::t('app', 'Lost Password');?></a></li>
                    <?php } else {?>
                        <li<?php echo $controller == 'user' && $action == 'index' ? ' class="active"' : '';?>><a href="<?php echo Yii::$app->urlManager->createUrl(['/site/user/index']);?>"><i class="fas fa-tachometer-alt"></i> <?php echo Yii::t('app', 'Dashboard');?></a></li>
                        <li<?php echo $controller == 'user' && $action == 'profile' ? ' class="active"' : '';?>><a href="<?php echo Yii::$app->urlManager->createUrl(['/site/setting/index']);?>"><i class="fas fa-user"></i> <?php echo Yii::t('app', 'Profile');?></a></li>
                        <li<?php echo $controller == 'user' && $action == 'login' ? ' class="active"' : '';?>><a href="<?php echo Yii::$app->urlManager->createUrl(['/site/user/login']);?>"><i class="fas fa-history"></i> <?php echo Yii::t('app', 'Login History');?></a></li>
                        <?php if(Yii::$app->user->identity->getIsAdmin()) {?>
                            <li><a href="<?php echo Yii::$app->urlManager->createUrl('/admin');?>"><i class="fas fa-user-shield"></i> <?php echo Yii::t('app', 'Admin');?></a></li>
                        <?php }?>
                        <li><a href="<?php echo Yii::$app->urlManager->createUrl(['/site/default/logout']);?>"><i class="fas fa-power-off"></i> <?php echo Yii::t('app', 'Logout');?></a></li>
                    <?php }?>
    </ul>
  </div>
  </div>
</nav>

        <?php echo $content;?>
		
        <div class="footer z-depth-3">
            <footer class="page-footer light-blue darken-1">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-6 col-xs-12">
                            <h3 class="white-text"><?php echo Yii::t('app', 'AutoVM System');?></h3>
                            <p class="grey-text text-lighten-4">Sunucu otomasyon sistemi, SatanHosting müşterilerine sanal sunucu yönetiminde tam yönetim sağlamaktadır.</p></p>
                        </div>
                        <div class="col-lg-4 col-xs-12">
                            <h3 class="white-text"><?php echo Yii::t('app', 'Useful Links');?></h3>
                            <ul>
                                <li><a class="grey-text text-lighten-3" href="https://www.SatanHosting.com.tr/blog" target="_blank"><?php echo Yii::t('app', 'Blog');?></a></li>
                                <li><a class="grey-text text-lighten-3" href="https://www.SatanHosting.com.tr/hakkimizda.html" target="_blank"><?php echo Yii::t('app', 'Hakkında');?></a></li>
                                <li><a class="grey-text text-lighten-3" href="https://www.SatanHosting.com.tr/iletisim" target="_blank"><?php echo Yii::t('app', 'İletişim');?></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="footer-copyright">
                    <div class="container">
                        <?php echo Yii::t('app', '© 2020 SatanHosting Telekomünikasyon Bilgi Teknolojileri, tüm hakları saklıdır.');?>
                        <a class="grey-text text-lighten-4 right" href="https://www.SatanHosting.com.tr" target="_blank">SatanHosting</a>
                    </div>
                </div>
            </footer>
        </div>
		
		

    <?php $this->endBody();?>
	
	        <script>
            $(document).ready(function (e) {
				$('.dropdown-button').dropdown();
                $(".button-collapse").sideNav();
            });
        </script>

	
    </body>
</html>
<?php $this->endPage();?>


<?php echo Yii::t('app', '
');?>
