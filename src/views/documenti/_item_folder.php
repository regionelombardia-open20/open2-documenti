<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\documenti\views\documenti
 * @category   CategoryName
 */

use lispa\amos\core\forms\ContextMenuWidget;
use lispa\amos\core\helpers\Html;
use lispa\amos\core\icons\AmosIcons;
use lispa\amos\documenti\AmosDocumenti;
use lispa\amos\notificationmanager\forms\NewsWidget;

/**
 * @var yii\web\View $this
 * @var lispa\amos\documenti\models\Documenti $model
 */

$modelViewUrl = [Yii::$app->controller->action->id, 'parentId' => $model->id];

?>
<div class="listview-container folder">
    <div class="post-horizontal">
        <div class="post-content col-xs-12 nop">
            <div class="post-title col-xs-12">
                <?= AmosIcons::show('folder-open', [], 'dash'); ?>
                <?= Html::a(Html::tag('h2', htmlspecialchars($model->titolo)), $modelViewUrl, ['title' => $model->titolo]) ?>
            </div>
            <?= NewsWidget::widget([
                'model' => $model,
            ]); ?>
        </div>
        <?= ContextMenuWidget::widget([
            'model' => $model,
            'actionModify' => $model->getFullUpdateUrl(),
            'actionDelete' => $model->getFullDeleteUrl(),
            'modelValidatePermission' => 'DocumentValidate',
            'labelDeleteConfirm' => AmosDocumenti::t('amosdocumenti', '#confirm_delete_folder')
//                    'mainDivClasses' => 'col-xs-1 nop'
        ]) ?>
    </div>
</div>
