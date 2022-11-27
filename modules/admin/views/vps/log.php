<?php use yii\helpers\Html;?>

  <div class="page-content__header">
    <div>
      <h2 class="page-content__header-heading"><i class="fas fa-history"></i> Sanal Sunucu Eylemler</h2>
    </div>
  </div>

<!-- content -->
<div class="main-container">     
        <a href="<?php echo Yii::$app->urlManager->createUrl('/admin/vps/index');?>" class="btn btn-info"><i class="fa fa-plus"></i>Sunucu listesi</a>
        <table class="table">
            <thead>
                <th>ID</th>
                <th>Eylem</th>
                <th>Açıklama</th>
                <th>Oluşturulma</th>
            </thead>
            <tbody>
            <?php foreach($logs as $log) {?>
                <tr>
                    <td><?php echo $log->id;?></td>
                    <td>
                    <?php if($log->getIsInstall()) {?>
                    Format / Kurulum
                    <?php } elseif ($log->getIsStart()) {?>
                    Başlat
                    <?php } elseif ($log->getIsStop()) {?>
                    Durdur
                    <?php } else if($log->getIsRestart()) {?>
                    Yeniden Başlat
                    <?php } else {?>
                    Yok
                    <?php }?>
                    </td>
                    <td><?php echo ($log->description ? Html::encode($log->description) : 'Yok');?></td>
                    <td><?php echo date('d M Y - H:i', $log->created_at);?></td>
                </tr>
            <?php }?>
            </body>
        </table>
        
        <?php echo \yii\widgets\LinkPager::widget(['pagination' => $pages]);?>
</div>
<!-- END content -->