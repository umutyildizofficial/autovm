<?php

namespace app\modules\admin\controllers;

use Yii;
use yii\web\Controller;
use yii\data\Pagination;
use yii\web\NotFoundHttpException;

use app\models\Vps;
use app\models\Log;
use app\models\Plan;
use app\modules\admin\filters\OnlyAdminFilter;
use yii\data\ActiveDataProvider;

class PlanController extends Controller
{
    public function behaviors()
    {
        return [
            OnlyAdminFilter::className(),
        ];
    }

    public function actionIndex()
    {
        $plans = Plan::find()->orderBy('id DESC');

        $dataProvider = new ActiveDataProvider([
              'query' => $plans,
              'pagination' => [
                'pageSize' => 10,
              ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate()
    {
		
        $model = new Plan;
		
		if($model->load(Yii::$app->request->post()) && $model->validate()){
			$Veriler = array();
			foreach($model->Attributes as $key => $value){
				if($key == "os_lists"){
					$Veriler[$key] = json_encode($value);
				}
				else{
					$Veriler[$key] = $value;
				}
			}
			$model->Attributes = $Veriler;
            if ($model->save(false)) {
                Yii::$app->session->addFlash('success', Yii::t('app', 'Yeni paket başarıyla oluşturuldu'));

                return $this->refresh();
            }
		}
		
        return $this->render('create', compact('model'));
		
    }

    public function actionEdit($id)
    {
        $model = Plan::findOne($id);

        if (!$model) {
            throw new NotFoundHttpException(Yii::t('app', 'Hiçbir şey bulunamadı'));
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
			$OsLists = json_encode($model->os_lists);
			$model->os_lists = $OsLists;
            if ($model->save(false)) {
                Yii::$app->session->addFlash('success', Yii::t('app', 'Paket düzenlendi'));

                return $this->refresh();
            }
        }
        return $this->render('edit', compact('model'));
    }

    public function actionDelete()
    {
        $data = Yii::$app->request->post('data');
        
        foreach ($data as $id) {
         
            $plan = Plan::find()->where(['id' => $id])->one();
            
            if ($plan) {
                
                $deleted = $plan->delete();
                
                if ($deleted) {
                    Log::log(sprintf('%s paketi %s tarafından silindi!', $plan->name, Yii::$app->user->identity->fullName));   
                }
            }
        }

        return $this->redirect(Yii::$app->request->referrer);
    }
}
