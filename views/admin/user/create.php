<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'Создание пользователя';
?>

<h1><?= Html::encode($this->title) ?></h1>

<div class="user-form">
    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin([
                  'id' => 'form', 
                  'method' => 'post',
                   'action' => ['store'],
                ]); ?>

            <?= $form->field($model, 'username')->textInput(['autofocus' => true])  ?>

            <?= $form->field($model, 'email') ?>

            <?= $form->field($model, 'password')->passwordInput() ?>

            <?= $form->field($model, 'confirmpassword')->passwordInput() ?>

            <?= $form->field($model, 'role')->dropDownList($roles, ['prompt' => 'Не выбрано']) ?>


            <div class="form-group">
                <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>