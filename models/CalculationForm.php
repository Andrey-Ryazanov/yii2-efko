<?php
namespace app\models;


use Yii;

use yii\base\Model;

class CalculationForm extends Model
{
    public $type_id;
    public $tonnage_id;
    public $month_id;
  
    public function rules()
    {
        return [
            [['type_id', 'tonnage_id', 'month_id'], 'required', 'message' => '{attribute} не может быть пустым!'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'type_id' => 'Тип сырья',
            'tonnage_id' => 'Тоннаж',
            'month_id' => 'Месяц',
        ];
    }

    public function checkAuth()
    {
        $loginSuccess = Yii::$app->session->getFlash('loginSuccess');
        if ($loginSuccess) 
        {
            return true;
        }

        return false;
    }

    public function saveToQueue():void
    {
        $basePath = Yii::getAlias('@runtime') . '/queue.job';
        if (file_exists($basePath))
        {
            unlink($basePath);
        }
        foreach($_POST['CalculationForm'] as $key => $value)
        {
            file_put_contents($basePath, "$key = $value" . PHP_EOL, FILE_APPEND);
        }
    }


    public function saveCalculationHistory($month_id, $raw_type_id, $tonnage_id)
    {
        $query = Price::find()
            ->alias('p')
            ->leftJoin(Tonnage::tableName(). 't', 'p.tonnage_id = t.id')
            ->leftJoin(Month::tableName(). 'mth', 'p.month_id = mth.id')
            ->leftJoin(RawType::tableName(). 'rt', 'p.raw_type_id = rt.id')
            ->select([
                'mth.name as month_name',
                'rt.name as raw_type_name',
                't.value as tonnage_value',
                'p.price as price'
            ]);
            
        $item = $query
            ->where(['p.month_id' => $month_id])
            ->andWhere(['p.raw_type_id' => $raw_type_id])
            ->andWhere(['p.tonnage_id' => $tonnage_id])
            ->asArray()
            ->one();
        
        $items = $query
            ->where(['p.raw_type_id' => $raw_type_id])
            ->asArray()
            ->all();

        if ($item !== null) {
            $calculationHistory = new CalculationHistory();
            $calculationHistory->user_id = Yii::$app->user->id;
            $calculationHistory->month_name = $item['month_name'];
            $calculationHistory->raw_type_name = $item['raw_type_name'];
            $calculationHistory->tonnage_value = $item['tonnage_value'];
            $calculationHistory->price = $item['price'];
            $calculationHistory->save();

            foreach ($items as $it)
            {
                $calculationSnapshot = new CalculationSnapshot();
                $calculationSnapshot->calculation_history_id = $calculationHistory->id;
                $calculationSnapshot->month_name = $it['month_name'];
                $calculationSnapshot->raw_type_name = $it['raw_type_name'];
                $calculationSnapshot->tonnage_value = $it['tonnage_value'];
                $calculationSnapshot->price = $it['price'];
                $calculationSnapshot->save();
            }

            return true;

        }

        return false;
    }
}
?>
