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
use open20\amos\documenti\controllers\DocumentiController;

/**
 * @var yii\web\View $this
 * @var open20\amos\documenti\models\DocumentiAcl $model
 */

$this->title = AmosDocumenti::t('amosdocumenti', '#associa_m2m_share_with_groups_page_title', ['folderName' => $model->titolo]);
$this->params['breadcrumbs'][] = $this->title;

/** @var AmosAdmin $adminModule */
$documentsModule = AmosDocumenti::instance();

/** @var DocumentiController $appController */
$appController = Yii::$app->controller;

$modelData = $model->getFolderGroups();

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
        'class' => $documentsModule->model('DocumentiAclGroups'),
        'query' => $appController->getAssociaM2mGroupsQuery($model),
    ],
    'itemsTargetPagination' => false,
    'gridId' => 'folder-groups-grid',
    'viewSearch' => true,
    'targetUrlController' => 'documenti',
    'moduleClassName' => AmosDocumenti::className(),
    'postName' => 'DocumentiAclGroups',
    'postKey' => 'documentiaclgroups',
    'targetColumnsToView' => [
        'name',
    ],
]);
