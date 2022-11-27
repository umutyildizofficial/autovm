<?php 
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
?>

  <div class="page-content__header">
    <div>
      <h2 class="page-content__header-heading">Kullanıcıları Listele</h2>
    </div>
  </div>

<div class="main-container user">
        
        <?php echo Html::beginForm(Yii::$app->urlManager->createUrl('/admin/user/delete'));?>

        <?php 
            echo GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    [
                        'label' => 'Seçim',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return '<label class="checkbox"><input type="checkbox" name="data[]" value="' . $model->id . '"><span></span></label>';
                        }
                    ],
                    'last_name',
                    [
                        'attribute' => 'Email',
                        'value' => 'email.email'
                    ],
                    [
                        'attribute' => 'is_admin',
                        'label' => 'Yetki',
                        'format' => 'raw',
                        'value' => function($model) {
                            return ($model->getIsAdmin() ? 'admin' : 'user');
                        }
                    ],
                    [
                        'label' => 'Sunucu',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return '<a href="' .Yii::$app->urlManager->createUrl(['/admin/vps/create', 'id' => $model->id]) . '" class="btn btn-danger">Oluştur</a>';
                        }
                    ],
                    [
                        'label' => 'Sunucular',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return '<a href="' . Yii::$app->urlManager->createUrl(['/admin/user/vps', 'id' => $model->id]) . '" class="btn btn-primary"><i class="fas fa-tv"></i> Sunucuları</a>';
                        }
                    ],
                    [
                        'label' => 'Düzenle',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return '<a href="' . Yii::$app->urlManager->createUrl(['/admin/user/edit', 'id' => $model->id]) . '" class="btn btn-success"><i class="fas fa-edit"></i> Kullanıcı Düzenle</a>';
                        }
                    ],
                    [
                        'label' => 'Şifre',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return '<a href="' . Yii::$app->urlManager->createUrl(['/admin/user/password', 'id' => $model->id]) . '" class="btn btn-warning"><i class="fas fa-key"></i> Şifre Değiştir</a>';
                        }
                    ],
                    [
                        'label' => 'giriş',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return '<a href="' . Yii::$app->urlManager->createUrl(['/admin/user/login', 'id' => $model->id]) . '" class="btn btn-primary"><i class="fas fa-sign-in-alt"></i> Giriş Yap</a>';
                        }
                    ],
                    [
                        'label' => 'güvenlik tokeni',
                        'format' => 'raw',
                        'value' => function($model) {
                            return '<a class="reset btn btn-danger" href="' . Yii::$app->urlManager->createUrl(['/admin/user/auth', 'id' => $model->id]) . '">Sıfırla</a>';
                        },
                    ],
                ],
            ]);
        ?>
        
        <a href="<?php echo Yii::$app->urlManager->createUrl('/admin/user/create');?>" class="btn btn-primary waves-effect waves-light"><i class="fa fa-plus"></i>Oluştur</a>
        <button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i>Sil</button>
        <br><br><hr>

        <?php echo Html::endForm();?>
    </div>

<?php

$js = <<<EOT

jQuery(".reset").click(function(e) {

    e.preventDefault();

    self = jQuery(this);

    jQuery.getJSON(self.attr("href"), function(data) {
        
        if (data.fine) {
            self.text("Tamamlandı");
        }
    });
});

EOT;

$this->registerJs($js);
