<?php

namespace app\modules\admin\controllers;

use Yii;
use yii\web\Controller;
use yii\data\Pagination;
use yii\web\NotFoundHttpException;

use app\models\Vps;
use app\models\Log;
use app\models\SatanHosting;
use app\models\Rdns;
use app\modules\admin\filters\OnlyAdminFilter;
use yii\data\ActiveDataProvider;
use app\models\searchs\searchRdnsdata;
use app\models\searchs\searchRdnsdurum;


class RdnsController extends Controller
{
    public function behaviors()
    {
        return [
            OnlyAdminFilter::className(),
        ];
    }

    public function actionIndex()
    {
        $rdns_users = Rdns::find()->orderBy('id DESC');

        $dataProvider = new ActiveDataProvider([
              'query' => $rdns_users,
              'pagination' => [
                'pageSize' => 10,
              ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }
	
    public function actionData()
    {
        $searchModel = new searchRdnsdata();

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('data', [
            'dataProvider' => $dataProvider,
			'searchModel' => $searchModel,
	   ]);
    }
	
    public function actionWaitpending()
    {
		
        $searchModel = new searchRdnsdurum();

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('waitpending', [
            'dataProvider' => $dataProvider,
			'searchModel' => $searchModel,
        ]);
    }
	
	public function actionRdnsGuncelle(){
		return SatanHosting::RdnsControllerRdnsGuncelle();
	}
	
	public function actionDataView(){
		$rdnsModuleSatanHosting = SatanHosting::RdnsControllerDataView();
		if($rdnsModuleSatanHosting['ok'] == true){
			return $this->renderAjax('data-edit', $rdnsModuleSatanHosting['response']);
		}
		else{
			return $rdnsModuleSatanHosting['response'];
		}
	}
	
	public function actionSettings(){
		$rdnsModule = SatanHosting::RdnsSettings();
		if(isset($rdnsModule['post'])){
			return $this->refresh();
		}
        return $this->render('settings', $rdnsModule['response']);
	}
	
    public function actionCreate()
    {
		$rdnsModule = SatanHosting::RdnsControllerCreateUser();
		if(isset($rdnsModule['post'])){
			return $this->refresh();
		}
        return $this->render('create', $rdnsModule['response']);
    }
	
    public function actionEdit($id)
    {
		$rdnsModule = SatanHosting::RdnsControllerEditUser($id);
		if(isset($rdnsModule['post'])){
			return $this->refresh();
		}
        return $this->render('edit', $rdnsModule['response']);
    }
	
    public function actionDelete()
    {
		SatanHosting::RdnsControllerDeleteUser();
        return $this->redirect(Yii::$app->request->referrer);
    }
	
	public function actionDeleteData(){
		SatanHosting::RdnsControllerDeleteData();
        return $this->redirect(Yii::$app->request->referrer);
	}
	
	public function actionDeletePending(){
		SatanHosting::RdnsControllerDeletePending();
        return $this->redirect(Yii::$app->request->referrer);
	}
	

}
