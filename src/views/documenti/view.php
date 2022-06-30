<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti\views\documenti
 * @category   CategoryName
 */

use open20\amos\attachments\components\AttachmentsList;
use open20\amos\attachments\models\File;
use open20\amos\core\forms\ContextMenuWidget;
use open20\amos\core\forms\ItemAndCardHeaderWidget;
use open20\amos\core\forms\PublishedByWidget;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\utilities\SortModelsUtility;
use open20\amos\documenti\AmosDocumenti;
use open20\amos\documenti\models\Documenti;
use open20\amos\documenti\utility\DocumentsUtility;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\web\View;
use open20\amos\attachments\utility\SortAttachmentsUtility;

/**
 * @var yii\web\View $this
 * @var open20\amos\documenti\models\Documenti $model
 */

$this->title = $model->titolo;
$ruolo = Yii::$app->authManager->getRolesByUser(Yii::$app->getUser()->getId());
if (isset($ruolo['ADMIN'])) {
    $url = ['index'];
}
$idDoc =  $model->id;

/** @var \open20\amos\documenti\controllers\DocumentiController $controller */
$controller = Yii::$app->controller;
$hidePubblicationDate = $controller->documentsModule->hidePubblicationDate;
$isFolder = $controller->documentIsFolder($model);
$controller->setNetworkDashboardBreadcrumb();
$this->params['breadcrumbs'][] = ['label' => AmosDocumenti::t('amosdocumenti', Yii::$app->session->get('previousTitle')), 'url' => Yii::$app->session->get('previousUrl')];
$this->params['breadcrumbs'][] = $this->title;

// Tab ids
$idTabCard = 'tab-card';
$idClassifications = 'tab-classifications';
$idTabAttachments = 'tab-attachments';
$document = $model->getDocumentMainFile();

$this->registerJs($js, View::POS_READY);

$jsCount = <<<JS
    $('#link-document-id').click(function() {
        $.ajax({
           url: 'increment-count-download-link?id=$idDoc',
           type: 'get',
           success: function (data) {
           }

      });
    })
JS;

$this->registerJs($jsCount);

$primoPiano = '';
$inEvidenza = '';

if ($model->status != Documenti::DOCUMENTI_WORKFLOW_STATUS_VALIDATO) {
    echo \open20\amos\workflow\widgets\WorkflowTransitionStateDescriptorWidget::widget([
        'model' => $model,
        'workflowId' => Documenti::DOCUMENTI_WORKFLOW,
        'classDivMessage' => 'message',
        'viewWidgetOnNewRecord' => true
    ]);
}

/** @var \open20\amos\report\AmosReport $reportModule */
$reportModule = Yii::$app->getModule('report');
$viewReportWidgets = (!is_null($reportModule) && in_array($model->className(), $reportModule->modelsEnabled));

?>

<div class="documents-view">
    <div class="row">
        <div class="col-xs-12 header-widget">
            <?= ItemAndCardHeaderWidget::widget(
                [
                    'model' => $model,
                    'publicationDateField' => 'data_pubblicazione',
                    'showPrevalentPartnershipAndTargets' => true,
                ]
            ) ?>
            <div class="more-info-content">
                <?php if ($viewReportWidgets) : ?>
                    <?= \open20\amos\report\widgets\ReportDropdownWidget::widget([
                        'model' => $model,
                    ]); ?>
                <?php endif; ?>
                <div class="m-l-10">
                    
                    <?php
                    $contextMenuWidgetConf = [
                        'model' => $model,
                        'actionModify' => '/documenti/documenti/update?id=' . $model->id,
                        'actionDelete' => '/documenti/documenti/delete?id=' . $model->id,
                        'modelValidatePermission' => 'DocumentValidate',
                        //'labelModify' => AmosDocumenti::t('amosdocumenti', "New document version"),
                        //'actionModify' => $controller->documentsModule->enableDocumentVersioning ? "/documenti/documenti/new-document-version?id=" . $model->id : "/documenti/documenti/update?id=" . $model->id,
                        //'checkModifyPermission' => $controller->documentsModule->enableDocumentVersioning ? !(\Yii::$app->user->can('DOCUMENTI_UPDATE')) : true,
                        //'confirmModify' => AmosDocumenti::t('amosdocumenti', '#NEW_DOCUMENT_VERSION_MODAL_TEXT')
                    ];
                    if ($model->is_folder) {
                        $contextMenuWidgetConf['labelDeleteConfirm'] = AmosDocumenti::t('amosdocumenti', '#confirm_delete_folder');
                    }

                    ?>
					<?= ContextMenuWidget::widget($contextMenuWidgetConf) ?>
					
                </div>
                <?php
            $moduleDocumenti = \Yii::$app->getModule(AmosDocumenti::getModuleName());
            if (\Yii::$app->user->can('ADMIN')) {
                $layoutPublishedByWidget = $moduleDocumenti->layoutPublishedByWidget['layoutAdmin'];
            } else {
                $layoutPublishedByWidget = $moduleDocumenti->layoutPublishedByWidget['layout'];
            }
            ?>
            </div>
        </div>
    </div>
    <div class="document-wrapper">
        <div class="row">
            <div class="col-md-6">
                <div class="document-title flexbox">
                    <?php if ($documentsModule->enableCatImgInDocView) : ?>
                        <div class="documents-category-new-rl">
                            <?= Html::img($documentCategory->getAvatarUrl('square_small'), [
                                'class' => 'gridview-image',
                                'alt' => AmosDocumenti::t('amosdocumenti', 'Immagine della categoria')
                            ]); ?>
                            <p><?= $documentCategory->titolo ?></p>
                        </div>
                    <?php else : ?>

                        <?php if ((in_array(strtolower($document['type']), ['jpg', 'png', 'jpeg', 'svg']))) : ?>
                            <span class="icon icon-image icon-sm mdi mdi-file-image"></span>
                        <?php elseif ((in_array(strtolower($document['type']), ['pdf']))) : ?>
                            <span class="icon icon-pdf icon-sm mdi mdi-file-pdf"></span>
                        <?php elseif ((in_array(strtolower($document['type']), ['doc', 'docx']))) : ?>
                            <span class="icon icon-word icon-sm mdi mdi-file-word"></span>
                        <?php elseif ((in_array(strtolower($document['type']), ['xls', 'xlsx']))) : ?>
                            <span class="icon icon-excel icon-sm mdi mdi-file-excel"></span>
                        <?php elseif ((in_array(strtolower($document['type']), ['csv']))) : ?>
                            <span class="icon icon-black icon-sm mdi mdi-file-delimited"></span>
                        <?php elseif ((in_array(strtolower($document['type']), ['pptx']))) : ?>
                            <span class="icon icon-powerpoint icon-sm mdi mdi-file-powerpoint"></span>
                        <?php elseif ((in_array(strtolower($document['type']), ['txt', 'rtf']))) : ?>
                            <span class="icon icon-black icon-sm mdi mdi-file-document"></span>
                        <?php elseif ((in_array(strtolower($document['type']), ['zip', 'rar']))) : ?>
                            <span class="icon icon-link icon-sm mdi mdi-folder-zip"></span>
                        <?php else : ?>
                            <span class="icon icon-link icon-sm mdi mdi-file-link"></span>
                        <?php endif; ?>


                    <?php endif; ?>
                    <div>
                        <?php
                        if ($model->getDocumentMainFile()->name) {
                            $name = (strlen($model->getDocumentMainFile()->name) > 80) ? substr($model->getDocumentMainFile()->name, 0, 75) . '[...]' : $model->getDocumentMainFile()->name . '.' . $model->getDocumentMainFile()->type;
                        } else {
                            $name = $model->titolo;
                        }

                        if (!is_null($model->getDocumentMainFile())) {
                            echo Html::a(
                                $name,
                                [
                                    '/attachments/file/download/', 'id' =>  $model->getDocumentMainFile()->id,
                                    'hash' =>  $model->getDocumentMainFile()->hash
                                ],
                                ['class' => 'filename ', 'data-toggle' => 'tooltip', 'title' => AmosDocumenti::t('amosdocumenti', 'Scarica file')]
                            );
                            echo Html::tag('span', (' (' . $model->documentMainFile->size % 1024) . ' Kb)', ['class' => 'text-muted small']);

                            if ($model->drive_file_id) {
                                echo Html::tag('em', AmosDocumenti::t('amosdocumenti', 'Questo è un file presente su Google Drive'));
                                if (!empty($model->drive_file_modified_at)) {
                                    echo Html::tag(
                                        'em',
                                        ' - ' . AmosDocumenti::t('amosdocumenti', 'aggiornato') . ' ' . \Yii::$app->formatter->asDatetime($model->drive_file_modified_at),
                                        [
                                            'id' => 'drive-file-modified-at-id',
                                            'style' => 'display:none'
                                        ]
                                    );
                                }
                            }
                        } else if ($model->is_folder && $model->drive_file_id) {
                            echo Html::tag('em', AmosDocumenti::t('amosdocumenti', 'Questa è una cartella sincronizzata con Google Drive'));
                        } else {

                            if ($documentLinkPresent) {
                                echo Html::a(
                                    (strlen($model->link_document) > 80)
                                        ? AmosDocumenti::t('amosdocumenti', '#download_document_for_view')
                                        : $model->link_document,
                                    $model->link_document,
                                    [
                                        'target' => '_blank',
                                        'class' => 'btn btn-navigation-primary link-documents',
                                        'id' => 'link-document-id'
                                    ]
                                );
                            } else {


                                echo Html::a(
                                    AmosDocumenti::tHtml('amosdocumenti', 'Apri link esterno'),
                                    $model->link_document,
                                    [
                                        'title' => $model->link_document,
                                        'target' => '_blank',
                                        'data-key' => $model->id
                                    ]
                                );
                            }
                        }
                        ?>
                    </div>

                </div>
            </div>
            <div class="col-md-6">
                <div class="download-box">
                    <?php echo $this->render('_download_box', [
                        'model' => $model,
                        'isFolder' => $isFolder,
                        'controller' => \Yii::$app->controller,
                        'showNewVersionButton' => true
                    ]) ?>
                </div>
            </div>
        </div>
        <div class="document-description m-t-20">
            <?= $model->descrizione; ?>
        </div>

        <div class="section-attachment " id="section-attachments">
            <?= AttachmentsList::widget([
                'model' => $model,
                'attribute' => 'documentAttachments',
                'viewDeleteBtn' => false,
                'viewDownloadBtn' => true,
                'viewFilesCounter' => true,
                'enableSort' => true,
                'viewSortBtns' => false,
                'requireModalMoveFile' => AmosDocumenti::instance()->requireModalMoveFile
            ]) ?>
        </div>
    </div>

    <?php if (!empty(\Yii::$app->getModule('tag'))) { ?>
        <div class="section-tags m-t-30" id="section-tags">


            <?= \open20\amos\core\forms\ListTagsWidget::widget([
                'userProfile' => $model->id,
                'className' => $model->className(),
                'viewFilesCounter' => true,
            ]);
            ?>

        </div>
    <?php } ?>

    <div class="footer-content">

        <div class="social-share-wrapper">
            <?= \open20\amos\core\forms\editors\socialShareWidget\SocialShareWidget::widget([
                'mode' => \open20\amos\core\forms\editors\socialShareWidget\SocialShareWidget::MODE_NORMAL,
                'configuratorId' => 'socialShare',
                'model' => $model,
                'url' => \yii\helpers\Url::to($baseUrl . '/documenti/documenti/public?id=' . $model->id, true),
                'title' => $model->title,
                'description' => $model->descrizione_breve,

            ]); ?>

        </div>

        <div class="widget-body-content">
            <?php echo \open20\amos\core\forms\editors\likeWidget\LikeWidget::widget([
                'model' => $model,
            ]);
            ?>
        </div>

    </div>
</div>