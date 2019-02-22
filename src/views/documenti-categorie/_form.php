<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\documenti\views\documenti-categorie
 * @category   CategoryName
 */

use lispa\amos\core\forms\ActiveForm;
use lispa\amos\core\forms\CloseSaveButtonWidget;
use lispa\amos\core\forms\CreatedUpdatedWidget;
use lispa\amos\documenti\AmosDocumenti;
use lispa\amos\core\helpers\Html;

/**
 * @var yii\web\View $this
 * @var lispa\amos\documenti\models\DocumentiCategorie $model
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

    <div class="row">
        <div class="col-xs-12"><?= Html::tag('h2', AmosDocumenti::t('amosdocumenti', '#settings_general_title'), ['class' => 'subtitle-form']) ?></div>
        <div class="col-lg-8 col-xs-12">
            <?= $form->field($model, 'titolo')->textInput(['maxlength' => true, 'placeholder' => AmosDocumenti::t('amosdocumenti', '#category_title_placeholder')]) ?>
            <?= $form->field($model, 'sottotitolo')->textInput(['maxlength' => true, 'placeholder' => AmosDocumenti::t('amosdocumenti', '#category_subtitle_placeholder')]) ?>
            <?= $form->field($model, 'descrizione_breve')->textInput(['maxlength' => true, 'placeholder' => AmosDocumenti::t('amosdocumenti', '#category_abstract_placeholder')]) ?>
            <?= $form->field($model, 'descrizione')->textarea(['rows' => 6,'placeholder' => AmosDocumenti::t('amosdocumenti', '#category_text_placeholder')]) ?>
        </div>
        <div class="col-lg-4 col-xs-12">
            <?= $form->field($model,
                'documentCategoryImage')->widget(\lispa\amos\attachments\components\AttachmentsInput::classname(), [
                'options' => [
                    'multiple' => FALSE,
                    'accept' => "image/*",
                ],
                'pluginOptions' => [ // Plugin options of the Kartik's FileInput widget
                    'maxFileCount' => 1,
                    'showRemove' => false,
                    'indicatorNew' => false,
                    'allowedPreviewTypes' => ['image'],
                    'previewFileIconSettings' => false,
                    'overwriteInitial' => false,
                    'layoutTemplates' => false
                ]
            ])->label(AmosDocumenti::t('amosdocumenti', '#category_image_field'))->hint(AmosDocumenti::t('amosdocumenti', '#category_image_field_hint')) ?>
        </div>
    </div>
    <div class="clearfix"></div>

    <div class="col-xs-12"><?= CreatedUpdatedWidget::widget(['model' => $model]) ?></div>
    <?= CloseSaveButtonWidget::widget(['model' => $model]); ?>
    <?php ActiveForm::end(); ?>
</div>
