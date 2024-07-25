<?php
/**
 * @link https://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license https://www.yiiframework.com/license/
 */

namespace app\commands;

use Yii;

use app\models\Price;
use app\models\Month;
use app\models\Tonnage;
use app\models\RawType;

use yii\console\Controller;
use yii\console\ExitCode;
use yii\helpers\Console;
use yii\helpers\BaseConsole;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Andrey Ryazanov <andrey58645@mail.ru>
 * @since 2.0
 */
class CalculateController extends Controller
{
    public $month;
    public $type;
    public $tonnage;
    
    public function options($actionID)
    {
        return ['month', 'type', 'tonnage'];
    }

    public function validate()
    {
        if (empty($this->type) === true && empty($this->tonnage) === true && empty($this->month) === true){
            Console::output(Console::ansiFormat("Ошибка: введённых значений недостаточно ". PHP_EOL , [BaseConsole::FG_RED]));
            return false;
        }
        return true;
    }

    public function validatePrice($prices)
    {
        $errorMessage = [];
        if (empty($prices[$this->type]) === true) {
            $errorMessage[] = "не найден прайс для значения: ".$this->type;
        }
        if (empty($prices[$this->type][$this->tonnage]) === true) {
            $errorMessage[] =  "не найден прайс для значения: ".$this->tonnage;
        }
        if (empty($prices[$this->type][$this->tonnage][$this->month]) === true) {
            $errorMessage[] = "не найден прайс для значения: ".$this->month;
        }
    
        if (count($errorMessage) > 0){
            Console::output(Console::ansiFormat("Ошибка: введённых значений недостаточно ". PHP_EOL . implode(PHP_EOL, $errorMessage) . PHP_EOL . "Проверьте корректность введённых значений" .PHP_EOL ,[BaseConsole::FG_RED]));
            return false;
        }       
        return true;
    }

    public function drawTable($prices)
    {
        $price = $prices[$this->type][$this->tonnage][$this->month] ?? null;
        if (empty($price) === false) {
            $months = array_keys($prices[$this->type][$this->tonnage]);
            $tonnages = array_keys($prices[$this->type]);
            $table  = "+----------------+".str_repeat('-----------------+', count($months)) . PHP_EOL;
            $table .= "| Месяц / Тоннаж |";
            foreach ($months as $month) {
                $table .= ' ' . str_pad(substr($month, 0, 22), 22, ' ', STR_PAD_BOTH) . ' |';
            }
            $table  .= PHP_EOL;
            $table  .= "+----------------+".str_repeat('-----------------+', count($months)) . PHP_EOL;
            foreach ($tonnages as $tonnage) {
                $table .= ' ' . str_pad(substr($tonnage, 0, 15), 15, ' ', STR_PAD_BOTH) . ' |';
                $price_list = array_values($prices[$this->type][$tonnage]);
                foreach ($price_list as $price_elem) {
                    $table .= ' ' . str_pad(substr($price_elem, 0, 15), 15, ' ', STR_PAD_BOTH) . ' |';
                }
                $table .= PHP_EOL;
                $table  .= "+----------------+".str_repeat('-----------------+', count($months)) . PHP_EOL;
            }
            echo $table;
        }
    }

    public function createAssocArray(): array
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

            if (empty($prices[$rawTypeName]) === true) {
                $prices[$rawTypeName] = [];
            }

            if (empty($prices[$rawTypeName][$tonnageValue]) === true) {
                $prices[$rawTypeName][$tonnageValue] = [];
            }

            $prices[$rawTypeName][$tonnageValue][$monthName] = $price;
        }

        return $prices;
    }

    public function actionIndex()
    {
        $prices = $this->createAssocArray();
        $this->month = mb_strtolower($this->month);
        $price = $prices[$this->type][$this->tonnage][$this->month] ?? null;
        if ($this->validate() === false){
            return ExitCode::DATAERR;
        }

        if ($this->validatePrice($prices) === false){
            return ExitCode::DATAERR;
        }

        $this->drawTable($prices);

        $this->stdout("введённые параметры:\n");
        $this->stdout("месяц — {$this->month}\n");
        $this->stdout("тип — {$this->type}\n");
        $this->stdout("тоннаж — {$this->tonnage}\n");
        $this->stdout("результат — {$price}");
        }
}