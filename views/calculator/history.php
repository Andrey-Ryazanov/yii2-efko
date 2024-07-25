<?php

use yii\helpers\Html;
use yii\grid\GridView;

/*
 * @var yii\web\View $this
 */

/*
 * @var yii\data\ActiveDataProvider $dataProvider
 */
if (Yii::$app->user->can('administrator')):
    $this->title = 'История расчетов пользователей';
else :
    $this->title = 'Мои расчеты';
endif;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'summary' => 'Показано {begin}-{end} из {totalCount} записей.',
        'columns' => [
            'id',
            [
                'attribute' => 'user.username',
                'label' => 'Имя пользователя',
                'visible' => Yii::$app->user->can('administrator'),
            ],
            'raw_type_name',
            [
                'attribute' => 'tonnage_value',
                'filter' => Html::activeTextInput($searchModel, 'tonnage_value', ['class' => 'form-control']),
            ],
            [
                'attribute' => 'month_name',
                'filter' => Html::activeTextInput($searchModel, 'month_name', ['class' => 'form-control']),
            ],
            'price',
            [
                'attribute' => 'created_at',
                'format' => 'datetime',
                'filter' => Html::activeTextInput($searchModel, 'created_at', ['class' => 'form-control', 'type' => 'date']),
                'value' => function($model) {
                    return $model->created_at;
                },
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{snapshot} {destroy}',
                'buttons' => [
                    'snapshot' => function ($url, $model, $key) {
                        return Html::a('<i class="bi bi-info-circle-fill"></i>', ['snapshot', 'id' => $model->id]);
                    },
                    'destroy' => function ($url, $model, $key) {
                        return Html::a('<i class="bi bi-trash-fill"></i>', ['destroy', 'id' => $model->id]);
                    },
                ],
            ],
        ],
    ]); ?>

</div>