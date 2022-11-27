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
      <h2 class="page-content__header-heading"> Rdns İşlem Bekleyenler</h2>
    </div>
  </div>



<!-- content -->
<div class="main-container">     
    
        <?php echo Html::beginForm(Yii::$app->urlManager->createUrl('/admin/rdns/delete-pending'), 'post', ['class' => 'delete']);?>
        
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
                        'label' => 'İşlem',
                        'format' => 'raw',
                        'value' => function ($model) {
                            if($model->pending_type == 1){
								return '<label class="badge badge-success"> Eklenme bekliyor.</badge>';
							}
                            else if($model->pending_type == 2){
								return '<label class="badge badge-lasur"> Düzenlenme bekliyor.</badge>';
							}
                            else if($model->pending_type == 3){
								return '<label class="badge badge-danger"> Silinme bekliyor.</badge>';
							}
                        }
                    ],
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
							if($model->pending_type != 3){
								return '<a href="javascript:;" onclick="rdns_pending_edit('.$model->id.')" class="btn btn-primary"><i class="fas fa-edit"></i></a>';
							}
							else{
								return '<font color="red">İşlem <br> Yapılamaz</font>';
							}
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