<?php

namespace app\modules\admin\models\forms;

use Yii;
use yii\base\Model;

class SettingForm extends Model
{
    public $title;
    public $terminate;
    public $language;
    public $change_limit;
    public $SatanHosting_rdns_data_types;
    public $SatanHosting_rdns_data_delete;

    public function rules()
    {
        return [
            [['title', 'terminate', 'language', 'change_limit'], 'required'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'title' => Yii::t('app', 'Panel Üst Başlığı'),
            'terminate' => Yii::t('app', 'Sunucu Oto Silinme'),
            'language' => Yii::t('app', 'Dil'),
            'change_limit' => Yii::t('app', 'Kullanıcı Format Limiti'),
            'SatanHosting_rdns_data_types' => Yii::t('app', 'Birden fazla olamayacak kayıt tiplerini seçiniz.'),
            'SatanHosting_rdns_data_delete' => Yii::t('app', 'Birden fazla olan kayıt tipleri otomatik silinsin.')
        ];
    }
}
