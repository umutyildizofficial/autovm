<?php
use yii\helpers\Url;
use yii\helpers\Html;

?>

            <select class="form-control" name="type">
				<?php foreach($rdnsTypes as $type){ ?>
					<option value="<?=$type;?>"><?=$type;?></option>
				<?php } ?>
			</select>
