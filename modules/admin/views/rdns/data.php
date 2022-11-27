<?php 
use yii\helpers\Html;
use yii\widgets\Pjax;
use kartik\grid\GridView;

$this->registerJsFile(
    '@web/adminassets/js/renderajax.js',
    ['depends' => [\yii\web\JqueryAsset::className()]]
);


?>

  <div class="page-content__header">
    <div>
      <h2 class="page-content__header-heading"> Rdns Verileri</h2>
    </div>
  </div>



<!-- content -->
<div class="main-container">     
    
        <?php echo Html::beginForm(Yii::$app->urlManager->createUrl('/admin/rdns/delete-data'), 'post', ['class' => 'delete']);?>
        
        <?php 
            echo GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'pjax'=>true,
                'columns' => [
                    [
                        'label' => 'seçim',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return '<label class="checkbox"><input type="checkbox" name="data[]" value="' . $model->id . '"><span></span></label>';
                        }
                    ],
                    [
                        'label' => 'Rdns Kullanıcı Adı',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return $model->rdns->rdns_username;
                        }
                    ],
					'rdns_server_id',
                    [
                        'attribute' => 'ana_ip', 'filterInputOptions' => ['class' => 'form-control', 'placeholder' => 'Arama..'],
                    ],
                    [
                        'attribute' => 'type', 'filterInputOptions' => ['class' => 'form-control', 'placeholder' => 'Arama..'],
                    ],
                    [
                        'attribute' => 'content', 'filterInputOptions' => ['class' => 'form-control', 'placeholder' => 'Arama..'],
                    ],
					'priority',
					'ttl',
                    [
                        'label' => 'Düzenle',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return '<a href="javascript:;" onclick="rdns_data_edit('.$model->id2.')" class="btn btn-primary"><i class="fas fa-edit"></i></a>';
                        }
                    ],
                ],
                'export'=>false
            ]);
        ?>
        	
        <button type="button" class="btn btn-danger btn-delete"><i class="fas fa-trash"></i>Sil</button>
        <br><br><hr>
        <?php echo Html::endForm();?>
</div>
<!-- END content -->
<?php

$js = <<<EOT

jQuery('.btn-delete').click(function() {

    confirm = confirm('Seçilen veri yada verileri silmek istediğinizden emin misiniz ?');
    
    if (confirm) {
        jQuery('.delete').submit();
    }
});

EOT;

$this->registerJs($js);