<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationPermissions;
use open20\amos\documenti\widgets\icons\WidgetIconDocumentiAclSharedWithMe;
use yii\rbac\Permission;

/**
 * Class m211216_222938_add_documenti_acl_widget_shared_with_me_permission
 */
class m211216_222938_add_documenti_acl_widget_shared_with_me_permission extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        $prefixStr = 'Permissions for the dashboard for the widget ';
        return [
            [
                'name' => WidgetIconDocumentiAclSharedWithMe::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => $prefixStr . 'WidgetIconDocumentiAclSharedWithMe',
                'parent' => ['DOCUMENTI_ACL_ADMINISTRATOR']
            ]
        ];
    }
}
