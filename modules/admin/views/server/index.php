<?php 
use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\grid\GridView;
?>

  <div class="page-content__header">
    <div>
      <h2 class="page-content__header-heading"><i class="fas fa-server"></i> Fiziksel Sunucu Listesi </h2>
    </div>
  </div>

<!-- content -->
       <div class="main-container"> 
           
            <?php echo Html::beginForm(Yii::$app->urlManager->createUrl('/admin/server/delete'));?>
           
        <?php 
        Pjax::begin(['id' => 'pjax', 'enablePushState' => false]);
            echo GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    [
                        'label' => 'seçim',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return '<label class="checkbox"><input type="checkbox" name="data[]" value="' . $model->id . '"><span></span></label>';
                        }
                    ],
                    'id',
                    'name',
                    ['attribute' => 'ip', 'filterInputOptions' => ['class' => 'form-control', 'placeholder' => 'Arama..']],
                    'port',
                    [
                        'label' => 'Eylemler',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return '<a href="' . Yii::$app->urlManager->createUrl(['/admin/ip/create', 'id' => $model->id]) . '" class="btn btn-purple"><i class="fas fa-location-arrow"></i> IP Ekle</a>  <a href="' .Yii::$app->urlManager->createUrl(['/admin/datastore/create', 'id' => $model->id]) . '" class="btn btn-danger"><i class="fas fa-hdd"></i> Disk Ekle</a>';
                        }
                    ],
                    [
                        'label' => 'Düzenle',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return '<a href="' . Yii::$app->urlManager->createUrl(['/admin/server/edit', 'id' => $model->id]) . '" class="btn btn-warning"><i class="fa fa-edit"></i> Düzenle</a>';
                        }
                    ],
                    [
                        'label' => 'Görüntüle',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return '<a href="' . Yii::$app->urlManager->createUrl(['/admin/server/view', 'id' => $model->id]) . '" class="btn btn-primary"><i class="fa fa-search"></i> Görüntüle</a>';
                        }
                    ],
                ],
            ]);
        Pjax::end();
        ?>
       
        <a href="<?php echo Yii::$app->urlManager->createUrl('/admin/server/create');?>" class="btn btn-primary waves-effect waves-light"><i class="fa fa-plus"></i>Oluştur </a>
        <button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i>Sil</button>
        <br><br><hr>
        <?php echo Html::endForm();?>
</div>
<!-- END content -->
