<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

class Rdnsdata extends \yii\db\ActiveRecord
{
	
    public static function tableName()
    {
        return 'rdns_data';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['rdns_url', 'rdns_username', 'rdns_password', 'rdns_language', 'rdns_ids'], 'required']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'rdns_server_id' => Yii::t('app', 'Domain ID'),
			
        ];
    }
	
    public function getSurec()
    {
        $surec = Rdnsdurum::find()->where(['dataid' => $this->id, 'status' => 0])->one();
		if($surec){
			if($surec->pending_type == 1){
				return '<label class="label label-success">Eklenme Sürecinde</label>';
			}
			else if($surec->pending_type == 2){
				return '<label class="label label-primary">Düzenlenme Sürecinde</label>';
			}
			else if($surec->pending_type == 3){
				return '<label class="label label-danger">Silinme Sürecinde</label>';
			}
			else{
				return '<label class="label label-warning">Süreç Bulunamadı</label>';
			}
		}
		else{
			return '<label class="label label-default">Süreç Yok</label>';
		}
    }

    public static function find()
    {
        return new \app\models\queries\RdnsdataQuery(get_called_class());
    }
	
    public function getRdns()
    {
        return $this->hasOne(Rdns::className(), ['id' => 'rdns_id']);
    }
    
    public function scenarios()
    {
        return [
            self::SCENARIO_DEFAULT => ['rdns_id', 'rdns_server_id', 'id2', 'name', 'type', 'content', 'priority', 'ttl', 'ana_ip'],
        ];
    }
	
    
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }
    
    
}
