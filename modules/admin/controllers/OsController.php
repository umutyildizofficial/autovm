<?php

namespace app\modules\admin\controllers;

use Yii;
use yii\web\Controller;
use yii\data\Pagination;
use yii\web\NotFoundHttpException;

use app\models\Vps;
use app\models\Log;
use app\models\Os;
use app\modules\admin\filters\OnlyAdminFilter;
use yii\data\ActiveDataProvider;

class OsController extends Controller
{
    public function behaviors()
    {
        return [
            OnlyAdminFilter::className(),
        ];
    }

    public function actionIndex()
    {
        $operationSystems = Os::find()->orderBy('id DESC');

        $dataProvider = new ActiveDataProvider([
              'query' => $operationSystems,
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
        $model = new Os;

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->operation_system=$_POST['Os']['operation_system'];
            if ($model->save(false)) {
                Yii::$app->session->addFlash('success', Yii::t('app', 'Yeni işletim sistemi kaydı başarıyla oluşturuldu'));

                return $this->refresh();
            }
        }

        return $this->render('create', compact('model'));
    }

    public function actionEdit($id)
    {
        $model = Os::findOne($id);

        if (!$model) {
            throw new NotFoundHttpException(Yii::t('app', 'Hiçbir şey bulunamadı'));
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->operation_system=$_POST['Os']['operation_system'];
            if ($model->save(false)) {
                Yii::$app->session->addFlash('success', Yii::t('app', 'İşletim sistemi düzenlendi'));

                return $this->refresh();
            }
        }

        return $this->render('edit', compact('model'));
    }

    public function actionDelete()
    {
        $data = Yii::$app->request->post('data');
        
        foreach ($data as $id) {
         
            $os = Os::find()->where(['id' => $id])->one();
            
            if ($os) {
             
                $deleted = $os->delete();
                
                if ($deleted) {
                    Log::log(sprintf('%s işletim sistemi %s tarafından silindi!', $os->name, Yii::$app->user->identity->fullName));   
                }
            }
        }

        return $this->redirect(Yii::$app->request->referrer);
    }
}
