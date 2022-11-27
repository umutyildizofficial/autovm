<?php

namespace app\modules\admin\controllers;

use app\models\Ssh;
use app\models\VpsIp;
use app\extensions\Api;
use Yii;
use yii\web\Response;
use yii\web\Controller;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;
use yii\helpers\Url;


use app\models\Log;
use app\models\Ip;
use app\models\Os;
use app\models\Vps;
use app\models\SatanHosting;
use app\models\SatanHostingSettings;
use app\models\User;
use app\models\Rdns;
use app\models\Rdnsdata;
use app\models\Rdnsdurum;
use app\models\Plan;
use app\models\Server;
use app\models\Bandwidth;
use app\models\VpsAction;
use app\models\Datastore;
use app\modules\admin\filters\OnlyAdminFilter;
use yii\data\ActiveDataProvider;
use app\models\searchs\searchVps;

class VpsController extends Controller
{
    public function behaviors()
    {
        return [
            [
                'class' => OnlyAdminFilter::className(),
                'except' => ['access'],
            ],
        ];
    }

    public function actionIndex()
    {
        $searchModel = new searchVps();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }
	
    public function actionRdnsEdit()
    {
		$rdnsModuleSatanHosting = SatanHosting::SiteVpsRdnsEdit(2);
		if($rdnsModuleSatanHosting['ok'] == true){
			return $this->renderAjax('rdns-edit', $rdnsModuleSatanHosting['response']);
		}
		else{
			return $rdnsModuleSatanHosting['response'];
		}
    }
	
    public function actionRdnsDelete()
    {
		$rdnsModuleSatanHosting = SatanHosting::AdminVpsRdnsDelete();
		if($rdnsModuleSatanHosting['ok'] == true){
			return $this->renderAjax('rdns-delete-success', $rdnsModuleSatanHosting['response']);
		}
		else{
			return $rdnsModuleSatanHosting['response'];
		}
    }
	
	public function actionPlanChart(){
		return SatanHosting::AdminChartPlan();
	}
	
	public function actionOsChart(){
		return SatanHosting::AdminChartOs();
	}
	
	public function actionIpChart(){
		return SatanHosting::AdminChartIp();
	}
	
    public function actionChangeRdns()
    {
		return SatanHosting::PostChangeRdns(2);
    }
	
	public function actionRdnsPendingEdit(){
		$rdnsModuleSatanHosting = SatanHosting::SiteVpsRdnsPendingEdit(2);
		if($rdnsModuleSatanHosting['ok'] == true){
			return $this->renderAjax('rdns-edit-pending', $rdnsModuleSatanHosting['response']);
		}
		else{
			return $rdnsModuleSatanHosting['response'];
		}
	}
	
	public function actionRdnsPendingDelete(){
		$rdnsModuleSatanHosting = SatanHosting::SiteVpsRdnsPendingDelete(2);
		if($rdnsModuleSatanHosting['ok'] == true){
			return $this->renderAjax('rdns-pending-delete-success', $rdnsModuleSatanHosting['response']);
		}
		else{
			return $rdnsModuleSatanHosting['response'];
		}
	}
	
	public function actionChangeRdnsPending(){
		return SatanHosting::PostChangeRdnsPending(2);
	}
	
	public function actionCreateRdns(){
		return SatanHosting::PostCreateRdns(2);
	}
	
	public function actionRdnsCreateView($id){
		$rdnsModuleSatanHosting = SatanHosting::AdminVpsRdnsCreateView($id);
		if($rdnsModuleSatanHosting['ok'] == true){
			return $this->renderAjax('rdns-create-view', $rdnsModuleSatanHosting['response']);
		}
		else{
			return $rdnsModuleSatanHosting['response'];
		}
	}

    public function actionUpdate($id)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $vps = Vps::findOne($id);

        if (!$vps) {
            return ['ok' => false, 'a' => true];
        }

        $data = [
            'server' => $vps->server->getAttributes(),
            'vps' => $vps->getAttributes(),
            'ip' => $vps->ip->getAttributes(),
            'os' => $vps->os->getAttributes(),
            'datastore' => $vps->datastore->getAttributes(),
        ];

        if ($vps->plan) {
            $data['plan'] = $vps->plan->getAttributes();
        }
        
        if (!$vps->os) {
            return ['ok' => false];   
        }

        $api = new Api;
        $api->setUrl(Yii::$app->setting->api_url);
        $api->setData($data);

        $result = $api->request(Api::ACTION_UPDATE);

        if (!$result) {
            return ['ok' => false];
        }

        return ['ok' => true];
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

    public function actionTerminate($id)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $vps = Vps::findOne($id);
		
		if(isset($vps->os->name)){

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

			$result = $api->request(Api::ACTION_DELETE);

			if (!$result) {
				return ['ok' => false, 'status' => Status::ERROR_SYSTEM];
			}
			
			SatanHosting::AutomaticDelete($vps);
			
			$vps->delete();
			
			Log::log(sprintf('%s sunucusu %s tarafından sonlandırıldı!', $vps->ip->ip, Yii::$app->user->identity->fullName));

			return ['ok' => true];
			
		}
		else{
			$vps->delete();
			SatanHosting::AutomaticDelete($vps);
			return ['ok' => true];
		}
    }
	
	public function actionView($id)
	{
		$vps = Vps::findOne($id);

		if (!$vps) {
			throw new NotFoundHttpException(Yii::t('app', 'Hiçbir şey bulunamadı'));
		}

		$vpsIpleri = [];
		foreach($vps->ips as $vpsIp){
			$vpsIpleri[] = $vpsIp->ip;
		}
		
		$rdnsDatas = Rdnsdata::find()->where(['ana_ip' => $vpsIpleri])->all();
		$rdnsWaitPendingInsert = Rdnsdurum::find()->where(['ana_ip' => $vpsIpleri, 'pending_type' => 1, 'status' => 0])->all();

        $used_bandwidth = Bandwidth::find()->where(['vps_id'=>$id])->active()->orderBy('id DESC')->one();

        $used_bandwidth = ($used_bandwidth ? $used_bandwidth->used : 0);

        $ips = Ip::find()->leftJoin('vps_ip', 'vps_ip.ip_id = ip.id')
            ->andWhere('vps_ip.id IS NULL')
            ->andWhere(['ip.server_id' => [$vps->server_id, $vps->server->parent_id]])
            ->all();

        $data = [
            'server' => $vps->server->getAttributes(),
            'vps' => $vps->getAttributes(),
        ];

        $api = new Api;
        $api->setUrl(Yii::$app->setting->api_url);
        $api->setData($data);

        $result = $api->request(Api::ACTION_LOG);
		
		if(isset($vps->plan->os_lists) && !empty($vps->plan->os_lists)){
		
			$osListesi = json_decode($vps->plan->os_lists);
			if(is_array($osListesi)){
				$WhereExecute = $osListesi;
			}
			else{
				$WhereExecute = array();
			}
			
			$osfind = Os::findAll($WhereExecute);
		
		}
		else{
			$osfind = Os::find()->all();
		}
		
		$ipObject = Ip::findOne($vps->vpsip->ip_id);

		return $this->render('view', [
			'vps' => $vps,
            'used_bandwidth'=>$used_bandwidth,
            'ips' => $ips,
            'result' => $result,
			'ipObject' => $ipObject,
            'os' => $osfind,
            'rdns_datas'=>$rdnsDatas,
            'rdnsWaitPendingInsert' =>$rdnsWaitPendingInsert
		]);
	}

    public function actionAccess($key, $id)
    {
        $user = User::find()->where(['auth_key' => $key, 'is_admin' => User::IS_ADMIN])->active()->one();

        if (!$user) {
            throw new NotFoundHttpException;
        }

        $vps = Vps::findOne($id);

        if (!$vps) {
            throw new NotFoundHttpException(Yii::t('app', 'Hiçbir şey bulunamadı'));
        }

        Yii::$app->user->login($user);

        $used_bandwidth = Bandwidth::find()->where(['vps_id'=>$id])->active()->orderBy('id DESC')->one();

        $used_bandwidth = ($used_bandwidth ? $used_bandwidth->used : 0);

        $ips = Ip::find()->leftJoin('vps_ip', 'vps_ip.ip_id = ip.id')
            ->andWhere('vps_ip.id IS NULL')
            ->andWhere(['ip.server_id' => $vps->server_id])
            ->all();

        $this->layout = 'access';

        return $this->render('access', [
            'vps' => $vps,
            'used_bandwidth'=>$used_bandwidth,
            'ips' => $ips,
            'os' => Os::find()->all(),
        ]);
    }

    public function actionStep($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $vps = Vps::findOne($id);

        if (!$vps) {
            return ['ok' => false];
        }

        $data = [
            'server' => $vps->server->getAttributes(),
            'vps' => $vps->getAttributes(),
        ];

        $api = new Api;
        $api->setUrl(Yii::$app->setting->api_url);
        $api->setData($data);

        $result = $api->request(Api::ACTION_STEP);

        if (empty($result)) {
            return ['ok' => false];
        }

        return ['ok' => true, 'step' => $result->step, 'percent' => $result->percent];
    }

    public function actionInstall($id)
    {
		
        Yii::$app->session->set('password',  Yii::$app->request->post('password'));

        Yii::$app->response->format = Response::FORMAT_JSON;

        if (!($password = Yii::$app->request->post('password')) || !($vpsId = Yii::$app->request->post('vpsId'))) {
            return ['status' => 0, 'error' => 'password'];
        }
				
        $vps = Vps::find()->where(['id' => $vpsId])->active()->one();
		
		$OsLists = json_decode($vps->plan->os_lists);
		if(is_array($OsLists)){
			$SatanHostingOsID = Yii::$app->request->post('osId');
			if(!in_array($SatanHostingOsID, $OsLists)){
				$errorFound = ['status' => 0, 'error' => 'os'];
			}
		}
		if(!isset($errorFound)){

        if (!$vps) {
            return ['status' => 0, 'error' => 'vps', 'userid' => Yii::$app->user->id];
        }

        $limit = Yii::$app->setting->change_limit;

        if ($limit <= $vps->change_limit) {
            return ['status' => 0, 'error' => 'limit'];
        }

        $os = Os::findOne(Yii::$app->request->post('osId'));

        if (!$os) {
            return ['status' => 0, 'error' => 'os'];
        }

        // validate password
        if (!preg_match('/(?=.*[A-Z]+)(?=.*[a-z]+)(?=.*[0-9]+)/', $password)) {
            return ['status' => 1];
        }

        $vps->os_id = $os->id;
        $vps->password = $password;

        if (!$vps->save(false)) {
            return ['status' => 0, 'error' => 'save'];
        }

        // raw password
        $vps->password = $password;

        $extend = Yii::$app->request->post('extend');
				
		$osAttributes = $vps->os->getAttributes();
		
		$ipAttributes = $vps->ip->getAttributes();
		
		$Os_Find_SatanHosting = SatanHosting::Os_Find($vps->os->name, $ipAttributes);
				
		if(isset($Os_Find_SatanHosting['network'])){
				
			$networkSettings = $Os_Find_SatanHosting['network'];
									
			$data = [
				'ip' => $ipAttributes,
				'vps' => $vps->getAttributes(),
				'os' => $osAttributes,
				'datastore' => $vps->datastore->getAttributes(),
				#'defaultDatastore' => $datastore->getAttributes(),
				'server' => $vps->server->getAttributes(),
				'extend' => $extend ? 1 : 2,
				'networkayari' => $networkSettings,
			];
				
			if ($vps->plan) {
				$data['plan'] = $vps->plan->getAttributes();
			}
				
			$action = new VpsAction;

			$action->vps_id = $vps->id;
			$action->action = VpsAction::ACTION_INSTALL;
			$action->description = $vps->os->name;

			$action->save(false);

			$api = new Api;
			$api->setUrl(Yii::$app->setting->api_url);
			$api->setData($data);

			$api->setTimeout(15);

			$result = $api->request(Api::ACTION_INSTALL);

			$vps->change_limit += 1;
			$vps->save(false);

			return ['status' => 0, 'error' => 'none', 'password' => Yii::$app->request->post('password'), 'osid' => Yii::$app->request->post('osId')];
			
			}
			else{
				return ['status' => 0, 'error' => 'kurulum'];
			}
		}
		else{
			return $errorFound;
		}
		
    }

    public function actionAdd($id)
    {
        $data = (object) Yii::$app->request->post('data');

        $vps = Vps::findOne($id);

        if (!$vps) {
            throw new ServerErrorHttpException;
        }

        $ip = Ip::findOne($data->ip);

        if (!$ip) {
            throw new ServerErrorHttpException;
        }

        $model = new VpsIp;

        $model->vps_id = $vps->id;
        $model->ip_id = $ip->id;

        $model->save(false);

        return $this->redirect(['view', 'id' => $id]);
    }
	
	public function actionMacView(){
		return $this->renderAjax('mac-view');
	}
	
	public function actionMac(){
		
		$servers = Server::find()->all();
		
		$mac = Yii::$app->request->post('mac');
		
		if(empty($mac)){
			return 'Mac adresini boş bırakamazsınız';
		}
		else{
		
			$sonuc = [];
			
			foreach($servers as $server){
				$data = [
					'server' => $server->getAttributes(),
					'mac' => $mac
				];
				
				$api = new Api;

				$api->setUrl(Yii::$app->setting->api_url);
				$api->setData($data);

				$result = $api->request(Api::ACTION_MAC);
				
				$sonuc[$server->id]['detail'] = array('name' => $server->name, 'ip' => $server->ip);
				
				if(isset($result) && !empty($result)){
					
					preg_match_all('@vmfs/volumes/(.*?)/(.*?)/@si',$result,$resultmatch);
					
					if(isset($resultmatch[1][0]) && isset($resultmatch[2][0])){
						$sonuc[$server->id]['data'][] = array('datastore' => $resultmatch[1][0], 'name' => $resultmatch[2][0]);
						break;
					}
					
				}
			}
			
			if(count($sonuc) > 0){
				$returnContent = "";
				foreach($sonuc as $sonuclar){
					$returnContent .= "<font color=\"green\">{$sonuclar['detail']['name']} / {$sonuclar['detail']['ip']} Fiziksel sunucu sonuçları : </font><br><br>";
					if(isset($sonuclar['data'])){
						if(count($sonuclar['data']) > 0){
							foreach($sonuclar['data'] as $dataResult){
								$returnContent .= "Datastore : <b>{$dataResult['datastore']}</b> <br>";
								$returnContent .= "Sunucu adı : <b>{$dataResult['name']}</b><br>";
							}
						}
					}
					else{
						$returnContent .= "<br>Sonuç yok.<br>";
					}
				}
				return $returnContent;
			}
			else{
				return false;
			}
		
		}
		
	}
	
    public function actionChangeip($id)
    {
		
        $model = VpsIp::find()->where(['ip_id' => $id])->one();

        if (!$model) {
            throw new NotFoundHttpException;
        }
		
		$ip = Ip::findOne($id);
		
		if(!$ip){
			throw new NotFoundHttpException;
		}
		
        $idVps = $model->vps_id;

        $vps = Vps::findOne($idVps);
		
		$old_address = $vps->ip->ip;
						
		$tumIpler = VpsIp::find()->where(['vps_id' => $idVps])->all();
		
		$ipArray = array( 0 => array('vps_id' => $idVps, 'ip_id' => $model->ip_id));
		
		foreach($tumIpler as $IpDetail){
			if($ipArray[0]['ip_id'] == $IpDetail->ip_id) continue;
			$ipArray[] = array('vps_id' => $IpDetail->vps_id, 'ip_id' => $IpDetail->ip_id);
		}
		
		$username = $vps->os->username;
		$password = $vps->password;
		
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
			return $this->redirect(['view', 'id' => $idVps, 'hata' => 'var']);
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
			
			
			return $this->redirect(['view', 'id' => $idVps]);
		
		}
		

    }

    public function actionDel($id)
    {
        $model = VpsIp::find()->where(['ip_id' => $id])->one();

        if (!$model) {
            throw new NotFoundHttpException;
        }

        $id = $model->vps_id;

        $model->delete();

        return $this->redirect(['view', 'id' => $id]);
    }

	public function actionResetBandwidth($id)
	{
		$vps = Vps::findOne($id);

		if ($vps) {

            $vps->notify_at = null;
            $vps->save(false);
            
		    $a = Bandwidth::find()->where(['vps_id' => $vps->id])->orderBy('id DESC')->limit(1)->one();
		    $b = Bandwidth::find()->where(['vps_id' => $vps->id])->orderby('id DESC')->limit(1)->offset(1)->one();

		    if ($a && $b) {
        	    $a->used = $a->pure_used = 0;
        	    $a->save(false);

        	    $b->used = $b->pure_used = 0;
        	    $b->save(false);
	        }
		}

		return $this->redirect(['/admin/vps/view', 'id' => $id]);
	}

    public function actionResetLimit($id)
    {
        $vps = Vps::findOne($id);

        if ($vps) {
            $vps->change_limit = 0;
            $vps->save(false);
        }

        return $this->redirect(['/admin/vps/view', 'id' => $id]);
    }

    public function actionCreate($id)
    {
        $model = new Vps();
        $model->user_id = $id;
        if(Yii::$app->request->isPost) {
            //print_r($_POST);exit;
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                $model->plan_type = $_POST['Vps']['plan_type'];
                if($model->plan_type==VpsPlansTypeCustom)
                {
                    $model->vps_ram = isset($_POST['Vps']['vps_ram']) && intval($_POST['Vps']['vps_ram']) ? $_POST['Vps']['vps_ram'] : 0;
                    $model->vps_hard = isset($_POST['Vps']['vps_hard']) ? $_POST['Vps']['vps_hard'] : 0;
                    if($model->vps_hard <21)
                    {
                        $model->addError('vps_hard',Yii::t('app','Disk boyutu 21GB ve üzeri olmalıdır!'));
                        return $this->sharedRender($model);
                    }
                    $model->vps_cpu_core = $_POST['Vps']['vps_cpu_core'];
                    $model->vps_cpu_mhz = $_POST['Vps']['vps_cpu_mhz'];
                    $model->vps_band_width = $_POST['Vps']['vps_band_width'];
                    $model->plan_id = 0;
                    //var_dump($model);exit;

                }
                else
                {

                }
                $model->os_id=0;
                $model->password='';
                Yii::$app->db->createCommand('set foreign_key_checks=0')->execute();
                if ($model->save()) {
                    //delete recent vps_ip
                    $oldIp = VpsIp::find()->where(['vps_id' => $model->id])->one();
                    if ($oldIp) {
                        $oldIp->delete();
                    }
                    //add new vps_ip
                    $ip = new VpsIp;
                    $ip->vps_id = $model->id;
                    $ip->ip_id = $model->ip_id;
                    $ip->save();
                    Yii::$app->session->addFlash('success', Yii::t('app', ''));

                    if ($model->view) {
                        return $this->redirect(['/admin/vps/view', 'id' => $model->id]);
                    } else {
                        return $this->refresh();
                    }
                }
                else {
                    var_dump($model->errors);
                    exit;
                }
            } else
            {
                var_dump($model->errors);exit;
            }

        }
        $model->plan_type = VpsPlansTypeDefault;
        return $this->sharedRender($model);
    }

    public function actionEdit($id)
    {
        $model = Vps::findOne($id);

        if (!$model) {
            throw new NotFoundHttpException(Yii::t('app', 'Hiçbir şey bulunamadı'));
        }
        Yii::$app->db->createCommand('set foreign_key_checks=0')->execute();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->plan_type = $_POST['Vps']['plan_type'];
            if($model->plan_type==VpsPlansTypeCustom)
            {
                $model->vps_ram = $_POST['Vps']['vps_ram'];
                $model->vps_hard = $_POST['Vps']['vps_hard'];
                $model->vps_cpu_core = $_POST['Vps']['vps_cpu_core'];
                $model->vps_cpu_mhz = $_POST['Vps']['vps_cpu_mhz'];
                $model->vps_band_width = $_POST['Vps']['vps_band_width'];
                $model->plan_id = 0;
                //var_dump($model);exit;

            }
            if ($model->save()) {
                //delete recent vps_ip
                //$oldIp = VpsIp::find()->where(['vps_id' => $model->id])->one();
                //if ($oldIp) {
                //    $oldIp->delete();
                //}
                //add new vps_ip
                //$ip = new VpsIp;
                $ip = VpsIp::find()->where(['vps_id' => $model->id])->one();
                if ($ip) {
                    $ip->vps_id = $model->id;
                    $ip->ip_id = $model->ip_id;
                    $ip->save();
                }
                Yii::$app->session->addFlash('success', Yii::t('app', 'Sunucu düzenlendi'));
				//turn off vps
                if($model->status == Vps::STATUS_INACTIVE)
                {
                    $data = [
                        'ip' => $model->ip->getAttributes(),
                        'vps' => $model->getAttributes(),
                        'server' => $model->server->getAttributes(),
                    ];

                    $api = new Api();
                    $api->setUrl(Yii::$app->setting->api_url);
                    $api->setData($data);
                    $result = $api->request(Api::ACTION_SUSPEND);
                    if ($result) {
                        $action = new VpsAction;
                        $action->vps_id = $model->id;
                        $action->action = VpsAction::ACTION_SUSPEND;
                        $action->save(false);
                    }

                }
                else
                {
                    $data = [
                        'ip' => $model->ip->getAttributes(),
                        'vps' => $model->getAttributes(),
                        'server' => $model->server->getAttributes(),
                    ];

                    $api = new Api();
                    $api->setUrl(Yii::$app->setting->api_url);
                    $api->setData($data);
                    $result = $api->request(Api::ACTION_START);
                    if ($result) {
                        $action = new VpsAction;
                        $action->vps_id = $model->id;
                        $action->action = VpsAction::ACTION_START;
                        $action->save(false);
                    }
               }

                if ($model->view) {
                    return $this->redirect(['/admin/vps/view', 'id' => $model->id]);
                } else {
                    return $this->refresh();
                }

            }
        }

        return $this->sharedRender($model);
    }

    public function actionLog($id)
    {
        $vps = Vps::findOne($id);

        if (!$vps) {
            throw new NotFoundHttpException(Yii::t('app', 'Hiçbir şey bulunamadı'));
        }

        $logs = VpsAction::find()->where(['vps_id' => $vps->id])->orderBy('id DESC');

        $count = clone $logs;
        $pages = new Pagination(['totalCount' => $count->count()]);
        $pages->setPageSize(10);

        $logs = $logs->offset($pages->offset)->limit($pages->limit)->all();

        return $this->render('log', [
            'vps' => $vps,
            'logs' => $logs,
            'pages' => $pages,
        ]);
    }

    public function actionDatastores()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $id = Yii::$app->request->post('id');

        $datastores = Datastore::find()->where(['server_id' => $id])->all();

        return ArrayHelper::map($datastores, 'id', 'value');
    }

    public function actionIps()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $id = Yii::$app->request->post('id');

        $server = Server::findOne($id);

        if (!$server) {
            return ['ok' => false];
        }

        $parentId = $server->parent_id;

        $ips = Ip::find()->leftJoin('vps_ip', 'vps_ip.ip_id = ip.id')
            ->andWhere('vps_ip.id IS NULL')
            ->andWhere(['ip.server_id' => [$id, $parentId]])
            ->orderBy('ip.id ASC')
            ->all();

        return ArrayHelper::map($ips, 'id', 'ip');
    }

    public function actionDelete()
    {
        $data = Yii::$app->request->post('data');
        
        foreach ($data as $id) {
            
            $machine = Vps::find()->where(['id' => $id])->one();
			$machine->ip->getAttributes();
            
            if ($machine && $machine->ip) {
				
				SatanHosting::AutomaticDelete($machine);
				
                $deleted = $machine->delete();
				
                if ($deleted) {
                    Log::log(sprintf('%s sunucusu %s tarafından silindi!', $machine->ip->ip, Yii::$app->user->identity->fullName));   
                }
            }
        }

        return $this->redirect(Yii::$app->request->referrer);
    }

    protected function sharedRender($model)
    {
        return $this->render($model->isNewRecord ? 'create' : 'edit', [
            'model' => $model,
            'plans' => Plan::find()->all(),
            'servers' => Server::find()->all(),
            'operationSystems' => Os::find()->all(),
        ]);
    }

    public function actionStart($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $vps = Vps::findOne($id);

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

        $result = $api->request(Api::ACTION_START);

        if (!$result) {
            return ['ok' => false];
        }

        return ['ok' => true];
    }

    public function actionStop($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $vps = Vps::findOne($id);

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

        $result = $api->request(Api::ACTION_STOP);

        if (!$result) {
            return ['ok' => false];
        }

        return ['ok' => true];
    }

    public function actionRestart($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $vps = Vps::findOne($id);

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

        $result = $api->request(Api::ACTION_RESTART);

        if (!$result) {
            return ['ok' => false];
        }

        return ['ok' => true];
    }

    public function actionStatus($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $vps = Vps::findOne($id);

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

        if ($result->power <> 'on') {
            return ['ok' => false];
        }

        return ['ok' => true];
    }

    public function actionAdvancedStatus($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $vps = Vps::findOne($id);

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
            $data['power'] = 'Açık';
        } else {
            $data['power'] = 'Kapalı';
        }

        if ($result->network == 'up') {
            $data['network'] = 'Aktif';
        } else {
            $data['network'] = 'Pasif';
        }

        return $data;
    }

    public function createPassword($chars)
    {
        $result = null;

        for($i=0; $i<3; $i++) {
            $result .= $chars[mt_rand(0, strlen($chars)-1)];
        }

        return $result;
    }

    public function actionConsole($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $vps = Vps::findOne($id);

        if (!$vps) {
            return ['ok' => false];
        }

        $port = mt_rand(0, 9999);

        $password = $this->createPassword('abcdefghijklmnopqrstuvwxyz') . $this->createPassword('ABCDEFGHIJKLMNOPQRSTUVWXYZ') . $this->createPassword('0123456789');

        $data = ['vps' => $vps->attributes, 'ip' => $vps->ip->attributes, 'server' => $vps->server->attributes, 'port' => $port, 'password' => $password];

        $api = new Api;

        $api->setUrl(Yii::$app->setting->api_url);
        $api->setData($data);

        $api->setTimeout(40);

        $result = $api->request(Api::ACTION_CONSOLE);
		
        $address = Url::base(true);
		
        return ['ok' => true, 'address' => $address, 'port' => $port, 'password' => $password];
    }
}
