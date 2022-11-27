<?php 
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
?>

  <div class="page-content__header">
    <div>
      <h2 class="page-content__header-heading"><i class="fab fa-linux"></i> Sanal Sunucular</h2>
    </div>
  </div>

<!-- content -->
<div class="main-container user">     
    <div class="col-md-12">

        <?php echo Html::beginForm(Yii::$app->urlManager->createUrl('/admin/vps/delete'));?>
        
        <?php 
        //Pjax::begin(['id' => 'pjax', 'enablePushState' => false]);
            echo GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    'id',
                    [
                        'label' => 'seçim',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return '<label class="checkbox"><input type="checkbox" name="data[]" value="' . $model->id . '"><span></span></label>';
                        }
                    ],
                    
                    [
                        'label' => 'sunucu',
                        'attribute' => 'server_id',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return '<a href="' . Yii::$app->urlManager->createUrl(['/admin/server/edit', 'id' => isset($model->server->id)?$model->server->id:'']) . '">' . Html::encode(isset($model->server->name)?$model->server->name:'') . '</a>';
                        }
                    ],
                    [
                        'attribute' => 'ip',
                        'label' => 'IP adresi',
                        'format' => 'raw',
                        'filterInputOptions' => ['class' => 'form-control', 'placeholder' => 'Arama..'],
                        'value' => function ($model) {
                            return ($model->ipf ? Html::encode($model->ip->ip) : ' IP Yok');
                        }
                    ],
                    [
                        'attribute' => 'email',
                        'label' => 'Kullanıcı Emaili',
                        'value' => 'user.email.email',
                        'filterInputOptions' => ['class' => 'form-control', 'placeholder' => 'Arama..'],
                    ],
                    [
                        'label' => 'İşletim Sistemi',
                        'value' => 'os.name',
                    ],
                    [
                        'label' => 'Paket',
                        'format' => 'raw',
                        'value' => function ($model) {
                            if(isset($model->plan->id))
                                return '<a href="' . Yii::$app->urlManager->createUrl(['/admin/plan/edit', 'id' => $model->plan->id]) . '" class="btn btn-warning">' . Html::encode($model->plan->name) . '</a>';
                            else
                                return '';
                        }
                    ],
                    [
                        'label' => 'Erişim',
                        'attribute' => 'status',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return ($model->getIsActive() ? '<b class="text-success"> Var</b>' : '<b class="text-danger"> Yok</b>');
                        }
                    ],
                    [
                        'label' => 'Sonlandır (!)',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return '<a class="terminate btn btn-danger" href="' . Yii::$app->urlManager->createUrl(['/admin/vps/terminate', 'id' => $model->id]) . '" ><i class="fas fa-trash"></i> </a>';   
                        }
                    ],
                    [
                        'label' => 'Düzenle',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return '<a href="' . Yii::$app->urlManager->createUrl(['/admin/vps/edit', 'id' => $model->id]) . '" class="btn btn-primary"><i class="fas fa-edit"></i></a>';
                        }
                    ],
                    [
                        'label' => 'Görüntüle',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return '<a href="' . Yii::$app->urlManager->createUrl(['/admin/vps/view', 'id' => $model->id]) . '" class="btn btn-purple"><i class="fas fa-search"></i></a>';
                        }
                    ],
                    [
                        'label' => 'giriş yap',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return '<a href="' . Yii::$app->urlManager->createUrl(['admin/user/login', 'id' => $model->user->id]) . '" class="btn btn-gray"><i class="fas fa-sign-in-alt"></i></a>';
                        }
                    ],                    
                ],
            ]);
        //Pjax::end();
        ?>
        
        <a href="<?php echo Yii::$app->urlManager->createUrl('/admin/user/index');?>" class="btn btn-primary waves-effect waves-light"><i class="fa fa-plus"></i>Oluştur</a>
        <button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i>Sil</button>
        <br><br><hr>
        <?php echo Html::endForm();?>
    </div>
</div>
<!-- END content -->

<?php

$js = <<<EOT

$(".terminate").click(function(e) {
    
    e.preventDefault();

    self = $(this);
        
    ok = confirm('Sunucuyu sonlandırmak istediğinizden emin misiniz?');
    
    if (ok) {

        self.text("Sonlandırılıyor..");

        $.getJSON(self.attr("href"), function(data) {
            if (data.ok) {
                window.location.href = window.location.href
            } else {
                alert("Bir hata oluştu");
            }

            self.text("Sonlandır");
        });
    }
});

EOT;

$this->registerJs($js);
