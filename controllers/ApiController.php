<?php
namespace app\controllers;

use Yii;
use yii\rest\Controller;
use app\models\PricesRepository;
use yii\helpers\Json;
use yii\web\Response;

class ApiController extends Controller
{
    public function actionCalculatePrice()
    {
        $rawType = Yii::$app->request->get('raw_type');
        $month = Yii::$app->request->get('month');
        $tonnage = Yii::$app->request->get('tonnage');
        $repository = new PricesRepository();

        $response = [
            'price' => $repository->getApiResultPrice($rawType, $tonnage, $month),
            'price_list' =>  $repository->getApiPriceList($rawType),
        ];
        $options = JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE;
        $json = Json::encode($response, $options);
        
        Yii::$app->response->format = Response::FORMAT_RAW;
        Yii::$app->response->headers->add('Content-Type', 'application/json; charset=UTF-8');
        
        return $json;
    }
}