<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\documenti\views\documenti
 * @category   CategoryName
 */

use lispa\amos\core\forms\ContextMenuWidget;
use lispa\amos\core\forms\ItemAndCardHeaderWidget;
use lispa\amos\core\forms\PublishedByWidget;
use lispa\amos\core\helpers\Html;
use lispa\amos\core\icons\AmosIcons;
use lispa\amos\core\views\toolbars\StatsToolbar;
use lispa\amos\documenti\AmosDocumenti;
use lispa\amos\notificationmanager\forms\NewsWidget;

/**
 * @var yii\web\View $this
 * @var lispa\amos\documenti\models\Documenti $model
 */

$modelViewUrl = $model->getFullViewUrl();
$document = $model->getDocumentMainFile();
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
                    <?= Html::a(Html::tag('h2', $model->titolo), $modelViewUrl) ?>
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
                            <?= $model->descrizione_breve ?>
                            <?= Html::a(AmosDocumenti::tHtml('amosdocumenti', 'Leggi tutto'), $modelViewUrl, ['class' => 'underline']) ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="sidebar col-sm-5 col-xs-12">
            <div class="container-sidebar">
                <div class="box">
                    <?= AmosIcons::show('download-general', ['class' => 'am-4'], 'dash') . Html::tag('p', $document->name . '.' . $document->type, ['class' => 'title']); ?>
                </div>
                <div class="box post-info">
                    <?= PublishedByWidget::widget([
                        'model' => $model,
                        'layout' => '{publisher}{targetAdv}{category}' . (Yii::$app->user->can('ADMIN') ? '{status}' : '')
                    ]) ?>
                    <p>
                        <strong><?= ($model->primo_piano) ? AmosDocumenti::tHtml('amosdocumenti', 'Pubblicato in prima pagina') : '' ?></strong>
                    </p>
                </div>
                <div class="footer_sidebar col-xs-12 nop">
                    <?= Html::a(AmosDocumenti::tHtml('amosdocumenti', 'Scarica file'), ['/attachments/file/download/', 'id' => $document->id, 'hash' => $document->hash], [
                        'title' => AmosDocumenti::t('amosdocumenti', 'Scarica file'),
                        'class' => 'bk-btnImport pull-right btn btn-amministration-primary',
                    ]); ?>
                    <?php
                    $visible = isset($statsToolbar) ? $statsToolbar : false;
                    if ($visible) {
                        echo StatsToolbar::widget([
                            'model' => $model,
                        ]);
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
