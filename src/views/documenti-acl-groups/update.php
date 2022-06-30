<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti\views\documenti-acl-groups
 * @category   CategoryName
 */

use open20\amos\documenti\AmosDocumenti;

/**
 * @var yii\web\View $this
 * @var open20\amos\documenti\models\DocumentiAclGroups $model
 */

$this->title = AmosDocumenti::t('amosdocumenti', 'Update group');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="documenti-acl-groups-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
