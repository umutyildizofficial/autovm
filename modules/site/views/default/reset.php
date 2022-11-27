<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

Yii::$app->setting->title .= ' - şifre sıfırlama';

$template = '{input}{error}';

?>
<div class="content">
    <div class="container">
        <div class="col-md-4 col-md-offset-4">
            <div class="title">
                <h3>Şifre sıfırlama <p>Lütfen yeni şifrenizi giriniz</p></h3>
            </div>
            <?php echo \app\widgets\Alert::widget();?>
            <?php $form = ActiveForm::begin(['fieldConfig' => ['template' => $template]]);?>
                <?php echo $form->field($model, 'password')->passwordInput(['placeholder' => 'Şifre']);?>
                <?php echo $form->field($model, 'repeatPassword')->passwordInput(['placeholder' => 'Tekrar Şifre']);?>
                <div class="form-group">
                    <button type="submit" class="btn btn-success">Değiştir</button>
                </div>
            <?php ActiveForm::end();?>
        </div>
    </div>
</div>