<?php
/**
 * @var $model \open20\amos\documenti\models\Documenti
 */
?>
<?php if (!empty($model)) { ?>
    <h3><?= $model->getFileTypeIcon().$model->titolo ?></h3>
<?php } ?>
<?= \open20\amos\core\views\AmosGridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        [
            'attribute' => 'model_attribute',
            'label' => "Campo"
        ],
        [
            'attribute' => 'old_value',
            'label' => "Valore iniziale"
        ],
        [
            'attribute' => 'new_value',
            'label' => "Valore finale"
        ]
    ]
]) ?>
