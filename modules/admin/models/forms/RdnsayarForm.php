<?php

namespace app\modules\admin\models\forms;

use Yii;
use yii\base\Model;

class RdnsayarForm extends Model
{
    public $SatanHosting_one_data_types;
    public $SatanHosting_one_data_delete;
	
    public function scenarios()
    {
        return [
            self::SCENARIO_DEFAULT => ['SatanHosting_one_data_types', 'SatanHosting_one_data_delete'],
        ];
    }
	
    public function attributeLabels()
    {
        return [
            'SatanHosting_one_data_types' => Yii::t('app', 'Birden fazla eklenemeyecek tipleri seçiniz.'),
            'SatanHosting_one_data_delete' => Yii::t('app', 'Birden fazla olan verileri otomatik sil'),
        ];
    }
}
