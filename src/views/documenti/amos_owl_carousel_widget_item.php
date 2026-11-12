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

use open20\amos\core\forms\ItemAndCardHeaderWidget;
use open20\amos\core\icons\AmosIcons;
use open20\amos\documenti\AmosDocumenti;
use open20\amos\documenti\utility\DocumentsUtility;
use yii\helpers\Html;

/**
 * @var \open20\amos\documenti\models\Documenti $model
 * @var \open20\amos\documenti\widgets\DocumentsOwlCarouselWidget $widget
 */

$documentIcon = DocumentsUtility::getDocumentIcon($model);
$titleUrl = [Yii::$app->controller->action->id, 'parentId' => $model->id];
$iconUrl = $titleUrl;
if (!$model->is_folder) {
    $titleUrl = $model->getFullViewUrl();
    $iconUrl = $model->getDocumentMainFileUrl();
}

?>

<div class="owl-item-content">
    <div class="col-xs-3 col-md-3 nop">
        <span class="date"><?= AmosDocumenti::t('amosdocumenti', '#carousel_update_at') ?></span>
        <span class="date"><?= \Yii::$app->formatter->asDate($model->data_pubblicazione) ?></span>
        <div>
            <?= Html::a($documentIcon, $iconUrl, [
                'title' => AmosDocumenti::t('amosdocumenti', '#carousel_download'),
                'class' => ($model->is_folder) ? 'is-folder' : 'is-file'
            ]); ?>
            <?php if (!$model->is_folder): ?>
                <?= Html::a(AmosDocumenti::t('amosdocumenti', '#carousel_download'), $iconUrl, [
                    'title' => AmosDocumenti::t('amosdocumenti', '#carousel_download'),
                    'class' => 'download-file',
                ]); ?>
            <?php endif; ?>
        </div>
    </div>
    <div class="col-xs-9 col-md-9 nop">
        <?= ItemAndCardHeaderWidget::widget([
            'model' => $model,
            'publicationDateNotPresent' => true,
            'showPrevalentPartnershipAndTargets' => true,
            'truncateLongWords' => true,
        ]) ?>
        <div class="col-xs-12 nop title">
            <?= Html::a($model->titolo, $titleUrl); ?>
        </div>
        <?php if (!is_null($model->parent)): ?>
            <div class="col-xs-12 nop directory">
                <?= AmosDocumenti::t('amosdocumenti', 'in') . ' "' . $model->parent->titolo . '"'; ?>
            </div>
        <?php endif; ?>
        <div class="col-xs-12 nop read-more">
            <?= Html::a(AmosDocumenti::t('amosdocumenti', '#carousel_details') . AmosIcons::show('chevron-right'), $titleUrl); ?>
        </div>
    </div>
</div>
