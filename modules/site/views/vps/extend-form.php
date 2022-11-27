<?php
use yii\helpers\Url;
use yii\helpers\Html;

$js = <<<EOT

$("form").submit(function(e) {
	e.preventDefault();
	
	self = $(this);
	
	new simpleAlert({title:"Genişletiliyor", content:"Lütfen bekleyiniz..."});
	
	$.post(self.attr("action"), self.serialize(), function(data) {
	
		if (data.status > 0) {
			new simpleAlert({title:"Başarılı", content:"Sunucu diskiniz başarıyla genişletilmiştir"});
		} else {
			new simpleAlert({title:"Hata", content:"Lütfen daha sonra tekrar deneyiniz"});
		}
	});
});

EOT;

$this->registerJs($js);
?>

<style type="text/css">
    .extend-table {
        box-shadow: none !important;
        border: 0 !important;
    }

    .extend-table td {
        border: 0 !important;
    }
</style>
<?php echo Html::beginForm(Url::toRoute(['extend']));?>
<table class="table extend-table">
    <tbody>
    <input type="hidden" name="vpsId" value="<?php echo $vpsId;?>">
    <tr>
        <td>
            <input type="text" name="username" class="form-control" placeholder="Sunucu Kullanıcı Adı">
        </td>
    </tr>
    <tr>
        <td>
            <input type="password" name="password" class="form-control" placeholder="Sunucu Şifre">
        </td>
    </tr>
    <tr>
        <td>
            <button type="submit" class="btn btn-success">Şimdi genişlet</button>
        </td>
    </tr>
    </tbody>
</table>
<?php echo Html::endForm();?>