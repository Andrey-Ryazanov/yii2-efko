<?php

namespace app\controllers\admin;

use app\models\CalculationHistory;
use app\models\CalculationSnapshot;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use app\models\User;
use app\models\UserForm;
use yii\helpers\ArrayHelper;

class UserController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['index', 'create','store','edit','update', 'destroy'],
                        'allow' => true,
                        'roles' => ['administrator'],
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
        $dataProvider = new ActiveDataProvider([
            'query' => User::find(),
        ]);
    
        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate()
    {
        $model = new UserForm(['scenario'=>'create']);
        $roles = ArrayHelper::map(Yii::$app->authManager->getRoles(), 'name', 'name');
        return $this->render('create', [
            'model' => $model,
            'roles' => $roles,
        ]);
    }

    public function actionStore()
    {
        $model = new UserForm(['scenario'=>'create']);
        
        if ($model->load(Yii::$app->request->post()) && $model->validate()) 
        {
            if ($model->createUser())
            {
                Yii::$app->session->setFlash('success', 'Пользователь успешно создан');
                return $this->redirect(['index']);
            }
        }

        Yii::$app->session->setFlash('error', $model->printErrors());
        return $this->redirect(['create']);
    }

    public function actionEdit(int $id)
    {
        $user = User::findOne($id);
        if (!$user) 
        {
            Yii::$app->session->setFlash('error', 'Пользователь не найден');
            return $this->redirect(['index']);
        }
        
        $model = new UserForm(['scenario'=>'update']);
        $model->loadUser($user);
        
        $roles = ArrayHelper::map(Yii::$app->authManager->getRoles(), 'name', 'name');
        
        return $this->render('edit', [
            'model' => $model,
            'roles' => $roles,
            'user' => $user,
        ]);
    }

    public function actionUpdate(int $id)
    {
        $user = User::findOne($id);

        if (!$user) 
        {
            Yii::$app->session->setFlash('error', 'Пользователь не найден');
            return $this->redirect(['index']);
        }
        
        $model = new UserForm(['scenario'=>'update']);      
        if ($model->load(Yii::$app->request->post()) && $model->validate()) 
        {
            if ($model->updateUser($user))
            {
                Yii::$app->session->setFlash('success', 'Пользователь успешно обновлен');
                return $this->redirect(['index']);
            }
        }
        
        Yii::$app->session->setFlash('error', $model->printErrors());
        return $this->redirect(['edit', 'id' => $id]);
    }

    public function actionDestroy(int $id)
    {
        $user = User::findOne($id);
        if ($user) 
        {
            $user->deleteWithRelatedData();
            Yii::$app->session->setFlash('success', 'Пользователь успешно удален');
        } 
        else 
        {
            Yii::$app->session->setFlash('error', 'Ошибка при удалении пользователя');
        }
        return $this->redirect(['index']);
    }
}