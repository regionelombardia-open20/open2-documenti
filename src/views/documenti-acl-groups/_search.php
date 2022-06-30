<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti\views\documenti-acl-groups
 * @category   CategoryName
 */

use open20\amos\core\helpers\Html;
use open20\amos\documenti\AmosDocumenti;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var open20\amos\documenti\models\search\DocumentiAclGroupsSearch $model
 * @var yii\widgets\ActiveForm $form
 */

?>
<div class="documenti-acl-groups-search element-to-toggle" data-toggle-element="form-search">
    
    <?php $form = ActiveForm::begin([
        'action' => (isset($originAction) ? [$originAction] : ['index']),
        'method' => 'get',
        'options' => [
            'class' => 'default-form'
        ]
    ]);
    ?>
    
    <?= Html::hiddenInput("enableSearch", "1") ?>

    <div class="col-xs-12">
        <h2 class="title">
            <?= AmosDocumenti::t('amosdocumenti', 'Search'); ?>:
        </h2>
    </div>

    <div class="col-xs-12">
        <?= $form->field($model, 'name')->textInput(['placeholder' => AmosDocumenti::t('amosdocumenti', 'Search by group name')]) ?>
    </div>
<!--    <div class="col-md-4">-->
<!--        < ?= $form->field($model, 'update_folder_content')->checkbox(['placeholder' => AmosDocumenti::t('amosdocumenti', 'Search by group description')]) ?>-->
<!--    </div>-->
<!---->
<!--    <div class="col-md-4">-->
<!--        < ?= $form->field($model, 'upload_folder_files')->checkbox(['placeholder' => AmosDocumenti::t('amosdocumenti', 'Search by group description')]) ?>-->
<!--    </div>-->
<!---->
<!--    <div class="col-md-4">-->
<!--        < ?= $form->field($model, 'read_folder_files')->checkbox(['placeholder' => AmosDocumenti::t('amosdocumenti', 'Search by group description')]) ?>-->
<!--    </div>-->

    <div class="col-xs-12">
        <div class="pull-right">
            <?= Html::resetButton(Yii::t('amoscore', 'Reset'), ['class' => 'btn btn-secondary']) ?>
            <?= Html::submitButton(Yii::t('amoscore', 'Search'), ['class' => 'btn btn-navigation-primary']) ?>
        </div>
    </div>

    <div class="clearfix"></div>
    
    <?php ActiveForm::end(); ?>
</div>
