<?php

namespace app\modules\admin\controllers;

use Yii;
use yii\web\Controller;
use yii\data\Pagination;
use yii\web\NotFoundHttpException;

use app\models\Vps;
use app\models\Log;
use app\models\Server;
use app\models\Datastore;
use app\modules\admin\filters\OnlyAdminFilter;
use yii\data\ActiveDataProvider;

class DatastoreController extends Controller
{
    public function behaviors()
    {
        return [
            OnlyAdminFilter::className(),
        ];
    }

    public function actionIndex()
    {
        $datastores = Datastore::find()->orderBy('id DESC');

        $dataProvider = new ActiveDataProvider([
              'query' => $datastores,
              'pagination' => [
                'pageSize' => 10,
              ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate($id)
    {
        $server = Server::findOne($id);

        if (!$server) {
            throw new NotFoundHttpException(Yii::t('app', 'Hiçbir şey bulunamadı'));
        }

        $model = new Datastore;
		$model->server_id = $server->id;

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->save(false)) {
                Yii::$app->session->addFlash('success', Yii::t('app', 'Yeni disk başarıyla oluşturuldu'));

                return $this->refresh();
            }
        }

        return $this->render('create', [
            'server' => $server,
            'model' => $model,
        ]);
    }

    public function actionEdit($id)
    {
        $model = Datastore::findOne($id);

        if (!$model) {
            throw new NotFoundHttpException(Yii::t('app', 'Hiçbir şey bulunamadı'));
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->save(false)) {
                Yii::$app->session->addFlash('success', Yii::t('app', 'Disk düzenlendi'));

                return $this->refresh();
            }
        }

        return $this->render('edit', compact('model'));
    }

    public function actionDelete()
    {
        $data = Yii::$app->request->post('data');
        
        foreach ($data as $id) {
         
            $datastore = Datastore::find()->where(['id' => $id])->one();
            
            if ($datastore) {
             
                $deleted = $datastore->delete();
                
                if ($deleted) {
                    Log::log(sprintf('%s diski %s tarafından silindi!', $datastore->value, Yii::$app->user->identity->fullName));   
                }
            }
        }

        return $this->redirect(Yii::$app->request->referrer);
    }
}
