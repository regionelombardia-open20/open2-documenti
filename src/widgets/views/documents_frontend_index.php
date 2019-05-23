<?php

use lispa\amos\core\helpers\Html;
use lispa\amos\documenti\AmosDocumenti;

?>
<?php
/** @var $model \lispa\amos\documenti\models\Documenti
 *  @var $widget \lispa\amos\documenti\widgets\DocumentsFrontendWidget
 */
?>
<?php
$showPageSummary = $widget->showPageSummary;
echo \lispa\amos\core\views\AmosGridView::widget([
    'dataProvider' => $dataProvider,
    'showPageSummary' => $showPageSummary,
    'columns' => [
        [
            'label' => '',
            'value' => function($model) use ($view_item){
                return $this->render($view_item, ['model' => $model]);
            },
            'format' => 'raw',
        ]
    ]
])
?>
