<?php
use yii\helpers\Html;

$this->registerJs('
    $(function(){
        var vpsId = ' . $vpsId . ';
        $(".pagination a").click(function(e){
            e.preventDefault();

            url = $(this).attr("href");
            $.ajax({
                url:url,
                type:"POST",
                dataType:"HTML",
                data:{vpsId:vpsId},
                success:function(data){
                    $("#data").html(data);
                }
            });
        });
    });
');
?>

<div id="data">

<table class="table">
    <thead><th>İsim</th> <th>Açıklama</th> <th>Oluşturulma</th></thead>
    <tbody>
        <?php foreach($actions as $action) {?>
            <tr>
                <td>
                <?php if($action->getIsStart()) {?>
                    Başlat
                <?php } elseif ($action->getIsStop()) {?>
                    Durdur
                <?php } elseif ($action->getIsRestart()) {?>
                    Yeniden Başlat
                <?php } elseif ($action->getIsInstall()) {?>
                    Yükle
                <?php }?>
                </td>
                <td><?php echo Html::encode($action->description ? $action->description : 'YOK');?></td>
                <td><?php echo date('d M Y - H:i', $action->created_at);?></td>
            </tr>
        <?php }?>
    </tbody>
</table>

<?php echo \yii\widgets\LinkPager::widget(['pagination' => $pages]);?>

</div>