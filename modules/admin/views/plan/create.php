<?php use yii\widgets\ActiveForm;?>

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
<!-- content -->

  <div class="page-content__header">
    <div>
      <h2 class="page-content__header-heading">Paket Ekle</h2>
    </div>
  </div>
  
  
  <div class="main-container">
        <?php echo \app\widgets\Alert::widget();?>
        <?php $form = ActiveForm::begin(['enableClientValidation' => true]);?>
  
  
            <?php echo $form->field($model, 'name');?>
            <?php echo $form->field($model, 'ram')->label('RAM Miktarı (MB)');?>
            <?php echo $form->field($model, 'cpu_mhz')->label('CPU Hızı (MHZ)');?>
            <?php echo $form->field($model, 'cpu_core')->label('CPU Çekirdeği');?>
            <?php echo $form->field($model, 'hard')->label('Disk Alanı (GB)');?>
            <?php echo $form->field($model, 'band_width')->label('Trafik (GB)');?>
            <?php echo $form->field($model, 'os_lists')->dropDownList(\app\models\Plan::getOperationSystem(), array('multiple' => 'multiple', 'class' => 'selectbox', 'id' => 'selectbox-ex1'));?>
            <?php echo $form->field($model, 'is_public')->dropDownList(\app\models\Plan::getPublicYesNo());?>
            <?php echo $form->field($model, 'network')->dropDownList(\app\models\Plan::getNetworkSettings());?>
            <div class="form-group">
                <button type="submit" class="btn btn-primary waves-effect waves-light">Kaydet</button>
                <button type="reset" class="btn btn-danger">Sıfırla</button>
            </div>
        <?php ActiveForm::end();?>

  
  </div>
  
  
