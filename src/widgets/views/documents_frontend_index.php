<?php

use lispa\amos\core\helpers\Html;
use lispa\amos\documenti\AmosDocumenti;

?>
<?php /** @var $model \lispa\amos\documenti\models\Documenti */ ?>
<?php
echo \lispa\amos\core\views\AmosGridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        [
            'label' => '',
            'value' => function($model) use ($view_item){
                return $this->render($view_item, ['model' => $model]);
            },
            'format' => 'raw'
        ]
    ]
])
?>
