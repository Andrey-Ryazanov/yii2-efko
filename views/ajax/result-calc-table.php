   <?php if (empty($model->type_id) === false && empty($model->tonnage_id) === false && empty($model->month_id) === false): ?>
        <div class="alert alert-success">Стоимость доставки составила: <?= $repository->getResultPrice($model->type_id, $model->tonnage_id, $model->month_id); ?> руб.</div>
        <div>
            <table class='table'>
                <thead>
                    <tr>
                        <th>м/т</th>
                        <?php foreach ($repository->getMonthByTypeAndTonnage($model->type_id, $model->tonnage_id) as $month):?>
                            <th><?= $month['month_name'] ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($repository->getTonnageByRawType($model->type_id) as $tonnage):?>
                    <tr>
                        <td><?= $tonnage['tonnage_value']; ?></td>
                        <?php foreach ($repository->getPriceByRawTypeAndTonnage($model->type_id, $tonnage['tonnage_id']) as $price): ?>
                            <td>
                              <?= $price['price']; ?>
                            </td> 
                        <?php endforeach; ?>
                    </tr> 
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
      <?php endif; ?>

    <?php if (Yii::$app->user->identity): ?>
          <?= \yii\helpers\Html::a('Сохранить', ['save', 'month_id' => $model->month_id,'raw_type_id' => $model->type_id, 'tonnage_id' => $model->tonnage_id], ['class' => 'btn btn-primary', 'name' => 'save-button', 'id' => 'save-button']) ?>
    <?php endif; ?>