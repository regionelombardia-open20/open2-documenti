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

use open20\amos\documenti\AmosDocumenti;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var \open20\amos\documenti\models\search\DocumentiSearch $model
 * @var yii\widgets\ActiveForm $form
 * @var array $queryParams
 */


if (!empty($queryParams['DocumentiSearch']) && $queryParams['DocumentiSearch']['orderAttribute'] && $queryParams['DocumentiSearch']['orderType']) {
    $queryParams['DocumentiSearch'] = \yii\helpers\ArrayHelper::merge($queryParams['DocumentiSearch'], [
        'orderAttribute' => $queryParams['orderAttribute'],
        'orderType' => $queryParams['orderType'],
    ]);
}

?>

<div class="documenti-order element-to-toggle" data-toggle-element="form-order">
    <div class="col-xs-12">
        <p class="h3"><?= AmosDocumenti::t('amosdocumenti', 'Ordina per') ?>:</p>
    </div>

    <?php $form = ActiveForm::begin([
        'action' => [isset($originAction) ? $originAction : Yii::$app->controller->action->id,
            'currentView' => $queryParams['currentView'],
            'parentId' => $queryParams['parentId'],
            'DocumentiSearch' => $queryParams['DocumentiSearch']
        ],
        'method' => 'get',
        'options' => [
            'class' => 'default-form'
        ]
    ]);
    echo Html::hiddenInput("currentView", Yii::$app->request->getQueryParam('currentView'));
    ?>
    
    <div class="col-sm-6 col-lg-4">
        <?= $form->field($model, 'orderAttribute')->dropDownList($model->getOrderAttributesLabels()) ?>
    </div>
    <div class="col-sm-6 col-lg-4">
        <?= $form->field($model, 'orderType')->dropDownList(
            [
                SORT_ASC => AmosDocumenti::t('amosdocumenti', 'Crescente'),
                SORT_DESC => AmosDocumenti::t('amosdocumenti', 'Decrescente'),
            ]
        )
        ?>
    </div>

    <div class="col-xs-12">
        <div class="pull-right">
            <?= Html::a(AmosDocumenti::t('amosdocumenti', 'Annulla'),
                [isset($originAction) ? $originAction : Yii::$app->controller->action->id, 'currentView' => $queryParams['currentView'], 'parentId' => $queryParams['parentId']],
                [
                    'class' => 'btn btn-secondary'
                ]
            ); ?>
            <?= Html::submitButton(AmosDocumenti::t('amosdocumenti', 'Ordina'), [
                'class' => 'btn btn-navigation-primary'
            ]); ?>
        </div>
    </div>

    <div class="clearfix"></div>
    <?php ActiveForm::end(); ?>

</div>