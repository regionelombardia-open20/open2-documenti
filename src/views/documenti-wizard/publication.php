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

use open20\amos\core\forms\ActiveForm;
use open20\amos\core\forms\WizardPrevAndContinueButtonWidget;
use open20\amos\documenti\AmosDocumenti;
use yii\base\Widget;

/**
 * @var yii\web\View $this
 * @var open20\amos\documenti\models\Documenti $model
 * @var yii\widgets\ActiveForm $form
 */

$this->title = AmosDocumenti::t('amosdocumenti', '#documents_wizard_page_title');

?>

<div class="document-wizard-publication col-xs-12 nop">
    <?php $form = ActiveForm::begin([
        'options' => [
            'id' => 'document-wizard-form',
            'class' => 'form',
            'enableClientValidation' => true,
            'errorSummaryCssClass' => 'error-summary alert alert-error'
        ]
    ]); ?>
    
    <?php if (count(\yii\base\Widget::$stack) && isset($model)): ?>
        <div class="row">
            <div class="col-xs-12">
                <?php $moduleCwh = Yii::$app->getModule('cwh'); ?>
                <?php if (isset($moduleCwh) && in_array(get_class($model), $moduleCwh->modelsEnabled) && $moduleCwh->behaviors): ?>
                    <?php /**@var \open20\amos\cwh\AmosCwh $moduleCwh */ ?>
                    <?= Yii::$app->controller->renderFile('@vendor/open20/amos-cwh/src/views/pubblicazione/cwh.php', [
                        'model' => $model,
                        'form' => Widget::$stack[0]
                    ]); ?>
                <?php endif; ?>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <?php $moduleTag = Yii::$app->getModule('tag'); ?>
                <?php if (isset($moduleTag) && in_array(get_class($model), $moduleTag->modelsEnabled) && $moduleTag->behaviors): ?>
                    <?php /**@var \open20\amos\tag\AmosTag $moduleTag */ ?>
                    <?= \open20\amos\tag\widgets\TagWidget::widget([
                        'model' => $model,
                        'attribute' => 'tagValues',
                        'form' => Widget::$stack[0]
                    ]); ?>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
    
    <?= WizardPrevAndContinueButtonWidget::widget([
        'model' => $model,
        'previousUrl' => Yii::$app->getUrlManager()->createUrl(['/documenti/documenti-wizard/details', 'id' => $model->id]),
        'cancelUrl' => Yii::$app->session->get(AmosDocumenti::beginCreateNewSessionKey()),
        'contentAlreadyExists' => true
    ]) ?>
    <?php ActiveForm::end(); ?>
</div>
