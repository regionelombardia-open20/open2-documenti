<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti\views\documenti-acl-groups
 * @category   CategoryName
 */

use open20\amos\core\forms\ActiveForm;
use open20\amos\core\forms\CloseSaveButtonWidget;
use open20\amos\core\forms\CreatedUpdatedWidget;
use open20\amos\core\forms\RequiredFieldsTipWidget;
use open20\amos\documenti\AmosDocumenti;
use open20\amos\documenti\widgets\DocumentsAclGroupUsersWidget;
use kartik\alert\Alert;

/**
 * @var yii\web\View $this
 * @var open20\amos\documenti\models\DocumentiAclGroups $model
 * @var yii\widgets\ActiveForm $form
 */

?>
<div class="documenti-acl-groups-form col-xs-12 nop">
    <?php
    $form = ActiveForm::begin([
        'options' => [
            'id' => 'documenti-acl-groups-form-id',
        ]
    ]);
    ?>

    <div class="row">
        <div class="col-xs-12">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
<!--    <div class="row">-->
<!--        <div class="col-md-3 col-xs-12">-->
<!--            < ?= $form->field($model, 'update_folder_content')->checkbox()->hint("L'utente può vedere e modificare tutto il contenuto della cartella") ?>-->
<!--        </div>-->
<!--    </div>-->
<!--    <div class="row">-->
<!--        <div class="col-md-3 col-xs-12">-->
<!--            < ?= $form->field($model, 'upload_folder_files')->checkbox()->hint("L'utente può vedere e modificare solo i file creati da lui nella cartella") ?>-->
<!--        </div>-->
<!--    </div>-->
<!--    <div class="row">-->
<!--        <div class="col-md-3 col-xs-12">-->
<!--            < ?= $form->field($model, 'read_folder_files')->checkbox()->hint("L'utente può vedere tutto il contenuto della cartella") ?>-->
<!--        </div>-->
<!--    </div>-->
    <div class="row">
        <?php if ($model->isNewRecord): ?>
            <div class="col-xs-12">
                <?= Alert::widget([
                    'type' => Alert::TYPE_WARNING,
                    'body' => AmosDocumenti::t('amosdocumenti', '#alert_add_users_to_group'),
                    'closeButton' => false
                ]); ?>
            </div>
        <?php else: ?>
            <?= DocumentsAclGroupUsersWidget::widget([
                'model' => $model,
                'isUpdate' => true
            ]); ?>
        <?php endif; ?>
    </div>
</div>

<?= RequiredFieldsTipWidget::widget() ?>
<?= CreatedUpdatedWidget::widget(['model' => $model]) ?>
<?= CloseSaveButtonWidget::widget([
    'model' => $model
]); ?>
<?php ActiveForm::end(); ?>
</div>
