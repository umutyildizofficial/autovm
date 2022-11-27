<?php use yii\widgets\ActiveForm;?>

  <div class="page-content__header">
    <div>
      <h2 class="page-content__header-heading"><i class="fas fa-compact-disc"></i> İSO Kalıbı Oluştur</h2>
    </div>
  </div>

<!-- content -->
<div class="main-container">     
        <?php echo \app\widgets\Alert::widget();?>
        <?php $form = ActiveForm::begin(['enableClientValidation' => true]);?>
            <?php echo $form->field($model, 'name');?>
            <?php echo $form->field($model, 'path')->label('Dosya adı');?>
			
            <div class="margin-top-10"></div>
            <div class="margin-top-10"></div>
            
            <div class="form-group">
                <button type="submit" class="btn btn-primary waves-effect waves-light">Kaydet</button>
                <button type="reset" class="btn btn-danger">Sıfırla</button>
            </div>
        <?php ActiveForm::end();?>
</div>
<!-- END content -->
