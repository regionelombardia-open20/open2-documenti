<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open20\amos\documenti\views\documenti
 * @author    Aria S.p.A. <protocollo@pec.ariaspa.it>
 * @license   GNU General Public License v3.0 <https://www.gnu.org/licenses/gpl-3.0.html>
 * @link      https://www.ariaspa.it/
 *
 * This file is part of OPEN 2.0.
 *
 * OPEN 2.0 is free software: you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * OPEN 2.0 is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with OPEN 2.0. If not, see <https://www.gnu.org/licenses/>.
 */

use open20\amos\core\forms\ContextMenuWidget;
use open20\amos\core\helpers\Html;
use open20\amos\core\forms\PublishedByWidget;
use open20\amos\documenti\AmosDocumenti;
use open20\amos\documenti\models\DocumentiCartellePath;
use open20\amos\notificationmanager\forms\NewsWidget;

/**
 * @var yii\web\View $this
 * @var open20\amos\documenti\models\Documenti $model
 */
$documentsModule = AmosDocumenti::instance();
$moduleId = \Yii::$app->controller->id;
$moduleCwh = \Yii::$app->getModule('cwh');
isset($moduleCwh) ? $showReceiverSection = true : null;
isset($moduleCwh) ? $scope = $moduleCwh->getCwhScope() : null;
$queryParams = \Yii::$app->request->getQueryParams();
$isTeamsDocument = !empty($model->msgraph_drive_item_id) ? true : false;


if ($moduleId == 'documenti') {
    $modelViewUrl = \Yii::$app->urlManager->createAbsoluteUrl([
        '/documenti/documenti/' . \Yii::$app->controller->action->id,
        'parentId' => $model->id,
        'DocumentiSearch' => $queryParams['DocumentiSearch'],
    ]);
} else {
    $modelViewUrl = \Yii::$app->urlManager->createAbsoluteUrl([
        '/documenti/documenti/all-documents',
        'parentId' => $model->id,
        'DocumentiSearch' => $queryParams['DocumentiSearch'],
    ]);
}
$stringa = DocumentiCartellePath::getPath($model);


$additionalButtons = [];

if ($documentsModule->enableMoveDoc && $scope['community'] && $documentsModule::getModuleName() == $moduleId && Yii::$app->getUser()->can('DocumentValidate', ['model' => $model])) {
    $additionalButtons[] = \yii\helpers\Html::a(AmosDocumenti::t('amoscommunity', "Sposta"), '#modalMove', [
        'class' => 'open-modalMove',
        'data-toggle' => 'modal',
        'data-id' => $model->id,
    ]);
}

$date = \Yii::$app->formatter->asDate($model->created_at, 'php:d mm yy');
$contentScopesAvatar = \open20\amos\core\utilities\CwhUtility::getTargetsString($model);


$jsCount = <<<JS
    $('.link-document-id').click(function() {
        var idDoc = $(this).attr('data-key');
        $.ajax({
           url: 'increment-count-download-link?id='+idDoc,
           type: 'get',
           success: function (data) {
           }

      });
    })
JS;

$this->registerJs($jsCount);

?>

<div class="document-item-container item-folder flexbox-column border-bottom py-4 w-100">

    <div class="flexbox align-item-center">
        <?php
        if ($model->drive_file_id) { ?>
            <span class="icon icon-folder icon-sm mdi mdi-folder-google-drive"></span>
            <?php
        } else { ?>
            <span class="icon icon-folder icon-sm mdi mdi-folder"></span>
            <?php
        }
        ?>
        <span class="small uppercase"><?= AmosDocumenti::t('amosdocumenti', 'Cartella') ?></span>

        <div class="ml-auto doc-actions d-flex">
            <div>
                <?= NewsWidget::widget(['model' => $model]); ?>
            </div>
            <div>
                <?php if (!$isTeamsDocument) { ?>
                    <?= ContextMenuWidget::widget([
                        'model' => $model,
                        'actionModify' => "/documenti/documenti/update?id=" . $model->id,
                        'actionDelete' => "/documenti/documenti/delete?id=" . $model->id,
                        'modelValidatePermission' => 'DocumentValidate',
                        'additionalButtons' => $additionalButtons,
                        'labelDeleteConfirm' => AmosDocumenti::t('amosdocumenti', '#confirm_delete_folder')
                    ]) ?>
                <?php } ?>
            </div>


        </div>
    </div>

    <?php
    $status = '';
    if ($model->status != \open20\amos\documenti\models\Documenti::DOCUMENTI_WORKFLOW_STATUS_VALIDATO) { ?>
        <?php $status = "<small><i> (" . AmosDocumenti::t('amosdocumenti', $model->status) . ")</i></small>"; ?>
    <?php } ?>
    <div class="flexbox align-item-center">

        <?= Html::a(Html::tag('p', htmlspecialchars($model->titolo) . $status, ['class' => 'h5 title-one-line']),
            $modelViewUrl,
            [
                'class' => 'link-list-title',
                'title' => AmosDocumenti::t('amosdocumenti', 'Vai alla cartella ') . $model->titolo
            ]
        ); ?>
        <?php if (!empty($contentScopesAvatar)) : ?>
            <a href="javascript:void(0)" data-toggle="tooltip" title="<?= $contentScopesAvatar ?>">
                <span class="icon icon-sm icon-secondary mdi mdi-account-supervisor-circle" role="img"
                      aria-label="Documento in community"></span>
                <span class="sr-only"><?= $contentScopesAvatar ?></span>
            </a>
        <?php endif; ?>
    </div>


    <div class="small mb-0">

        <?= PublishedByWidget::widget([
            'model' => $model,
            'layout' => (isset(\Yii::$app->params['hideListsContentCreatorName']) && (\Yii::$app->params['hideListsContentCreatorName'] === true) ? '' : '{publisher}') . '<div class="item"><label>il&nbsp</label><span>' . $date . ' </span></div>' . ((!$isFolder && $enableCategories) ? '{category}' : '')
        ]) ?>
    </div>
    <div class="small mb-2">
        <?php
        if (!empty($model->parent_id)) {
            echo AmosDocumenti::t('amosdocumenti', '<strong>Percorso:</strong> ')
                . $stringa
                . $model->titolo;
        }
        ?>
    </div>
    <div>
        <?= Html::a(
            AmosDocumenti::t('amosdocumenti', 'Apri'),
            $modelViewUrl,
            [
                'class' => 'read-more d-inline mr-2 uppercase bold small',
                'title' => AmosDocumenti::t('amosdocumenti', 'Apri la cartella ')
            ]
        ) ?>
    </div>

</div>