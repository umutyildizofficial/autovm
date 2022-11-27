<?php

namespace app\models\queries;

/**
 * This is the ActiveQuery class for [[\app\models\Plan]].
 *
 * @see \app\models\Plan
 */
class RdnsdurumQuery extends \yii\db\ActiveQuery
{
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\models\Plan|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
	
	public function andQuery($query){
        return $this->andWhere($query);
	}
	public function orQuery($query){
        return $this->orWhere($query);
	}
	
}