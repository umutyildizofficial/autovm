<?php
use yii\helpers\Url;
use yii\widgets\ActiveForm;

?>

<?php

$this->registerJsFile(
    '@web/adminassets/vendor/sumo-select/jquery.sumoselect.min.js',
    ['depends' => [\yii\web\JqueryAsset::className()]]
);

$this->registerCssFile(
    '@web/adminassets/vendor/sumo-select/sumoselect.min.css',
    ['depends' => [yii\bootstrap\BootstrapAsset::className()]]
);


$this->registerJs('!function(e){"use strict";e(document).ready(function(){e("#selectbox-ex1").SumoSelect(),e("#selectbox-ex2").SumoSelect(),e("#selectbox-ex3").SumoSelect({okCancelInMulti:!0}),e("#selectbox-ex4").SumoSelect({selectAll:!0})})}(jQuery);');


?>

<style>

.margin-top-10{
	margin-top:45px;
}

</style>

<div class="main-container">
    
            <div class="title_up"><h3>Cron işleri</h3></div>
            <p style="line-height:30px;">Aşağıda ki komutları cron işlerine ekleyiniz</p>
            <p style="font-size:12px;line-height:30px;">*/10 * * * php <?php echo Yii::getAlias('@app');?>/yii cron/rdns</p>
		
		</div>
		
<div class="main-container">
    
		<?php
			$model->SatanHosting_one_data_types = json_decode($model->SatanHosting_one_data_types);
			if(!is_array($model->SatanHosting_one_data_types)){
				$model->SatanHosting_one_data_types = array();
			}
		?>
	
        <?php $form = ActiveForm::begin(['enableClientValidation' => true]);?>
			<?php echo $form->field($model, 'SatanHosting_one_data_delete')->checkBox(['value' => 1]);?>
			<hr>
            <?php echo $form->field($model, 'SatanHosting_one_data_types')->dropDownList(\app\models\Rdns::getTypes(), array('multiple' => 'multiple', 'class' => 'selectbox', 'id' => 'selectbox-ex1'));?>
			<div style="clear:both;"></div>
            <div class="margin-top-10"></div>

            <div class="form-group">
                <button type="submit" class="btn btn-success waves-effect waves-light">Kaydet</button>
            </div>
        <?php ActiveForm::end();?>
		
		</div>
		



<style>
.form-control{
    display:inline-block;
}
</style>
