function rdns_edit(target){
    var id = target;

    $.ajax({
        url:baseUrl + "/admin/vps/rdns-edit",
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

function rdns_delete(target){
	if (!confirm('Rdns verisini silmek istediğinizden emin misiniz ? ')) return false;
	
    var id = target;

    $.ajax({
        url:baseUrl + "/admin/vps/rdns-delete",
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

function rdns_pending_edit(target){
    var id = target;

    $.ajax({
        url:baseUrl + "/admin/vps/rdns-pending-edit",
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
        url:baseUrl + "/admin/vps/rdns-pending-delete",
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

function rdns_data_edit(target){
	
    var id = target;

    $.ajax({
        url:baseUrl + "/admin/rdns/data-view",
        type:'POST',
        dataType:'HTML',
        data:{id:id},
        success:function(data){
            new simpleAlert({title:'Rdns Verisini Değiştir.', content:data});
        },
        beforeSend:function() {
            new simpleAlert({title:'Yükleniyor..', content:'Lütfen bekleyiniz...'});
        }
    });
	
}
