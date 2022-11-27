<?php
use yii\helpers\Url;
use yii\widgets\ActiveForm;

?>

  <div class="page-content__header">
    <div>
      <h2 class="page-content__header-heading"><i class="fas fa-envelope"></i> Mail Ayarları</h2>
    </div>
  </div>

    <div class="main-container">
    <div class="userstyle">

        <?php $form = ActiveForm::begin(['enableClientValidation' => true]);?>
            <?php echo $form->field($model, 'hostname');?>
            <?php echo $form->field($model, 'username');?>
            <?php echo $form->field($model, 'password')->passwordInput();?>
            <?php echo $form->field($model, 'security');?>
            <?php echo $form->field($model, 'port');?>
            <?php echo $form->field($model, 'from');?>
            <div class="margin-top-10"></div>
            <div class="margin-top-10"></div>

            <div class="form-group">
                <button type="submit" class="btn btn-success waves-effect waves-light">Kaydet</button>
            </div>
        <?php ActiveForm::end();?>
    </div>   
	
	</div> 
