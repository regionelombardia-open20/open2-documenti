<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti\views\documenti-acl\email
 * @category   CategoryName
 */

use open20\amos\core\helpers\Html;
use open20\amos\documenti\AmosDocumenti;

/**
 * @var yii\web\View $this
 * @var open20\amos\core\user\User $mmUser
 * @var array $allowedPermissions
 * @var open20\amos\documenti\models\DocumentiAcl $folder
 * @var open20\amos\documenti\models\DocumentiAclGroups $group
 */

?>
<div>
    <?= AmosDocumenti::txt('#notify_acl_hi', ['nomeCognome' => $mmUser->userProfile->nomeCognome]) ?>,
</div>
<div style="font-weight: normal">
    <p><?= AmosDocumenti::txt('#notify_acl_group_new_permissions_text0', [
            'folderName' => Html::tag('strong', $folder->getTitle()),
            'groupName' => Html::tag('strong', $group->name)
        ]) ?>:</p>
    <?= $allowedPermissions; ?>
    <p style="text-align: center">
        <a href="<?= $folder->getFolderUrl(true) ?>"><strong><?= AmosDocumenti::txt('#notify_acl_text_go_to_folder') ?></strong></a>
    </p>
</div>
