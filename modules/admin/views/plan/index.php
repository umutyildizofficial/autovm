<?php 
use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\grid\GridView;
?>

  <div class="page-content__header">
    <div>
      <h2 class="page-content__header-heading"> Paket Listesi</h2>
    </div>
  </div>



<!-- content -->
<div class="main-container user">     
    <div class="col-md-12">
    
        <?php echo Html::beginForm(Yii::$app->urlManager->createUrl('/admin/plan/delete'));?>
        
        <?php 
        Pjax::begin(['id' => 'pjax', 'enablePushState' => false]);
            echo GridView::widget([
                'dataProvider' => $dataProvider,
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
                    'ram',
                    'cpu_mhz',
                    'cpu_core',
                    'hard',
                    [
                        'attribute' => 'band_width',
                        'label' => 'Trafik (GB)',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return ($model->band_width);
                        }
                    ],
                    [
                        'attribute' => 'is_public',
                        'label' => 'Herkese açık mı?',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return ($model->getIsPublic() ? '<b class="text-success">Evet</b>' : '<b class="text-danger">Hayır</b>');
                        }
                    ],
                    [
                        'label' => 'Düzenle',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return '<a href="' . Yii::$app->urlManager->createUrl(['/admin/plan/edit', 'id' => $model->id]) . '" class="btn btn-primary"><i class="fas fa-edit"></i> Düzenle</a>';
                        }
                    ],
                ],
            ]);
            Pjax::end();
        ?>
        	
        <a href="<?php echo Yii::$app->urlManager->createUrl('/admin/plan/create');?>" class="btn btn-primary waves-effect waves-light"><i class="fa fa-plus"></i>Oluştur</a>
        <button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i>Sil</button>
        <br><br><hr>
        <?php echo Html::endForm();?>
    </div>
</div>
<!-- END content -->
<?php

$js = <<<EOT

jQuery('form').submit(function() {

    return confirm('Seçilen planlara ait tüm sanal sunucular silinecek')
});

EOT;

$this->registerJs($js);