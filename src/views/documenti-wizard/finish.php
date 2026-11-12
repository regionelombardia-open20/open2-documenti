<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open20\amos\documenti\views\documenti-wizard
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

use open20\amos\core\forms\WizardPrevAndContinueButtonWidget;
use open20\amos\documenti\AmosDocumenti;

/**
 * @var yii\web\View $this
 * @var open20\amos\documenti\models\Documenti $model
 * @var string $finishMessage
 */

$this->title = $model;

?>

<div class="row m-b-30">
    <div class="col-xs-12">
        <div class="pull-left">
            <!-- ?= AmosIcons::show('file-text-o', ['class' => 'am-4 icon-calendar-intro m-r-15'], 'dash') ?-->
            <div class="like-widget-img color-primary ">
                <?= \open20\amos\core\icons\AmosIcons::show('file-text-o', [], 'dash'); ?>
            </div>
        </div>
        <div class="text-wrapper">
            <h3><?= $finishMessage ?></h3>
            <h4><?= AmosDocumenti::tHtml('amosdocumenti', '#BACK_TO_DOC_STR') ?></h4>
        </div>
    </div>
</div>


<?= WizardPrevAndContinueButtonWidget::widget([
    'model' => $model,
    'previousUrl' => Yii::$app->getUrlManager()->createUrl(['/documenti/documenti-wizard/summary', 'id' => $model->id]),
    'viewPreviousBtn' => false,
    'continueLabel' => AmosDocumenti::tHtml('amosdocumenti', '#BACK_TO_DOC_BTN'),
    'finishUrl' => Yii::$app->session->get(AmosDocumenti::beginCreateNewSessionKey())
]) ?>
