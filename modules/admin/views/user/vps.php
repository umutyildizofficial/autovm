<?php 
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Html;
?>

  <div class="page-content__header">
    <div>
      <h2 class="page-content__header-heading"><i class="fab fa-linux"></i> Sunucularını Listeliyorsunuz.</h2>
    </div>
  </div>


<!-- content -->
<div class="main-container">     
    <div class="col-md-12">
        <?php 
        Pjax::begin(['id' => 'pjax', 'enablePushState' => false]);
            echo GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    'id',
                    [
                        'label' => 'Sunucu',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return '<a href="' . Yii::$app->urlManager->createUrl(['/admin/server/edit', 'id' => $model->server->id]) . '">' . Html::encode($model->server->name) . '</a>';
                        }
                    ],
                    [
                        'label' => 'IP adresi',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return ($model->ip ? Html::encode($model->ip->ip) : ' IP Yok');
                        }
                    ],
                    [
                        'label' => 'İşletim Sistemi',
                        'value' => 'os.name',
                    ],
                    [
                        'label' => 'Paket',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return '<a href="' . Yii::$app->urlManager->createUrl(['/admin/plan/edit', 'id' => isset($model->plan->id)?$model->plan->id:'']) . '" class="btn btn-purple">' . Html::encode(isset($model->plan->name)?$model->plan->name:'') . ' </a>';
                        }
                    ],
                    [
                        'label' => 'Durum',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return ($model->getIsActive() ? '<b class="text-success"> Aktif</b>' : '<b class="text-danger"> Pasif</b>');
                        }
                    ],
                    [
                        'label' => 'Düzenle',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return '<a href="' . Yii::$app->urlManager->createUrl(['/admin/vps/edit', 'id' => $model->id]) . '" class="btn btn-warning"><i class="fa fa-edit"></i> Sunucu Düzenle</a>';
                        }
                    ],
                    [
                        'label' => 'Görüntüle',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return '<a href="' . Yii::$app->urlManager->createUrl(['/admin/vps/view', 'id' => $model->id]) . '" class="btn btn-success"><i class="fa fa-search"></i> Görüntüle</a>';
                        }
                    ],
                ],
            ]);
        Pjax::end();
        ?>
    </div>
</div>
<!-- END content -->