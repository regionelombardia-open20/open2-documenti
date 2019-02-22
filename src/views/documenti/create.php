<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\documenti\views\documenti
 * @category   CategoryName
 */

use lispa\amos\documenti\AmosDocumenti;

/**
 * @var yii\web\View $this
 * @var lispa\amos\documenti\models\Documenti $model
 */

/** @var \lispa\amos\documenti\controllers\DocumentiController $controller */
$controller = Yii::$app->controller;
$controller->setNetworkDashboardBreadcrumb();
$isFolder = $controller->documentIsFolder($model);
if ($isFolder) {
    $this->title = AmosDocumenti::t('amosdocumenti', '#create_folder_title');
} else {
    $this->title = AmosDocumenti::t('amosdocumenti', 'Inserisci documento');
}
$this->params['breadcrumbs'][] = ['label' => AmosDocumenti::t('amosdocumenti', Yii::$app->session->get('previousTitle')), 'url' => Yii::$app->session->get('previousUrl')];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="documenti-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>