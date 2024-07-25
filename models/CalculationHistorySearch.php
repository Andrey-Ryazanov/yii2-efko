<?php
namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\CalculationHistory;

class CalculationHistorySearch extends CalculationHistory
{
    public $month_name;
    public $raw_type_name;
    public $tonnage_value;
    public $created_at;

    public function rules()
    {
        return [
            [['month_name', 'raw_type_name', 'tonnage_value','created_at'], 'safe'],
        ];
    }

    public function scenarios()
    {
        return parent::scenarios();
    }

    public function search($params)
    {
        $query = CalculationHistory::find()
            ->alias('ch')
            ->joinWith('user');

        if(!Yii::$app->user->can('administrator')) 
        {
            $query = $query->where(['ch.user_id' => Yii::$app->user->id]);
        } 

        $dataProvider = new ActiveDataProvider([
            'query' => $query, 
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'ch.month_name', $this->month_name])
            ->andFilterWhere(['like', 'ch.raw_type_name', $this->raw_type_name])
            ->andFilterWhere(['like', 'ch.tonnage_value', $this->tonnage_value])
            ->andFilterWhere(['like', 'ch.created_at', $this->created_at]);;

        return $dataProvider;
    }
}