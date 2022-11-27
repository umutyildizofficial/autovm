<?php

namespace app\models\searchs;


use app\models\Ip;
use yii\data\ActiveDataProvider;
use yii\base\Model;

class searchIpempty extends Ip
{
    
    public function rules()
    {
        return [
            [['ip'], 'safe'],
        ];
    }
    
    public function scenarios()
    {
        return Model::scenarios();
    }
    
    public function search($params)
    {
        $query = Ip::find()->leftJoin('vps_ip', 'vps_ip.ip_id = ip.id')
            ->andWhere('vps_ip.id IS NULL');
        
        $dataProvider = new ActiveDataProvider([
              'query' => $query,
              'pagination' => [
                'pageSize' => 10,
              ],
        ]);
        
        if (!($this->load($params)) && $this->validate()) {
            return $dataProvider;
        }
        
        $query->andFilterWhere(['like', 'ip', trim($this->ip)]);
        
        return $dataProvider;
    }
}