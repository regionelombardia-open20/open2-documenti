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
use open20\amos\core\icons\AmosIcons;
use open20\amos\documenti\AmosDocumenti;
use open20\amos\documenti\controllers\DocumentiController;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var open20\amos\documenti\models\Documenti $model
 * @var bool $isFolder
 * @var \open20\amos\documenti\controllers\DocumentiController $controller
 * @var bool $showNewVersionButton
 */
$select2Id = 'all-document-versions-id';
$assetBundle = \open20\amos\documenti\assets\ModuleDocumentiAsset::register($this);
$js = "
$('#" . $select2Id . "').on('change', function(e) {
    e.preventDefault();
    var selectedValue = $(this).val();
    window.location.href = '/documenti/documenti/view?id=' + selectedValue;
});
";

$this->registerJs("
    $('.new-version-$model->id').on('click', function() {
        $(this).addClass('disabled').css('pointer-events', 'none');
    });
");

$idModel = $model->id;
$jsGoogleDrive = <<<JS
      $.ajax({
           url: 'is-google-drive-document-modified?id=$idModel',
           type: 'get',
           success: function (data) {
               console.log(data);
              if(data == true){
                  $('#sync-file-button-id').show();
                  $('#drive-file-modified-at-id').hide();
              }else {
                  $('#sync-file-button-id').hide();
                  $('#drive-file-modified-at-id').show();
              }
           }

      });
JS;

/** @var DocumentiController $appController */
$appController = Yii::$app->controller;

/** @var AmosDocumenti $documentsModule */
$documentsModule = $appController->documentsModule;

$documentMainFile = $model->getDocumentMainFile();
$documentLinkPresent = (!empty($model->link_document));
$enableContentDuplication = $documentsModule->enableContentDuplication;
$foldersEnabled = $documentsModule->enableFolders;
$enableDocumentVersioning = $documentsModule->enableDocumentVersioning;

if ($model->drive_file_id) {
    $this->registerJs($jsGoogleDrive);
}
$this->registerJs($js, \yii\web\View::POS_READY);
?>
<div class="download-file">

    <?php if (!$isFolder && $enableDocumentVersioning) : ?>
        <div class="select-container">
            <?=
            Select2::widget([
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
            ])
            ?>
        </div>
    <?php endif; ?>
    <div class="action-document">


        <?php
        $btns = $this->render('_duplicate_btn', [
            'model' => $model,
            'isInIndex' => false
        ]);

        if ($model->drive_file_id) {
            $btns .= Html::a(
                            AmosIcons::show('refresh-sync-alert'),
                            [
                                '/documenti/documenti/sync-doc-file', 'id' => $model->id,
                            ],
                            [
                                'id' => 'sync-file-button-id',
                                'title' => AmosDocumenti::t('amosdocumenti', 'Sync drive file'),
                                'class' => 'bk-btnImport pull-right btn btn-icon',
                                'style' => 'display:none',
                                'data-confirm' => AmosDocumenti::t('amosdocumenti', "Vuoi sincronizzare il file con l'ultima versione aggiornata sul drive?")
                            ]
            );
        }

        if ($showNewVersionButton) {
            if (!$isFolder && $enableDocumentVersioning) {
                if ($personalizedCanNewVersion || Yii::$app->user->can('DOCUMENTI_UPDATE', ['model' => $model, 'newVersion' => true])) {
                    $btns .= \open20\amos\core\utilities\ModalUtility::addConfirmRejectWithModal([
                                'modalId' => 'new-document-version-modal-id-' . $model->id,
                                'modalDescriptionText' => AmosDocumenti::t('amosdocumenti', '#NEW_DOCUMENT_VERSION_MODAL_TEXT'),
                                'btnText' => AmosDocumenti::t('amosdocumenti', 'New document version'),
                                'btnLink' => Yii::$app->urlManager->createUrl([
                                    '/documenti/documenti/new-document-version',
                                    'id' => $model['id']
                                ]),
                                'btnOptions' => [
                                    'title' => AmosDocumenti::t('amosdocumenti', 'New document version'),
                                    'class' => 'bk-btnImport btn btn-primary m-l-5',
                                ],
                                'confirmBtnOptions' => [
                                    'class' => 'btn btn-navigation-primary new-version-' . $model->id,
                                ],
                    ]);
                }
            }
        }
        echo $btns;
        ?>


    </div>



</div>
