<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "rdns".
 *
 * @property string $id
 * @property string $name
 * @property string $ram
 * @property string $cpu_mhz
 * @property string $cpu_core
 * @property string $hard
 * @property integer $band_width
 * @property integer $is_public
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Vps[] $vps
 */
class Rdns extends \yii\db\ActiveRecord
{
	
    public static function tableName()
    {
        return 'rdns_settings';
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
        return new \app\models\queries\RdnsQuery(get_called_class());
    }
    
    public function scenarios()
    {
        return [
            self::SCENARIO_DEFAULT => ['rdns_url', 'rdns_username', 'rdns_password', 'rdns_language', 'rdns_ids', 'rdns_types'],
        ];
    }
	
	public function getLanguageRdns(){
		return [
			'zh_CN' => 'Çince',
			'cs_CZ' => 'Çekce',
			'Dutch' => 'Flemenkçe',
			'en_EN' => 'İngilizce',
			'fr_FR' => 'Fransızca',
			'de_DE' => 'Almanca',
			'ja_JP' => 'Japonca',
			'lt_LT' => 'Litvanya',
			'nb_NO' => 'Norveççe',
			'pl_PL' => 'Lehçe',
			'ru_RU' => 'Rusça',
			'tr_TR' => 'Türkçe'
			
		];
	}
	
	public function getTypes(){
		return [
			"A" => "A",
			"AAAA" => "AAAA",
			"AFSDB" => "AFSDB",
			"CERT" => "CERT",
			"CNAME" => "CNAME",
			"DHCID" => "DHCID",
			"DLV" => "DLV",
			"DNSKEY" => "DNSKEY",
			"DS" => "DS",
			"EUI48" => "EUI48",
			"EUI64" => "EUI64",
			"HINFO" => "HINFO",
			"IPSECKEY" => "IPSECKEY",
			"KEY" => "KEY",
			"KX" => "KX",
			"LOC" => "LOC",
			"MINFO" => "MINFO",
			"MR" => "MR",
			"MX" => "MX",
			"NAPTR" => "NAPTR",
			"NS" => "NS",
			"NSEC" => "NSEC",
			"NSEC3" => "NSEC3",
			"NSEC3PARAM" => "NSEC3PARAM",
			"OPT" => "OPT",
			"PTR" => "PTR",
			"RKEY" => "RKEY",
			"RP" => "RP",
			"RRSIG" => "RRSIG",
			"SOA" => "SOA",
			"SPF" => "SPF",
			"SRV" => "SRV",
			"SSHFP" => "SSHFP",
			"TLSA" => "TLSA",
			"TSIG" => "TSIG",
			"TXT" => "TXT",
			"WKS" => "WKS"			
		];
	}
    
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }
    
    
}
