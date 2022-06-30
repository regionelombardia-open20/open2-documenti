<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti\views\documenti-acl-groups
 * @category   CategoryName
 */

use open20\amos\documenti\widgets\DocumentsAclGroupUsersWidget;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var open20\amos\documenti\models\DocumentiAclGroups $model
 * @var bool $isUpdate
 */

$widgetConf = [
    'model' => $model,
    'isUpdate' => (isset($isUpdate) ? $isUpdate : false)
];
?>
<?= DocumentsAclGroupUsersWidget::widget($widgetConf); ?>
