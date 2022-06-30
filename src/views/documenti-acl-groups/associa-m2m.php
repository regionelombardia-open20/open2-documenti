<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti\views\documenti-acl-groups
 * @category   CategoryName
 */

use open20\amos\admin\AmosAdmin;
use open20\amos\core\forms\editors\m2mWidget\M2MWidget;
use open20\amos\documenti\AmosDocumenti;
use open20\amos\documenti\controllers\DocumentiAclGroupsController;

/**
 * @var yii\web\View $this
 * @var open20\amos\documenti\models\DocumentiAclGroups $model
 */

$this->title = AmosDocumenti::t('amosdocumenti', '#associa_m2m_groups_page_title', ['groupName' => $model->name]);
$this->params['breadcrumbs'][] = $this->title;

/** @var AmosAdmin $adminModule */
$adminModule = AmosAdmin::instance();

/** @var DocumentiAclGroupsController $appController */
$appController = Yii::$app->controller;

$modelData = $model->getGroupUsers();

?>

<?= M2MWidget::widget([
    'model' => $model,
    'modelId' => $model->id,
    'modelData' => $modelData,
    'modelDataArrFromTo' => [
        'from' => 'id',
        'to' => 'id'
    ],
    'modelTargetSearch' => [
        'class' => $adminModule->model('User'),
        'query' => $appController->getAssociaM2mQuery($model),
    ],
    'gridId' => 'group-users-grid',
    'viewSearch' => true,
    'targetUrlController' => 'documenti-acl-groups',
    'moduleClassName' => AmosDocumenti::className(),
    'postName' => 'DocumentiAclGroups',
    'postKey' => 'documentiaclgroups',
    'targetColumnsToView' => [
        'profile.surnameName',
    ],
]);
