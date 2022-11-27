<?php 
use yii\helpers\Html;
use yii\widgets\Pjax;
use kartik\grid\GridView;
?>

  <div class="page-content__header">
    <div>
      <h2 class="page-content__header-heading">Boş Ip Listesi</h2>
    </div>
  </div>

<!-- content -->
<div class="main-container">     
        
        <?php echo Html::beginForm(Yii::$app->urlManager->createUrl('/admin/ip/delete'), 'post', ['class' => 'delete']);?>
        
        <?php 

            echo GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'pjax'=>true,
                'columns' => [
                    [
                        'label' => 'Seçim',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return '<label class="checkbox"><input type="checkbox" name="data[]" value="' . $model->id . '"><span></span></label>';
                        }
                    ],
                    [
                        'attribute' => 'server_id',
                        'label' => 'Sunucu',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return $model->server->name;
                        }
                    ],
                    [
                        'attribute' => 'ip', 'filterInputOptions' => ['class' => 'form-control', 'placeholder' => 'Arama..'],
                    ],
                    'gateway',
                    'netmask',
                    [
                        'label' => 'Mac adresi',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return "<input type='text' class='mac' value='".$model->mac_address."' data=".$model->id." >";
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
                            return '<a href="' . Yii::$app->urlManager->createUrl(['/admin/ip/edit', 'id' => $model->id]) . '" class="btn btn-success"><i class="fa fa-edit"></i> Düzenle</a>';
                        }
                    ],
                ],
                'export'=>false
            ]);
        ?>
        
        <a href="<?php echo Yii::$app->urlManager->createUrl('/admin/server/index');?>" class="btn btn-primary waves-effect waves-light"><i class="fa fa-plus"></i>Oluştur</a>
        <button type="button" class="btn btn-danger btn-delete"><i class="fas fa-trash"></i>Sil</button>
        <br><br><hr>
        
        <?php echo Html::endForm();?>
    </div>
<?php

$url = \yii\helpers\Url::to(['ip/index']);

$js = <<<EOT

jQuery('.btn-delete').click(function() {

    confirm = confirm('Seçilen ip yada ip adreslerine ait tüm sanal sunucular silinecektir');
    
    if (confirm) {
        jQuery('.delete').submit();
    }
});


$( ".mac" ).change(function() {

  self = $(this);

    var value=$(this).val();
    var id=$(this).attr('data');

    $.post( "$url", { id: id, value: value })
        .done(function( data ) {
            if(data==1)
            {
                self.parent().html("Veri kaydedildi");
            }
        });

});
EOT;

$this->registerJs($js);
