<?php

use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
?>
<?php $this->title = 'Калькулятор расчёта стоимости доставки сырья'; ?>

<h1><?= Html::encode($this->title) ?></h1>
<?php $form = ActiveForm::begin(); ?>
    <fieldset class="form-control" id="disabledInput" type="text" placeholder="...">
        <legend>Калькулятор расчёта стоимости доставки</legend>
        <div>
            <div class="mb-3">
                <?= $form->field($model, 'month_name') ?>
            </div>
            <div class="mb-3">
                <?= $form->field($model, 'raw_type_name') ?>
            </div>
            <div class="mb-3">
                <?= $form->field($model, 'tonnage_value') ?>
            </div>
        </div>
    </fieldset>
<?php ActiveForm::end(); ?>

<div data-widget = "result-table" class = "mt-3">
    <div class="alert alert-success">Стоимость доставки составила: <?= $repository->getHistoryCalculation($model->id); ?> руб.</div>
    <div>
        <table class='table'>
            <thead>
                <tr>
                    <th>м/т</th>
                    <?php foreach ($repository->getMonthByTypeAndTonnage($model->id, $model->raw_type_name, $model->tonnage_value) as $month): ?>
                        <th><?= $month['month_name'] ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($repository->getTonnageByRawType($model->id, $model->raw_type_name) as $tonnage): ?>
                    <tr>
                        <td><?= $tonnage['tonnage_value']; ?></td>
                        <?php foreach ($repository->getPriceByRawTypeAndTonnage($model->id, $model->raw_type_name, $tonnage['tonnage_value']) as $price): ?>
                            <td>
                                <?= $price['price']; ?>
                            </td> 
                        <?php endforeach; ?>
                    </tr> 
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

