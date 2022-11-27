<?php use yii\widgets\ActiveForm;?>
<!-- content -->

  <div class="page-content__header">
    <div>
      <h2 class="page-content__header-heading"><i class="fas fa-server"></i> <?=$model->name;?> Adlı Fiziksel Sunucu </h2>
    </div>
  </div>

<div class="main-container">
        <?php echo \app\widgets\Alert::widget();?>
        <?php $form = ActiveForm::begin(['enableClientValidation' => true]);?>
            <?php echo $form->field($model, 'name');?>
            <?php echo $form->field($model, 'ip');?>
            <?php echo $form->field($model, 'port');?>
            <?php echo $form->field($model, 'username');?>
            <?php echo $form->field($model, 'password')->passwordInput();?>
            <?php echo $form->field($model, 'network')->label('VM Network Adı');?>
            <?php echo $form->field($model, 'second_network')->label('İkincil Network Adı');?>
            <?php echo $form->field($model, 'version')->label('VM Sunucu Sürümü')->dropDownList(\app\models\Server::getVersionList());?>
            <?php echo $form->field($model, 'vcenter_ip');?>
            <?php echo $form->field($model, 'vcenter_username');?>
            <?php echo $form->field($model, 'vcenter_password')->passwordInput();?>
            <?php echo $form->field($model, 'parent_id')->label('IP Adresleri:')->dropDownList(\app\models\Server::getListData(), ['prompt' => 'Yok']);?>
			<?php echo $form->field($model, 'virtualization')->dropDownList(\app\models\Server::getVirtualizationList());?>


            <?php echo $form->field($model, 'dns1');?>
            <?php echo $form->field($model, 'dns2');?>

            <?php echo $form->field($model, 'server_address');?>

            <div class="margin-top-10"></div>
            <div class="margin-top-10"></div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary waves-effect waves-light">Kaydet</button>
                <button type="reset" class="btn btn-danger">Sıfırla</button>
            </div>
        <?php ActiveForm::end();?>
</div>
<!-- END content -->
