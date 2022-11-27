<?php

namespace app\modules\admin\models\forms;

use Yii;
use yii\base\Model;

class MailayarForm extends Model
{
    public $hostname;
	
    public $username;
	
    public $password;
	
    public $security;
	
    public $port;
	
    public $from;
	
    public function scenarios()
    {
        return [
            self::SCENARIO_DEFAULT => ['hostname', 'username', 'password', 'security', 'port', 'from'],
        ];
    }
	
    public function attributeLabels()
    {
        return [
            'hostname' => Yii::t('app', 'SMTP Hostname.'),
            'username' => Yii::t('app', 'Email Adresinizi Giriniz'),
            'password' => Yii::t('app', 'Email Şifrenizi Giriniz'),
            'security' => Yii::t('app', 'Şifreleme Türünü Yazınız Örnek : ssl'),
            'port' => Yii::t('app', 'Şifreleme Portunu Giriniz. Örnek : 465'),
            'from' => Yii::t('app', 'Gönderilen mailde gözükecek kullanıcı adını giriniz.')
        ];
    }
}
