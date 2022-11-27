<?php

namespace app\models;

use Yii;

class Rdnsayar extends \yii\db\ActiveRecord
{
	public $toplam;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rdns_ayar';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['key'], 'required'],
            [['value'], 'string'],
            [['key'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'key' => Yii::t('app', 'key'),
            'value' => Yii::t('app', 'Value'),
        ];
    }

    public static function find()
    {
        return new \app\models\queries\RdnsayarQuery(get_called_class());
    }
	
}
