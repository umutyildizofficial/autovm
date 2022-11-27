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
			new simpleAlert({title:"Başarılı", content:"Rdns başarıyla eklendi"});
		} else {
			new simpleAlert({title:"Hata", content:data.error});
		}
	});
});

EOT;

$this->registerJs($js);
?>

<?php echo Html::beginForm(Url::toRoute(['create-rdns']));?>

	<div class="form-group">
			<label>Ip Adresi</label>
			<select class="form-control" name="ip_adres" onchange="vps_change(this.options[this.selectedIndex].value)">
				<?php 
					foreach($VpsIpleri as $VpsId => $vpsIp){
						echo "<option value=\"{$vpsIp['id']}\">{$vpsIp['ip']}</option>";
					}
				?>
			</select>
	</div>
	<div class="form-group">
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
	</div>
	<div class="form-group">
			<label>Rdns Content</label>
			<input type="text" class="form-control" name="content">
	</div>
	<div class="form-group">
			<label>Rdns Priority</label>
			<input type="text" class="form-control" name="priority">
	</div>
	<div class="form-group">
			<label>Rdns TTL</label>
			<input type="text" class="form-control" name="ttl">
	</div>
	<div class="form-group">
		<button type="submit" class="btn btn-success">Ekle</button>
	</div>

<?php echo Html::endForm();?>