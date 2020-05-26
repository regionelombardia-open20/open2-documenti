<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti\views\documenti
 * @category   CategoryName
 */

use open20\amos\core\forms\ContextMenuWidget;
use open20\amos\core\forms\ItemAndCardHeaderWidget;
use open20\amos\core\forms\PublishedByWidget;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\views\toolbars\StatsToolbar;
use open20\amos\documenti\AmosDocumenti;
use open20\amos\notificationmanager\forms\NewsWidget;

/**
 * @var yii\web\View $this
 * @var open20\amos\documenti\models\Documenti $model
 */

$modelViewUrl = $model->getFullViewUrl();
$document = $model->getDocumentMainFile();
$documentPresent = ($document != null);
$documentLinkPresent = (!empty($model->link_document));
$visible = isset($statsToolbar) ? $statsToolbar : false;


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

<div class="listview-container document">
    <div class="post-horizontal">
        <div class="col-sm-7 col-xs-12 nop">
            <div class="col-xs-12 nop">
                <?= ItemAndCardHeaderWidget::widget([
                    'model' => $model,
                    'publicationDateField' => 'data_pubblicazione',
                ]);
                ?>
            </div>
        </div>
        <div class="col-sm-7 col-xs-12 nop">
            <div class="post-content col-xs-12 nop">
                <div class="post-title col-xs-10">
                    <?= Html::a(Html::tag('h2', htmlspecialchars($model->titolo)), $modelViewUrl) ?>
                </div>
                <?php
                echo NewsWidget::widget([
                    'model' => $model,
                ]);
                ?>
                <?= ContextMenuWidget::widget([
                    'model' => $model,
                    'actionModify' => $model->getFullUpdateUrl(),
                    'actionDelete' => $model->getFullDeleteUrl(),
                    'modelValidatePermission' => 'DocumentValidate',
                    'mainDivClasses' => 'col-xs-1 nop'
                ]) ?>
                <div class="clearfix"></div>
                <div class="row nom post-wrap">
                    <div class="post-text col-xs-12">
                        <p>
                            <?= htmlspecialchars($model->descrizione_breve) ?><br>
                            <?= Html::a(AmosDocumenti::tHtml('amosdocumenti', 'Leggi tutto'), $modelViewUrl, ['class' => 'underline']) ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="sidebar col-sm-5 col-xs-12">
            <div class="container-sidebar">
                <?php if ($documentPresent): ?>
                    <div class="box">
                        <?php
                        echo AmosIcons::show('download-general', ['class' => 'am-4'], 'dash') . Html::tag('p', $document->name . '.' . $document->type, ['class' => 'title']);
                        ?>
                    </div>
                <?php endif; ?>
                <?php if ($documentLinkPresent): ?>
                    <div class="box">
                       <?php
                        echo AmosIcons::show('doc-www', ['class' => 'am-4'], 'dash') . Html::tag('p', \open20\amos\core\utilities\StringUtils::shortText($model->titolo, 80) , ['class' => 'title']);
                        ?>

                        <?php
                        echo  Html::tag('p', \open20\amos\core\utilities\StringUtils::shortText($model->link_document, 50) , ['class' => 'title']);
                        ?>
                        
                    </div>
                <?php endif; ?>
                <div class="box post-info">
                    <?= PublishedByWidget::widget([
                        'model' => $model,
                        'layout' => (isset(\Yii::$app->params['hideListsContentCreatorName']) && (\Yii::$app->params['hideListsContentCreatorName'] === true) ? '' : '{publisher}') . '{targetAdv}{category}' . (Yii::$app->user->can('ADMIN') ? '{status}' : '')
                    ]) ?>
                    <p>
                        <strong><?= ($model->primo_piano) ? AmosDocumenti::tHtml('amosdocumenti', 'Pubblicato in prima pagina') : '' ?></strong>
                    </p>
                </div>
                <?php if ($documentPresent || $documentLinkPresent || $visible): ?>
                    <div class="footer_sidebar col-xs-12 nop">
                        <?php
                        if ($documentPresent) {
                            echo Html::a(
                                AmosDocumenti::tHtml('amosdocumenti', 'Scarica file'),
                                [
                                    '/attachments/file/download/',
                                    'id' => $document->id,
                                    'hash' => $document->hash
                                ],
                                [
                                    'title' => AmosDocumenti::t('amosdocumenti', 'Scarica file'),
                                    'class' => 'bk-btnImport pull-right btn btn-amministration-primary',
                                ]
                            );
                        } else {
                            if ($documentLinkPresent) {
                                echo Html::a(
                                    AmosDocumenti::tHtml('amosdocumenti', 'Open file'),
                                    $model->link_document,
                                    [
                                        'title' => AmosDocumenti::t('amosdocumenti', 'Open file'),
                                        'class' => 'link-document-id bk-btnImport pull-right btn btn-amministration-primary',
                                        'target' => '_blank',
                                        'data-key' => $model->id
                                    ]
                                );
                            }
                        }

                        if ($visible) {
                            echo StatsToolbar::widget([
                                'model' => $model,
                            ]);
                        }
                        ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
