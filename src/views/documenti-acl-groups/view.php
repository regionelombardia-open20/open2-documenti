<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti\views\documenti-acl-groups
 * @category   CategoryName
 */

use open20\amos\core\forms\CloseButtonWidget;
use open20\amos\core\forms\ContextMenuWidget;
use open20\amos\documenti\widgets\DocumentsAclGroupUsersWidget;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/**
 * @var yii\web\View $this
 * @var open20\amos\documenti\models\DocumentiAclGroups $model
 */

$this->title = $model->name;
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="documenti-acl-groups-view">
    <div class="row">
        <div class="col-xs-12 m-b-10 m-t-10">
            <?= ContextMenuWidget::widget([
                'model' => $model,
                'actionModify' => $model->getFullUpdateUrl(),
                'actionDelete' => $model->getFullDeleteUrl()
            ]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'name',
                    // 'update_folder_content:boolean',
                    // 'upload_folder_files:boolean',
                    // 'read_folder_files:boolean',
                ],
            ]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <?= DocumentsAclGroupUsersWidget::widget([
                'model' => $model,
                'isUpdate' => false,
            ]); ?>
        </div>
    </div>
    <?= CloseButtonWidget::widget(); ?>
</div>

<div id="form-actions" class="bk-btnFormContainer pull-right">
    <?= Html::a(Yii::t('amoscore', 'Chiudi'), Url::previous(), ['class' => 'btn btn-secondary']); ?></div>
