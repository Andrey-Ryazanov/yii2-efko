<?php

namespace app\models;


use Yii;
use yii\helpers\ArrayHelper;

class PricesRepository
{
    public function getType()
    {
        $list = RawType::find()
            ->select([
                'id',
                'name'
            ])
            ->asArray()
            ->all();
            
        return ArrayHelper::map($list, 'id', 'name');
    }

    public function getMonth()
    {
        $list = Month::find()
            ->select([
                'id',
                'name'
            ])
            ->asArray()
            ->all();
            
        return ArrayHelper::map($list, 'id', 'name');
    }

    public function getTonnage()
    {
        $list = Tonnage::find()
            ->select([
                'id',
                'value'
            ])
            ->asArray()
            ->all();
            
        return ArrayHelper::map($list, 'id', 'value');
    }

    public function getResultPrice(int $raw_type_id, int $tonnage_id, int $month_id): int
    {
        return Price::find()
        ->select([
            'price'
        ])
        ->where([
            'month_id' => $month_id
        ])
        ->andWhere([
            'raw_type_id' => $raw_type_id
        ])
        ->andWhere([
            'tonnage_id' => $tonnage_id
        ])
        ->scalar();
    }

    public function getPriceByRawTypeAndTonnage(int $raw_type_id, int $tonnage_id): array
    {
        return Price::find()
        ->select([
            'price'
        ])
        ->where([
            'raw_type_id' => $raw_type_id
        ])
        ->andWhere([
            'tonnage_id' => $tonnage_id
        ])
        ->asArray()
        ->all();
    }

    public function getMonthByTypeAndTonnage(int $raw_type_id, int $tonnage_id): array
    {
        return Price::find()
        ->alias('p')
        ->leftJoin(Month::tableName(). 'mth', 'p.month_id = mth.id')
        ->select([
            'mth.id as month_id',
            'mth.name as month_name'
        ])
        ->where([
            'p.raw_type_id' => $raw_type_id
        ])
        ->andWhere([
            'p.tonnage_id' => $tonnage_id
        ])
        ->asArray()
        ->all();
    }

    public function getTonnageByRawType(int $raw_type_id): array
    {
        return Price::find()
        ->alias('p')
        ->leftJoin(Tonnage::tableName() . ' t', 'p.tonnage_id = t.id')
        ->select([
            't.id as tonnage_id',
            't.value as tonnage_value'
        ])
        ->where([
            'p.raw_type_id' => $raw_type_id
        ])
        ->groupBy('tonnage_id')
        ->asArray()
        ->all();
    }

    public function getApiPriceList(string $raw_type): array
    {
        $prices = [];

        $priceModels = Price::find()
        ->alias('p')
        ->leftJoin(Tonnage::tableName(). 't', 'p.tonnage_id = t.id')
        ->leftJoin(Month::tableName(). 'mth', 'p.month_id = mth.id')
        ->leftJoin(RawType::tableName(). 'rt', 'p.raw_type_id = rt.id')
        ->all();
      
        foreach ($priceModels as $priceModel) {
            $rawTypeName = $priceModel->rawType->name;
            $tonnageValue = $priceModel->tonnage->value;
            $monthName = $priceModel->month->name;
            $price = $priceModel->price;

            if ($rawTypeName == $raw_type)
            {
                if (empty($prices[$rawTypeName]) === true) {
                    $prices[$rawTypeName] = [];
                }
    
                if (empty($prices[$rawTypeName][$monthName]) === true) {
                    $prices[$rawTypeName][$monthName] = [];
                }
    
                $prices[$rawTypeName][$monthName][$tonnageValue] = $price;
            }
        }
        
        return $prices;
    }
    
    public function getApiResultPrice(string $raw_type, int $tonnage, string $month): int
    {
        return Price::find()
        ->alias('p')
        ->leftJoin(Tonnage::tableName(). 't', 'p.tonnage_id = t.id')
        ->leftJoin(Month::tableName(). 'mth', 'p.month_id = mth.id')
        ->leftJoin(RawType::tableName(). 'rt', 'p.raw_type_id = rt.id')
        ->select([
            'p.price'
        ])
        ->where([
            'mth.name' => $month
        ])
        ->andWhere([
            'rt.name' => $raw_type
        ])
        ->andWhere([
            't.value' => $tonnage
        ])
        ->scalar();
    }
}