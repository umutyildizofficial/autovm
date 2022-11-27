<?php
use yii\helpers\Url;
use yii\helpers\Html;

$js = <<<EOT

$("form").submit(function(e) {
	e.preventDefault();
	
	self = $(this);
	
	new simpleAlert({title:"İşleniyor", content:"Lütfen bekleyiniz..."});
	
	$.post(self.attr("action"), self.serialize(), function(data) {
	
		if (data.ok) {
			new simpleAlert({title:"Başarılı", content:"Hostname başarıyla değiştirilmiştir."});
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
<?php echo Html::beginForm(Url::toRoute(['change-host']));?>
<table class="table extend-table">
    <tbody>
    <input type="hidden" name="id" value="<?php echo $vps->id;?>">
    <tr>
        <td>
			<label>Eski Şifrenizi Giriniz</label>
            <input type="text" name="oldpassword" class="form-control" placeholder="Eski Şifrenizi Giriniz.">
        </td>
    </tr>
    <tr>
        <td>
			<label>Yeni Şifrenizi Giriniz</label>
            <input type="text" name="newpassword" class="form-control" placeholder="Yeni Şifrenizi Giriniz.">
        </td>
    </tr>
    <tr>
        <td>
			<label>Yeni Şifrenizi Tekrar Giriniz</label>
            <input type="text" name="newpasswordretry" class="form-control" placeholder="Yeni Şifrenizi Tekrar Giriniz.">
        </td>
    </tr>
    <tr>
        <td>
            <button type="submit" class="btn btn-success">Değiştir</button>
            <button type="button" class="btn btn-warning" onclick="sifre_gonder(<?=$vps->id;?>)"><i class="fas fa-envelope"></i> Şifremi Gönder</button>
        </td>
    </tr>
    </tbody>
</table>
<?php echo Html::endForm();?>