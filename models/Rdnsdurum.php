<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

class Rdnsdurum extends \yii\db\ActiveRecord
{
	
    public static function tableName()
    {
        return 'rdns_status';
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
            'rdns_url' => Yii::t('app', 'Rdns Url'),
            'rdns_username' => Yii::t('app', 'Rdns Kullanıcı Adı')
			
        ];
    }

    public static function find()
    {
        return new \app\models\queries\RdnsdurumQuery(get_called_class());
    }
	
    public function getData()
    {
        return $this->hasOne(Rdnsdata::className(), ['id' => 'dataid']);
    }
    
    public function scenarios()
    {
        return [
            self::SCENARIO_DEFAULT => ['dataid', 'pending_type', 'type', 'content', 'priority', 'ttl', 'status', 'ana_ip', 'ip_son', 'rdns_server_id', 'rdns_id'],
        ];
    }
	
    
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }
    
    
}
