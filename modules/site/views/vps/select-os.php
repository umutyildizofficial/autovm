<?php
use yii\helpers\Url;
use yii\helpers\Html;
use app\models\SatanHosting;


$bundle = \app\modules\site\assets\Asset::register($this);
$this->registerJs('

ij=0;
function passwordStrength(password)
{
	var desc = new Array();
	desc[0] = "Çok Zayıf";
	desc[1] = "Zayıf";
	desc[2] = "Daha İyi";
	desc[3] = "Orta";
	desc[4] = "Güçlü";
	desc[5] = "Daha Güçlü";
	var score   = 0;
	//if password bigger than 6 give 1 point
	if (password.length >= 6) score++;
	//if password has both lower and uppercase characters give 1 point
	if ( ( password.match(/[a-z]/) ) && ( password.match(/[A-Z]/) ) ) score++;
	//if password has at least one number give 1 point
	if (password.match(/\d+/)) score++;
	//if password has at least one special caracther give 1 point
	if ( password.match(/.[!,@,#,$,%,^,&,*,?,_,~,-,(,)]/) )	score++;
	//if password bigger than 12 give another 1 point
	if (password.length >= 8) score++;
	return desc[score];
}
String.prototype.shuffle = function () {
    var a = this.split(""),
        n = a.length;

    for(var i = n - 1; i > 0; i--) {
        var j = Math.floor(Math.random() * (i + 1));
        var tmp = a[i];
        a[i] = a[j];
        a[j] = tmp;
    }
    return a.join("");
}
function randomPassword(length) {
    var chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYX1234567890";
    var pass = "";
    for (var x = 0; x < length-3; x++) {
        var i = Math.floor(Math.random() * chars.length);
        pass += chars.charAt(i);
    }
    var i = Math.floor(Math.random() * 26);
    pass += chars.charAt(i);

    var i = Math.floor(Math.random() * 26 + 26);
    pass += chars.charAt(i);

    var i = Math.floor(Math.random() * 10 + 52);
    pass += chars.charAt(i);
    return pass.shuffle();
}
(function ($) {
    $.toggleShowPassword = function (options) {
        var settings = $.extend({
            field: "#password",
            control: "#toggle_show_password",
        }, options);

        var control = $(settings.control);
        var field = $(settings.field)

        control.bind(\'click\', function () {
            if (control.is(\':checked\')) {
                field.attr(\'type\', \'text\');
            } else {
                field.attr(\'type\', \'password\');
            }
        })
    };
}(jQuery));

$(document).ready(function() {
$(".strength").keyup(function(e){
if($(this).val().match(/.[!,@,#,$,%,^,&,*,?,_,~,-,(,)]/) ){
alert("Only use [a-z],[A-Z],[0-9]!");
$(this).val("");
}
 $(".pwstrength_viewport").html(passwordStrength($(this).val()));
});
$(".password_random").click(function(e){
 $(".strength").val(randomPassword(10));
 $(".pwstrength_viewport").html(passwordStrength($(".strength").val()));
});
$.toggleShowPassword({
    field: ".strength",
    control: "#showpassword"
});
    $("#install").click(function(e){

        self = $(this);

        var password = $("#password").val();

        if(password == "") {
            new simpleAlert({title:"Hata", content:"Lütfen tekrar deneyiniz ve şifrenizi giriniz"});
            return false;
        }

        if (!password.match(/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[0-9a-zA-Z]{8,}$/)) {
            new simpleAlert({title:"Hata", content:"Şifre çok zayıf! kullanılabilir [A-Z, a-z, 0-9]"});
            return false;
        }

        var _username = "administrator";

        var vpsId = ' . $vpsId . ';
        var osId = $("input[name=osId]:checked").val();

        if(osId == "" || osId == undefined) {
            new simpleAlert({title:"Hata", content:"Lütfen tekrar deneyiniz ve işletim sistemi seçiniz"});
            return false;
        }

        url = self.data("os") + "?id=" + osId;

        $.getJSON(url, function(data) {

            if (data.ok && data.status == 1) {
                _username = "administrator";
            } else if (data.ok && data.status == 2) {
                _username = "root";
            } else {
                _username = "administrator (OR) root";
            }
        });

        extend = $("input[name=extend]").prop("checked");
		
		var username = "root";

        function getStatus() {

        	inter = setInterval(function() {
				
	        	url = "' . Yii::$app->urlManager->createUrl(['/site/vps/step', 'id' => $vpsId]) . '";

	        	$.getJSON(url, function(data) {

	        		if (data.ok) {

                    if (data.percent) {
                        $(".percent").text(data.percent);
                    }

					if (data.step == 1) {
						$(".steps1").find(".step-icon").html("<i class=\'fas fa-check green-icon\'></i>");
					}

					if (data.step >= 2) {
						$(".steps1").find(".step-icon").html("<i class=\'fas fa-check green-icon\'></i>");
						$(".steps2").find(".step-icon").html("<i class=\'fas fa-check green-icon\'></i>");
					}

					if (data.step >= 3) {
						$(".steps1").find(".step-icon").html("<i class=\'fas fa-check green-icon\'></i>");
						$(".steps2").find(".step-icon").html("<i class=\'fas fa-check green-icon\'></i>");
						$(".steps3").find(".step-icon").html("<i class=\'fas fa-check green-icon\'></i>");
					}

					if (data.step >= 4) {
						$(".steps1").find(".step-icon").html("<i class=\'fas fa-check green-icon\'></i>");
						$(".steps2").find(".step-icon").html("<i class=\'fas fa-check green-icon\'></i>");
						$(".steps3").find(".step-icon").html("<i class=\'fas fa-check green-icon\'></i>");
						$(".steps4").find(".step-icon").html("<i class=\'fas fa-check green-icon\'></i>");
						
						text = "<span class=\"green-icon\"><i class=\"fas fa-check\"></i></span> Sunucunuz başarıyla kurulmuştur<br>";
						text += "<span class=\"pink-text\"><i class=\"fas fa-user\"></i></span> Kullanıcı Adı: " + _username + "<br>";
						text += "<span class=\"pink-text\"><i class=\"fas fa-lock\"></i></span> Şifre: " + password + "<br>";

					}
					if (data.step >= 5 && data.percent == 100){
						$(".steps1").find(".step-icon").html("<i class=\'fas fa-check green-icon\'></i>");
						$(".steps2").find(".step-icon").html("<i class=\'fas fa-check green-icon\'></i>");
						$(".steps3").find(".step-icon").html("<i class=\'fas fa-check green-icon\'></i>");
						$(".steps4").find(".step-icon").html("<i class=\'fas fa-check green-icon\'></i>");
						$(".steps5").find(".step-icon").html("<i class=\'fas fa-check green-icon\'></i>");
						setTimeout(function(){
							'.SatanHosting::AllSettings().'
						}, 6000);
						setTimeout(function(){
						new simpleAlert({title:"Kurulum tamamlanmıştır, Lütfen sunucu erişimi gelene kadar bekleyiniz!", content:text });
						}, 2000);
						clearInterval(inter);
						
				   }
	             }
	           });
             }, 5000);
           }

        extend = 1;

        $.ajax({
            type:"POST",
            dataType:"JSON",
            url:"' . Yii::$app->urlManager->createUrl('/site/vps/install') . '",
            data:{password:password, vpsId:vpsId, osId:osId, extend:extend},
            beforeSend:function() {
            	html = $(".pending").html();
            	new simpleAlert({title:"Yükleniyor.. kurulum bitene kadar lütfen bekleyiniz!", content:html});
            },
            success:function(data) {
				'.SatanHosting::RegisterJs().'
            }
        });
    });
});
');

$this->registerJs('
	$(".steps1").find(".step-icon").html("<i class=\'fas fa-check green-icon\'></i>");

')

?>

<style type="text/css">
    .select-os-table {
        box-shadow: none !important;
        border: 0 !important;
    }

    .select-os-table td {
        border: 0 !important;
    }
	
	.select-os-table span small{
		display:block;
		margin-top:66px;
	}
	
	.simple-alert-content{
		overflow:auto;
	}
	
</style>



                <?php foreach ($operationSystems as $os) { ?>
                    <div class="col-md-6 col-sm-12 col-xs-12">
                        <label class="checkbox"><input type="radio" name="osId" value="<?php echo $os->id; ?>">
                            <span></span> <?php echo Html::encode($os->name); ?></label>
                    </div>
                <?php } ?>
				
				<div class="form-group">
					<div class="row">
					<div class="col-md-9 col-xs-12">
					<input type="password" name="password" id="password" placeholder="<?php echo Yii::t('app', 'Password');?>" class="form-control strength">
					<div class="pwstrength_viewport"></div>
					</div>
					<div class="col-md-3 col-xs-12">
					<span><small>(A-Z, a-z, 0-9)</small></span>
            <input type="checkbox" id="showpassword" />
            <label for="showpassword"><?php echo Yii::t('app', 'Show password');?></label>
					</div>
					</div>
				</div>

				<div class="form-group">
            <button class="btn btn-primary password_random" style="margin-right: 20px"><?php echo Yii::t('app', 'Random Password');?></button>
            <button class="btn btn-success" type="button" id="install" data-os="<?php echo Url::toRoute(['os']);?>"><?php echo Yii::t('app', 'Install');?></button>
				
				</div>


<div class="pending" style="display:none;">
	<div class="osInstalling">
	  <div class="row">
	    <div class="col s12">
          <p style="text-align:center;font-size:20px;font-weight:bold;"><span class="percent">0</span>%</p>
	      <p class="grey-text steps1"><span class="step-icon"><i class="fas fa-ellipsis-h"></i></span> <?php echo Yii::t('app', 'Prepairing OS Files');?></p>
	      <p class="grey-text steps2"><span class="step-icon"><i class="fas fa-ellipsis-h"></i></span> <?php echo Yii::t('app', 'Getting Files Ready For Installation');?></p>
	      <p class="grey-text steps3"><span class="step-icon"><i class="fas fa-ellipsis-h"></i></span> <?php echo Yii::t('app', 'Installing Features');?></p>
	      <p class="grey-text steps4"><span class="step-icon"><i class="fas fa-ellipsis-h"></i></span> <?php echo Yii::t('app', 'Finishing UP');?></p>
		  <p class="grey-text steps5"><span class="step-icon"><i class="fas fa-ellipsis-h"></i></span> <?php echo Yii::t('app', 'Network Pinging');?></p>
          <p style="text-align:center;"><img src="<?php echo $bundle->baseUrl;?>/img/prog.gif"></p>
	    </div>
	  </div>
	</div>
</div>

<div class="complete" style="display:none;">
<p class="text-center"><span class="green-icon"><i class="fas fa-check"></i></span><?php echo Yii::t('app', 'Your VM has been created successfully');?></p>
<p><span class="pink-text"><i class="fas fa-user"></i></span> Kullanıcı Adı:<b>root</b></p>
<p><span class="pink-text"><i class="fas fa-lock"></i></span> Şifre: <b>{{password}}</b></p>
</div>
<style>
.simple-alert{
top:8%!important;
}
</style>