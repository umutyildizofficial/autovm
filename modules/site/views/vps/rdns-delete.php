<?php
use yii\helpers\Url;
use yii\helpers\Html;

$this->registerJs("$(\"div#rdns-result-{$vps->id}\").load(baseUrl + \"/site/vps/rdns-result-change?id={$vps->id}\");");

?>

<i class="fas fa-check"></i> Rdns Silme Talebiniz Başarıyla Alındı.