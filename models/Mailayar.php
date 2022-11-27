<?php

namespace app\models;

use Yii;

class Mailayar extends \yii\db\ActiveRecord
{
	public $toplam;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'SatanHosting_mailer';
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
        return new \app\models\queries\MailayarQuery(get_called_class());
    }
	
}
