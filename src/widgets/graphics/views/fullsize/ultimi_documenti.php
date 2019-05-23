<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\documenti\widgets\graphics\views
 * @category   CategoryName
 */

use lispa\amos\core\forms\WidgetGraphicsActions;
use lispa\amos\core\icons\AmosIcons;
use lispa\amos\documenti\AmosDocumenti;
use lispa\amos\documenti\assets\ModuleDocumentiAsset;
use lispa\amos\documenti\models\Documenti;
use lispa\amos\documenti\widgets\graphics\WidgetGraphicsUltimiDocumenti;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\Pjax;
use lispa\amos\documenti\utility\DocumentsUtility;

/**
 * @var View $this
 * @var ActiveDataProvider $listaDocumenti
 * @var WidgetGraphicsUltimiDocumenti $widget
 * @var string $toRefreshSectionId
 */

ModuleDocumentiAsset::register($this);

/** @var AmosDocumenti $moduleDocumenti */
$moduleDocumenti = \Yii::$app->getModule(AmosDocumenti::getModuleName());
$listaDocumenti->query->andWhere(['is_folder' => 0]);
$documents = $listaDocumenti->getModels();

?>
<div class="box-widget-header">
    <?php if (isset($moduleDocumenti) && !$moduleDocumenti->hideWidgetGraphicsActions) { ?>
        <?= WidgetGraphicsActions::widget([
            'widget' => $widget,
            'tClassName' => AmosDocumenti::className(),
            'actionRoute' => '/documenti/documenti/create',
            'toRefreshSectionId' => $toRefreshSectionId
        ]);
    } ?>

    <div class="box-widget-wrapper">
        <h2 class="box-widget-title">
            <?= AmosIcons::show('file-text-o', [], AmosIcons::DASH)?>
            <span class="pluginName"> <?= $widget->widgetTitle ?> </span>
        </h2>
    </div>

    <?php if (count($documents) == 0):
        $textReadAll = AmosDocumenti::t('amosdocumenti', '#addDocument');
        $linkReadAll = ['/documenti/documenti/create'];
        else:
            $textReadAll = AmosDocumenti::t('amosdocumenti', 'Visualizza Tutti') . AmosIcons::show('chevron-right');
            $linkReadAll = $widget->linkReadAll;
    endif; ?>

    <div class="read-all"><?= Html::a($textReadAll, $linkReadAll, ['class' => '']); ?></div>
</div>

<div class="box-widget box-widget-column latest-documents">
    <section>
        <h2 class="sr-only"><?= $widget->getLabel() ?></h2>
            <?php if (count($documents) == 0): ?>
                <div class="list-items list-empty"><h3><?= AmosDocumenti::tHtml('amosdocumenti', 'Nessun documento'); ?></h3></div>
            <?php else: ?>
                <div class="list-items">
                    <?php foreach ($documents as $document): ?>
                        <?php
                        /** @var Documenti $document */
                        if ($document->is_folder) {
                            $documentViewUrl = ['/documenti/documenti/own-interest-documents', 'parentId' => $document->id];
                        } else {
                            $documentViewUrl = $document->getFullViewUrl();
                        }
                        $documentTitle = htmlspecialchars($document->titolo);
                        ?>
                        <div class="widget-listbox-option" role="option">
                            <article class="wrap-item-box text-center">

                                <?php
                                $icon = DocumentsUtility::getDocumentIcon($document, true);
                                $documentInfo = $document->getDocumentMainFile();
//                                    ['id' => $documentInfo->id, 'hash' => $documentInfo->hash],
//                                    ['title' => AmosDocumenti::t('amosdocumenti', 'Scarica file')]

                                if (strlen($documentTitle) > 150) {
                                    $stringCut = substr($documentTitle, 0, 150);
                                    $documentTitle = substr($stringCut, 0, strrpos($stringCut, ' ')) . '... ';
                                }
                                ?>
                                <?= Html::a('                              
                                    <div class="widget-iconbox-container">
                                        <div class="container-img">
                                            '.AmosIcons::show($icon , ['class' => 'icon_widget_graph'], 'dash') .'
                                        </div>
                                        <div class="container-text">
                                            <h2 class="box-widget-subtitle">
                                                '.$documentTitle.'
                                            </h2>
                                            <div class="box-widget-info-bottom">
                                                <span> '. $documentInfo->size .' Kb</span>
                                            </div>
                                        </div>
                                    </div>
                                ',
                                ['/attachments/file/download/', 'id' => $documentInfo->id, 'hash' => $documentInfo->hash],
                                ['title' => AmosDocumenti::t('amosdocumenti', 'Scarica file'), 'class' => 'iconbox-link']); ?>

                                <div class="box-widget-info-top">
                                    <p><?= Yii::$app->getFormatter()->asDatetime($document->created_at); ?></p>
                                </div>
                                <div class="footer-listbox footer-listbox-center">
                                    <?= Html::a('<span class="sr-only">' . AmosDocumenti::t('amosdocumenti', 'VISUALIZZA') .'</span>' . AmosIcons::show('info'), $documentViewUrl, ['class' => 'btn-action']); ?>
                                </div>
                            </article>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
    </section>
</div>
