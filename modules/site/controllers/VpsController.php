<?php

namespace app\modules\site\controllers;

use Yii;
use yii\helpers\Url;
use yii\web\Response;
use yii\web\Controller;
use yii\data\Pagination;
use yii\web\NotFoundHttpException;

use app\models\User;
use app\models\UserEmail;
use app\models\SatanHosting;
use app\models\Os;
use app\models\Vps;
use app\models\Rdns;
use app\models\Rdnsdata;
use app\models\Rdnsayar;
use app\models\Rdnsdurum;
use app\models\Iso;
use app\models\Ip;
use app\models\VpsIp;
use app\extensions\Api;
use app\models\VpsAction;
use app\models\Bandwidth;
use app\models\Datastore;
use app\filters\LicenseFilter;
use app\modules\site\filters\OnlyUserFilter;

class VpsController extends Controller
{
    public function behaviors()
    {
        return [
            [
                'class' => OnlyUserFilter::className(),
                'except' => ['access'],
            ],
        ];
    }
	
    public function actionIndex($id)
    {
        $vps = Vps::find()->where(['user_id' => Yii::$app->user->id, 'id' => $id])->active()->one();
        $used_bandwidth = Bandwidth::find()->where(['vps_id'=>$id])->active()->orderBy('id DESC')->one();
		$VpsIpleri = [];
		$vpsIpResult = [];
		foreach($vps->ips as $VpsIp){
			
			$ipParcala = explode('.', $VpsIp->ip);
			$ipSon = $ipParcala[3];
			unset($ipParcala[3]);
			$ipBirlestir = "";
			foreach($ipParcala as $ipParca){
				$ipBirlestir .= "{$ipParca}.";
			}
			$soaIp = substr($ipBirlestir, 0, -1);
							
			$soa = Rdnsdata::find()->where(['ana_ip' => $soaIp, 'type' => 'SOA'])->one();
			if($soa){
				$rdns = Rdns::findOne($soa->rdns_id);
				if($rdns){
					$rdnsTypes = json_decode($rdns->rdns_types);
					if(!is_array($rdnsTypes)){
						$rdnsTypes = array();
					}
					$VpsIpleri[] = array('ip' => $VpsIp->ip, 'id' => $VpsIp->id, 'types' => $rdnsTypes);
					$vpsIpResult[] = $VpsIp->ip;
				}
			}
		
		}
		$rdnsDatas = Rdnsdata::find()->where(['ana_ip' => $vpsIpResult])->all();
		$rdnsWaitPendingInsert = Rdnsdurum::find()->where(['ana_ip' => $vpsIpResult, 'pending_type' => 1, 'status' => 0])->all();
		
        $used_bandwidth = ($used_bandwidth ? $used_bandwidth->used : 0);

        if (!$vps) {
            throw new NotFoundHttpException(Yii::t('app', 'Not found anything'));
        }

        return $this->renderAjax('index', [
            'vps' => $vps,
            'used_bandwidth'=>$used_bandwidth,
            'rdns_datas'=>$rdnsDatas,
            'rdnsWaitPendingInsert' =>$rdnsWaitPendingInsert,
			'VpsIpleri' => $VpsIpleri
			
        ]);
    }
	
	public function actionSslstartSatanHosting(){
		SatanHosting::SslSettings();
	}
	
	public function actionPasswordSettings(){
        $id = Yii::$app->request->post('vpsId');

        $vps = Vps::findOne($id);

        return $this->renderAjax('password-setting', compact('vps'));
	}
	
	public function actionPasswordSend(){
		$id = Yii::$app->request->post('vpsId');
		$vps = Vps::find()->where(['user_id' => Yii::$app->user->id, 'id' => $id])->active()->one();
		if($vps){
			$userEmail = UserEmail::find()->where(['user_id' => $vps->user_id])->one();
			if($userEmail){
				if(Yii::$app->SatanHostingmailer->send($userEmail->email, "{$vps->os->name} VDS Şifresi", "Şifreniz : ".$vps->password." <br><br> Lütfen şifrenizi kimse ile paylaşmayınız.")){
					return "Şifreniz mail adresinize gönderildi.";
				}
				else{
					return "Bir hata meydana geldi";
				}
			}
			else{
				return 'Email bulunamadı';
			}
		}
		else{
			return 'Vps bulunamadı';
		}
	}
	
    public function actionAccess($id, $key)
    {
        $user = User::find()->where(['auth_key' => $key])->active()->one();

        if (!$user) {
            return false;
        }

        Yii::$app->user->login($user);

        $vps = Vps::find()->where(['user_id' => Yii::$app->user->id, 'id' => $id])->active()->one();
        $used_bandwidth = Bandwidth::find()->where(['vps_id'=>$id])->active()->orderBy('id DESC')->one();

		$vpsIpleri = [];
		$vpsIpResult = [];
		foreach($vps->ips as $VpsIp){
			
			$ipParcala = explode('.', $VpsIp->ip);
			$ipSon = $ipParcala[3];
			unset($ipParcala[3]);
			$ipBirlestir = "";
			foreach($ipParcala as $ipParca){
				$ipBirlestir .= "{$ipParca}.";
			}
			$soaIp = substr($ipBirlestir, 0, -1);
							
			$soa = Rdnsdata::find()->where(['ana_ip' => $soaIp, 'type' => 'SOA'])->one();
			if($soa){
				$rdns = Rdns::findOne($soa->rdns_id);
				if($rdns){
					$rdnsTypes = json_decode($rdns->rdns_types);
					if(!is_array($rdnsTypes)){
						$rdnsTypes = array();
					}
					$vpsIpleri[] = array('ip' => $VpsIp->ip, 'id' => $VpsIp->id, 'types' => $rdnsTypes);
					$vpsIpResult[] = $VpsIp->ip;
				}
			}
		
		}
				
		$rdnsDatas = Rdnsdata::find()->where(['ana_ip' => $vpsIpResult])->all();
		$rdnsWaitPendingInsert = Rdnsdurum::find()->where(['ana_ip' => $vpsIpResult, 'pending_type' => 1, 'status' => 0])->all();
		
        $used_bandwidth = ($used_bandwidth ? $used_bandwidth->used : 0);

        if (!$vps) {
            throw new NotFoundHttpException(Yii::t('app', 'Not found anything'));
        }

        $this->layout = 'access';

        return $this->render('access', [
            'vps' => $vps,
            'used_bandwidth'=>$used_bandwidth,
            'rdns_datas'=>$rdnsDatas,
            'rdnsWaitPendingInsert' =>$rdnsWaitPendingInsert,
            'VpsIpleri' =>$vpsIpleri
        ]);
    }

    public function actionBandwidth()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        if (!($vpsId = Yii::$app->request->post('vpsId'))) {
            return ['status' => 0];
        }

        $times = [];

        for ($i=10; $i>=0; $i--) {
            $date = date('Y-m-d', strtotime("-{$i} days"));
            $times[$date] = ['date' => $date, 'total' => 0];
        }

        $time = time() - (20*86400);

        $sql = "SELECT used as total, FROM_UNIXTIME(created_at, '%Y-%m-%d') as date FROM bandwidth WHERE vps_id = :id AND status = :status AND created_at >= $time";

        Yii::$app->db->createCommand('SET time_zone = "+03:30"')->execute();

        $result = Yii::$app->db->createCommand($sql);
        $result->bindValue(':id', $vpsId);
        $result->bindValue(':status', Bandwidth::STATUS_ACTIVE);
        $result = $result->queryAll();

        foreach ($result as $data) {
            if (isset($times[$data['date']])) {
                $times[$data['date']]['total'] = $data['total']; // mb
            }
        }

        return array_values($times);
    }

    public function actionSelectOs()
    {
        $id = Yii::$app->request->post('vpsId');
		
		$vps = Vps::findOne($id);
		
		if(isset($vps->plan->os_lists) && !empty($vps->plan->os_lists)){
		
			$osListesi = json_decode($vps->plan->os_lists);
			if(is_array($osListesi)){
				$WhereExecute = $osListesi;
			}
			else{
				$WhereExecute = array();
			}

			$operationSystems = Os::findAll($WhereExecute);
		
		}
		else{
			$operationSystems = Os::find()->all();
		}

        return $this->renderAjax('select-os', [
            'operationSystems' => $operationSystems,
            'vpsId' => $id,
        ]);
    }
	
	public function actionRdnsPendingEdit(){
		$rdnsModuleSatanHosting = SatanHosting::SiteVpsRdnsPendingEdit();
		if($rdnsModuleSatanHosting['ok'] == true){
			return $this->renderAjax('rdns-edit-pending', $rdnsModuleSatanHosting['response']);
		}
		else{
			return $rdnsModuleSatanHosting['response'];
		}
	}
	
	public function actionRdnsPendingDelete(){
		$rdnsModuleSatanHosting = SatanHosting::SiteVpsRdnsPendingDelete();
		if($rdnsModuleSatanHosting['ok'] == true){
			return $this->renderAjax('rdns-pending-delete-success', $rdnsModuleSatanHosting['response']);
		}
		else{
			return $rdnsModuleSatanHosting['response'];
		}
	}
	
	public function actionChangeRdnsPending(){
		return SatanHosting::PostChangeRdnsPending();
	}
	
    public function actionChangeRdns()
    {
		return SatanHosting::PostChangeRdns();
    }
	
    public function actionRdnsDelete()
    {
		$rdnsModuleSatanHosting = SatanHosting::SiteVpsRdnsDelete();
		if($rdnsModuleSatanHosting['ok'] == true){
			return $this->renderAjax('rdns-delete', $rdnsModuleSatanHosting['response']);
		}
		else{
			return $rdnsModuleSatanHosting['response'];
		}
    }
	
    public function actionRdnsEdit()
    {
		$rdnsModuleSatanHosting = SatanHosting::SiteVpsRdnsEdit();
		if($rdnsModuleSatanHosting['ok'] == true){
			return $this->renderAjax('rdns-edit', $rdnsModuleSatanHosting['response']);
		}
		else{
			return $rdnsModuleSatanHosting['response'];
		}
    }
	
	public function actionCreateRdns(){
		return SatanHosting::PostCreateRdns();
	}
	
	public function actionRdnsSelectChangeCreate(){
		$rdnsModuleSatanHosting = SatanHosting::SiteVpsRdnsSelectChangeCreate();
		if($rdnsModuleSatanHosting['ok'] == true){
			return $this->renderAjax('rdns-select-change-create', $rdnsModuleSatanHosting['response']);
		}
		else{
			return $rdnsModuleSatanHosting['response'];
		}
	}
	
	public function actionRdnsCreate(){
		$rdnsModuleSatanHosting = SatanHosting::SiteVpsRdnsCreate();
		if($rdnsModuleSatanHosting['ok'] == true){
			return $this->renderAjax('rdns-create', $rdnsModuleSatanHosting['response']);
		}
		else{
			return $rdnsModuleSatanHosting['response'];
		}
	}

	public function actionRdnsResultChange($id){
		$rdnsModuleSatanHosting = SatanHosting::SiteVpsRdnsResultChange($id);
		if($rdnsModuleSatanHosting != false){
			return $this->renderAjax('rdns-result-change', $rdnsModuleSatanHosting);
		}
	}

    public function actionLoadHost()
    {
        $id = Yii::$app->request->post('vpsId');

        $vps = Vps::findOne($id);

        return $this->renderAjax('load-host', compact('vps'));
    }

    public function actionChangeHost()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $id = Yii::$app->request->post('id');

        if (empty($id)) {
            return ['ok' => false];
        }

        $vps = Vps::findOne($id);

        if (!$vps) {
            return ['ok' => false];
        }

        $vps->hostname = Yii::$app->request->post('host');
        $vps->save(false);

        /*$data = [
    		'server' => $vps->server->getAttributes(),
    		'vps' => $vps->getAttributes(),
            'ip' => $vps->ip->getAttributes(),
    	];

    	$api = new Api;
    	$api->setUrl(Yii::$app->setting->api_url);
    	$api->setData($data);

    	$result = $api->request(Api::ACTION_CREATE_SHOT);

    	if (!$result) {
    		return ['ok' => false];
    	}*/

        return ['ok' => true];
    }

    public function actionOs($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $os = Os::findOne($id);

        if (!$os) {
            return ['ok' => false];
        }

        if (stripos($os->name, 'windows') !== false) {
            $status = 1;
        } else {
            $status = 2;
        }

        return ['ok' => true, 'status' => $status];
    }

    public function actionSelectShot()
    {
        $id = Yii::$app->request->post('vpsId');

        $vps = Vps::find()->where(['id' => $id, 'user_id' => Yii::$app->user->id])->one();

        return $this->renderAjax('select-shot', compact('id', 'vps'));
    }

    public function actionCreateShot()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $id = Yii::$app->request->post('id');

        if (empty($id)) {
            return ['ok' => false];
        }

        $vps = Vps::findOne($id);

        if (!$vps) {
            return ['ok' => false];
        }

        if ($vps->getCannotSnapshot()) {
            return ['ok' => false];
        }

        $data = [
    		'server' => $vps->server->getAttributes(),
    		'vps' => $vps->getAttributes(),
            'ip' => $vps->ip->getAttributes(),
    	];

    	$api = new Api;
    	$api->setUrl(Yii::$app->setting->api_url);
    	$api->setData($data);

    	$result = $api->request(Api::ACTION_CREATE_SHOT);

    	if (!$result) {
    		return ['ok' => false];
    	}

        return ['ok' => true];
    }

    public function actionReverseShot()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $id = Yii::$app->request->post('id');

        if (empty($id)) {
            return ['ok' => false];
        }

        $vps = Vps::findOne($id);

        if (!$vps) {
            return ['ok' => false];
        }

        $data = [
    		'server' => $vps->server->getAttributes(),
    		'vps' => $vps->getAttributes(),
            'ip' => $vps->ip->getAttributes(),
    	];

    	$api = new Api;
    	$api->setUrl(Yii::$app->setting->api_url);
    	$api->setData($data);

    	$result = $api->request(Api::ACTION_REVERSE_SHOT);

    	if (!$result) {
    		return ['ok' => false];
    	}

        return ['ok' => true];
    }

    public function actionSelectIso()
    {
        $id = Yii::$app->request->post('vpsId');

        $items = Iso::find()->all();

        return $this->renderAjax('select-iso', [
            'items' => $items,
            'vpsId' => $id,
        ]);
    }

    public function actionIso()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $id = Yii::$app->request->post('id');
        $iso = Yii::$app->request->post('iso');

        if (empty($id) || empty($iso)) {
            return ['ok' => false];
        }

        $vps = Vps::findOne($id);

        if (!$vps) {
            return ['ok' => false];
        }

        $iso = Iso::findOne($iso);

        if (!$iso) {
            return ['ok' => false];
        }

        $datastore = Datastore::find()->where(['server_id' => $vps->server->id, 'is_default' => 1])->one();

        if (!$datastore) {
            return ['ok' => false];
        }

        $data = [
    		'server' => $vps->server->getAttributes(),
            'datastore' => $datastore->getAttributes(),
    		'vps' => $vps->getAttributes(),
            'iso' => $iso->getAttributes(),
            'ip' => $vps->ip->getAttributes(),
    	];

    	$api = new Api;
    	$api->setUrl(Yii::$app->setting->api_url);
    	$api->setData($data);

    	$result = $api->request(Api::ACTION_ISO);

    	if (!$result) {
    		return ['ok' => false];
    	}

        return ['ok' => true];
    }

    public function actionIsou()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $id = Yii::$app->request->post('id');

        if (empty($id)) {
            return ['ok' => false];
        }

        $vps = Vps::findOne($id);

        if (!$vps) {
            return ['ok' => false];
        }

        $data = [
    		'server' => $vps->server->getAttributes(),
    		'vps' => $vps->getAttributes(),
            'ip' => $vps->ip->getAttributes(),
    	];

    	$api = new Api;
    	$api->setUrl(Yii::$app->setting->api_url);
    	$api->setData($data);

    	$result = $api->request(Api::ACTION_ISOU);

    	if (!$result) {
    		return ['ok' => false];
    	}

        return ['ok' => true];
    }

    public function actionStep($id)
    {
		$SatanHostingStepInstall = SatanHosting::StepInstall($id);
		return $SatanHostingStepInstall;
    }

    public function actionInstall()
    {
		Yii::$app->response->format = Response::FORMAT_JSON;
		$SatanHostingVpsID = Yii::$app->request->post('vpsId');
		$SatanHostingVps = Vps::find()->where(['user_id' => Yii::$app->user->id, 'id' => $SatanHostingVpsID])->active()->one();
		$OsLists = json_decode($SatanHostingVps->plan->os_lists);
		if(is_array($OsLists)){
			$SatanHostingOsID = Yii::$app->request->post('osId');
			if(!in_array($SatanHostingOsID, $OsLists)){
				$errorFound = ['status' => 0, 'error' => 'kurulum'];
			}
		}
		if(isset($errorFound)){
			return $errorFound;
		}
		else{
			$SatanHostingOsInstall = SatanHosting::InstallOs();
			return $SatanHostingOsInstall;
		}
    }

    public function actionExtendForm()
    {
        $vpsId = Yii::$app->request->post('vpsId');

        return $this->renderAjax('extend-form', compact('vpsId'));
    }

    public function actionExtend()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        if ((!$vpsId = Yii::$app->request->post('vpsId'))) {
            return ['status' => 0];
        }

        $vps = Vps::find()->where(['user_id' => Yii::$app->user->id, 'id' => $vpsId])->active()->one();

        if (!$vps) {
            return ['status' => 0];
        }
        
        if (!$vps->os) {
            return ['status' => 0];   
        }

        $data = [
            'ip' => $vps->ip->getAttributes(),
            'vps' => $vps->getAttributes(),
            'os' => $vps->os->getAttributes(),
            'server' => $vps->server->getAttributes(),
            'username' => Yii::$app->request->post('username'),
            'password' => Yii::$app->request->post('password'),
        ];

        $api = new Api;

        $api->setUrl(Yii::$app->setting->api_url);
        $api->setData($data);

        $result = $api->request(Api::ACTION_EXTEND);

        if ($result) {
            return ['status' => 1];
        }

        return ['status' => 0];
    }

    public function actionConsoleForm()
    {
        $vpsId = Yii::$app->request->post('vpsId');
        $port = mt_rand(0, 9999);

        return $this->renderAjax('console-form', compact('port', 'vpsId'));
    }

    public function actionConsole()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        if ((!$vpsId = Yii::$app->request->post('vpsId'))) {
            return ['status' => 0];
        }

        $vps = Vps::find()->where(['user_id' => Yii::$app->user->id, 'id' => $vpsId])->active()->one();

        if (!$vps) {
            return ['status' => 0];
        }

        $data = [
            'ip' => $vps->ip->getAttributes(),
            'vps' => $vps->getAttributes(),
            //'os' => $vps->os->getAttributes(),
            'server' => $vps->server->getAttributes(),
            'password' => Yii::$app->request->post('password'),
            'port' => Yii::$app->request->post('port'),
        ];

        $api = new Api;

        $api->setUrl(Yii::$app->setting->api_url);
        $api->setData($data);

        $api->setTimeout(40);

        $result = $api->request(Api::ACTION_CONSOLE);

        $address = Url::base(true);

        return ['status' => 1, 'address' => $address];
    }

    public function actionStart()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        if (!($vpsId = Yii::$app->request->post('vpsId'))) {
            return ['status' => 0];
        }

        $vps = Vps::find()->where(['user_id' => Yii::$app->user->id, 'id' => $vpsId])->active()->one();

        if (!$vps) {
            return ['status' => 0];
        }

        $data = [
            'ip' => $vps->ip->getAttributes(),
            'vps' => $vps->getAttributes(),
            'server' => $vps->server->getAttributes(),
        ];

        $api = new Api;

        $api->setUrl(Yii::$app->setting->api_url);
        $api->setData($data);

        $result = $api->request(Api::ACTION_START);

        if (!empty($api->result->type) && $api->result->type == 'os') {
            return ['status' => 0, 'message' => Yii::t('app', 'OS was not found')];
        }

        if ($result) {
            $action = new VpsAction;
            $action->vps_id = $vps->id;
            $action->action = VpsAction::ACTION_START;
            $action->save(false);

            return ['status' => 1];
        }

        return ['status' => 0];
    }
	
	public function actionIpSettingChange(){
		
		Yii::$app->response->format = Response::FORMAT_JSON;
		
		$id = Yii::$app->request->post('ip');
		
		$password = Yii::$app->request->post('password');
				 
        $model = VpsIp::find()->where(['ip_id' => $id])->one();

        if (!$model) {
			return ['error' => 'Kullanılan İp Adresi Bulunamadı'];
        }
				
		$ip = Ip::findOne($id);
		
		if(!$ip){
			return ['error' => 'İP Adresi bulunamadı'];
		}
		
        $idVps = $model->vps_id;

        $vps = Vps::find()->where(['id' => $idVps, 'user_id' => Yii::$app->user->id])->one();
		
		if(!$vps){
			return ['error' => 'Vps Bulunamadı.'];
		}
		
		if($id == $vps->ip->id){
			return ['error' => "Varsayılan IP Adresiniz Zaten : <b>{$vps->ip->ip}</b>"];
		}
		
		$old_address = $vps->ip->ip;
						
		$tumIpler = VpsIp::find()->where(['vps_id' => $idVps])->all();
		
		$ipArray = array( 0 => array('vps_id' => $idVps, 'ip_id' => $model->ip_id));
		
		foreach($tumIpler as $IpDetail){
			if($ipArray[0]['ip_id'] == $IpDetail->ip_id) continue;
			$ipArray[] = array('vps_id' => $IpDetail->vps_id, 'ip_id' => $IpDetail->ip_id);
		}
		
		$username = $vps->os->username;
		
        $data = [
            'ip' => $ip->getAttributes(),
            'vps' => $vps->getAttributes(),
            'server' => $vps->server->getAttributes(),
			'password' => $password,
			'username' => $username,
			'os' => $vps->os->operation_system,
			'old_address' => $old_address
        ];

        $api = new Api;

        $api->setUrl(Yii::$app->setting->api_url);
        $api->setData($data);

        $result = $api->request(Api::ACTION_CHANGEIP);
		
		if (!$result) {
			return ['error' => 'Sistem hatası meydana geldi.'];
		}
		else{

			foreach($tumIpler as $IpDetail){
				$ipSil = VpsIp::findOne($IpDetail->id);
				$ipSil->delete();
			}
			
			foreach($ipArray as $ipValue){
			
				$insert = new VpsIp;

				$insert->vps_id = $ipValue['vps_id'];
				$insert->ip_id = $ipValue['ip_id'];

				$insert->save(false);
			
			}
			
			return ['ok' => true, 'success' => "Varsayılan ip başarıyla değiştirilmiştir. <br><br> Yeni giriş yapacağınız ip adresi : <b>{$ip->ip}</b>"];
		
		}
		 
	}
	
	public function actionIpSettingView(){
		
		$id = Yii::$app->request->post('vpsId');
		
        $vps = Vps::find()->where(['user_id' => Yii::$app->user->id, 'id' => $id])->active()->one();
		
		$vpsIps = [];
		
		foreach($vps->ips as $vpsIpArray){
			if($vpsIpArray->ip == $vps->ip->ip) continue;
			$vpsIps[] = $vpsIpArray->ip;
		}
		
		if(count($vpsIps) > 0){
			if($vps){
				return $this->renderAjax('ip-setting-view', compact('vps'));
			}
			else{
				return 'Vps Bulunamadı.';
			}
		}
		else{
			return 'Bu işlemi kullanabilmek için birden fazla IP Adresinizin olması gerekir.';
		}
		
	}
	
    public function actionChangePassword()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        if (!($vpsId = Yii::$app->request->post('vpsId'))) {
            return ['status' => 0, 'error' => 'Vps bulunamadı'];
        }
		
		$password = Yii::$app->request->post('oldpassword');
		
		$newpassword = Yii::$app->request->post('newpassword');
		
		$newpasswordretry = Yii::$app->request->post('newpasswordretry');
		
		if(empty($newpassword)){
            return ['status' => 0, 'error' => 'Şifrenizi boş bırakamazsınız.'];
		}
		else if($newpassword != $newpasswordretry){
            return ['status' => 0, 'error' => 'Şifreleriniz eşleşmedi.'];
		}

        $vps = Vps::find()->where(['user_id' => Yii::$app->user->id, 'id' => $vpsId])->active()->one();

        if (!$vps) {
            return ['status' => 0, 'error' => 'Vps bulunamadı'];
        }
		
		$username = $vps->os->username;

        $data = [
            'ip' => $vps->ip->getAttributes(),
            'vps' => $vps->getAttributes(),
            'server' => $vps->server->getAttributes(),
			'password' => $password,
			'username' => $username,
			'newpassword' => $newpassword,
			'os' => $vps->os->operation_system
        ];

        $api = new Api;
        $api->setUrl(Yii::$app->setting->api_url);
        $api->setData($data);

        $result = $api->request(Api::ACTION_CHANGEPASSWORD);

        if (!empty($api->result->type) && $api->result->type == 'os') {
            return ['status' => 0, 'message' => Yii::t('app', 'VM did not found, Please install an Operating System.'), 'error' => Yii::t('app', 'VM did not found, Please install an Operating System.')];
        }

        if ($result) {
			$vps->password = $newpassword;
			if($vps->save(false)){
				return ['status' => 1];
			}
			else{
				return ['status' => 0, 'error' => 'Sistem hatası meydana geldi.'];
			}
        }

        return ['status' => 0, 'error' => 'Lütfen daha sonra tekrar deneyiniz.'];
    }

    public function actionStop()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        if (!($vpsId = Yii::$app->request->post('vpsId'))) {
            return ['status' => 0];
        }

        $vps = Vps::find()->where(['user_id' => Yii::$app->user->id, 'id' => $vpsId])->active()->one();

        if (!$vps) {
            return ['status' => 0];
        }

        $data = [
            'ip' => $vps->ip->getAttributes(),
            'vps' => $vps->getAttributes(),
            'server' => $vps->server->getAttributes(),
        ];

        $api = new Api;
        $api->setUrl(Yii::$app->setting->api_url);
        $api->setData($data);

        $result = $api->request(Api::ACTION_STOP);

        if (!empty($api->result->type) && $api->result->type == 'os') {
            return ['status' => 0, 'message' => Yii::t('app', 'VM did not found, Please install an Operating System.')];
        }

        if ($result) {
            $action = new VpsAction;
            $action->vps_id = $vps->id;
            $action->action = VpsAction::ACTION_STOP;
            $action->save(false);

            return ['status' => 1];
        }

        return ['status' => 0];
    }

    public function actionRestart()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        if (!($vpsId = Yii::$app->request->post('vpsId'))) {
            return ['status' => 0];
        }

        $vps = Vps::find()->where(['user_id' => Yii::$app->user->id, 'id' => $vpsId])->active()->one();

        if (!$vps) {
            return ['status' => 0];
        }

        $data = [
            'ip' => $vps->ip->getAttributes(),
            'vps' => $vps->getAttributes(),
            'server' => $vps->server->getAttributes(),
        ];

        $api = new Api;
        $api->setUrl(Yii::$app->setting->api_url);
        $api->setData($data);

        $result = $api->request(Api::ACTION_RESTART);

        if (!empty($api->result->type) && $api->result->type == 'os') {
            return ['status' => 0, 'message' => Yii::t('app', 'VM did not found, Please install an Operating System.')];
        }

        if ($result) {
            $action = new VpsAction;
            $action->vps_id = $vps->id;
            $action->action = VpsAction::ACTION_RESTART;
            $action->save(false);

            return ['status' => 1];
        }

        return ['status' => 0];
    }

    public function actionStatus()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        if (!($vpsId = Yii::$app->request->post('vpsId'))) {
            return ['status' => 0];
        }

        $vps = Vps::find()->where(['user_id' => Yii::$app->user->id, 'id' => $vpsId])->active()->one();

        if (!$vps) {
            return ['status' => 0];
        }

        $data = [
            'ip' => $vps->ip->getAttributes(),
            'vps' => $vps->getAttributes(),
            'server' => $vps->server->getAttributes(),
        ];

        $api = new Api;
        $api->setUrl(Yii::$app->setting->api_url);
        $api->setData($data);

        $result = $api->request(Api::ACTION_STATUS);

        if (!$result) {
            return ['status' => 0];
        }

        if ($result->power == 'on') {
            return ['status' => 1];
        } else {
            return ['status' => 2];
        }
    }

    public function actionAdvancedStatus()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $userId = Yii::$app->user->id;
        $vpsId = Yii::$app->request->post('vpsId');

        $vps = Vps::find()->where(['user_id' => $userId, 'id' => $vpsId])->active()->one();

        if (!$vps) {
            return ['ok' => false];
        }

        $data = [
            'ip' => $vps->ip->getAttributes(),
            'vps' => $vps->getAttributes(),
            'server' => $vps->server->getAttributes(),
        ];

        $api = new Api;
        $api->setUrl(Yii::$app->setting->api_url);
        $api->setData($data);

        $result = $api->request(Api::ACTION_STATUS);

        if (!$result) {
            return ['ok' => false];
        }

        $data = ['ok' => true];

        if ($result->power == 'on') {
            $data['power'] = 'Online';
        } else {
            $data['power'] = 'Offline';
        }

        if ($result->network == 'up') {
            $data['network'] = 'Up';
        } else {
            $data['network'] = 'Down';
        }

        return $data;
    }

    public function actionMonitor()
    {
        if (!($vpsId = Yii::$app->request->post('vpsId'))) {
            return false;
        }

        $vps = Vps::find()->where(['user_id' => Yii::$app->user->id, 'id' => $vpsId])->active()->one();

        if (!$vps) {
            return false;
        }

        $data = [
            'ip' => $vps->ip->getAttributes(),
            'vps' => $vps->getAttributes(),
            'server' => $vps->server->getAttributes(),
        ];

        $api = new Api;
        $api->setUrl(Yii::$app->setting->api_url);
        $api->setData($data);

        $result = $api->request(Api::ACTION_MONITOR);

        if (!$result) {
            return false;
        }

        return $this->renderAjax('monitor', [
            'vps' => $vps,
            'ram' => $result->ram,
            'usedRam' => $result->usedRam,
            'cpu' => $result->cpu,
            'usedCpu' => $result->usedCpu,
            'uptime' => Yii::$app->helper->calcTime($result->uptime),
        ]);
    }

    public function actionActionLog()
    {
        if (!($vpsId = Yii::$app->request->post('vpsId'))) {
            return false;
        }

        $actions = VpsAction::find()->where(['vps_id' => $vpsId])->orderBy('id DESC');

        $count = clone $actions;
        $pages = new Pagination(['totalCount' => $count->count()]);
        $pages->setPageSize(5);

        $actions = $actions->offset($pages->offset)->limit($pages->limit)->all();

        return $this->renderAjax('action-log', [
            'actions' => $actions,
            'pages' => $pages,
            'vpsId' => $vpsId,
        ]);
    }
}
