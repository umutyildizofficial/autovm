<?php 
use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\grid\GridView;
?>

  <div class="page-content__header">
    <div>
      <h2 class="page-content__header-heading"><i class="fas fa-compact-disc"></i> İSO Kalıpları</h2>
    </div>
  </div>

<!-- content -->
<div class="main-container user">     
    <div class="col-md-12">

        
        <?php echo Html::beginForm(Yii::$app->urlManager->createUrl('/admin/iso/delete'));?>

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
                    'path',

                    [
                        'label' => 'Düzenle',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return '<a href="' . Yii::$app->urlManager->createUrl(['/admin/iso/edit', 'id' => $model->id]) . '" class="btn btn-purple"><i class="fa fa-edit"></i> Düzenle</a>';
                        }
                    ],
                ],
            ]);
        Pjax::end();
        ?>
                
        <a href="<?php echo Yii::$app->urlManager->createUrl('/admin/iso/create');?>" class="btn btn-primary waves-effect waves-light"><i class="fa fa-plus"></i>Oluştur</a>
        <button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i>Sil</button>
        <br><br><hr>
        <?php echo Html::endForm();?>
    </div>
</div>
<!-- END content -->
