<?php
use yii\helpers\Url;
use yii\helpers\Html;

$this->registerJs('
    $("#iso").click(function(e){
    
        self = $(this);
        
        iso = $("input[name=iso]:checked").val();
        vpsId = ' . $vpsId . ';

        $.ajax({
            type:"POST",
            dataType:"JSON",
            url:"' . Yii::$app->urlManager->createUrl('/site/vps/iso') . '",
            data:{id:vpsId, iso:iso},

            beforeSend:function() {
            	new simpleAlert({title:"Yerleştiriliyor", content:"Lütfen bekleyiniz..."});
            },
            
            success:function(data) {
            	if(data.ok) {
                    new simpleAlert({title:"Eylem Durumu", content:"Başarıyla tamamlandı"});
                } else {
                    new simpleAlert({title:"Eylem Durumu", content:"Bir hata oluştu, lütfen tekrar deneyiniz"});
                }
            }
        });
    });
    
    $("#isou").click(function(e){
    
        self = $(this);
        
        vpsId = ' . $vpsId . ';

        $.ajax({
            type:"POST",
            dataType:"JSON",
            url:"' . Yii::$app->urlManager->createUrl('/site/vps/isou') . '",
            data:{id:vpsId},

            beforeSend:function() {
            	new simpleAlert({title:"Çıkarılıyor", content:"Lütfen bekleyiniz..."});
            },
            
            success:function(data) {
                if(data.ok) {
                    new simpleAlert({title:"Eylem Durumu", content:"Başarıyla tamamlandı"});
                } else {
                    new simpleAlert({title:"Eylem Durumu", content:"Bir hata oluştu, lütfen tekrar deneyiniz"});
                }
            }
        });
    });
');

?>



            <div class="row">
                <?php foreach ($items as $item) { ?>
                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
                        <label class="checkbox"><input type="radio" name="iso" value="<?php echo $item->id; ?>">
                            <span></span> <?php echo Html::encode($item->name); ?></label>
                    </div>
                <?php } ?>
            </div>
			
			<div class="form-group">
			
            <button class="btn btn-danger" id="iso" type="button">Şimdi yerleştir</button>
            <button class="btn waves-effect waves-light amber" id="isou" type="button" style="margin-left:10px;">Çıkar</button>
			
			</div>



