<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\documenti\widgets\graphics\views\hierarchical-documents
 * @category   CategoryName
 */

use lispa\amos\core\views\DataProviderView;

/**
 * @var yii\web\View $this
 * @var lispa\amos\documenti\widgets\graphics\WidgetGraphicsHierarchicalDocuments $widget
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var string $currentView
 */

?>

<?= DataProviderView::widget([
    'dataProvider' => $dataProvider,
    'currentView' => $currentView,
    'gridView' => [
        'columns' => $widget->getGridViewColumns()
    ],
    'iconView' => [
        'itemView' => '_icon',
        'itemOptions' => [
            'class' => 'col-xs-12 col-sm-4 col-md-2',
            'aria-selected' => 'false',
            'role' => 'option'
        ]
    ]
]); ?>
