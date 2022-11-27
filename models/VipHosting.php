<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\helpers\Url;
use yii\web\Response;
use yii\web\Controller;
use yii\data\Pagination;
use yii\web\NotFoundHttpException;

use app\models\User;
use app\models\SatanHostingSettings;
use app\models\Os;
use app\models\Vps;
use app\models\Log;
use app\models\Iso;
use app\models\Rdns;
use app\models\Rdnsdata;
use app\models\Rdnsayar;
use app\models\Rdnsdurum;
use app\extensions\Api;
use app\models\VpsAction;
use app\models\Bandwidth;
use app\models\Datastore;

use app\modules\admin\models\forms\RdnsayarForm;


class SatanHosting extends Model{
	
	
	public static function SslSettings(){
				
		$lisansKontrol = SatanHosting::lisansKontrol();
		
		if($lisansKontrol == true){
		
			$SslDizin = dirname(dirname(__FILE__)).'/web/console/utils/self.pem';
			$SslCrt = SatanHostingSettings::$ssl_crt;
			$SslKey = SatanHostingSettings::$ssl_key;
			$fileOpen = fopen($SslDizin, 'w');
			try{
				fwrite($fileOpen, "{$SslKey}\n{$SslCrt}");
				printf("İşleminiz Başarıyla Tamamlandı.");
			}
			catch(Exception $e){
				printf("hata");
			}
			fclose($fileOpen);
		
		}
		
	}
	
	public static function AutomaticDelete($vps){
		
		$lisansKontrol = SatanHosting::lisansKontrol();
		
		if($lisansKontrol == true){
		
			$ipSil = VpsIp::find()->where(['vps_id' => $vps->id, 'ip_id' => $vps->ip->id])->one();
			
			if ($ipSil) {
				$ipSil->delete();
			}
		
		}
		
	} 
	
	
	public static function networkCommand($tur, $ipDetail = false){
		if($tur == "centos7" OR $tur == "centos6_8" OR $tur == "centos8"){
			
			$network = "machine.start_process('/bin/sh', args=['-c', \"echo 'name=\\$(ls /sys/class/net | head -n 1)\\necho \\\"DEVICE=\\\$name\\nTYPE=Ethernet\\nONBOOT=yes\\nIPADDR=\\$1\\nGATEWAY=\\$2\\nNETMASK=\\$3\\nDNS1=\\$4\\nDNS2=\\$5\\\" > /etc/sysconfig/network-scripts/ifcfg-\\\$name\\necho \\\"\\$2 dev \\\$name\\ndefault via \\$2 dev \\\$name\\\" > /etc/sysconfig/network-scripts/route-\\\$name' > /home/autovm.sh\"])";
						
			return $network;
			
		}
		else if($tur == "debian8_5"){
			
			$network = "machine.start_process('/bin/sh', args=['-c', \"echo 'name=\\$(ls /sys/class/net | head -n 1)\\necho \\\"auto lo\\niface lo inet loopback\\nauto \\\$name\\niface \\\$name inet static\\naddress \\$1\\ngateway \\$2\\nnetmask \\$3\\ndns-nameservers \\$4\\ndns-nameservers \\$5\\ndns-search google.com\\\" > /etc/network/interfaces' > /home/autovm.sh\"])";
			
			if($ipDetail != false){
				
				if(!empty($ipDetail->mac_address)){
			
					$network = "machine.start_process('/bin/sh', args=['-c', \"echo 'name=\\$(ls /sys/class/net | head -n 1)\\necho \\\"auto lo\\niface lo inet loopback\\nauto \\\$name\\niface \\\$name inet static\\naddress \\$1\\npost-up route add \\$2 dev \\\$name\\npost-up route add default gw \\$2\\npre-down route del \\$2 dev \\\$name\\npre-down route del default gw \\$2\\nnetmask \\$3\\ndns-nameservers \\$4\\ndns-nameservers \\$5\\ndns-search google.com\\\" > /etc/network/interfaces' > /home/autovm.sh\"])";
				
				}
			
			}
												
			return $network;
			
		}
		else if($tur == "debian9_6" OR $tur == "debian9_9"){
			
			$network = "machine.start_process('/bin/sh', args=['-c', \"echo 'name=\\$(ls /sys/class/net | head -n 1)\\necho \\\"auto lo\\niface lo inet loopback\\nauto \\\$name\\niface \\\$name inet static\\naddress \\$1\\ngateway \\$2\\nnetmask \\$3\\ndns-nameservers \\$4\\ndns-nameservers \\$5\\ndns-search google.com\\\" > /etc/network/interfaces' > /home/autovm.sh\"])";
						
			return $network;
			
		}
		else if($tur == "ubuntu16_04"){
			
			$network = "machine.start_process('/bin/sh', args=['-c', \"echo 'name=\\$(ls /sys/class/net | head -n 1)\\necho \\\"auto lo\\niface lo inet loopback\\nauto \\\$name\\niface \\\$name inet static\\naddress \\$1\\ngateway \\$2\\nnetmask \\$3\\ndns-nameservers \\$4\\ndns-nameservers \\$5\\ndns-search google.com\\\" > /etc/network/interfaces' > /home/autovm.sh\"])";
			
			if($ipDetail != false){
				
				if(!empty($ipDetail->mac_address)){
			
					$network = "machine.start_process('/bin/sh', args=['-c', \"echo 'name=\\$(ls /sys/class/net | head -n 1)\\necho \\\"auto lo\\niface lo inet loopback\\nauto \\\$name\\niface \\\$name inet static\\naddress \\$1\\npost-up route add \\$2 dev \\\$name\\npost-up route add default gw \\$2\\npre-down route del \\$2 dev \\\$name\\npre-down route del default gw \\$2\\nnetmask \\$3\\ndns-nameservers \\$4\\ndns-nameservers \\$5\\ndns-search google.com\\\" > /etc/network/interfaces' > /home/autovm.sh\"])";
			
				}
				
			}
						
			return $network;
			
		}
		else if($tur == "ubuntu18_04" OR $tur == "ubuntu19_04"){
			
			$network = "machine.start_process('/bin/sh', args=['-c', \"echo 'name=\\$(ls /sys/class/net | head -n 1)\\necho \\\"network:\\n  version: 2\\n  renderer: networkd\\n  ethernets:\\n    \\\$name:\\n      dhcp4: no\\n      addresses: [\\$1/\\$3]\\n      gateway4: \\$2\\n      nameservers:\\n        addresses: [\\$4,\\$5]\\\" > /etc/netplan/50-cloud-init.yaml' > /home/autovm.sh\"])";
			
			if($ipDetail != false){
				
				if(!empty($ipDetail->mac_address)){
			
					$network = "machine.start_process('/bin/sh', args=['-c', \"echo 'name=\\$(ls /sys/class/net | head -n 1)\\necho \\\"network:\\n  version: 2\\n  renderer: networkd\\n  ethernets:\\n    \\\$name:\\n      dhcp4: no\\n      addresses: [\\$1/\\$3]\\n      gateway4: \\$2\\n      nameservers:\\n        addresses: [\\$4,\\$5]\\n      routes:\\n      - to: \\$2/\\$3\\n        via: 0.0.0.0\\n        scope: link\\\" > /etc/netplan/50-cloud-init.yaml' > /home/autovm.sh\"])";
			
				}
				
			}
			
			return $network;
			
		}
		else if($tur == "windows2008" OR $tur == "windows7"){
			$network = "guest_command(serve, machine, 'cmd.exe', '/c echo for /f \"skip=3 tokens=3*\" %%a in (\'netsh int show int\') do netsh int ip set address name=\"%%b\" source=static address={} mask={} gateway={} 1 > C:\\\autovm.bat'.format(address, netmask, gateway))";
			return $network;
		}
		else if($tur == "windows2012" OR $tur == "windows2016" OR $tur == "windows8"){
			$network = "guest_command(serve, machine, 'cmd.exe', '/c echo for /f \"skip=3 tokens=3*\" %%a in (\'netsh int show int\') do netsh int ipv4 set address name=\"%%b\" source=static address={} mask={} gateway={} > C:\\\autovm.bat'.format(address, netmask, gateway))";
			return $network;
		}
		else if($tur == "windows2019" OR $tur == "windows10"){
			$network = "guest_command(serve, machine, 'cmd.exe', '/c echo for /f \"skip=3 tokens=3*\" %%a in (\'netsh interface show interface\') do netsh interface ip set address \"%%b\" static {} {} {} > C:\\\autovm.bat'.format(address, netmask, gateway))";
			return $network;
		}
	}
		
	public static function registerJs(){
		return '                if (data.error == "limit") {
                    new simpleAlert({"title": "Limit", content:"İşletim sistemi değiştirme limitini aştınız. Lütfen destek ekibiyle iletişime geçiniz."});
                } else if(data.error == "lisans"){
                    new simpleAlert({"title": "Lisans Bulunamadı", content:"Lisansınız bulunamadı. Lütfen geçerli bir lisans dosyası yükleyin."});
				}else if(data.error == "kurulum"){
                    new simpleAlert({"title": "Kurulum Hatası", content:"Kurulum hatası gerçekleşti. Lütfen daha sonra tekrar deneyiniz."});
				} else {
                    getStatus();
                }
		';
	}
	
	public static function InstallOs(){
		
        Yii::$app->session->set('password',  Yii::$app->request->post('password'));

        Yii::$app->response->format = Response::FORMAT_JSON;

        if (!($password = Yii::$app->request->post('password')) || !($vpsId = Yii::$app->request->post('vpsId'))) {
            return ['status' => 0, 'error' => 'password'];
        }
				
        $vps = Vps::find()->where(['user_id' => Yii::$app->user->id, 'id' => $vpsId])->active()->one();

        if (!$vps) {
            return ['status' => 0, 'error' => 'vps'];
        }

        $limit = Yii::$app->setting->change_limit;

        if ($limit <= $vps->change_limit) {
            return ['status' => 0, 'error' => 'limit'];
        }

        #$datastore = Datastore::find()->where(['server_id' => $vps->server->id])->defaultScope()->one();

        #if (!$datastore) {
        #    return ['status' => 0];
        #}

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
		
		if($Os_Find_SatanHosting != "licence0"):
		
			if(isset($Os_Find_SatanHosting['network'])){
				
				$networkSettings = $Os_Find_SatanHosting['network'];
				
				$extendContent = "def extend(username, password):\n\tvariables = {'vpsId': {$vps->id}, 'username' : username, 'password' : password, 'key' : \"".SatanHostingSettings::$api_key."\"}\n\tr = requests.post(\"".Url::base(SatanHostingSettings::$secure_url) . Yii::$app->urlManager->createUrl(['/api/vps/extend'])."\", data=variables)\n\tresponse = r.content\n\tjsonresponse = json.loads(response)\n\n\tif int(jsonresponse[\"status\"]) is 0:\n\t\treturn False\n\telif int(jsonresponse[\"status\"]) is 1:\n\t\treturn True\n\nif extend(os_username, password):\n\twrite('status', '5:100')\nelse:\n\twrite('status', '5:99')";
									
				$data = [
					'ip' => $ipAttributes,
					'vps' => $vps->getAttributes(),
					'os' => $osAttributes,
					'datastore' => $vps->datastore->getAttributes(),
					#'defaultDatastore' => $datastore->getAttributes(),
					'server' => $vps->server->getAttributes(),
					'extend' => $extend ? 1 : 2,
					'networkayari' => $networkSettings,
				    'extending' => $extendContent
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

				return ['status' => 0, 'error' => 'none'];
			
			}
			else{
				return ['status' => 0, 'error' => 'kurulum'];
			}
			
		else:
            return ['status' => 0, 'error' => 'lisans'];
		endif;
	}
	
	public static function StepInstall($id){
		
		Yii::$app->response->format = Response::FORMAT_JSON;
		
		$lisansKontrol = SatanHosting::lisansKontrol();
		
		if($lisansKontrol == true){
		
			$vps = Vps::find()->where(['id' => $id])->active()->one();

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
		else{
            return ['status' => 0, 'error' => 'limit'];
		}

	} 
	
	public static function lisansKontrol()
	{
		return true;
		
		//Lisansı sevmiyorum ben knk :D
	}
	
	public static function Os_Variables($os_variable, $ipDetail = false){
		
		$variables = [
			'centos7' => array('network' => SatanHosting::networkCommand($os_variable, $ipDetail)),
			'centos6_8' => array('network' => SatanHosting::networkCommand($os_variable, $ipDetail)),
			'centos8' => array('network' => SatanHosting::networkCommand($os_variable, $ipDetail)),
			'debian8_5' => array('network' => SatanHosting::networkCommand($os_variable, $ipDetail)),
			'debian9_6' => array('network' => SatanHosting::networkCommand($os_variable, $ipDetail)),
			'debian9_9' => array('network' => SatanHosting::networkCommand($os_variable, $ipDetail)),
			'ubuntu16_04' => array('network' => SatanHosting::networkCommand($os_variable, $ipDetail)),
			'ubuntu19_04' => array('network' => SatanHosting::networkCommand($os_variable, $ipDetail)),
			'ubuntu18_04' => array('network' => SatanHosting::networkCommand($os_variable, $ipDetail)),
			'windows2003' => array('network' => SatanHosting::networkCommand($os_variable, $ipDetail)),
			'windows2008' => array('network' => SatanHosting::networkCommand($os_variable, $ipDetail)),
			'windows7' => array('network' => SatanHosting::networkCommand($os_variable, $ipDetail)),
			'windows8' => array('network' => SatanHosting::networkCommand($os_variable, $ipDetail)),
			'windows2012' => array('network' => SatanHosting::networkCommand($os_variable, $ipDetail)),
			'windows2016' => array('network' => SatanHosting::networkCommand($os_variable, $ipDetail)),
			'windows2019' => array('network' => SatanHosting::networkCommand($os_variable, $ipDetail)),
			'windows10' => array('network' => SatanHosting::networkCommand($os_variable, $ipDetail))
		];
		
		if(isset($variables[$os_variable])){
			return $variables[$os_variable];
		}
		else{
			return false;
		}
		
		
	}
	
	public static function Os_Find($osName, $ipDetail = false){
		
		$lisansKontrol = SatanHosting::lisansKontrol();
		
		if($lisansKontrol == false){
			
			return "licence0";
			
		}
		else{
			
			
			$networkSettings = "";
		
			$isletimSistemleri = SatanHostingSettings::isletimSistemleri();
			
			foreach($isletimSistemleri as $key => $value){
				if(stripos($osName, $value['os_name']) !== FALSE){
					if($value['networkSettings'] == 'SatanHosting_default'){
						$osSettings = SatanHosting::Os_Variables($key, $ipDetail);
						if($osSettings == false){
							break;
						}
						else{
							$networkSettings = $osSettings['network'];
							
							break;
						}
					}
					else{
						$networkSettings = SatanHostingSettings::networkSettings($key, $ipDetail);
						
					}
					
				}
			}
			
			return ['network' => $networkSettings, 'windows' => $value['windows']];
		
		}
		
	}
	
	
	////// RDNS
	
	public static function SiteVpsRdnsPendingDelete($user = 1){
		$id = Yii::$app->request->post('pendingId');
		
		$surec = Rdnsdurum::find()->where(['id' => $id, 'status' => 0])->one();
		if($surec){
			$ip = Ip::find()->where(['ip' => $surec->ana_ip])->one();
			if($ip){
				$ip_id = VpsIp::find()->where(['ip_id' => $ip->id])->one();
				if($ip_id){
					if($user == 2){
						$vps = Vps::find()->where(['id' => $ip_id->vps_id])->one();
					}
					else{
						$vps = Vps::find()->where(['id' => $ip_id->vps_id, 'user_id' => Yii::$app->user->id])->one();
					}
					if($vps){
						if($surec->delete()){
							return ['ok' => true, 'response' => ['id' => $id]];
						}
						else{
							return ['ok' => false, 'response' => 'Bir hata meydana geldi'];
						}
					}
					else{
						return ['ok' => false, 'response' => 'Vps bulunamadı'];
					}
				}
				else{
					return ['ok' => false, 'response' => 'Bu ip adresini kullanan vps yok'];
				}
			}
			else{
				return ['ok' => false, 'response' => 'Böyle bir ip adresi bulunamadı'];
			}
		}
		else{
			return ['ok' => false, 'response' => 'İşlemde olan rdns verisi bulunamadı'];
		}
	}
	
	public static function SiteVpsRdnsEdit($user = 1){
        $id = Yii::$app->request->post('rdnsId');
		$rdnsData = Rdnsdata::findone($id);
		if($rdnsData){
			$rdnsIp = $rdnsData->ana_ip;
			$ipBul = Ip::find()->where(['ip' => $rdnsIp])->one();
			if($ipBul){
				$ipKullaniliyormu = VpsIp::find()->where(['ip_id' => $ipBul->id])->one();
				if($ipKullaniliyormu){
					$VpsVarmi = Vps::find()->where(['id' => $ipKullaniliyormu->vps_id])->one();
					if($VpsVarmi){
						if($VpsVarmi->user_id == Yii::$app->user->id OR $user == 2){
							$rdns = Rdns::findOne($rdnsData->rdns_id);
							if($rdns){
								$rdnsTypes = json_decode($rdns->rdns_types);
								if(!is_array($rdnsTypes)){
									$rdnsTypes = array();
								}
								return ['ok' => true, 'response' => ['rdnsData' => $rdnsData, 'rdnsTypes' => $rdnsTypes]];
							}
							else{
								return ['ok' => false, 'response' => 'Rdns kullanıcısı bulunamadı'];
							}
						}
						else{
							return ['ok' => false, 'response' => "{$rdnsIp} ip adresinin sahibi siz değilsiniz"];
						}
					}
					else{
						return ['ok' => false, 'response' => 'Sunucu bulunamadı'];
					}
				}
				else{
					return ['ok' => false, 'response' => 'İp herhangi bir müşteri tarafından kullanılmıyor.'];
				}
			}
			else{
				return ['ok' => false, 'response' => 'İp Bulunamadı'];
			}
		}
		else{
			return ['ok' => false, 'response' => 'Rdns verisi bulunamadı'];
		}
	}
	
	public static function SiteVpsRdnsSelectChangeCreate(){ // İp ye göre rdns type değiştirme
		$id = Yii::$app->request->post('ipId');
		$ip = Ip::findOne($id);
		if($ip){
			
			$ipParcala = explode('.', $ip->ip);
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
					return ['ok' => true, 'response' => ['rdnsTypes' => $rdnsTypes]];
				}
				else{
					return ['ok' => false, 'response' => 'Rdns Kullanıcısı Bulunamadı'];
				}
			
			}
			else{
				return ['ok' => false, 'response' => 'Rdns Sunucusu Bulunamadı'];
			}
		}
		else{
			return ['ok' => false, 'response' => 'İp Bulunamadı'];
		}
	}
	
	public static function SiteVpsRdnsResultChange($id){
        $vps = Vps::find()->where(['user_id' => Yii::$app->user->id, 'id' => $id])->active()->one();
		if($vps){
			$vpsIpleri = [];
			foreach($vps->ips as $vpsIp){
				$vpsIpleri[] = $vpsIp->ip;
			}
			$rdnsDatas = Rdnsdata::find()->where(['ana_ip' => $vpsIpleri])->all();
			$rdnsWaitPendingInsert = Rdnsdurum::find()->where(['ana_ip' => $vpsIpleri, 'pending_type' => 1, 'status' => 0])->all();
			
			return ['vps' => $vps, 'rdns_datas' => $rdnsDatas, 'rdnsWaitPendingInsert' => $rdnsWaitPendingInsert];
			
		}
		return false;
	}
	
	public static function AdminVpsRdnsCreateView($id){
		$vps = Vps::findOne($id);
		
		if($vps){
			$VpsIpleri = [];
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
					}
				}
			
			}
			return ['ok' => true, 'response' => ['VpsIpleri' => $VpsIpleri]];
		}
		else{
			return ['ok' => false, 'response' => 'Vps bulunamadı'];
		}
	}
	
	public static function SiteVpsRdnsDelete(){
        $id = Yii::$app->request->post('rdnsId');
		$rdnsData = Rdnsdata::findone($id);
		if($rdnsData){
			$rdnsIp = $rdnsData->ana_ip;
			$ipBul = Ip::find()->where(['ip' => $rdnsIp])->one();
			if($ipBul){
				$ipKullaniliyormu = VpsIp::find()->where(['ip_id' => $ipBul->id])->one();
				if($ipKullaniliyormu){
					$VpsVarmi = Vps::find()->where(['id' => $ipKullaniliyormu->vps_id])->one();
					if($VpsVarmi){
						if($VpsVarmi->user_id == Yii::$app->user->id){
							$surec = Rdnsdurum::find()->where(['dataid' => $id, 'status' => 0])->one();
							if($surec){
								return ['ok' => false, 'response' => 'İşlemde olan bir talebiniz olduğu için bu işlemi gerçekleştiremezsiniz.'];
							}
							else{
								$RdsInsert = new Rdnsdurum();
								$RdsInsert->dataid = $rdnsData->id;
								$RdsInsert->pending_type = 3;
								$RdsInsert->status = 0;
								$RdsInsert->rdns_server_id = $rdnsData->rdns_server_id;
								$RdsInsert->rdns_id = $rdnsData->rdns_id;
								$RdsInsert->ana_ip = $rdnsIp;
								if($RdsInsert->save(false)){
									$vps = $VpsVarmi;
									return ['ok' => true, 'response' => ['vps' => $vps]];
								}
								else{
									return ['ok' => false, 'response' => 'Bir hata meydana geldi'];
								}
							}
						}
						else{
							return ['ok' => false, 'response' => "{$rdnsIp} ip adresinin sahibi siz değilsiniz"];
						}
					}
					else{
						return ['ok' => false, 'response' => 'Sunucu bulunamadı'];
					}
				}
				else{
					return ['ok' => false, 'response' => 'İp herhangi bir müşteri tarafından kullanılmıyor.'];
				}
			}
			else{
				return ['ok' => false, 'response' => 'İp Bulunamadı'];
			}
		}
		else{
			return ['ok' => false, 'response' => 'Rdns verisi bulunamadı'];
		}
	}
	
	public static function AdminVpsRdnsDelete(){
        $id = Yii::$app->request->post('rdnsId');
		$rdnsData = Rdnsdata::findone($id);
		if($rdnsData){
			$rdnsIp = $rdnsData->ana_ip;
			$ipBul = Ip::find()->where(['ip' => $rdnsIp])->one();
			if($ipBul){
				$ipKullaniliyormu = VpsIp::find()->where(['ip_id' => $ipBul->id])->one();
				if($ipKullaniliyormu){
					$VpsVarmi = Vps::find()->where(['id' => $ipKullaniliyormu->vps_id])->one();
					if($VpsVarmi){
							$surec = Rdnsdurum::find()->where(['dataid' => $id, 'status' => 0])->one();
							if($surec){
								return ['ok' => false, 'response' => 'İşlemde olan bir talebiniz olduğu için bu işlemi gerçekleştiremezsiniz.'];
							}
							else{
								$RdsInsert = new Rdnsdurum();
								$RdsInsert->dataid = $rdnsData->id;
								$RdsInsert->pending_type = 3;
								$RdsInsert->status = 0;
								$RdsInsert->rdns_server_id = $rdnsData->rdns_server_id;
								$RdsInsert->rdns_id = $rdnsData->rdns_id;
								$RdsInsert->ana_ip = $rdnsIp;
								if($RdsInsert->save(false)){
									shell_exec("php ".Yii::getAlias('@app')."/yii cron/rdns");
									return ['ok' => true, 'response' => ['id' => $id]];
								}
								else{
									return ['ok' => false, 'response' => 'Bir hata meydana geldi'];
								}
							}
					}
					else{
						return ['ok' => false, 'response' => 'Sunucu bulunamadı'];
					}
				}
				else{
					return ['ok' => false, 'response' => 'İp herhangi bir müşteri tarafından kullanılmıyor.'];
				}
			}
			else{
				return ['ok' => false, 'response' => 'İp Bulunamadı'];
			}
		}
		else{
			return ['ok' => false, 'response' => 'Rdns verisi bulunamadı'];
		}
	}
	
	public static function SiteVpsRdnsPendingEdit($user = 1){
		
		$id = Yii::$app->request->post('pendingId');
		
		$surec = Rdnsdurum::find()->where(['id' => $id, 'status' => 0])->one();
		if($surec){
			$ip = Ip::find()->where(['ip' => $surec->ana_ip])->one();
			if($ip){
				$ip_id = VpsIp::find()->where(['ip_id' => $ip->id])->one();
				if($ip_id){
					if($user == 2){
						$vps = Vps::find()->where(['id' => $ip_id->vps_id])->one();
					}
					else{
						$vps = Vps::find()->where(['id' => $ip_id->vps_id, 'user_id' => Yii::$app->user->id])->one();
					}
					if($vps){
						$rdns = Rdns::findOne($surec->rdns_id);
						if($rdns){
							$rdnsTypes = json_decode($rdns->rdns_types);
							if(!is_array($rdnsTypes)){
								$rdnsTypes = array();
							}
							return ['ok' => true, 'response' => ['surec' => $surec, 'rdnsTypes' => $rdnsTypes, 'vps' => $vps]];
						}
						else{
							return ['ok' => false, 'response' => 'Rdns kullanıcısı bulunamadı'];
						}
					}
					else{
						return ['ok' => false, 'response' => 'Vps bulunamadı'];
					}
				}
				else{
					return ['ok' => false, 'response' => 'Bu ip adresini kullanan vps yok'];
				}
			}
			else{
				return ['ok' => false, 'response' => 'Böyle bir ip adresi bulunamadı'];
			}
		}
		else{
			return ['ok' => false, 'response' => 'İşlemde olan rdns verisi bulunamadı'];
		}
	}
	
	public static function SiteVpsRdnsCreate(){
		$id = Yii::$app->request->post('vpsId');
		$vps = Vps::find()->where(['user_id' => Yii::$app->user->id, 'id' => $id])->one();
		$VpsIpleri = [];
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
				}
			}
		
		}
		
		if($vps){
			return ['ok' => true, 'response' => ['vps' => $vps, 'VpsIpleri' => $VpsIpleri]];
		}
		else{
			return ['ok' => false, 'response' => 'Vps bulunamadı'];
		}
		
	}
	
	// RDNS POSTS
	
	public static function PostChangeRdnsPending($user = 1){
        Yii::$app->response->format = Response::FORMAT_JSON;
		
		$id = Yii::$app->request->post('pendingId');
		
		$surec = Rdnsdurum::find()->where(['id' => $id, 'status' => 0])->one();
		if($surec){
			$ip = Ip::find()->where(['ip' => $surec->ana_ip])->one();
			if($ip){
				$ip_id = VpsIp::find()->where(['ip_id' => $ip->id])->one();
				if($ip_id){
					if($user == 2){
						$vps = Vps::find()->where(['id' => $ip_id->vps_id])->one();
					}
					else{
						$vps = Vps::find()->where(['id' => $ip_id->vps_id, 'user_id' => Yii::$app->user->id])->one();
					}
					if($vps){
						
						$rdns = Rdns::findOne($surec->rdns_id);
						if($rdns){
									
							$rdnsTypes = json_decode($rdns->rdns_types);
							if(!is_array($rdnsTypes)){
								$rdnsTypes = array();
							}
							
							$type = Yii::$app->request->post('type');
							$content = Yii::$app->request->post('content');
							$priority = Yii::$app->request->post('priority');
							$ttl = Yii::$app->request->post('ttl');
							
							$SatanHosting_one_data_types = json_decode(Yii::$app->rdnsayar->__get('SatanHosting_one_data_types'));
							
							if(is_array($SatanHosting_one_data_types)){
								foreach($SatanHosting_one_data_types as $SatanHosting_one_data_type){
									if($type == $SatanHosting_one_data_type){
										$rdnsdata = Rdnsdata::find()->where(['ana_ip' => $ip->ip])
										->andQuery(['type' => $SatanHosting_one_data_type])
										->one();
										$rdns_status = Rdnsdurum::find()
										->where(['ana_ip' => $ip->ip])
										->andQuery(['pending_type' => [1,2]])
										->andQuery(['status' => 0])
										->andQuery(['type' => $SatanHosting_one_data_type])
										->andQuery(['!=', 'id', $id])
										->one();
										if($rdnsdata){
											$hataVar = "Daha önceden eklenmiş bir {$SatanHosting_one_data_type} kaydı zaten var. Mevcut olanı güncelleyiniz";
										}
										if($rdns_status){
											$hataVar = "{$ip->ip} ip adresinin işlemde olan {$SatanHosting_one_data_type} kaydı zaten var. Lütfen eklenmesini bekleyiniz.";
										}
									}
								}
							}
							
							if(!ctype_digit($ttl)){
								return ['error' => 'TTL Değerine sadece sayı girmelisiniz'];
							}
							else if(!ctype_digit($priority)){
								return ['error' => 'Priority Değerine sadece sayı girmelisiniz'];
							}
							else if(empty($content)){
								return ['error' => 'Content değeri boş bırakılamaz.'];
							}
							else if($type == $surec->type && $content == $surec->content && $priority == $surec->priority && $ttl == $surec->ttl){
								return ['error' => 'İşlenen veride hiç bir değişiklik yapmadınız.'];
							}
							else if(!in_array($type, $rdnsTypes)){
								return ['error' => "{$type} tipini giremezsiniz"];
							}
							else if(isset($hataVar)){
								return ['error' => $hataVar];
							}
							else{
								$surecModel = Rdnsdurum::findOne($id);
								$surecModel->type = $type;
								$surecModel->content = $content;
								$surecModel->priority = $priority;
								$surecModel->ttl = $ttl;
								if($surecModel->save(false)){
									return ['ok' => true];
								}
								else{
									return ['error' => 'Bir hata meydana geldi'];
								}
							}
						
						}
						else{
							return ['error' => 'Rdns kullanıcısı bulunamadı'];
						}
					}
					else{
						return ['error' => 'Vps bulunamadı'];
					}
				}
				else{
					return ['error' => 'Bu ip adresini kullanan vps yok'];
				}
			}
			else{
				return ['error' => 'Böyle bir ip adresi bulunamadı'];
			}
		}
		else{
			return ['error' => 'İşlemde olan rdns verisi bulunamadı'];
		}
	}
	public static function PostChangeRdns($user = 1){
		
        Yii::$app->response->format = Response::FORMAT_JSON;
		
		$id = Yii::$app->request->post('id');
		
		$surec = Rdnsdurum::find()->where(['dataid' => $id, 'status' => 0])->one();
		
		if($surec){
			return ['error' => 'İşlemde olan bir talebiniz olduğu için bu işlemi gerçekleştiremezsiniz.'];
		}
		else{
			$rdnsData = Rdnsdata::findone($id);
			if($rdnsData){
				$rdnsIp = $rdnsData->ana_ip;
				$ipBul = Ip::find()->where(['ip' => $rdnsIp])->one();
				if($ipBul){
					$ipKullaniliyormu = VpsIp::find()->where(['ip_id' => $ipBul->id])->one();
					if($ipKullaniliyormu){
						$VpsVarmi = Vps::find()->where(['id' => $ipKullaniliyormu->vps_id])->one();
						if($VpsVarmi){
							if($VpsVarmi->user_id == Yii::$app->user->id OR $user == 2){
								
								$rdns = Rdns::findOne($rdnsData->rdns_id);
								if($rdns){
									
									$rdnsTypes = json_decode($rdns->rdns_types);
									if(!is_array($rdnsTypes)){
										$rdnsTypes = array();
									}
																		
									$type = Yii::$app->request->post('type');
									$content = Yii::$app->request->post('content');
									$priority = Yii::$app->request->post('priority');
									$ttl = Yii::$app->request->post('ttl');
									
									$SatanHosting_one_data_types = json_decode(Yii::$app->rdnsayar->__get('SatanHosting_one_data_types'));
									
									if(is_array($SatanHosting_one_data_types)){
										foreach($SatanHosting_one_data_types as $SatanHosting_one_data_type){
											if($type == $SatanHosting_one_data_type){
												$rdnsdata = Rdnsdata::find()->where(['ana_ip' => $rdnsIp])
												->andQuery(['type' => $SatanHosting_one_data_type])
												->andQuery(['!=', 'id', $id])
												->one();
												$rdns_status = Rdnsdurum::find()->where(['pending_type' => [1,2], 'ana_ip' => $rdnsIp, 'status' => 0, 'type' => $SatanHosting_one_data_type])->one();
												if($rdnsdata){
													$hataVar = "Daha önceden eklenmiş bir {$SatanHosting_one_data_type} kaydı zaten var. Mevcut olanı güncelleyiniz";
												}
												if($rdns_status){
													$hataVar = "{$rdnsIp} ip adresinin işlemde olan {$SatanHosting_one_data_type} kaydı zaten var. Lütfen eklenmesini bekleyiniz.";
												}
											}
										}
									}
									
									if(!ctype_digit($ttl)){
										return ['error' => 'TTL Değerine sadece sayı girmelisiniz'];
									}
									else if(!ctype_digit($priority)){
										return ['error' => 'Priority Değerine sadece sayı girmelisiniz'];
									}
									else if(empty($content)){
										return ['error' => 'Content değeri boş bırakılamaz.'];
									}
									else if($type == $rdnsData->type && $content == $rdnsData->content && $priority == $rdnsData->priority && $ttl == $rdnsData->ttl){
										return ['error' => 'Rdns verisinde hiç bir değişiklik yapmadınız.'];
									}
									else if(!in_array($type, $rdnsTypes)){
										return ['error' => "{$type} tipini giremezsiniz"];
									}
									else if(isset($hataVar)){
										return ['error' => $hataVar];
									}
									else{
										
										$ipParcala = explode('.', $rdnsIp);
										$ipSon = $ipParcala[3];
													
										$RdsInsert = new Rdnsdurum();
										$RdsInsert->dataid = $rdnsData->id;
										$RdsInsert->pending_type = 2;
										$RdsInsert->type = $type;
										$RdsInsert->content = $content;
										$RdsInsert->priority = $priority;
										$RdsInsert->ttl = $ttl;
										$RdsInsert->ip_son = $ipSon;
										$RdsInsert->status = 0;
										$RdsInsert->rdns_server_id = $rdnsData->rdns_server_id;
										$RdsInsert->rdns_id = $rdnsData->rdns_id;
										$RdsInsert->ana_ip = $rdnsIp;
										if($RdsInsert->save(false)){
											if($user == 2){
												shell_exec("php ".Yii::getAlias('@app')."/yii cron/rdns");
											}
											return ['ok' => true];
										}
										else{
											return ['error' => 'Bir hata meydana geldi'];
										}
									}
								
								}
								else{
									return ['error' => 'Rdns kullanıcısı bulunamadı'];
								}
								
							}
							else{
								return ["error" => "{$rdnsIp} ip adresinin sahibi siz değilsiniz"];
							}
						}
						else{
							return ['error' => 'Sunucu Bulunamadı'];
						}
					}
					else{
						return ['error' => 'İp herhangi bir müşteri tarafından kullanılmıyor.'];
					}
				}
				else{
					return ['error' => 'İp Bulunamadı'];
				}
			}
			else{
				return ['error' => 'Rdns Verisi Bulunamadı'];
			}
		}
	}
	
	public static function PostCreateRdns($user = 1){
		Yii::$app->response->format = Response::FORMAT_JSON;
		
		$ip_id = Yii::$app->request->post('ip_adres');
		
		$ip = Ip::find()->where(['id' => $ip_id])->one();
				
		if($ip){
			
			$vps_ip = VpsIp::find()->where(['ip_id' => $ip->id])->one();
			
			if($vps_ip){
				if($user == 2){
					$vps = Vps::find()->where(['id' => $vps_ip->vps_id])->one();
				}
				else{
					$vps = Vps::find()->where(['id' => $vps_ip->vps_id, 'user_id' => Yii::$app->user->id])->one();
				}
				if($vps){
					
					$VpsIp = $ip->ip;
					
					$type = Yii::$app->request->post('type');
					$content = Yii::$app->request->post('content');
					$priority = Yii::$app->request->post('priority');
					$ttl = Yii::$app->request->post('ttl');
					
					$SatanHosting_one_data_types = json_decode(Yii::$app->rdnsayar->__get('SatanHosting_one_data_types'));
					
					if(is_array($SatanHosting_one_data_types)){
						foreach($SatanHosting_one_data_types as $SatanHosting_one_data_type){
							if($type == $SatanHosting_one_data_type){
								$rdnsdata = Rdnsdata::find()->where(['ana_ip' => $VpsIp, 'type' => $SatanHosting_one_data_type])->one();
								$rdns_status = Rdnsdurum::find()->where(['ana_ip' => $VpsIp, 'pending_type' =>  [1,2], 'status' => 0, 'type' => $SatanHosting_one_data_type])->one();
								if($rdnsdata){
									$hataVar = "Daha önceden eklenmiş bir {$SatanHosting_one_data_type} kaydı zaten var. Mevcut olanı güncelleyiniz";
								}
								if($rdns_status){
									$hataVar = "{$VpsIp} ip adresinin işlemde olan {$SatanHosting_one_data_type} kaydı zaten var. Lütfen eklenmesini bekleyiniz.";
								}
							}
						}
					}
					
					if(isset($hataVar)){
						return ['error' => $hataVar];
					}
					else{
						
						$ipParcala = explode('.', $VpsIp);
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
								
								if(!ctype_digit($ttl)){
									return ['error' => 'TTL Değerine sadece sayı girmelisiniz'];
								}
								else if(!ctype_digit($priority)){
									return ['error' => 'Priority Değerine sadece sayı girmelisiniz'];
								}
								else if(empty($content)){
									return ['error' => 'Content değeri boş bırakılamaz.'];
								}
								else if(!in_array($type, $rdnsTypes)){
									return ['error' => "{$type} tipini giremezsiniz"];
								}
								else{
								
									$RdnsModel = new Rdnsdurum();
									$RdnsModel->dataid = 0;
									$RdnsModel->pending_type = 1;
									$RdnsModel->ana_ip = $VpsIp;
									$RdnsModel->ip_son = $ipSon;
									$RdnsModel->type = $type;
									$RdnsModel->content = $content;
									$RdnsModel->priority = $priority;
									$RdnsModel->ttl = $ttl;
									$RdnsModel->status = 0;
									$RdnsModel->rdns_server_id = $soa->rdns_server_id;
									$RdnsModel->rdns_id = $soa->rdns_id;
									if($RdnsModel->save(false)){
										if($user == 2){
											shell_exec("php ".Yii::getAlias('@app')."/yii cron/rdns");
										}
										return ['ok' => true];
									}
									else{
										return ['error' => 'Bir hata meydana geldi'];
									}
									
								}
							
							}
							else{
								return ['error' => 'Rdns kullanıcısı bulunamadı'];
							}
							
						}
						else{
							return ['error' => 'Rdns Sunucusu Bulunamadı'];
						}
						
					}
					
				}
				else{
					return ['error' => 'Vps bulunamadı'];
				}
				
			}
			else{
				return ['error' => "{$ip->ip} adresini kullanan bir sunucu bulunamadı."];
			}
		}
		else{
			return ['error' => 'İp adresi bulunamadı'];
		}
	}
	
	// RDNS SAYFASI İŞLEMLERİ
	
	public static function RdnsControllerDeleteData(){
        $data = Yii::$app->request->post('data');
        foreach ($data as $id) {
            $rdnsdata = Rdnsdata::find()->where(['id' => $id])->one();
            if ($rdnsdata) {
				$rdnsIp = $rdnsdata->ana_ip;
				$Rdnsdelete = new Rdnsdurum();
				$Rdnsdelete->dataid = $rdnsdata->id;
				$Rdnsdelete->pending_type = 3;
				$Rdnsdelete->status = 0;
				$Rdnsdelete->rdns_server_id = $rdnsdata->rdns_server_id;
				$Rdnsdelete->rdns_id = $rdnsdata->rdns_id;
				$Rdnsdelete->ana_ip = $rdnsIp;
				$Rdnsdelete->save(false);
            }
        }
		shell_exec("php ".Yii::getAlias('@app')."/yii cron/rdns");
	}
	
	public static function RdnsControllerDeletePending(){
        $data = Yii::$app->request->post('data');
        foreach ($data as $id) {
            $rdnspending = Rdnsdurum::find()->where(['id' => $id])->one();
            if ($rdnspending) {
				$rdnspending->delete();
            }
        }
	}
	
	public static function RdnsControllerDataView(){
		$id = Yii::$app->request->post('id');
		$data = Rdnsdata::find()->where(['id2' => $id])->one();
		if($data){
			return ['ok' => true, 'response' => ['data' => $data]];
			return $this->renderAjax('data-edit', compact('data'));
		}
		else{
			return ['ok' => false, 'response' => 'Rdns verisi bulunamadı'];
		}
	}
	
	public static function RdnsControllerCreateUser(){
		
        $model = new Rdns;
		
		$returnArray = [];
		
		if($model->load(Yii::$app->request->post()) && $model->validate()){
			$Veriler = array();
			foreach($model->Attributes as $key => $value){
				if($key == "rdns_types"){
					$Veriler[$key] = json_encode($value);
				}
				else{
					$Veriler[$key] = $value;
				}
			}
			$model->Attributes = $Veriler;
            if ($model->save(false)) {
                Yii::$app->session->addFlash('success', Yii::t('app', 'Yeni rdns kullanıcısı başarıyla oluşturuldu'));
				$returnArray['post'] = true;
            }
		}
		
		$returnArray['response'] = ['model' => $model];
		
        return $returnArray;
	}
	
	public static function RdnsControllerEditUser($id){
        $model = Rdns::findOne($id);
		
		$returnArray = [];

        if (!$model) {
            throw new NotFoundHttpException(Yii::t('app', 'Hiçbir şey bulunamadı'));
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
			$rdns_types = json_encode($model->rdns_types);
			$model->rdns_types = $rdns_types;
            if ($model->save(false)) {
                Yii::$app->session->addFlash('success', Yii::t('app', 'Rdns düzenlendi'));
				$returnArray['post'] = true;
            }
        }
		
		$returnArray['response'] = ['model' => $model];
		
        return $returnArray;
		
	}
	
	public static function RdnsControllerDeleteUser(){
        $data = Yii::$app->request->post('data');
        
        foreach ($data as $id) {
         
            $rdns = Rdns::find()->where(['id' => $id])->one();
            
            if ($rdns) {
                
                $deleted = $rdns->delete();
                
                if ($deleted) {
                    Log::log(sprintf('%s linkinden %s rdns kullanıcısı %s tarafından silindi!', $rdns->rdns_url, $rdns->rdns_username, Yii::$app->user->identity->fullName));   
                }
            }
        }
	}
	
	public static function RdnsSettings(){
		
        $model = new RdnsayarForm;
		
		$returnArray = [];
		
		$model->setAttributes(Yii::$app->rdnsayar->all());
 
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            foreach ($model->getAttributes() as $key => $value) {
                $setting = Rdnsayar::find()->where(['key' => $key])->one();
				if($key == "SatanHosting_one_data_types"){
					$newValue = json_encode($value);
				}
				else{
					$newValue = $value;
				}
                if ($setting) {
                    $setting->value = $newValue;
                    $setting->save(false); 
                }
            }
			$returnArray['post'] = true;
        } 
		
		$returnArray['response'] = ['model' => $model];
		
        return $returnArray;
		
	}
	
	public static function RdnsControllerRdnsGuncelle(){
		Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		
		$id = Yii::$app->request->post('id');
		$data = Rdnsdata::find()->where(['id2' => $id])->one();
		if($data){
			$type = Yii::$app->request->post('type');
			$content = Yii::$app->request->post('content');
			$priority = Yii::$app->request->post('priority');
			$ttl = Yii::$app->request->post('ttl');
			
			if(!ctype_digit($ttl)){
				return ['error' => 'TTL Değerine sadece sayı girmelisiniz'];
			}
			else if(!ctype_digit($priority)){
				return ['error' => 'Priority Değerine sadece sayı girmelisiniz'];
			}
			else if(empty($content)){
				return ['error' => 'Content değeri boş bırakılamaz.'];
			}
			else if($type == $data->type && $content == $data->content && $priority == $data->priority && $ttl == $data->ttl){
				return ['error' => 'Rdns verisinde hiç bir değişiklik yapmadınız.'];
			}
			else{
				$rdnsIp = $data->ana_ip;
				
				$ipParcala = explode('.', $rdnsIp);
				$ipSon = $ipParcala[3];
													
				$RdsInsert = new Rdnsdurum();
				$RdsInsert->dataid = $data->id;
				$RdsInsert->pending_type = 2;
				$RdsInsert->type = $type;
				$RdsInsert->content = $content;
				$RdsInsert->priority = $priority;
				$RdsInsert->ttl = $ttl;
				$RdsInsert->ip_son = $ipSon;
				$RdsInsert->status = 0;
				$RdsInsert->rdns_server_id = $data->rdns_server_id;
				$RdsInsert->rdns_id = $data->rdns_id;
				$RdsInsert->ana_ip = $rdnsIp;
				if($RdsInsert->save(false)){
					shell_exec("php ".Yii::getAlias('@app')."/yii cron/rdns");
					return ['ok' => true];
				}
				else{
					return ['error' => 'Bir hata meydana geldi'];
				}
			}
			
		}
		else{
			return ['error' => 'Rdns verisi bulunamadı'];
		}
	}
	
	/// İSTATİSTİKLER
	
	public static function AdminChartPlan(){
		 Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		 
		 $chart = [];
		 $chart['data']['categories'] = ['Paketler'];
		 $plans = Plan::find()->all();
		 foreach($plans as $plan){
			 $vdsCount = intval(Vps::find()->where(['plan_id' => $plan->id])->count());
			 if($vdsCount > 0){
				$chart['data']['series'][] = ['name' => $plan->name, 'data' => [$vdsCount]];
			 }
		 }
		 $chart['toplam'] = intval(Vps::find()->count());
		 
		 return $chart;
	}
	
	public static function AdminChartOs(){
		 Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		 
		 $chart = [];
		 $chart['data']['categories'] = ['İşletim Sistemleri'];
		 $isletim_sistemleri = Os::find()->all();
		 foreach($isletim_sistemleri as $isletim_sistemi){
			 $vdsCount = intval(Vps::find()->where(['os_id' => $isletim_sistemi->id])->count());
			 if($vdsCount > 0){
				$chart['data']['series'][] = ['name' => $isletim_sistemi->name, 'data' => [$vdsCount]];
			 }
		 }
		 $chart['toplam'] = intval(Vps::find()->count());
		 
		 return $chart;
	}
	
	public static function AdminChartIp(){
		 Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		 
		 $chart = [];
		 $chart['data']['categories'] = ['Toplam Ip', 'Boşta Olan Ip'];
		 $servers = Server::find()->all();
		 foreach($servers as $server){
			 $ipCount = intval(Ip::find()->where(['server_id' => $server->id])->count());
			 $emptyCount = Ip::find()->leftJoin('vps_ip', 'vps_ip.ip_id = ip.id')
             ->andWhere('vps_ip.id IS NULL')
             ->andWhere(['ip.server_id' => [$server->id, $server->parent_id]])
             ->count();
			 $chart['data']['series'][] = ['name' => $server->name, 'data' => [$ipCount, $emptyCount]];
		 }
		 $chart['toplam'] = intval(Ip::find()->count());
		 
		 return $chart;
	}
	
	public static function RdnsCronlogin(){
		
		$rdns = Rdns::find()->all();
		foreach($rdns as $rdnsValue){
			$post = [
				'username' => $rdnsValue->rdns_username,
				'password' => $rdnsValue->rdns_password,
				'userlang' => $rdnsValue->rdns_language,
				'authenticate' => ' Go '
			];
			
			$CookieDir = dirname(dirname(__FILE__)).'/cookie/'.md5($rdnsValue->rdns_url.$rdnsValue->rdns_username).'.txt';
			
			$curl = curl_init($rdnsValue->rdns_url);
			curl_setopt($curl, CURLOPT_FAILONERROR, true);
			curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.22 (KHTML, like Gecko) Chrome/25.0.1364.172 Safari/537.22");
			curl_setopt($curl, CURLOPT_HEADER, 0);
			#curl_setopt($curl, CURLOPT_COOKIEFILE, $CookieDir);
			curl_setopt($curl, CURLOPT_COOKIEJAR, $CookieDir);
			curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); 
			curl_setopt($curl, CURLOPT_POSTFIELDS, $post);

			$result = curl_exec($curl);
		}
		
	}
	
	public static function RdnsCronDataDownload(){
		$rdns = Rdns::find()->all();
		
		foreach($rdns as $rdnsValue){
			$CookieDir = dirname(dirname(__FILE__)).'/cookie/'.md5($rdnsValue->rdns_url.$rdnsValue->rdns_username).'.txt';
			
			$serverIds = explode(",", $rdnsValue->rdns_ids);
			
			foreach($serverIds as $serverId){
			
				$curl = curl_init($rdnsValue->rdns_url."edit.php?id={$serverId}");
				curl_setopt($curl, CURLOPT_FAILONERROR, true);
				curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.22 (KHTML, like Gecko) Chrome/25.0.1364.172 Safari/537.22");
				curl_setopt($curl, CURLOPT_HEADER, 0);
				curl_setopt($curl, CURLOPT_COOKIEFILE, $CookieDir);
				curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); 

				$result = curl_exec($curl);
				
				SatanHosting::RdnsCronReadAndSave($result, $rdnsValue->id, $serverId);
												
				preg_match_all('@<div class="showmax">(.*?)</div>@si',$result,$sayfapreg);

				preg_match_all('@<a href="(.*?)">(.*?)</a>@si',$sayfapreg[0][0],$sayfalar);
				
				if(is_array($sayfalar) && isset($sayfalar[1]) && count($sayfalar[1]) > 0){
					
					foreach($sayfalar[1] as $sayfaUrl){
																		
						$sayfaUrl = substr($sayfaUrl, 2);
						
						echo $rdnsValue->rdns_url.$sayfaUrl;
						
						
						$curlPage = curl_init($rdnsValue->rdns_url.$sayfaUrl);
						curl_setopt($curlPage, CURLOPT_FAILONERROR, true);
						curl_setopt($curlPage, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.22 (KHTML, like Gecko) Chrome/25.0.1364.172 Safari/537.22");
						curl_setopt($curlPage, CURLOPT_HEADER, 0);
						curl_setopt($curlPage, CURLOPT_COOKIEFILE, $CookieDir);
						curl_setopt($curlPage, CURLOPT_FOLLOWLOCATION, true);
						curl_setopt($curlPage, CURLOPT_RETURNTRANSFER, true); 
						$resultPage = curl_exec($curlPage);
						
						var_dump($resultPage);
						
						/*
						
						SatanHosting::RdnsCronReadAndSave($resultPage, $rdnsValue->id, $serverId);
						
						*/
						
												
					}
				}
			}
		}
	}
	
	public static function rdns_ip_coz($name){
		
		$ip = substr($name, 0, -13);
		
		$parcalaip = explode(".", $ip);
		
		$karistir = array_reverse($parcalaip);
		
		$yeniIp = "";
		
		foreach($karistir as $birlestir){ $yeniIp .= "{$birlestir}."; }
		
		$yeniIp = substr($yeniIp, 0, -1);
		
		return $yeniIp;
	}
	
	public static function RdnsCronDeleteOneType(){
		$RdnsayarDelete = Rdnsayar::find()->where(['key' => 'SatanHosting_one_data_delete'])->one();
		if($RdnsayarDelete){
			if($RdnsayarDelete->value == 1){
				
				$RdnsayarTypes = Rdnsayar::find()->where(['key' => 'SatanHosting_one_data_types'])->one();
				
				if($RdnsayarTypes){
					
					$DeleteTypes = json_decode($RdnsayarTypes->value);
					
					if(is_array($DeleteTypes)){
						
						foreach($DeleteTypes as $DeleteType){
				
							$TypeLists = Rdnsdata::find()->where(['type' => $DeleteType])->all();
							
							$Veriler = [];
							
							foreach($TypeLists as $typelist){
								$Veriler[$typelist->ana_ip][] = array('ip' => $typelist->ana_ip, 'id2' => $typelist->id2);
							}
							
							$dataDelete = [];
											
							foreach($Veriler as $ip => $veriler){
								foreach($veriler as $veri){
									if(count($veriler) > 1){
										$dataDelete[$veri['ip']][] = $veri['id2'];
									}
								}
							}
							
							$Silincekler = [];
							
							foreach($dataDelete as $valueDelete){
								sort($valueDelete);
								unset($valueDelete[0]);
								foreach($valueDelete as $valDelete){
									$datafind = Rdnsdata::find()->where(['id2' => $valDelete])->one();
									if($datafind){
										$Silincekler[] = $datafind;
									}
								}
							}
							
							if(count($Silincekler) > 0){
								foreach($Silincekler as $Silincek){
									$deleteExistQuery = Rdnsdurum::find()
									->where(['rdns_server_id' => $Silincek->rdns_server_id, 'rdns_id' => $Silincek->rdns_id, 'dataid' => $Silincek->id, 'ana_ip' => $Silincek->ana_ip])
									->one();
									if(!$deleteExistQuery){
										$RdsInsert = new Rdnsdurum();
										$RdsInsert->dataid = $Silincek->id;
										$RdsInsert->pending_type = 3;
										$RdsInsert->status = 0;
										$RdsInsert->rdns_server_id = $Silincek->rdns_server_id;
										$RdsInsert->rdns_id = $Silincek->rdns_id;
										$RdsInsert->ana_ip = $Silincek->ana_ip;
										$RdsInsert->save();
									}
								}
							}
						
						}
					
					}
				
				}
			}
		}
	}
	
	public static function RdnsCronInsert(){
		$wait_pending_insert = Rdnsdurum::find()->where(['pending_type' => 1, 'status' => 0])->all();
		foreach($wait_pending_insert as $waiting_insert){
			$RdnsServer = Rdns::findOne($waiting_insert->rdns_id);
			if($RdnsServer){
				$post = [
					'domain' => $waiting_insert->rdns_server_id,
					'name' => $waiting_insert->ip_son,
					'type' => $waiting_insert->type,
					'content' => $waiting_insert->content,
					'prio' => $waiting_insert->priority,
					'ttl' => $waiting_insert->ttl,
					'commit' => 'Add record'
				];
				$CookieDir = dirname(dirname(__FILE__)).'/cookie/'.md5($RdnsServer->rdns_url.$RdnsServer->rdns_username).'.txt';
				$curl = curl_init($RdnsServer->rdns_url."add_record.php?id={$waiting_insert->rdns_server_id}");
				curl_setopt($curl, CURLOPT_FAILONERROR, true);
				curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.22 (KHTML, like Gecko) Chrome/25.0.1364.172 Safari/537.22");
				curl_setopt($curl, CURLOPT_HEADER, 0);
				curl_setopt($curl, CURLOPT_COOKIEFILE, $CookieDir);
				curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); 
				curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
				$result = curl_exec($curl);
				
				$RdnsModul = Rdnsdurum::findOne($waiting_insert->id);
				$RdnsModul->status = 1;
				$RdnsModul->save(false);
				
			}
		}
	}
	
	public static function RdnsCronUpdate(){
		$wait_pending_update = Rdnsdurum::find()->where(['pending_type' => 2, 'status' => 0])->all();
		foreach($wait_pending_update as $waiting_update){
			$RdnsServer = Rdns::findOne($waiting_update->rdns_id);
			if($RdnsServer){
				$post = [
					'rid' => $waiting_update->data->id2,
					'zid' => $waiting_update->rdns_server_id,
					'name' => $waiting_update->ip_son,
					'type' => $waiting_update->type,
					'content' => $waiting_update->content,
					'prio' => $waiting_update->priority,
					'ttl' => $waiting_update->ttl,
					'commit' => 'Commit changes'
				];
				$CookieDir = dirname(dirname(__FILE__)).'/cookie/'.md5($RdnsServer->rdns_url.$RdnsServer->rdns_username).'.txt';
				$curl = curl_init($RdnsServer->rdns_url."edit_record.php?domain={$waiting_update->rdns_server_id}&id={$waiting_update->data->id2}");
				curl_setopt($curl, CURLOPT_FAILONERROR, true);
				curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.22 (KHTML, like Gecko) Chrome/25.0.1364.172 Safari/537.22");
				curl_setopt($curl, CURLOPT_HEADER, 0);
				curl_setopt($curl, CURLOPT_COOKIEFILE, $CookieDir);
				curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); 
				curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
				$result = curl_exec($curl);
				
				$RdnsModul = Rdnsdurum::findOne($waiting_update->id);
				$RdnsModul->status = 1;
				$RdnsModul->save(false);
				
				$RdnsDataSil = Rdnsdata::findOne($waiting_update->data->id);
				$RdnsDataSil->delete();
				
			}
		}
	}
	
	public static function RdnsCronDelete(){
		$wait_pending_delete = Rdnsdurum::find()->where(['pending_type' => 3, 'status' => 0])->all();
		foreach($wait_pending_delete as $waiting_delete){
			$RdnsServer = Rdns::findOne($waiting_delete->rdns_id);
			if($RdnsServer){
				
				$CookieDir = dirname(dirname(__FILE__)).'/cookie/'.md5($RdnsServer->rdns_url.$RdnsServer->rdns_username).'.txt';
				$curl = curl_init($RdnsServer->rdns_url."delete_record.php?id={$waiting_delete->data->id2}&confirm=1");
				curl_setopt($curl, CURLOPT_FAILONERROR, true);
				curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.22 (KHTML, like Gecko) Chrome/25.0.1364.172 Safari/537.22");
				curl_setopt($curl, CURLOPT_HEADER, 0);
				curl_setopt($curl, CURLOPT_COOKIEFILE, $CookieDir);
				curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); 
				$result = curl_exec($curl);
				
				$RdnsModul = Rdnsdurum::findOne($waiting_delete->id);
				$RdnsModul->status = 1;
				$RdnsModul->save(false);
				
				$RdnsDataSil = Rdnsdata::findOne($waiting_delete->data->id);
				$RdnsDataSil->delete();
				
			}
		}
	}
	
	public static function RdnsCronReadAndSave($read, $rdnsID, $rdnsServerID){
		
		$returnContent = "";
		
		preg_match_all('@<table>(.*?)</table>@si',$read,$veri);
		preg_match_all('@<tr>(.*?)</tr>@si',$veri[0][0],$veri2);

		foreach($veri2[0] as $veriler){
			
			preg_match_all('@<td class="(.*?)">(.*?)</td>@si',$veriler,$veri3);

			if(!isset($veri3[2][1])) continue;

			$id = $veri3[2][1];
			$name = $veri3[2][2];
			$type = $veri3[2][3];
			$content = $veri3[2][4];
			$priority = $veri3[2][5];
			$ttl = $veri3[2][6];
			
			$nameinput = preg_match_all('@<input (.*?) value="(.*?)">@si',$name,$namepreg);
			$ttlinput = preg_match_all('@<input (.*?) value="(.*?)">@si',$ttl,$ttlpreg);
			$contentinput = preg_match_all('@<input (.*?) value="(.*?)">@si',$content,$contentpreg);
			$priorityinput = preg_match_all('@<input (.*?) value="(.*?)">@si',$priority,$prioritypreg);
			$typeselect = preg_match_all('@<option SELECTED (.*?)>@si',$type,$typepreg);
			if(isset($typepreg[1][0])){
				$typeselectedValue = preg_match_all('@value="(.*?)"@si',$typepreg[1][0],$typepregvalue);
			}
			
			
			if(isset($namepreg[2][0])){
				$name = $namepreg[2][0];
			}
			else{
				$name = $name;
			}
			
			if(isset($ttlpreg[2][0])){
				$ttl = $ttlpreg[2][0];
			}
			else{
				$ttl = $ttl;
			}
			
			if(isset($contentpreg[2][0])){
				$content = $contentpreg[2][0];
			}
			else{
				$content = $content;
			}
			
			if(isset($prioritypreg[2][0])){
				$priority = $prioritypreg[2][0];
			}
			else{
				$priority = $priority;
			}
			
			if(isset($typepregvalue)){
				if(isset($typepregvalue[1][0])){
					$type = $typepregvalue[1][0];
				}
				else{
					$type = $type;
				}
			}
			else{
				$type = $type;
			}
			
			$rdnsFind = Rdnsdata::find()->where(['id2' => $id, 'rdns_id' => $rdnsID, 'rdns_server_id' => $rdnsServerID])->one();
			if(!$rdnsFind){
				$model = new Rdnsdata();
				$model->rdns_id = $rdnsID;
				$model->rdns_server_id = $rdnsServerID;
				$model->id2 = $id;
				$model->name = $name;
				$model->type = $type;
				$model->content = $content;
				$model->priority = $priority;
				$model->ttl = $ttl;
				$model->ana_ip = SatanHosting::rdns_ip_coz($name);
				$model->save();
			}
			
		}
		
		return $returnContent;
	}
	
}
