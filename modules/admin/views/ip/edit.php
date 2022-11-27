<?php use yii\widgets\ActiveForm;?>
<!-- content -->

  <div class="page-content__header">
    <div>
      <h2 class="page-content__header-heading"><?=$model->ip;?> </h2>
    </div>
  </div>

<div class="main-container">
        <?php echo \app\widgets\Alert::widget();?>
        <?php $form = ActiveForm::begin(['enableClientValidation' => true]);?>
            <?php echo $form->field($model, 'ip');?>
            <?php echo $form->field($model, 'gateway');?>
            <?php echo $form->field($model, 'netmask');?>
            <?php echo $form->field($model, 'mac_address');?>
            <?php echo $form->field($model, 'is_public')->dropDownList(\app\models\Ip::getPublicYesNo());?>
            <?php echo $form->field($model, 'network')->dropDownList(\app\models\Ip::getNetworks());?>
            <?php echo $form->field($model, 'is_dhcp')->dropDownList(\app\models\Ip::getDhcpYesNo());?>

            <div class="margin-top-10"></div>
            <div class="margin-top-10"></div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary waves-effect waves-light">Kaydet</button>
                <button type="reset" class="btn btn-danger">Sıfırla</button>
            </div>
        <?php ActiveForm::end();?>
</div>
<!-- END content -->
