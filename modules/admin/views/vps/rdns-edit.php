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
			new simpleAlert({title:"Başarılı", content:"Rdns güncelleme isteğiniz işleme alınmıştır."});
		} else {
			new simpleAlert({title:"Hata", content:data.error});
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
<?php echo Html::beginForm(Url::toRoute(['change-rdns']));?>
<table class="table extend-table">
    <tbody>
    <input type="hidden" name="id" value="<?php echo $rdnsData->id;?>">
    <tr>
        <td>
			<label>Rdns Name</label>
			<input disabled type="text" class="form-control" value="<?=$rdnsData->name;?>">
        </td>
    </tr>
    <tr>
        <td>
			<label>Rdns Type</label>
            <select class="form-control" name="type">
				<?php foreach($rdnsTypes as $type){ ?>
					<option value="<?=$type;?>" <?=(($rdnsData->type == $type) ? "selected" : "");?>><?=$type;?></option>
				<?php } ?>
			</select>
        </td>
    </tr>
    <tr>
        <td>
			<label>Rdns Content</label>
			<input type="text" class="form-control" name="content" value="<?=$rdnsData->content;?>">
        </td>
    </tr>
    <tr>
        <td>
			<label>Rdns Priority</label>
			<input type="text" class="form-control" name="priority" value="<?=$rdnsData->priority;?>">
        </td>
    </tr>
    <tr>
        <td>
			<label>Rdns TTL</label>
			<input type="text" class="form-control" name="ttl" value="<?=$rdnsData->ttl;?>">
        </td>
    </tr>
    <tr>
        <td>
            <button type="submit" class="btn btn-success">Değiştir</button>
        </td>
    </tr>
    </tbody>
</table>
<?php echo Html::endForm();?>