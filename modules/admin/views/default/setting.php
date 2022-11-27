<?php
use yii\helpers\Url;
use yii\widgets\ActiveForm;

?>

<div class="main-container">
    
        <div class="userstyle" style="height:auto!important;min-height:auto!important">
            <div class="title_up"><h3>Cron işleri</h3></div>
            <p style="line-height:30px;">Aşağıda ki komutları cron işlerine ekleyiniz</p>
            <p style="font-size:12px;line-height:30px;">*/5 * * * php <?php echo Yii::getAlias('@app');?>/yii cron/index</p>
            <p style="font-size:12px;line-height:30px;">0 0 * * php <?php echo Yii::getAlias('@app');?>/yii cron/reset</p>
        </div>
		
		</div>
		
		<div class="row">
    <div class="col-md-6">
    <div class="main-container">
    <div class="userstyle">
        <div class="title_up"><h3>Ayarlar</h3></div>

        <?php $form = ActiveForm::begin(['enableClientValidation' => true]);?>
            <?php echo $form->field($model, 'title');?>
            <?php echo $form->field($model, 'language')->dropDownList(Yii::$app->lang->langs);?>
            <?php echo $form->field($model, 'terminate')->dropDownList([1 => 'Evet', 2 => 'Hayır']);?>
            <?php echo $form->field($model, 'change_limit');?>

            <div class="margin-top-10"></div>
            <div class="margin-top-10"></div>

            <div class="form-group">
                <button type="submit" class="btn btn-success waves-effect waves-light">Kaydet</button>
                <a href="<?php echo Url::toRoute(['log/index']);?>" class="btn btn-primary waves-effect waves-light">Sistem logları</a>
            </div>
        <?php ActiveForm::end();?>
    </div>   </div> </div>
    <div class="col-md-6">
    <div class="main-container">
        <div class="userstyle" style="height:auto!important;min-height:auto!important">
            <div class="title_up"><h3>Konfigürasyon</h3></div>
            <p style="line-height:30px;">Tüm ayarların "TAMAM" olması gerekmektedir</p>
            <p style="line-height:30px;">php exec <span style="float:right;"><?php echo (function_exists('exec') ? 'TAMAM' : 'DÜZELTİLMELİ');?></span></p>
            <p style="line-height:30px;">php allow_url_fopen <span style="float:right;"><?php echo (ini_get('allow_url_fopen') ? 'TAMAM' : 'DÜZELTİLMELİ');?></span></p>
            <p style="line-height:30px;">php zip extension <span style="float:right;"><?php echo (extension_loaded('zip') ? 'TAMAM' : 'DÜZELTİLMELİ');?></span></p>
            <p style="line-height:30px;">php max_execution_time <span style="float:right;"><?php echo ini_get('max_execution_time');?></span></p>
        </div>
    </div>
    </div>
	
	</div>



<style>
.form-control{
    display:inline-block;
}
</style>
