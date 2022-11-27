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

  <div class="page-content__header">
    <div>
      <h2 class="page-content__header-heading">Rds Kullanıcı Düzenle</h2>
    </div>
  </div>
  
  
  <div class="main-container">
        <?php echo \app\widgets\Alert::widget();?>
        <?php $form = ActiveForm::begin(['enableClientValidation' => true]);?>
			<?php
				$rdns_types = json_decode($model->rdns_types);
				$model->rdns_types = $rdns_types;
			?>
  
            <?php echo $form->field($model, 'rdns_url')->label('Rdns Url Adresi');?>
            <?php echo $form->field($model, 'rdns_username')->label('Rdns Kullanıcı Adı');?>
            <?php echo $form->field($model, 'rdns_password')->label('Rdns Şifre');?>
            <?php echo $form->field($model, 'rdns_language')->dropDownList(\app\models\Rdns::getLanguageRdns());?>
            <?php echo $form->field($model, 'rdns_types')->dropDownList(\app\models\Rdns::getTypes(), array('multiple' => 'multiple', 'class' => 'selectbox', 'id' => 'selectbox-ex1'));?>
            <?php echo $form->field($model, 'rdns_ids')->label('Rdns Fiziksel Sunucu ID leri. ( Kullanıcının içinde 1 den fazla sunucu var ise , ile ayırınız. Örnek : 1,2)');?>
            <div class="form-group">
                <button type="submit" class="btn btn-success waves-effect waves-light">Düzenle</button>
                <button type="reset" class="btn btn-danger">Sıfırla</button>
            </div>
        <?php ActiveForm::end();?>

  
  </div>
  
  
