<?php use yii\helpers\Html;?>
<!-- content -->

  <div class="page-content__header">
    <div>
      <h2 class="page-content__header-heading"><i class="fas fa-history"></i> Giriş Geçmişi</h2>
    </div>
  </div>

<div class="main-container user">     
    <div class="col-md-12">

        <table class="table">
            <thead>
                <th>ID</th>
                <th>Kullanıcı</th>
                <th>Ip Adresi</th>
                <th>İşletim Sistemi</th>
                <th>Tarayıcı</th>
                <th>Oluşturulan</th>
                <th>Durum</th>
            </thead>
            <tbody>
            <?php foreach($logins as $login) {?>
                <tr>
                    <td><?php echo $login->id;?></td>
                    <td><a href="<?php echo Yii::$app->urlManager->createUrl(['/admin/user/edit', 'id' => $login->user->id]);?>"><?php echo Html::encode($login->user->getFullName());?></a></td>
                    <td><?php echo Html::encode($login->ip);?></td>
                    <td><?php echo Html::encode($login->os_name); ?></td>
                    <td><?php echo Html::encode($login->browser_name); ?></td>
                    <td><?php echo date('d M Y - H:i', $login->created_at);?></td>
                    <td><?php echo ($login->getIsSuccessful() ? ' Başarılı' : ' Başarısız');?></td>
                </tr>
            <?php }?>
            </tbody>
        </table>
        
        <?php echo \yii\widgets\LinkPager::widget(['pagination' => $pages]);?>
    </div>
</div>
<!-- END content -->