<?php
use yii\helpers\Url;
use yii\helpers\Html;

$js = <<<EOT

function vps_change(target){
	
    $.ajax({
        url:baseUrl + "/site/vps/rdns-select-change-create",
        type:'POST',
        dataType:'HTML',
        data:{ipId:target},
        success:function(data){
            $("div#select_rdns_type").html(data);
        }
    });
	
}

$("form").submit(function(e) {
	e.preventDefault();
	
	self = $(this);
	
	new simpleAlert({title:"İşleniyor", content:"Lütfen bekleyiniz..."});
	
	$.post(self.attr("action"), self.serialize(), function(data) {
	
		if (data.ok) {
			$("div#rdns-result-{$vps->id}").load(baseUrl + "/site/vps/rdns-result-change?id={$vps->id}");
			new simpleAlert({title:"Başarılı", content:"Rdns ekleme isteğiniz işleme alınmıştır."});
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
<?php echo Html::beginForm(Url::toRoute(['create-rdns']));?>
<table class="table extend-table">
    <tbody>
    <tr>
        <td>
			<label>Ip Adresi</label>
			<select class="form-control" name="ip_adres" onchange="vps_change(this.options[this.selectedIndex].value)">
				<?php 
					foreach($VpsIpleri as $VpsId => $vpsIp){
						echo "<option value=\"{$vpsIp['id']}\">{$vpsIp['ip']}</option>";
					}
				?>
			</select>
        </td>
    </tr>
    <tr>
        <td>
			<label>Rdns Type</label>
			<?php if(isset($VpsIpleri[0]['types'])){ ?>
			<div id="select_rdns_type">
            <select class="form-control" name="type">
				<?php foreach($VpsIpleri[0]['types'] as $type){ ?>
					<option value="<?=$type;?>"><?=$type;?></option>
				<?php } ?>
			</select>
			</div>
			<?php } ?>
        </td>
    </tr>
    <tr>
        <td>
			<label>Rdns Content</label>
			<input type="text" class="form-control" name="content">
        </td>
    </tr>
    <tr>
        <td>
			<label>Rdns Priority</label>
			<input type="text" class="form-control" name="priority">
        </td>
    </tr>
    <tr>
        <td>
			<label>Rdns TTL</label>
			<input type="text" class="form-control" name="ttl">
        </td>
    </tr>
    <tr>
        <td>
            <button type="submit" class="btn btn-success">Ekle</button>
        </td>
    </tr>
    </tbody>
</table>
<?php echo Html::endForm();?>