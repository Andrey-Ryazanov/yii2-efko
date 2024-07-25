<?php

namespace app\controllers;

use app\models\CalculationsRepository;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\CalculationForm;
use app\models\CalculationHistorySearch;
use app\models\CalculationHistory;
use app\models\CalculationSnapshot;
use app\models\PricesRepository;
use yii\bootstrap5\ActiveForm;

class CalculatorController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'validate', 'submit'],
                        'roles' => ['?', 'выполнить расчет'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['save'],
                        'roles' => ['записать результат расчета в историю'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['history', 'snapshot'],
                        'roles' => ['просмотреть расчёты'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['destroy'],
                        'roles' => ['удалить расчёт'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex()
    {
        $model = new CalculationForm();
        $repository = new PricesRepository();
        if ($model->load(Yii::$app->request->post()))
        {
            $model->saveToQueue();
        }
        return $this->render('index',[
            'model' => $model,
            'repository' => $repository,
        ]);
    }

    public function actionValidate()
    {
        $model = new CalculationForm();
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post()) && $model->validate()) 
        {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
    }

    public function actionSubmit()
    {
        $model = new CalculationForm();
        $repository = new PricesRepository();
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) 
        {
            if ($model->validate()) 
            {
                $response = [
                    'model' => $model,
                    'repository' => $repository,
                ];
    
                return $this->renderPartial('/ajax/result-calc-table', $response);
            }
        }
        
        return $this->redirect(['index']);
    }

    public function actionSave(int $month_id, int $raw_type_id, int $tonnage_id)
    {
        $model = new CalculationForm();
        if ($model->saveCalculationHistory($month_id, $raw_type_id, $tonnage_id)) 
        {
            Yii::$app->session->setFlash('success', 'Расчёт успешно сохранен');
            return $this->redirect(['index']);
        }

        Yii::$app->session->setFlash('error', 'Не удалось сохранить расчёт');
        return $this->redirect(['index']);      
    }

    public function actionHistory()
    {
        $searchModel = new CalculationHistorySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
    
        return $this->render('history',[
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    public function actionSnapshot(int $id)
    {
        $model = CalculationHistory::findOne($id);
        $repository = new CalculationsRepository();

        return $this->render('snapshot',[
            'model' => $model,
            'repository' => $repository,
        ]);
    }

    public function actionDestroy(int $id)
    {
        $calculationHistory = CalculationHistory::findOne($id);
        if ($calculationHistory) 
        {
            $calculationHistory->deleteWithSnapshots();
            Yii::$app->session->setFlash('success', 'Расчёт успешно удален');
        } 
        else 
        {
            Yii::$app->session->setFlash('error', 'Ошибка при удалении расчёта');
        }
        return $this->redirect(['history']);
    }

}
