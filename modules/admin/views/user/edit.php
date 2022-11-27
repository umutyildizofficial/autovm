<?php use yii\widgets\ActiveForm;?>
<!-- content -->

  <div class="page-content__header">
    <div>
      <h2 class="page-content__header-heading"><?="{$user->first_name} {$user->last_name}";?> düzenle</h2>
    </div>
  </div>


<div class="main-container">     
        <?php echo \app\widgets\Alert::widget();?>
        <?php $form = ActiveForm::begin(['enableClientValidation' => true]);?>
            <?php echo $form->field($user, 'first_name');?>
            
            <?php echo $form->field($user, 'last_name');?>
            
          
            
            
            <?php echo $form->field($user, 'is_admin')->dropDownList(\app\models\User::getAdminYesNo());?>
            
            <?php echo $form->field($user, 'status')->dropDownList(\app\models\User::getStatusList());?>

            <div class="margin-top-10"></div>
            <div class="margin-top-10"></div>
            
            <div class="form-group">
                <button type="submit" class="btn btn-primary waves-effect waves-light">Kaydet</button>
                <button type="reset" class="btn btn-danger">Sıfırla</button>
            </div>
        <?php ActiveForm::end();?>
</div>
<!-- END content -->