<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open20\amos\documenti\widgets\graphics\views
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

use open20\amos\attachments\models\File;
use open20\amos\core\forms\WidgetGraphicsActions;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\documenti\AmosDocumenti;
use open20\amos\documenti\assets\ModuleDocumentiAsset;
use open20\amos\documenti\models\Documenti;
use open20\amos\documenti\utility\DocumentsUtility;
use open20\amos\documenti\widgets\graphics\WidgetGraphicsUltimiDocumenti;
use yii\data\ActiveDataProvider;
use yii\web\View;

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
$alwaysLinkToViewWidgetGraphicLastDocs = $moduleDocumenti->alwaysLinkToViewWidgetGraphicLastDocs;

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
            <?= AmosIcons::show('file-text-o', [], AmosIcons::DASH) ?>
            <span class="pluginName"> <?= $widget->widgetTitle ?> </span>
        </h2>
    </div>

    <?php
    if (count($documents) == 0) {
        $textReadAll = AmosDocumenti::t('amosdocumenti', '#addDocument');
        $linkReadAll = '/documenti/documenti/create';
        $checkPermNew = true;
    } else {
        $textReadAll = AmosDocumenti::t('amosdocumenti', 'Visualizza Tutti') . AmosIcons::show('chevron-right');
        $linkReadAll = $widget->linkReadAll;
        $checkPermNew = false;
    }
    ?>

    <div class="read-all"><?= Html::a($textReadAll, $linkReadAll, ['class' => ''], $checkPermNew); ?></div>
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
                    $documentViewUrl = '';
                    $icon = DocumentsUtility::getDocumentIcon($document, true);
                    $documentInfo = $document->getDocumentMainFile();
                    $documentInfoIsFile = ($documentInfo instanceof File);
                    $documentIconboxTitle = AmosDocumenti::t('amosdocumenti', 'Scarica file');
                    $documentIconboxUrl = null;

                    if ($document->is_folder) {
                        $documentViewUrl = ['/documenti/documenti/own-interest-documents', 'parentId' => $document->id];
                        $documentIconboxUrl = ['/documenti/documenti/own-interest-documents', 'parentId' => $document->id];
                    } else {
                        $documentViewUrl = $document->getFullViewUrl();
                        if ($alwaysLinkToViewWidgetGraphicLastDocs) {
                            $documentIconboxUrl = $documentViewUrl;
                            $documentIconboxTitle = AmosDocumenti::t('amosdocumenti', 'Apri');
                        } else {
                            if ($documentInfoIsFile) {
                                $documentIconboxUrl = $documentInfo->getUrl('original',true);
                            }
                        }
                    }

                    $documentTitle = htmlspecialchars($document->titolo);
                    if (strlen($documentTitle) > 150) {
                        $stringCut = substr($documentTitle, 0, 150);
                        $documentTitle = substr($stringCut, 0, strrpos($stringCut, ' ')) . '... ';
                    }
                    ?>
                    <div class="widget-listbox-option" role="option">
                        <article class="wrap-item-box text-center">
                            <?php
                            $linkOptions = ['title' => $documentIconboxTitle, 'class' => 'iconbox-link'];
                            if (!empty($document->link_document)) {
                                $linkOptions['target'] = '_blank';
                                $documentIconboxUrl = $document->link_document;
                            }
                            ?>
                            <?= Html::a('
                                    <div class="widget-iconbox-container">
                                        <div class="container-img">
                                            ' . AmosIcons::show($icon, ['class' => 'icon_widget_graph'], 'dash') . '
                                        </div>
                                        <div class="container-text">
                                            <h2 class="box-widget-subtitle">
                                                ' . $documentTitle . '
                                            </h2>
                                            <div class="box-widget-info-bottom">
                                                <span> ' . ($documentInfoIsFile ? $documentInfo->formattedSize : '') . '</span>
                                            </div>
                                        </div>
                                    </div>
                                ',
                                ((isset($documentIconboxUrl) && !empty($documentIconboxUrl)) ? $documentIconboxUrl : ''),
                                $linkOptions); ?>

                            <div class="box-widget-info-top">
                                <p><?= Yii::$app->getFormatter()->asDatetime($document->created_at); ?></p>
                            </div>
                            <?php if (!$alwaysLinkToViewWidgetGraphicLastDocs): ?>
                                <div class="footer-listbox footer-listbox-center">
                                    <?= Html::a('<span class="sr-only">' . AmosDocumenti::t('amosdocumenti', 'VISUALIZZA') . '</span>' . AmosIcons::show('info'), $documentViewUrl, ['class' => 'btn-action']); ?>
                                </div>
                            <?php endif; ?>
                        </article>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>
</div>
