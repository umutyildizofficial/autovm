<?php
use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\grid\GridView;
?>

  <div class="page-content__header">
    <div>
      <h2 class="page-content__header-heading"><i class="fas fa-history"></i> Sistem Logları</h2>
    </div>
  </div>

<!-- content -->
<div class="main-container user">
    <div class="col-md-12">

        <?php
        Pjax::begin(['id' => 'pjax', 'enablePushState' => false]);
            echo GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    'id',
                    'description',
                    [
                        'label' => 'oluşturulma',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return date('d M Y - H:i', $model->created_at);
                        }
                    ],
                ],
            ]);
        Pjax::end();
        ?>
    </div>
</div>
<!-- END content -->
