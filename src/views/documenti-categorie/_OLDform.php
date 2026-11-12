<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open20\amos\documenti\views\documenti-categorie
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
use open20\amos\core\forms\CloseSaveButtonWidget;
use open20\amos\core\forms\CreatedUpdatedWidget;
use open20\amos\documenti\AmosDocumenti;
use yii\bootstrap\Tabs;

/**
 * @var yii\web\View $this
 * @var open20\amos\documenti\models\DocumentiCategorie $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="documenti-categorie-form col-xs-12">
    <?php
    $form = ActiveForm::begin([
        'options' => ['enctype' => 'multipart/form-data'] // important
    ]);
    
    $customView = Yii::$app->getViewPath() . '/imageField.php';
    ?>
    
    <?php $this->beginBlock('dettagli'); ?>
    <div class="row">
        <div class="col-lg-8 col-sm-8">
            
            <?= $form->field($model, 'titolo')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'sottotitolo')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-lg-4 col-sm-4">
            <?= $form->field($model,
                'documentCategoryImage')->widget(\open20\amos\attachments\components\AttachmentsInput::classname(), [
                'options' => [
                    'multiple' => FALSE,
                    'accept' => "image/*",
                ],
                'pluginOptions' => [ // Plugin options of the Kartik's FileInput widget
                    'maxFileCount' => 1,
                    'showRemove' => false,// Client max files,
                    'indicatorNew' => false,
                    'allowedPreviewTypes' => ['image'],
                    'previewFileIconSettings' => false,
                    'overwriteInitial' => false,
                    'layoutTemplates' => false
                ]
            ])->label(AmosDocumenti::t('amosdocumenti', 'Immagine categoria')) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-sm-12">
            <?= $form->field($model, 'descrizione_breve')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 col-sm-12">
            <?= $form->field($model, 'descrizione')->textarea(['rows' => 6]) ?>
        </div>
    </div>
    <div class="clearfix"></div>
    <?php $this->endBlock('dettagli'); ?>
    
    <?php
    $itemsTab[] = [
        'label' => AmosDocumenti::tHtml('amosdocumenti', 'Dettagli '),
        'content' => $this->blocks['dettagli'],
    ];
    ?>
    
    <?=
    Tabs::widget(
        [
            'encodeLabels' => false,
            'items' => $itemsTab
        ]
    );
    ?>
    <?= CreatedUpdatedWidget::widget(['model' => $model]) ?>
    <?= CloseSaveButtonWidget::widget(['model' => $model]); ?>
    <?php ActiveForm::end(); ?>
</div>
