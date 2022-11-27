<?php use yii\helpers\Html;?>
<!-- content -->

  <div class="page-content__header">
    <div>
      <h2 class="page-content__header-heading"><i class="fas fa-robot"></i> Api Listesi</h2>
    </div>
  </div>

<div class="main-container">     
    <div class="col-md-12">
        <?php echo Html::beginForm(Yii::$app->urlManager->createUrl('/admin/api/delete'));?>
            <a href="<?php echo Yii::$app->urlManager->createUrl('/admin/api/create');?>" class="btn btn-primary waves-effect waves-light"><i class="fa fa-plus"></i>Oluştur</a>
            <button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i>Sil</button>
            <table class="table table-bordered">
                <thead>
                    <th>ID</th>
                    <th>Seçim</th>
                    <th>Anahtar</th>
                    <th>Oluşturulma</th>
                    <th>Güncellenme</th>
                    <th>Loglar</th>
                </thead>
                <tbody>
                <?php foreach($apis as $api) {?>
                    <tr>
                        <td><?php echo $api->id;?></td>
                        <td><label class="checkbox"><input type="checkbox" name="data[]" value="<?php echo $api->id;?>"><span></span></label></td>
                        <td><?php echo Html::encode($api->key);?></td>
                        <td><?php echo date('d M Y - H:i', $api->created_at);?></td>
                        <td><?php echo date('d M Y - H:i', $api->updated_at);?></td>
                        <td><a href="<?php echo Yii::$app->urlManager->createUrl(['/admin/api/log', 'id' => $api->id]);?>"><i class="fa fa-search"></i></a></td>
                    </tr>
                <?php }?>
                </tbody>
            </table>
        <?php echo Html::endForm();?>
        
        <?php echo \yii\widgets\LinkPager::widget(['pagination' => $pages]);?>
    </div>
</div>
<!-- END content -->