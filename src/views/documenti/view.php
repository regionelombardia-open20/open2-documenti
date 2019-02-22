<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\documenti\views\documenti
 * @category   CategoryName
 */

use lispa\amos\attachments\components\AttachmentsList;
use lispa\amos\core\forms\ContextMenuWidget;
use lispa\amos\core\forms\ItemAndCardHeaderWidget;
use lispa\amos\core\forms\PublishedByWidget;
use lispa\amos\core\helpers\Html;
use lispa\amos\core\icons\AmosIcons;
use lispa\amos\documenti\AmosDocumenti;
use lispa\amos\documenti\models\Documenti;
use lispa\amos\documenti\utility\DocumentsUtility;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\web\View;

/**
 * @var yii\web\View $this
 * @var lispa\amos\documenti\models\Documenti $model
 */

$this->title = $model->titolo;
$ruolo = Yii::$app->authManager->getRolesByUser(Yii::$app->getUser()->getId());
if (isset($ruolo['ADMIN'])) {
    $url = ['index'];
}

/** @var \lispa\amos\documenti\controllers\DocumentiController $controller */
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

$select2Id = 'all-document-versions-id';

$js = "
$('#" . $select2Id . "').on('change', function(e) {
    e.preventDefault();
    var selectedValue = $(this).val();
    window.location.href = '/documenti/documenti/view?id=' + selectedValue;
});
";
$this->registerJs($js, View::POS_READY);

$hidePubblicationDate = $controller->documentsModule->hidePubblicationDate;
$documentMainFile = $model->getDocumentMainFile();

$primoPiano = '';
$inEvidenza = '';

if ($model->status != Documenti::DOCUMENTI_WORKFLOW_STATUS_VALIDATO) {
    echo \lispa\amos\workflow\widgets\WorkflowTransitionStateDescriptorWidget::widget([
        'model' => $model,
        'workflowId' => Documenti::DOCUMENTI_WORKFLOW,
        'classDivMessage' => 'message',
        'viewWidgetOnNewRecord' => true
    ]);
}

?>

<div class="documents-view col-xs-12 nop">
    <div class="col-md-8 col-xs-12">
        <div class="col-xs-12 header-widget nop">
            <?= ItemAndCardHeaderWidget::widget([
                    'model' => $model,
                    'publicationDateField' => 'data_pubblicazione',
                    'showPrevalentPartnershipAndTargets' => true,
                ]
            ) ?>
            <?php
            $contextMenuWidgetConf = [
                'model' => $model,
                'actionModify' => "/documenti/documenti/update?id=" . $model->id,
                'actionDelete' => "/documenti/documenti/delete?id=" . $model->id,
                'modelValidatePermission' => 'DocumentValidate',
//                'labelModify' => AmosDocumenti::t('amosdocumenti', "New document version"),
//                'actionModify' => $controller->documentsModule->enableDocumentVersioning ? "/documenti/documenti/new-document-version?id=" . $model->id : "/documenti/documenti/update?id=" . $model->id,
//                'checkModifyPermission' => $controller->documentsModule->enableDocumentVersioning ? !(\Yii::$app->user->can('DOCUMENTI_UPDATE')) : true,
//                'confirmModify' => AmosDocumenti::t('amosdocumenti', '#NEW_DOCUMENT_VERSION_MODAL_TEXT')
            ];
            if ($model->is_folder) {
                $contextMenuWidgetConf['labelDeleteConfirm'] = AmosDocumenti::t('amosdocumenti', '#confirm_delete_folder');
            }
            ?>
            <?= ContextMenuWidget::widget($contextMenuWidgetConf) ?>
            <?php
            $moduleDocumenti = \Yii::$app->getModule(AmosDocumenti::getModuleName());
            if (\Yii::$app->user->can('ADMIN')) {
                $layoutPublishedByWidget = $moduleDocumenti->layoutPublishedByWidget['layoutAdmin'];
            } else {
                $layoutPublishedByWidget = $moduleDocumenti->layoutPublishedByWidget['layout'];
            }
            ?>
            <?= PublishedByWidget::widget([
                'model' => $model,
                'layout' => $layoutPublishedByWidget,
                'isTooltip' => true
            ]) ?>
            <?=
            \lispa\amos\report\widgets\ReportFlagWidget::widget([
                'model' => $model,
            ])
            ?>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="col-md-8 col-xs-12">
        <div class="header col-xs-12 nop">
            <div class="title col-xs-12">
                <h2 class="title-text"><?= $model->titolo ?></h2>
                <h3 class="subtitle-text"><?= $model->sottotitolo ?></h3>
            </div>
        </div>
        <div class="col-xs-12 download-file nop">
            <?php if (!$isFolder && $controller->documentsModule->enableDocumentVersioning): ?>
                <div class="col-xs-12">
                    <?= Select2::widget([
                        'model' => $model,
                        'attribute' => 'version',
                        'data' => ArrayHelper::map($model->allDocumentVersions, 'id', 'versionInfo'),
                        'options' => [
                            'placeholder' => AmosDocumenti::t('amosdocumenti', 'Cambia versione'),
                            'id' => $select2Id,
                            'lang' => substr(Yii::$app->language, 0, 2),
                            'multiple' => false,
                            'value' => $model->id
                        ],
                        //                'addon' => [
                        //                    'prepend' => $model->getAttributeLabel('version')
                        //                ]
                    ]) ?>
                </div>
            <?php endif; ?>
            <div class="col-xs-12 action-document">
                <div>
                    <div>
                        <?=
                        DocumentsUtility::getDocumentIcon($model);
                        ?>
                    </div>
                    <div>
                        <?=
                        Html::tag('p', ((strlen($documentMainFile->name) > 80) ? substr($documentMainFile->name, 0, 75) . '[...]' : $documentMainFile->name) . '.' . $documentMainFile->type, ['class' => 'filename']);
                        ?>
                    </div>
                </div>
                <div>
                    <?= Html::a(/*AmosDocumenti::tHtml('amosdocumenti', 'Scarica file') . */
                        AmosIcons::show('download'), ['/attachments/file/download/', 'id' => $model->getDocumentMainFile()->id, 'hash' => $model->getDocumentMainFile()->hash], [
                        'title' => AmosDocumenti::t('amosdocumenti', 'Scarica file'),
                        'class' => 'bk-btnImport pull-right btn btn-icon',
                    ]); ?>

                    <?php
                    if (!$isFolder && $controller->documentsModule->enableDocumentVersioning) {
                        if (Yii::$app->user->can('DOCUMENTI_UPDATE', ['model' => $model, 'newVersion' => true])) {
                            $btn = \lispa\amos\core\utilities\ModalUtility::addConfirmRejectWithModal([
                                'modalId' => 'new-document-version-modal-id-' . $model->id,
                                'modalDescriptionText' => AmosDocumenti::t('amosdocumenti', '#NEW_DOCUMENT_VERSION_MODAL_TEXT'),
                                'btnText' => AmosDocumenti::t('amosdocumenti', 'New document version'),
                                'btnLink' => Yii::$app->urlManager->createUrl([
                                    '/documenti/documenti/new-document-version',
                                    'id' => $model['id']
                                ]),
                                'btnOptions' => [
                                    'title' => AmosDocumenti::t('amosdocumenti', 'New document version'),
                                    'class' => 'bk-btnImport pull-right btn btn-secondary m-r-5'],

                            ]);
                            echo $btn;
                        }
                    }
                    ?>
                </div>
            </div>

        </div>
        <div class="text-content col-xs-12 nop">
            <?= $model->descrizione; ?>
        </div>
        <div class="widget-body-content col-xs-12 nop">
            <?=
            \lispa\amos\report\widgets\ReportDropdownWidget::widget([
                'model' => $model,
            ]);
            ?>
            <?= \lispa\amos\core\forms\editors\socialShareWidget\SocialShareWidget::widget([
                'mode' => \lispa\amos\core\forms\editors\socialShareWidget\SocialShareWidget::MODE_DROPDOWN,
                'configuratorId' => 'socialShare',
                'model' => $model,
                'url' => \yii\helpers\Url::to(\Yii::$app->params['platform']['backendUrl'] . '/documenti/documenti/view?id=' . $model->id, true),
                'title' => $model->title,
                'description' => $model->descrizione_breve,
//                'imageUrl'      => !empty($model->getNewsImage()) ? $model->getNewsImage()->getWebUrl('square_small') : '',
            ]); ?>
        </div>
    </div>
    <div class="col-md-4 col-xs-12">
        <div class="col-xs-12 attachment-section-sidebar nop" id="section-attachments">
            <?= Html::tag('h2', AmosIcons::show('paperclip', [], 'dash') . AmosDocumenti::t('amosdocumenti', '#attachments_title')) ?>
            <div class="col-xs-12">
                <?= AttachmentsList::widget([
                    'model' => $model,
                    'attribute' => 'documentAttachments',
                    'viewDeleteBtn' => false,
                    'viewDownloadBtn' => true,
                    'viewFilesCounter' => true,
                ]) ?>
            </div>
        </div>
        <?php if (!empty(\Yii::$app->getModule('tag'))) { ?>
            <div class="tags-section-sidebar col-xs-12 nop" id="section-tags">
                <?= Html::tag('h2', AmosIcons::show('tag', [], 'dash') . AmosDocumenti::t('amosdocumenti', '#tags_title')) ?>
                <div class="col-xs-12">
                    <?= \lispa\amos\core\forms\ListTagsWidget::widget([
                        'userProfile' => $model->id,
                        'className' => $model->className(),
                        'viewFilesCounter' => true,
                    ]);
                    ?>
                </div>
            </div>
        <?php } ?>
    </div>


</div>
<?= Html::a(AmosDocumenti::t('amosdocumenti', '#go_back'),(\Yii::$app->request->referrer ?: Yii::$app->session->get('previousUrl')), [
    'class' => 'btn btn-secondary pull-left'
]) ?>
