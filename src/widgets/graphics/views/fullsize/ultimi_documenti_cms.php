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

use open20\amos\documenti\AmosDocumenti;
use open20\amos\documenti\assets\ModuleDocumentiAsset;
use open20\amos\documenti\widgets\graphics\WidgetGraphicsUltimiDocumenti;
use yii\data\ActiveDataProvider;
use yii\web\View;

/**
 * @var View $this
 * @var ActiveDataProvider $listaDocumenti
 * @var WidgetGraphicsUltimiDocumenti $widget
 * @var string $toRefreshSectionId
 */


/** @var AmosDocumenti $moduleDocumenti */
$moduleDocumenti  = \Yii::$app->getModule(AmosDocumenti::getModuleName());
$listaModels = $listaDocumenti->getModels();
ModuleDocumentiAsset::register($this);

?>
<?php
$modelLabel = 'documenti';

$urlLinkAll = '/documenti/documenti/all-documents';
$labelLinkAll = AmosDocumenti::t('amosdocumenti', 'Tutti i documenti');
$titleLinkAll = AmosDocumenti::t('amosdocumenti', 'Visualizza la lista dei documenti');

$labelCreate = AmosDocumenti::t('amosdocumenti', 'Nuovo');
$titleCreate = AmosDocumenti::t('amosdocumenti', 'Crea un nuovo documento');
$labelManage = AmosDocumenti::t('amosdocumenti', 'Gestisci');
$titleManage = AmosDocumenti::t('amosdocumenti', 'Gestisci i documenti');
$urlCreate = '/documenti/documenti/create';

$manageLinks = [];
$controller = \open20\amos\documenti\controllers\DocumentiController::class;
if (method_exists($controller, 'getManageLinks')) {
    $manageLinks = $controller::getManageLinks();
}


$moduleCwh = \Yii::$app->getModule('cwh');
if (isset($moduleCwh) && !empty($moduleCwh->getCwhScope())) {
    $scope = $moduleCwh->getCwhScope();
    $isSetScope = (!empty($scope)) ? true : false;
}

?>
<div class="widget-graphic-cms-bi-less card-<?= $modelLabel ?> container">
    <div class="page-header">
        <?= $this->render(
            "@vendor/open20/amos-layout/src/views/layouts/fullsize/parts/bi-less-plugin-header",
            [
                'isGuest' => \Yii::$app->user->isGuest,
                'isSetScope' => $isSetScope,
                'modelLabel' => 'documenti',
                'titleSection' => $widget->getLabel(),
                'urlLinkAll' => $urlLinkAll,
                'labelLinkAll' => $labelLinkAll,
                'titleLinkAll' => $titleLinkAll,
                'labelCreate' => $labelCreate,
                'titleCreate' => $titleCreate,
                'labelManage' => $labelManage,
                'titleManage' => $titleManage,
                'hideCreate' => $moduleDocumenti->hideNewButtonInWGCmsUltimiDocumenti,
                'urlCreate' => $urlCreate,
                'manageLinks' => $manageLinks,
                'canCreate' => \Yii::$app->user->can('DOCUMENTI_CREATE', ['model' => AmosDocumenti::instance()->createModel('Documenti')]),
            ]
        );
        ?>
    </div>

    <?php
    $moduleCommunity = (\Yii::$app->getModule('community'));
    if ($moduleCommunity && $moduleCommunity->enableNewCommunityHeader) {
        echo \open20\amos\core\views\IconView::widget([
            'dataProvider' => $listaDocumenti,
            'itemView' => '@vendor/open20/amos-documenti/src/views/documenti/_icon',
            'itemOptions' => [
                'role' => 'option',
                'class' => 'card-icon-wrapper',
            ],
        ]);
    } else {

    ?>
        <div class="list-view">
            <div>
                <div class="" data-role="list-view">
                    <?php foreach ($listaModels as $singolodocumento) : ?>
                        <div>
                            <?php if ($singolodocumento->is_folder) { ?>
                                <?= $this->render("@vendor/open20/amos-documenti/src/views/documenti/_item_folder", ['model' => $singolodocumento]); ?>
                            <?php } else { ?>
                                <?= $this->render("@vendor/open20/amos-documenti/src/views/documenti/_icon", ['model' => $singolodocumento]); ?>
                            <?php } ?>

                        </div>
                    <?php endforeach ?>
                </div>
            </div>
        </div>
    <?php } ?>
</div>