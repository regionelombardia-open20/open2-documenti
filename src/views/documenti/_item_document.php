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
use open20\amos\core\forms\PublishedByWidget;
use open20\amos\core\helpers\Html;
use open20\amos\documenti\AmosDocumenti;
use open20\amos\documenti\assets\ModuleSpinnerAsset;
use open20\amos\notificationmanager\forms\NewsWidget;
use open20\amos\documenti\models\Documenti;

/**
 * @var yii\web\View $this
 * @var open20\amos\documenti\models\Documenti $model
 */

/** @var AmosDocumenti $documentsModule */
$documentsModule = AmosDocumenti::instance();
$documentMainFile = $model->getDocumentMainFile();
$modelViewUrl = $model->getFullViewUrl();
$document = $model->getDocumentMainFile();
$documentPresent = ($document != null);
$documentLinkPresent = (!empty($model->link_document));
$visible = isset($statsToolbar) ? $statsToolbar : false;
$enableContentDuplication = $documentsModule->enableContentDuplication;
$enableCatImgInDocView = $documentsModule->enableCatImgInDocView;
$documentCategory = $model->documentiCategorie;
$isFolder = $model->is_folder;
$enableCategories = $documentsModule->enableCategories;
$modelTitleSpecialChars = htmlspecialchars($model->titolo);
$isTeamsDocument = !empty($model->msgraph_drive_item_id) ? true : false;
$additionalButtons = [];
$moduleCwh = \Yii::$app->getModule('cwh');
isset($moduleCwh) ? $showReceiverSection = true : null;
isset($moduleCwh) ? $scope = $moduleCwh->getCwhScope() : null;

$controller = Yii::$app->controller;
$moduleId = $controller->module->id;
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

$onlyofficeModule = AmosDocumenti::instance()->getModuleOnlyOffice();
if ($onlyofficeModule) {
    $iconoo = $onlyofficeModule->isValidExtension($document->type) ? ($model->type == Documenti::MAIN_DOCUMENT_TYPE_ONLYOFFICE ? true : false) : false;
}
$assetBundle = ModuleSpinnerAsset::register($this);
?>

<div class="document-item-container d-flex border-bottom py-4 w-100">
    <div class="info-doc">
        <div>
            <?php if ($iconoo) { ?>
                <img class="only-office-image" src="<?= $assetBundle->baseUrl ?>/img/ONLYOFFICE_logo.png"
                     title="Only Office" alt="Only Office">
            <?php } ?>

            <?= \open20\amos\documenti\utility\DocumentsUtility::getDocumentIcon($model); ?>
            <span class="text-secondary small"><?= is_null($model->getDocumentMainFile() && empty($model->link_document)) ? AmosDocumenti::tHtml('amosdocumenti', 'Documento testuale') : $docExtension = strtoupper($document->type); ?>
                <?php if ($documentPresent): ?>

                    (<?= round($model->documentMainFile->size / 1024, 0) . ' Kb' ?>) - <?= AmosDocumenti::tHtml('amosdocumenti', 'Nome file principale:') ?>
                <?php endif; ?>
             </span>

            <?php
            if ($documentPresent) {
                echo Html::tag('span', ((strlen($documentMainFile->name) > 80) ? substr($documentMainFile->name, 0, 75) . '[...]' : $documentMainFile->name) . '.' . $documentMainFile->type, ['class' => 'text-secondary small']);
            } else {
                if ($documentLinkPresent) {
                    echo Html::tag('span', (AmosDocumenti::tHtml('amosdocumenti', 'File esterno')), ['class' => 'text-secondary small']);
                } else {
                    echo Html::tag('span', (AmosDocumenti::tHtml('amosdocumenti', 'Documento testuale')), ['class' => 'text-secondary small']);
                }
            }
            ?>

        </div>
        <div class="flexbox align-item-center">
            <?php if ($documentLinkPresent) : ?>
                <?= Html::a(
                    $model->titolo,
                    $model->link_document,
                    [
                        'class' => 'link-list-title h5 m-b-0',
                        'target' => 'blank',
                        'title' => AmosDocumenti::t('amosdocumenti', 'Apri il link esterno al documento') . ' ' . $modelTitleSpecialChars
                    ]
                )
                ?>
            <?php else : ?>
                <?= Html::a(
                    $model->titolo,
                    $modelViewUrl,
                    [
                        'class' => 'link-list-title h5  m-b-0',
                        'title' => AmosDocumenti::t('amosdocumenti', 'Apri la scheda del documento') . ' ' . $modelTitleSpecialChars
                    ]
                )
                ?>
            <?php endif; ?>
            <?php if (!empty($contentScopesAvatar)) : ?>
                <a href="javascript:void(0)" data-toggle="tooltip" title="<?= $contentScopesAvatar ?>">
                    <span class="icon icon-sm icon-secondary mdi mdi-account-supervisor-circle" role="img"
                          aria-label="Documento in community"></span>
                    <span class="sr-only"><?= $contentScopesAvatar ?></span>
                </a>
            <?php endif; ?>

        </div>

        <?php if ($model->status != \open20\amos\documenti\models\Documenti::DOCUMENTI_WORKFLOW_STATUS_VALIDATO) { ?>
            <i style='color:#777777'><?= "(" . AmosDocumenti::t('amosdocumenti', $model->status) . ")"; ?></i>
        <?php } ?>

        <?php if ($model->descrizione_breve) { ?>
            <p class="mb-0 m-t-5 text-secondary">
                <?= htmlspecialchars($model->descrizione_breve) ?>
            </p>
        <?php } ?>
        <div class="small mb-0 m-t-10">
            <?= PublishedByWidget::widget([
                'model' => $model,
                'layout' => (isset(\Yii::$app->params['hideListsContentCreatorName']) && (\Yii::$app->params['hideListsContentCreatorName'] === true) ? '' : '{publisher}<div class="item"><label>il&nbsp</label><span>' . $date . ' </span></div>') . ((!$isFolder && $enableCategories) ? '<div class="item"><label>Categoria:</label>' . '<span class="nome-categoria">' . ' ' . $documentCategory->titolo . '</span></div>' : '')
            ]) ?>

        </div>
        <div class="small mb-2">
            <?php
            if (!empty($model->parent_id)) {
                $stringa = \open20\amos\documenti\models\DocumentiCartellePath::getPath($model);
                echo AmosDocumenti::t(
                        'amosdocumenti',
                        '<strong>Percorso:</strong> '
                    ) . $stringa . $model->titolo;
            }
            ?>
        </div>
        <div>
            <?php
            if ($documentPresent) {
                echo Html::a(
                    AmosDocumenti::t('amosdocumenti', 'Scarica'),
                    $document->getUrl(),
                    [
                        'title' => AmosDocumenti::t('amosdocumenti', 'Scarica il documento') . ' ' . $modelTitleSpecialChars,
                        'class' => 'm-r-10 small uppercase bold',
                    ]
                );
            }

            ?>


            <?= Html::a(
                AmosDocumenti::t('amosdocumenti', '#detail'),
                $modelViewUrl,
                [
                    'class' => 'small uppercase bold',
                    'title' => AmosDocumenti::t('amosdocumenti', '#see_document_detail') . ' ' . $modelTitleSpecialChars
                ]
            )
            ?>

        </div>
    </div>
    <div class="ml-auto doc-actions d-flex">
        <div>
            <?= NewsWidget::widget(['model' => $model]); ?>
        </div>
        <div class="" style="align-self: flex-end">
            <?= $model->getArchiving(true) ?>
        </div>
        <div>
            <?php if (!$isTeamsDocument) { ?>
                <?= ContextMenuWidget::widget([
                    'model' => $model,
                    'actionModify' => "/documenti/documenti/update?id=" . $model->id,
                    'actionDelete' => "/documenti/documenti/delete?id=" . $model->id,
                    'modelValidatePermission' => 'DocumentValidate',
                    'additionalButtons' => $additionalButtons,
                    'mainDivClasses' => 'manage-documents'
                ]) ?>
            <?php } ?>

        </div>

    </div>
</div>
