<?php

use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
?>
<?php $this->title = 'Калькулятор расчёта стоимости доставки сырья'; ?>

<?php if($model->checkAuth()): ?>
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    Здравствуйте, <?= Yii::$app->user->identity->username ?>, вы авторизовались в системе расчета стоимости доставки. 
    Теперь все ваши расчеты будут сохранены для последующего просмотра в <a href="<?= Url::to(['calculator/history']) ?>">журнале расчетов</a>. 
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
<?php endif; ?>

<h1><?= Html::encode($this->title) ?></h1>
<div class="">
  <?php $form = ActiveForm::begin([
    'id' => 'form', 
    'method' => 'post',
    'action' => Url::to(['calculator/submit']), 
    'enableAjaxValidation' => true, 
    'validationUrl' => Url::to(['calculator/validate']), 
    ]);?>
    <fieldset class="form-control" id="disabledInput" type="text" placeholder="...">
      <legend>Калькулятор расчёта стоимости доставки</legend>
      <div>
        <div class="mb-3">
        <?= $form->field($model, 'month_id')->dropDownList($repository->getMonth(),
        ['prompt' => 'Не выбрано'])?>
        </div>
        <div class="mb-3">
          <?= $form->field($model, 'type_id')->dropDownList($repository->getType(),
          ['prompt' => 'Не выбрано']) ?>
        </div>
        <div class="mb-3">
          <?= $form->field($model, 'tonnage_id')->dropDownList($repository->getTonnage(),
          ['prompt' => 'Не выбрано']) ?>
        </div>
      </div>
      <div class="form-group"> 
        <?= Html::Button('Рассчитать', ['class' => 'btn btn-success', 'name' => 'calc-button', 'id' =>'calc-button']) ?>
      </div>
    </fieldset>
    <?php ActiveForm::end() ?>
</div>

<div data-widget = "result-table" class = "mt-3"></div>

<?php $this->registerJsFile('@web/js/calculator.js'); ?>
