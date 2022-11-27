<?php

namespace app\models\searchs;


use app\models\Rdnsdata;
use yii\data\ActiveDataProvider;
use yii\base\Model;

class searchRdnsdata extends Rdnsdata
{
    
    public function rules()
    {
        return [
            [['ana_ip', 'type', 'content'], 'safe'],
        ];
    }
    
    public function scenarios()
    {
        return Model::scenarios();
    }
    
    public function search($params)
    {
        $query = Rdnsdata::find();
        
        $dataProvider = new ActiveDataProvider([
              'query' => $query,
              'pagination' => [
                'pageSize' => 15,
              ],
        ]);
        
        if (!($this->load($params)) && $this->validate()) {
            return $dataProvider;
        }
        
        $query->andFilterWhere(['like', 'ana_ip', trim($this->ana_ip)]);
        $query->andFilterWhere(['like', 'type', trim($this->type)]);
        $query->andFilterWhere(['like', 'content', trim($this->content)]);
        
        return $dataProvider;
    }
}