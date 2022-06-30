<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti\views\documenti-acl-groups
 * @category   CategoryName
 */

use open20\amos\core\views\DataProviderView;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var open20\amos\documenti\models\search\DocumentiAclGroupsSearch $model
 */

?>
<div class="documenti-acl-groups-index">
    <?= $this->render('_search', ['model' => $model]); ?>
    <?= DataProviderView::widget([
        'dataProvider' => $dataProvider,
        'currentView' => $currentView,
        'gridView' => [
            'columns' => [
                'name',
                // 'update_folder_content:boolean',
                // 'upload_folder_files:boolean',
                // 'read_folder_files:boolean',
                [
                    'class' => 'open20\amos\core\views\grid\ActionColumn',
                ]
            ]
        ]
    ]); ?>
</div>
