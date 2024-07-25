<?php

namespace app\models;


use Yii;
use yii\helpers\ArrayHelper;

class CalculationsRepository
{

    public function getPriceByRawTypeAndTonnage(int $calculation_id, string $raw_type_name, string $tonnage_value): array
    {
        return CalculationSnapshot::find()
        ->select([
            'price'
        ])
        ->where([
            'raw_type_name' => $raw_type_name
        ])
        ->andWhere([
            'tonnage_value' => $tonnage_value
        ])
        ->andWhere([
            'calculation_history_id' => $calculation_id
        ])
        ->asArray()
        ->all();
    }

    public function getMonthByTypeAndTonnage(int $calculation_id, string $raw_type_name, string $tonnage_value): array
    {
        return CalculationSnapshot::find()
        ->select([
            'month_name'
        ])
        ->where([
            'raw_type_name' => $raw_type_name
        ])
        ->andWhere([
            'tonnage_value' => $tonnage_value
        ])
        ->andWhere([
            'calculation_history_id' => $calculation_id
        ])
        ->asArray()
        ->all();
    }

    public function getTonnageByRawType(int $calculation_id, string $raw_type_name): array
    {
        return CalculationSnapshot::find()
        ->select([
            'tonnage_value'
        ])
        ->where([
            'raw_type_name' => $raw_type_name
        ])
        ->andWhere([
            'calculation_history_id' => $calculation_id
        ])
        ->groupBy('tonnage_value')
        ->asArray()
        ->all();
    }
    public function getHistoryCalculation(int $calculation_id): int
    {
        return CalculationHistory::find()
        ->select([
            'price'
        ])
        ->where([
            'id' => $calculation_id
        ])
        ->scalar();
    }
}