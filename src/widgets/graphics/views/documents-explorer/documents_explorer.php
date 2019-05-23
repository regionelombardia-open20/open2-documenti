<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\documenti\widgets\graphics\views
 * @category   CategoryName
 */

/**
 * @var View $this
 */

use lispa\amos\core\forms\WidgetGraphicsActions;
use lispa\amos\core\icons\AmosIcons;
use lispa\amos\documenti\AmosDocumenti;
use lispa\amos\documenti\models\Documenti;
use lispa\amos\documenti\widgets\graphics\WidgetGraphicsUltimeDocumenti;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\Pjax;
use lispa\amos\documenti\assets\ModuleDocumentiAsset;

\lispa\amos\documenti\assets\ModuleDocumentiDocumentsExplorerAsset::register($this);
$moduleDocumenti = \Yii::$app->getModule(AmosDocumenti::getModuleName());
/* \pcd20\import\assets\SpinnerWaitAsset::register($this); //TODO remove this asset and files */
$moduleL = \Yii::$app->getModule('layout');
if (!empty($moduleL)) {
    \lispa\amos\layout\assets\SpinnerWaitAsset::register($this);
} else {
    \lispa\amos\core\views\assets\SpinnerWaitAsset::register($this);
}

// Importing explorer html parts
echo $this->render('parts/navbar');
echo $this->render('parts/breadcrumb');
echo $this->render('parts/folders');
echo $this->render('parts/files');
echo $this->render('parts/modals/new-folder-modal');
echo $this->render('parts/modals/delete-file-modal');
echo $this->render('parts/modals/delete-folder-modal');
echo $this->render('parts/modals/delete-area-modal');
echo $this->render('parts/modals/delete-stanza-modal');
?>
<div class="documents-explorer-item grid-item grid-item--fullwidth">
    <div class="loading" id="loader" hidden></div>
    <div class="box-widget">
        <div class="box-widget-toolbar">
            <h1 class="box-widget-title">Documenti
                <!--<?= AmosDocumenti::tHTml('amosdocumenti', 'Documenti') ?>--></h1>
        </div>
        <section>
            <div id="documents-explorer">
                <section id="content-explorer-navbar"></section>
                <div class="col-md-4 col-xs-12 documents-explorer-sidebar-container">
                    <div id="location-title" class="col-xs-12 sidebar-container-header">
                        <h2><?= Yii::t('amosdocumenti', 'Aree di condivisione'); ?></h2>
<!--                        <span id="go-back-room" class="am am-arrow-left" title="Torna indietro"> <!--TODO add class hidden if first layer-->
<!--                            <span class="sr-only">Indietro</span>-->
<!--                        </span>-->
<!--                        <h2 class=""></h2> <!--TODO change with room name-->
                    </div>
                    <div class="col-xs-12 documents-explorer-sidebar">
                        <section id="content-explorer-sidebar">
                            <div class="stanze-list" id="stanze-list">
                            </div>
                        </section>
                    </div>
                </div>
                <div class="col-md-8 col-xs-12 documents-explorer-items-container">
                    <div class="col-xs-12 items-container-header">
                        <section id="content-explorer-breadcrumb"></section>
                        <!--                        <span id="go-back-files" class="am am-arrow-left" title="Torna indietro"><span class="sr-only">Indietro</span></span>-->
                        <!--                        <h2 class="current-directory">Cartella corrente</h2> <!--TODO change with folder name if subfolder-->
                    </div>
                    <div class="col-xs-12 documents-explorer-items">
                        <section id="content-explorer-folders">
                        </section>
                        <section id="content-explorer-files">
                        </section>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>