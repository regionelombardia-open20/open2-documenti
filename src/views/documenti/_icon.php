<?php

/**
 * @var $model \open20\amos\documenti\models\Documenti
 * @var $hideHeader bool Hide title header
 */

use open20\amos\documenti\models\Documenti;
use open20\amos\documenti\AmosDocumenti;
use open20\amos\core\forms\ContextMenuWidget;
use open20\amos\core\helpers\Html;
use open20\amos\core\forms\PublishedByWidget;
use open20\amos\documenti\widgets\icons\WidgetIconDocumentiDaValidare;

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
if($documentsModule->enableLogDocumentActivity && \Yii::$app->user->can(WidgetIconDocumentiDaValidare::class))  {
    $additionalButtons[] = \yii\helpers\Html::a(AmosDocumenti::t('amoscommunity', "Storico log"), ['/documenti/documenti/history-log','UserActivityDocLogSearch[id]' => $model->id], [
        'class' => '',
        'data-id' => $model->id,
    ]);
}

$date = \Yii::$app->formatter->asDate($model->created_at, 'php:d mm yy');
$onlyofficeModule = AmosDocumenti::instance()->getModuleOnlyOffice();
if ($onlyofficeModule) {
    $iconoo = $onlyofficeModule->isValidExtension($document->type) ? ($model->type == Documenti::MAIN_DOCUMENT_TYPE_ONLYOFFICE ? true : false) : false;
}


$isFirst = $this->params['isFirst'] ?? true;
$isCurrentFolder = $model->is_folder;
$isPreviousFolder = $this->params['previousFolder'] ?? null;
$isTeamsDocument = !empty($model->msgraph_drive_item_id) ? true : false;
$iconTeams = '';
if($isTeamsDocument){
    $iconTeams = \open20\amos\documenti\utility\DocumentsUtility::getIconMsTeams();
}

if (!$hideHeader && ($isFirst || $isCurrentFolder !== $isPreviousFolder)) {
    echo Html::tag('div', $isCurrentFolder ? AmosDocumenti::t('amoscommunity', "Cartelle") : AmosDocumenti::t('amoscommunity', "Documenti"), ['class' => 'title-header-type-doc']);
}



// Aggiorna i parametri per il prossimo elemento
$this->params['previousFolder'] = $isCurrentFolder;
$this->params['isFirst'] = false;
?>
<?php if ($isCurrentFolder) { ?>
    <div class="document-icon-wrapper folder-card ">
        <div class="info-doc">
            <div class="card-wrapper">
                <div class="card-header">
                    <?php if ($iconoo) { ?>
                        <img class="only-office-image" src="<?= $assetBundle->baseUrl ?>/img/ONLYOFFICE_logo.png"
                             title="Only Office" alt="Only Office">
                    <?php } ?>

                    <?= \open20\amos\documenti\utility\DocumentsUtility::getDocumentIcon($model); ?>

                    <?php if ($documentLinkPresent) : ?>
                        <?=
                        Html::a(
                            $model->titolo,
                            $model->link_document,
                            [
                                'class' => 'link-list-title title-one-line h6 m-b-0',
                                'target' => 'blank',
                                'title' => AmosDocumenti::t('amosdocumenti', 'Apri il link esterno al documento') . ' ' . $modelTitleSpecialChars
                            ]
                        )
                        ?>
                    <?php else : ?>
                        <?=
                        Html::a(
                            $model->titolo,
                            $modelViewUrl,
                            [
                                'class' => 'link-list-title h6 title-one-line  m-b-0',
                                'data-toggle' => 'tooltip',
                                'title' => AmosDocumenti::t('amosdocumenti', 'Apri la scheda del documento') . ' ' . $modelTitleSpecialChars
                            ]
                        )
                        ?>
                    <?php endif; ?>
                    <?= $iconTeams ?>

                    <?php if (!empty($contentScopesAvatar)) : ?>
                        <a href="javascript:void(0)" data-toggle="tooltip" title="<?= $contentScopesAvatar ?>">
                            <span class="icon icon-sm icon-secondary mdi mdi-account-supervisor-circle" role="img"
                                  aria-label="Documento in community"></span>
                            <span class="sr-only"><?= $contentScopesAvatar ?></span>
                        </a>
                    <?php endif; ?>
                    <?php if (!$isTeamsDocument) { ?>
                        <?=
                        ContextMenuWidget::widget([
                            'model' => $model,
                            'actionModify' => "/documenti/documenti/update?id=" . $model->id,
                            'actionDelete' => "/documenti/documenti/delete?id=" . $model->id,
                            'modelValidatePermission' => 'DocumentValidate',
                            'additionalButtons' => $additionalButtons,
                            'mainDivClasses' => 'manage-documents'
                        ])
                        ?>
                    <?php } ?>
                </div>

                <?php if ($model->status == Documenti::DOCUMENTI_WORKFLOW_STATUS_BOZZA) { ?>
                    <span class="chip chip-simple  chip-default"><span
                                class="chip-label"><?= AmosDocumenti::t('amosdocumenti', Documenti::DOCUMENTI_WORKFLOW_STATUS_BOZZA); ?></span></span>
                <?php }
                if ($model->status == Documenti::DOCUMENTI_WORKFLOW_STATUS_DAVALIDARE) { ?>
                    <span class="chip chip-simple  chip-warning"><span
                                class="chip-label"><?= AmosDocumenti::t('amosdocumenti', 'da validare') ?></span></span>
                <?php }
                if ($model->status == Documenti::DOCUMENTI_WORKFLOW_STATUS_VALIDATO) { ?>
                    <div class="small mb-0 m-t-10 text-secondary">
                        <?=
                        PublishedByWidget::widget([
                            'model' => $model,
                            'layout' => (isset(\Yii::$app->params['hideListsContentCreatorName']) && (\Yii::$app->params['hideListsContentCreatorName'] === true) ? '' : ' <div class="item text-secondary"><span class="text-secondary">Pubblicata il&nbsp</span><span>' . $date . ' </span></div>') . ((!$isFolder && $enableCategories && !$isTeamsDocument) ? '<div class="item text-secondary"><span>Categoria:</span>' . '<span class="nome-categoria text-secondary">' . ' ' . $documentCategory->titolo . '</span></div>' : '')
                        ])
                        ?>
                    </div>
                <?php } ?>

            </div>
        </div>
    </div>
<?php } else { ?>
    <div class="document-icon-wrapper file-card">
        <div class="info-doc">
            <div class="card-wrapper">
                <div class="card-header">
                    <?php if ($iconoo) { ?>
                        <img class="only-office-image" src="<?= $assetBundle->baseUrl ?>/img/ONLYOFFICE_logo.png"
                             title="Only Office" alt="Only Office">
                    <?php } ?>

                    <?= \open20\amos\documenti\utility\DocumentsUtility::getDocumentIcon($model); ?>

                    <span class="text-secondary small"><?= is_null($model->getDocumentMainFile() && empty($model->link_document)) ? AmosDocumenti::tHtml('amosdocumenti', 'Documento testuale') : $docExtension = strtoupper($document->type); ?>
                        <?php if ($documentPresent) : ?>

                            (<?= round($model->documentMainFile->size / 1024, 0) . ' Kb' ?>)
                            <!-- - < ?= AmosDocumenti::tHtml('amosdocumenti', 'Nome file principale:') ? > -->
                        <?php endif; ?>

                    </span>
                    <?php
                    if ($documentLinkPresent) {
                        echo Html::tag('span', (AmosDocumenti::tHtml('amosdocumenti', 'File esterno')), ['class' => 'text-secondary small']);
                    }
                    echo $iconTeams
                    ?>
                    <?php if ($model->status == Documenti::DOCUMENTI_WORKFLOW_STATUS_BOZZA) { ?>
                        <span class="chip chip-simple  chip-default"><span
                                    class="chip-label"><?= AmosDocumenti::t('amosdocumenti', $model->status); ?></span></span>
                    <?php }
                    if ($model->status == Documenti::DOCUMENTI_WORKFLOW_STATUS_DAVALIDARE) { ?>
                        <span class="chip chip-simple  chip-warning"><span
                                    class="chip-label"><?= AmosDocumenti::t('amosdocumenti', 'da validare') ?></span></span>
                    <?php } ?>
                    <?php if (!$isTeamsDocument) { ?>
                        <?=
                        ContextMenuWidget::widget([
                            'model' => $model,
                            'actionModify' => "/documenti/documenti/update?id=" . $model->id,
                            'actionDelete' => "/documenti/documenti/delete?id=" . $model->id,
                            'modelValidatePermission' => 'DocumentValidate',
                            'additionalButtons' => $additionalButtons,
                            'mainDivClasses' => 'manage-documents'
                        ])
                        ?>
                    <?php } ?>
                </div>
                <div class="flexbox align-item-center">
                    <?php if ($documentLinkPresent) : ?>
                        <?=
                        Html::a(
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
                        <?=
                        Html::a(
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


                <?php
                if ($model->status == Documenti::DOCUMENTI_WORKFLOW_STATUS_VALIDATO) { ?>
                    <div class="small mb-0 m-t-10 text-secondary">
                        <?=
                        PublishedByWidget::widget([
                            'model' => $model,
                            'layout' => (isset(\Yii::$app->params['hideListsContentCreatorName']) && (\Yii::$app->params['hideListsContentCreatorName'] === true) ? '' : '{publisher}<div class="item text-secondary m-t-5"><strong class="text-secondary">il&nbsp</strong><span>' . $date . ' </span></div>') . ((!$isFolder && $enableCategories && !$isTeamsDocument) ? '<div class="item text-secondary m-t-5"><strong>Categoria:</strong>' . '<span class="nome-categoria text-secondary">' . ' ' . $documentCategory->titolo . '</span></div>' : '')
                        ])
                        ?>
                    </div>
                <?php } ?>
                <div class="footer-card">
                    <?php
                    if ($documentPresent) {
                        echo Html::a(
                            AmosDocumenti::t('amosdocumenti', 'Scarica'),
                            $document->getUrl(),
                            [
                                'data-key' => $model->id,
                                'title' => AmosDocumenti::t('amosdocumenti', 'Scarica il documento') . ' ' . $modelTitleSpecialChars,
                                'class' => 'm-r-10 small uppercase bold link-document-id',
                            ]
                        );
                    } else if($isTeamsDocument){
                        echo Html::a(
                            AmosDocumenti::t('amosdocumenti', 'Scarica'),
                            $model->link_document,
                            [
                                'data-key' => $model->id,
                                'title' => AmosDocumenti::t('amosdocumenti', 'Scarica il documento') . ' ' . $modelTitleSpecialChars,
                                'class' => 'm-r-10 small uppercase bold link-document-id',
                            ]
                        );
                    }
                    ?>


                    <?=
                    Html::a(
                        AmosDocumenti::t('amosdocumenti', '#detail'),
                        $modelViewUrl,
                        [
                            'class' => 'small uppercase bold',
                            'title' => AmosDocumenti::t('amosdocumenti', '#see_document_detail') . ' ' . $modelTitleSpecialChars
                        ]
                    )
                    ?>
                    <!-- < ?php
                    if ($documentPresent) {
                        echo Html::tag('span', ((strlen($documentMainFile->name) > 80) ? substr($documentMainFile->name, 0, 75) . '[...]' : $documentMainFile->name) . '.' . $documentMainFile->type, ['class' => 'text-secondary small']);
                    } else {
                        if ($documentLinkPresent) {
                            echo Html::tag('span', (AmosDocumenti::tHtml('amosdocumenti', 'File esterno')), ['class' => 'text-secondary small']);
                        } else {
                            echo Html::tag('span', (AmosDocumenti::tHtml('amosdocumenti', 'Documento testuale')), ['class' => 'text-secondary small']);
                        }
                    }
                    ? > -->
                </div>

            </div>
        </div>
    </div>
<?php } ?>