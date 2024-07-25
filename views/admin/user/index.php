<?php

use yii\helpers\Html;
use yii\grid\GridView;

/*
 * @var yii\web\View $this
 */

/*
 * @var yii\data\ActiveDataProvider $dataProvider
 */

$this->title = 'Управление пользователями';
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Добавить пользователя', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'summary' => 'Показано {begin}-{end} из {totalCount} записей.',
        'columns' => [
            'id',
            'username',
            'email:email',
            [
                'attribute' => 'created_at',
                'format' => 'datetime',
            ],
            [
                'attribute' => 'updated_at',
                'format' => 'datetime',
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{edit} {destroy}',
                'buttons' => [
                    'edit' => function ($url, $model, $key) {
                        return Html::a('<i class="bi bi-pencil-square"></i>', ['edit', 'id' => $model->id]);
                    },
                    'destroy' => function ($url, $model, $key) {
                        return Html::a('<i class="bi bi-trash-fill"></i>', ['destroy', 'id' => $model->id]);
                    },
                ],
            ],
        ],
    ]);
    ?>

</div>