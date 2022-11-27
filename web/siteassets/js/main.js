function changeOs(target) {

    var id = target;

    $.ajax({
        url:baseUrl + "/site/vps/select-os",
        type:'POST',
        dataType:'HTML',
        data:{vpsId:id},
        success:function(data){
            new simpleAlert({title:'İşletim Sistemi Seçiniz', content:data});
        },
        beforeSend:function() {
            new simpleAlert({title:'Yükleniyor..', content:'Lütfen bekleyiniz...'});
        }
    });
}

function loadPassword(target) {

    var id = target;

    $.ajax({
        url:baseUrl + "/site/vps/password-settings",
        type:'POST',
        dataType:'HTML',
        data:{vpsId:id},
        success:function(data){
            new simpleAlert({title:'Şifre İşlemleri', content:data});
        },
        beforeSend:function() {
            new simpleAlert({title:'Yükleniyor..', content:'Lütfen bekleyiniz...'});
        }
    });
}

function sifre_gonder(target){
    var id = target;

    $.ajax({
        url:baseUrl + "/site/vps/password-send",
        type:'POST',
        dataType:'HTML',
        data:{vpsId:id},
        success:function(data){
            new simpleAlert({title:'Şifre Sonucu', content:data});
        },
        beforeSend:function() {
            new simpleAlert({title:'Gönderiliyor..', content:'Lütfen bekleyiniz...'});
        }
    });
}

function loadHostName(target) {

    var id = target;

    $.ajax({
        url:baseUrl + "/site/vps/load-host",
        type:'POST',
        dataType:'HTML',
        data:{vpsId:id},
        success:function(data){
            new simpleAlert({title:'Hostname Değiştir', content:data});
        },
        beforeSend:function() {
            new simpleAlert({title:'Yükleniyor..', content:'Lütfen bekleyiniz...'});
        }
    });
}

function loadIso(target) {

    var id = target;

    $.ajax({
        url:baseUrl + "/site/vps/select-iso",
        type:'POST',
        dataType:'HTML',
        data:{vpsId:id},
        success:function(data){
            new simpleAlert({title:'Iso Seçimi', content:data});
        },
        beforeSend:function() {
            new simpleAlert({title:'Yükleniyor..', content:'Lütfen bekleyiniz...'});
        }
    });
}


function rdns_pending_edit(target){
    var id = target;

    $.ajax({
        url:baseUrl + "/site/vps/rdns-pending-edit",
        type:'POST',
        dataType:'HTML',
        data:{pendingId:id},
        success:function(data){
            new simpleAlert({title:'Rdns İşlemini Değiştir.', content:data});
        },
        beforeSend:function() {
            new simpleAlert({title:'Yükleniyor..', content:'Lütfen bekleyiniz...'});
        }
    });
}

function rdns_pending_delete(target){
	
	if (!confirm('İşlemde olan verinizi silmek istediğinizden emin misiniz ?')) return false;
	
    var id = target;

    $.ajax({
        url:baseUrl + "/site/vps/rdns-pending-delete",
        type:'POST',
        dataType:'HTML',
        data:{pendingId:id},
        success:function(data){
			new simpleAlert({title:"Durum", content:data});
        },
        beforeSend:function() {
            new simpleAlert({title:'Siliniyor..', content:'Lütfen bekleyiniz...'});
        }
    });
	

}

function rdns_delete(target){
	
	if (!confirm('Rdns verisini silmek istediğinizden emin misiniz ? ')) return false;
	
    var id = target;

    $.ajax({
        url:baseUrl + "/site/vps/rdns-delete",
        type:'POST',
        dataType:'HTML',
        data:{rdnsId:id},
        success:function(data){
			new simpleAlert({title:"Durum", content:data});
        },
        beforeSend:function() {
            new simpleAlert({title:'Siliniyor..', content:'Lütfen bekleyiniz...'});
        }
    });
	

}

function rdns_edit(target){
    var id = target;

    $.ajax({
        url:baseUrl + "/site/vps/rdns-edit",
        type:'POST',
        dataType:'HTML',
        data:{rdnsId:id},
        success:function(data){
            new simpleAlert({title:'Rdns Değiştir', content:data});
        },
        beforeSend:function() {
            new simpleAlert({title:'Yükleniyor..', content:'Lütfen bekleyiniz...'});
        }
    });
}

function rdns_create(target){
    var id = target;

    $.ajax({
        url:baseUrl + "/site/vps/rdns-create",
        type:'POST',
        dataType:'HTML',
        data:{vpsId:id},
        success:function(data){
            new simpleAlert({title:'Rdns Oluştur', content:data});
        },
        beforeSend:function() {
            new simpleAlert({title:'Yükleniyor..', content:'Lütfen bekleyiniz...'});
        }
    });
}

function loadShot(target) {

    var id = target;

    $.ajax({
        url:baseUrl + "/site/vps/select-shot",
        type:'POST',
        dataType:'HTML',
        data:{vpsId:id},
        success:function(data){
            new simpleAlert({title:'Yedekleme', content:data});
        },
        beforeSend:function() {
            new simpleAlert({title:'Yükleniyor..', content:'Lütfen bekleyiniz...'});
        }
    });
}

function resetOs(target) {

    var id = target;

    $.ajax({
        url:baseUrl + "/site/vps/restart",
        type:'POST',
        dataType:'JSON',
        data:{vpsId:id},
        success:function(data){
            if(data.status == 1) {
                new simpleAlert({title:'Eylem Durumu', content:'Sunucunuz başarıyla yeniden başlatılmıştır.'});
            } else {
                if (data.message) {
                    new simpleAlert({title:'Eylem Durumu', content:data.message});
                } else {
                    new simpleAlert({title:'Eylem Durumu', content:'Bir hata oluştu, lütfen daha sonra tekrar deneyiniz'});
                }
            }
        },
        beforeSend:function() {
            new simpleAlert({title:'Yükleniyor..', content:'Lütfen bekleyiniz...'});
        }
    });
}

function stopVps(target) {

    var id = target;

    $.ajax({
        url:baseUrl + "/site/vps/stop",
        type:'POST',
        dataType:'JSON',
        data:{vpsId:id},
        success:function(data){
            if(data.status == 1) {
                new simpleAlert({title:'Eylem Durumu', content:'Sunucunuz başarıyla kapatılmıştır.'});
            } else {
                if (data.message) {
                    new simpleAlert({title:'Eylem Durumu', content:data.message});
                } else {
                    new simpleAlert({title:'Eylem Durumu', content:'Bir hata oluştu, lütfen daha sonra tekrar deneyiniz'});
                }
            }
        },
        beforeSend:function() {
            new simpleAlert({title:'Yükleniyor..', content:'Lütfen bekleyiniz...'});
        }
    });
}

function startVps(target) {

    var id = target;

    $.ajax({
        url:baseUrl + "/site/vps/start",
        type:'POST',
        dataType:'JSON',
        data:{vpsId:id},
        success:function(data){
            if(data.status == 1) {
                new simpleAlert({title:'Eylem Durumu', content:'Sunucunuz başarıyla aktif edilmiştir.'});
            } else {
                if (data.message) {
                    new simpleAlert({title:'Eylem Durumu', content:data.message});
                } else {
                    new simpleAlert({title:'Eylem Durumu', content:'Bir hata oluştu, lütfen daha sonra tekrar deneyiniz'});
                }
            }
        },
        beforeSend:function() {
            new simpleAlert({title:'Yükleniyor..', content:'Lütfen bekleyiniz...'});
        }
    });
}

function statusVps(target) {

    var id = target;

    $.ajax({
        url:baseUrl + "/site/vps/advanced-status",
        type:'POST',
        dataType:'JSON',
        data:{vpsId:id},
        success:function(data){
            if(data.ok) {
                new simpleAlert({title:'Eylem Durumu', content:'<b><i class="fas fa-server"></i> Sunucu :</b>  ' + data.power + ' <br><br> <b><i class="fas fa-network-wired"></i> Network :</b>  ' + data.network});
            } else {
                new simpleAlert({title:'Eylem Durumu', content:'Bir hata oluştu, lütfen daha sonra tekrar deneyiniz'});
            }
        },
        beforeSend:function() {
            new simpleAlert({title:'Yükleniyor..', content:'Lütfen bekleyiniz...'});
        }
    });
}

function monitorVps(target) {

    var id = target;

    $.ajax({
        url:baseUrl + "/site/vps/monitor",
        type:'POST',
        dataType:'HTML',
        data:{vpsId:id},
        success:function(data){
            if (data == "") {
                new simpleAlert({title:'Eylem Durumu', content:'Bir hata oluştu, lütfen daha sonra tekrar deneyiniz'});
            } else {
                new simpleAlert({title:'Monitör', content:data});
            }
        },
        beforeSend:function() {
            new simpleAlert({title:'Yükleniyor..', content:'Lütfen bekleyiniz...'});
        }
    });
}

function extendVps(target) {

    var id = target;

    $.ajax({
        url:baseUrl + "/site/vps/extend-form",
        type:'POST',
        dataType:'HTML',
        data:{vpsId:id},
        success:function(data){
            new simpleAlert({title:'Disk genişletme', content:data});
        },
        beforeSend:function() {
            new simpleAlert({title:'Yükleniyor..', content:'Lütfen bekleyiniz...'});
        }
    });
}

function logVps(target) {

    var id = target;

    $.ajax({
        url:baseUrl + "/site/vps/action-log",
        type:'POST',
        dataType:'HTML',
        data:{vpsId:id},
        success:function(data){
            new simpleAlert({title:'Loglar', content:data});
        },
        beforeSend:function() {
            new simpleAlert({title:'Yükleniyor..', content:'Lütfen bekleyiniz...'});
        }
    });
}

function consoleVps(target) {

    var id = target;

    $.ajax({
        url:baseUrl + "/site/vps/console-form",
        type:'POST',
        dataType:'HTML',
        data:{vpsId:id},
        success:function(data){
            new simpleAlert({title:'Konsol', content:data});
        },
        beforeSend:function() {
            new simpleAlert({title:'Yükleniyor..', content:'Lütfen bekleyiniz...'});
        }
    });
}

function loadServer(target) {

    id = target.data("id");
	
	var targetClass = $("#bodyVirtualServer-"+id).attr('class');
	
	if(targetClass == "collapse"){
		$("div#server-"+id).attr('class', 'col-md-12');
		$("div#vpsid-"+id).attr('class', 'vps_item col-md-2 col-sm-12 col-xs-12');
		$("div#vpsip-"+id).attr('class', 'vps_item col-md-2 col-sm-12 col-xs-12');
		$("div#vpshostname-"+id).attr('class', 'vps_item col-md-2 col-sm-12 col-xs-12');
		$("div#vpsos-"+id).attr('class', 'vps_item col-md-2 col-sm-12 col-xs-12');
		$("div#vpsactions-"+id).attr('class', 'vps_item col-md-4 col-sm-12 col-xs-12');
	}
	else{
		$("div#server-"+id).attr('class', 'col-md-4 col-xs-12 col-sm-12');
		$("div#vpsid-"+id).attr('class', 'vps_item col-lg-12');
		$("div#vpsip-"+id).attr('class', 'vps_item col-lg-12');
		$("div#vpshostname-"+id).attr('class', 'vps_item col-lg-12');
		$("div#vpsos-"+id).attr('class', 'vps_item col-lg-12');
		$("div#vpsactions-"+id).attr('class', 'vps_item col-lg-12');
	}
	
    jQuery.ajax({
        url: baseUrl + "/site/vps/index",
        type: "GET",
        data:{id : id},
        success: function(data) {
            $("#bodyVirtualServer-"+id).html(data);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            alert('failed');
        }
    });
}


function reloadPage() {
    location.reload();
}

function loadItem(vpsid, action){
	
        target = vpsid;

        if (action == 1) {
            changeOs(target);
        } else if (action == 2) {
            resetOs(target);
        } else if (action == 3) {
            stopVps(target);
        } else if (action == 4) {
            startVps(target);
        } else if(action == 5) {
            statusVps(target);
        } else if (action == 6) {
            monitorVps(target);
        } else if (action == 7) {
            extendVps(target);
        } else if (action == 8) {
            logVps(target);
        } else if (action == 9) {
            consoleVps(target);
        } else if (action == 10) {
            loadIso(target);
        } else if (action == 11) {
            loadShot(target);
        } else if (action == 12) {
            loadHostName(target);
        } else if (action == 13){
            loadPassword(target);
		}
		else {
            loadServer(self);
        }
}


$(function() {
	
    $("a#deneme").click(function(e) {
			alert("deneme123");
    });

    $(".collapsible li a.btn").on("click",function(e){

        setTimeout(function() {
        	//var indexCol = $(this).parent().parent().parent().index();
        	//$('.collapsible').collapsible('close', indexCol-1);
        	$(".active").removeClass("active");
		$(".collapsible-body").removeAttr("style").hide();
  	}, 100);

    });

    //$(".collapsible").collapsible();

    $(".access a").click(function(e) {

        target = $(this);

        action = target.data("action");

        if (action == 1) {
            changeOs(target);
        } else if (action == 2) {
            resetOs(target);
        } else if (action == 3) {
            stopVps(target);
        } else if (action == 4) {
            startVps(target);
        } else if(action == 5) {
            statusVps(target);
        } else if (action == 6) {
            monitorVps(target);
        } else if (action == 7) {
            extendVps(target);
        } else if (action == 8) {
            logVps(target);
        } else if (action == 9) {
            consoleVps(target);
        } else if (action == 10) {
            loadIso(target);
        } else if (action == 11) {
            loadShot(target);
        } else if (action == 12) {
            loadHostName(target);
        }
    });

    $(".server").click(function(e) {
		
        e.preventDefault();

        self = $(this);
        target = $(e.target);

        action = target.data("action");

        loadServer(self);
    });
});
