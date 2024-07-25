<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

/**
 * @var yii\web\View    $this
 */

$this->title = 'Регистрация';
?>
<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Для регистрации заполните следующие поля:</p>
    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin([
                'id' => 'registration-form',
                'fieldConfig' => [
                    'template' => "{label}\n{input}\n{error}",
                    'labelOptions' => ['class' => 'col-lg-1 col-form-label mr-lg-3'],
                    'inputOptions' => ['class' => 'col-lg-3 form-control'],
                    'errorOptions' => ['class' => 'col-lg-7 invalid-feedback'],
                ],
            ]); ?>

            <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

            <?= $form->field($model, 'email')?>

            <?= $form->field($model, 'password')->passwordInput() ?>

            <?= $form->field($model, 'confirmpassword')->passwordInput()?>

            <div class="form-group">
                <div>
                    <?= Html::submitButton( 'Зарегистрироваться', ['class' => 'btn btn-success btn-block']) ?>
                </div>
            </div>

            <?php ActiveForm::end(); ?>
            
            <?= 'Уже зарегистрированы? ' . Html::a('Войдите!', ['/login']) ?>
        </div>
    </div>
</div>
