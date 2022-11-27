<?php

namespace app\modules\admin\controllers;

use Yii;
use yii\web\Controller;
use yii\data\Pagination;
use yii\web\NotFoundHttpException;

use app\models\Vps;
use app\models\Server;
use app\models\Ip;
use app\models\User;
use app\models\Os;
use app\models\Plan;
use app\models\Setting;
use app\models\Mailayar;
use app\models\UserLogin;
use app\models\VpsAction;
use app\models\Bandwidth;
use app\modules\admin\filters\OnlyAdminFilter;
use app\modules\admin\models\forms\SettingForm;
use app\modules\admin\models\forms\MailayarForm;

class DefaultController extends Controller
{
    public function behaviors()
    {
        return [
            OnlyAdminFilter::className(),
        ];
    }
	
	public function actionMailer(){
		echo Yii::$app->SatanHostingmailer->send('mesutmeric61@gmail.com', 'test', 'test');
	}

    
    public function actionIndex()
    {
        $stats = new \stdClass;
        
        $stats->totalVps = Vps::find()->count();
        $stats->totalServer = Server::find()->count();
        $stats->totalUsers = User::find()->count();
        $stats->totalIp = Ip::find()->count();
        $stats->totalOs = Os::find()->count();
        $stats->totalPlan = Plan::find()->count();
        $stats->totalVpsAction = VpsAction::find()->count();
        $stats->emptyIp = Ip::find()->leftJoin('vps_ip', 'vps_ip.ip_id = ip.id')
            ->andWhere('vps_ip.id IS NULL')
            ->count();
        $stats->vpsActions = VpsAction::find()->orderBy('id DESC')->limit(6)->all();
		$stats->bandwidth = Bandwidth::find()->sum('pure_used');
		
		$stats->logins = UserLogin::find()->orderBy('id DESC')->limit(8)->all();
        
        return $this->render('index', compact('stats'));
    }
    
    public function actionLogin()
    {
        $logins = UserLogin::find()->orderBy('id DESC');
        
        $count = clone $logins;
        $pages = new Pagination(['totalCount' => $count->count()]);
        $pages->setPageSize(8);
        
        $logins = $logins->offset($pages->offset)->limit($pages->limit)->all();
        
        return $this->render('login', [
            'logins' => $logins,
            'pages' => $pages,
        ]);
    }
    
    public function actionSetting()
    {
        $model = new SettingForm;
        
        $model->setAttributes(Yii::$app->setting->all());
        
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            foreach ($model->getAttributes() as $key => $value) {
                $setting = Setting::find()->where(['key' => $key])->one();
                
                if ($setting) {
                    $setting->value = $value;
                    $setting->save(false);  
                }
            }
            
            return $this->refresh();
        } 
        
        return $this->render('setting', compact('model'));
    }
	
    public function actionMail()
    {
        $model = new MailayarForm;
        
        $model->setAttributes(Yii::$app->SatanHostingmailer->all());
        
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            foreach ($model->getAttributes() as $key => $value) {
                $setting = Mailayar::find()->where(['key' => $key])->one();
                
				if($key == 'password'){
					$value = base64_encode(Yii::$app->security->encryptByPassword($value, Yii::$app->params['secret']));
				}
				
                if ($setting) {
                    $setting->value = $value;
                    $setting->save(false);  
                }
            }
            
            return $this->refresh();
        } 
        
        return $this->render('mail', compact('model'));
    }
}