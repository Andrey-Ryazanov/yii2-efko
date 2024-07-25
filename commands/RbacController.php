<?php
namespace app\commands;

use Yii;
use yii\console\Controller;

class RbacController extends Controller
{
    public function actionInit()
    {
        $auth = Yii::$app->authManager;

        $executeCalculation = $auth->createPermission('выполнить расчет');
        $executeCalculation->description = 'Выполнить расчет';
        $auth->add($executeCalculation);

        $recordingCalculateToHistory = $auth->createPermission('записать результат расчета в историю');
        $recordingCalculateToHistory->description = 'Записать результат расчета в историю';
        $auth->add($recordingCalculateToHistory);

        $viewCalculationHistory = $auth->createPermission('просмотреть расчеты');
        $viewCalculationHistory->description = 'Просмотреть расчеты';
        $auth->add($viewCalculationHistory);

        $deleteCalculationHistory = $auth->createPermission('удалить расчет');
        $deleteCalculationHistory->description = 'Удалить расчет';
        $auth->add($deleteCalculationHistory);

        $viewUsers = $auth->createPermission('просмотреть всех пользователей');
        $viewUsers->description = 'Просмотреть всех пользователей';
        $auth->add($viewUsers);

        $createUser = $auth->createPermission('создать пользователя');
        $createUser->description = 'Создать пользователя';
        $auth->add($createUser);

        $editUser = $auth->createPermission('отредактировать пользователя');
        $editUser->description = 'Отредактировать пользователя';
        $auth->add($editUser);

        $deleteUser = $auth->createPermission('удалить пользователя');
        $deleteUser->description = 'Удалить пользователя';
        $auth->add($deleteUser);

        $viewUsersCalculations = $auth->createPermission('просмотреть расчёты всех пользователей');
        $viewUsersCalculations->description = 'Просмотреть расчёты всех пользователей';
        $auth->add($viewUsersCalculations);

        $user = $auth->createRole('user');
        $auth->add($user);
        $auth->addChild($user, $executeCalculation);
        $auth->addChild($user, $viewCalculationHistory);
        $auth->addChild($user, $recordingCalculateToHistory);

        $administrator = $auth->createRole('administrator');
        $auth->add($administrator);
        $auth->addChild($administrator, $executeCalculation);
        $auth->addChild($administrator, $viewCalculationHistory);
        $auth->addChild($administrator, $deleteCalculationHistory);
        $auth->addChild($administrator, $viewUsers);
        $auth->addChild($administrator, $createUser);
        $auth->addChild($administrator, $editUser);
        $auth->addChild($administrator, $deleteUser);
        $auth->addChild($administrator, $viewUsersCalculations);
        $auth->addChild($administrator, $recordingCalculateToHistory);

        $auth->assign($user, 2);
        $auth->assign($administrator, 1);
    }
}