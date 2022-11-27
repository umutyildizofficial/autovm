<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\behaviors\TimestampBehavior;

use app\extensions\Api;

/**
 * This is the model class for table "server".
 *
 * @property string $id
 * @property string $parent_id
 * @property string $name
 * @property string $ip
 * @property integer $port
 * @property string $username
 * @property string $password
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Datastore[] $datastores
 * @property Ip[] $ips
 * @property Vps[] $vps
 */
class Server extends \yii\db\ActiveRecord
{
    public function afterFind()
    {
        $this->password = Yii::$app->security->decryptByPassword(base64_decode($this->password), Yii::$app->params['secret']);
        
        if ($this->vcenter_password) {
            $this->vcenter_password = Yii::$app->security->decryptByPassword(base64_decode($this->vcenter_password), Yii::$app->params['secret']);   
        }
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'server';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ip', 'name', 'port', 'username', 'password', 'dns1', 'dns2', 'server_address'], 'required'],
            [['version'], 'in', 'range' => self::getVersionList()],
            [['parent_id', 'port', 'created_at', 'updated_at', 'version'], 'integer'],
            [['ip'], 'string', 'max' => 45],
			[['password'], 'string'],
            [['name', 'username', 'vcenter_ip', 'vcenter_username', 'vcenter_password', 'network', 'second_network', 'virtualization', 'dns1', 'dns2', 'server_address'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'parent_id' => Yii::t('app', 'Parent ID'),
            'name' => Yii::t('app', 'Sunucu Adı'),
            'ip' => Yii::t('app', 'IP Adresi'),
            'port' => Yii::t('app', 'Port'),
            'username' => Yii::t('app', 'Username'),
            'password' => Yii::t('app', 'Password'),
            'vcenter_ip' => Yii::t('app', 'vCenter IP'),
            'vcenter_username' => Yii::t('app', 'vCenter Kullanıcı Adı'),
            'vcenter_password' => Yii::t('app', 'vCenter Şifre'),
            'network' => Yii::t('app', 'Network'),
            'second_network' => Yii::t('app', 'Second Network'),
            'version' => Yii::t('app', 'Esxi Version'),
            'virtualization' => Yii::t('app', 'VMWare Sürümü'),
            'dns1' => Yii::t('app', 'DNS1'),
            'dns2' => Yii::t('app', 'DNS2'),
            'server_address' => Yii::t('app', 'Sunucu Adresi'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDatastores()
    {
        return $this->hasMany(Datastore::className(), ['server_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIps()
    {
        return $this->hasMany(Ip::className(), ['server_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVps()
    {
        return $this->hasMany(Vps::className(), ['server_id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return \app\models\queries\ServerQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\queries\ServerQuery(get_called_class());
    }

    public function scenarios()
    {
        return [
            self::SCENARIO_DEFAULT => ['parent_id', 'name', 'ip', 'port', 'username', 'password', 'vcenter_ip', 'vcenter_username', 'vcenter_password', 'network', 'second_network', 'version', 'virtualization', 'dns1', 'dns2', 'server_address'],
        ];
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    public function beforeSave($insert)
    {
        $this->password = base64_encode(Yii::$app->security->encryptByPassword($this->password, Yii::$app->params['secret']));
       
        if ($this->vcenter_password) {
            $this->vcenter_password = base64_encode(Yii::$app->security->encryptByPassword($this->vcenter_password, Yii::$app->params['secret']));   
        }
        
        return parent::beforeSave($insert);
    }
    
    public function afterSave($insert, $changedAttributes)
    {
        $server = Server::findOne($this->id);
        
        if ($server && $insert) {

            $api = new Api;

            $api->setData(['server' => $server->attributes]);

            $result = $api->request(Api::ACTION_DS);
            
            if (!empty($result)) {

                foreach ($result as $item) {
                    
                    $record = new Datastore;
                    
                    $record->server_id = $this->id;
                    
                    $record->uuid = $item->hash;
                    $record->value = $item->name;
                    $record->space = $item->capacity;
                    
                    $record->is_default = Datastore::IS_NOT_DEFAULT;
                    
                    $record->save(false);
                }
            }
        }

        return parent::afterSave($insert, $changedAttributes);
    }

    public static function getVersionList()
    {
        return array_combine(range(8, 15), range(8, 15));
    }

    public static function getVirtualizationList()
    {
        return [
            'VMware ESXi 5.x' => 'VMware ESXi 5.x',
            'VMware ESXi 6.0' => 'VMware ESXi 6.0',
            'VMware ESXi 6.5' => 'VMware ESXi 6.5',
            'VMware ESXi 6.7' => 'VMware ESXi 6.7',
            'VMware ESXi 6.7 B' => 'VMware ESXi 6.7 B',
        ];
    }

    public static function getListData()
    {
        return ArrayHelper::map(self::find()->all(), 'id', 'name');
    }
}
