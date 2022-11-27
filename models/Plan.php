<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "plan".
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
class Plan extends \yii\db\ActiveRecord
{
    const IS_PUBLIC = 1;
    const IS_NOT_PUBLIC = 2;
    const IS_FIRST = 1;
    const IS_NOT_FIRST = 2;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'plan';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'ram', 'cpu_mhz', 'cpu_core', 'hard'], 'required'],
            [['ram', 'cpu_mhz', 'cpu_core', 'hard', 'is_public', 'created_at', 'updated_at','band_width'], 'integer'],
            [['name'], 'string', 'max' => 255],
            ['hard', 'compare', 'compareValue' => 21, 'operator' => '>=']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Paket Adı'),
            'ram' => Yii::t('app', 'RAM Miktarı'),
            'cpu_mhz' => Yii::t('app', 'CPU Hızı Mhz'),
            'cpu_core' => Yii::t('app', 'CPU Çekirdeği'),
            'hard' => Yii::t('app', 'Disk Alanı'),
            'is_public' => Yii::t('app', 'Herkese açık mı?'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'band_width'=> Yii::t('app', 'BandWidth'),
            'network'=> Yii::t('app', 'Network'),
			'os_lists' => Yii::t('app', 'İşletim Sistemleri'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVps()
    {
        return $this->hasMany(Vps::className(), ['plan_id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return \app\models\queries\PlanQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\queries\PlanQuery(get_called_class());
    }
    
    public function scenarios()
    {
        return [
            self::SCENARIO_DEFAULT => ['name', 'ram', 'cpu_mhz', 'cpu_core', 'hard', 'band_width', 'is_public', 'network', 'os_lists'],
        ];
    }
    
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }
    
    public function getIsPublic()
    {
        return $this->is_public == self::IS_PUBLIC;
    }
    
    public static function getPublicYesNo()
    {
        return [
            self::IS_PUBLIC => Yii::t('app', 'Evet'),
            self::IS_NOT_PUBLIC => Yii::t('app', 'Hayır'),
        ];
    }
    public static function getNetworkSettings()
    {
        return [
            self::IS_FIRST => Yii::t('app', 'Birincil Network'),
            self::IS_NOT_FIRST => Yii::t('app', 'İkincil Network'),
        ];
    }
	public static function getOperationSystem(){
		
		$operationSystems = Os::find()->active()->all();
		$returnArray = [];
		foreach($operationSystems as $os){
			$returnArray[$os->id] = $os->name;
		}
		return $returnArray;
		
	}
}
