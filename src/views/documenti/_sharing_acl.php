<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti\views\documenti
 * @category   CategoryName
 */

use open20\amos\core\helpers\Html;
use open20\amos\documenti\AmosDocumenti;
use open20\amos\documenti\models\DocumentiAclGroupsUserMm;
use kartik\alert\Alert;

/**
 * @var yii\web\View $this
 * @var open20\amos\documenti\AmosDocumenti $documentsModule
 * @var yii\widgets\ActiveForm $form
 * @var open20\amos\documenti\models\DocumentiAcl $model
 * @var bool $isUpdate
 */

/** @var DocumentiAclGroupsUserMm $emptyGroupMmModel */
$emptyGroupMmModel = $documentsModule->createModel('DocumentiAclGroupsUserMm');

?>

<?= Html::tag('h2', AmosDocumenti::t('amosdocumenti', '#sharing') . ' ' . strtolower($model->getGrammar()->getModelSingularLabel()), ['class' => 'subtitle-form']) ?>

<?php if (!$model->isNewRecord): ?>
    <?= $this->render('_sharing_acl_groups', [
        'documentsModule' => $documentsModule,
        'form' => $form,
        'model' => $model,
        'emptyGroupMmModel' => $emptyGroupMmModel,
        'isUpdate' => $isUpdate,
    ]) ?>
    
    <?= $this->render('_sharing_acl_users', [
        'documentsModule' => $documentsModule,
        'form' => $form,
        'model' => $model,
        'emptyGroupMmModel' => $emptyGroupMmModel,
        'isUpdate' => $isUpdate,
    ]) ?>
<?php else: ?>
    <?= Alert::widget([
        'type' => Alert::TYPE_WARNING,
        'body' => AmosDocumenti::t('amosdocumenti', '#alert_sharing_acl'),
        'closeButton' => false
    ]); ?>
<?php endif; ?>
