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
use open20\amos\documenti\rules\acl\AclSharedWithMeWidgetRule;
use open20\amos\documenti\widgets\icons\WidgetIconDocumentiAclAdmin;
use open20\amos\documenti\widgets\icons\WidgetIconDocumentiAclDashboard;
use open20\amos\documenti\widgets\icons\WidgetIconDocumentiAclSharedWithMe;
use open20\amos\documenti\widgets\icons\WidgetIconDocumentiAclSharedWithMeAdmin;
use yii\rbac\Permission;

/**
 * Class m220316_191353_update_cl_documenti_acl_new_widgets_permissions
 */
class m220316_191353_update_cl_documenti_acl_new_widgets_permissions extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        $prefixStr = 'Permissions for the dashboard for the widget ';
        return [
            [
                'name' => WidgetIconDocumentiAclDashboard::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => $prefixStr . 'WidgetIconDocumentiAclDashboard',
                'parent' => ['DOCUMENTI_ACL_ADMINISTRATOR']
            ],
            [
                'name' => WidgetIconDocumentiAclAdmin::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => $prefixStr . 'WidgetIconDocumentiAclAdmin',
                'parent' => ['DOCUMENTI_ACL_ADMINISTRATOR']
            ],
            [
                'name' => WidgetIconDocumentiAclSharedWithMeAdmin::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => $prefixStr . 'WidgetIconDocumentiAclSharedWithMeAdmin',
                'parent' => ['DOCUMENTI_ACL_ADMINISTRATOR']
            ],
            [
                'name' => AclSharedWithMeWidgetRule::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Regola per il widget shared with me base',
                'ruleName' => AclSharedWithMeWidgetRule::className(),
                'parent' => ['DOCUMENTI_ACL_VIEWER'],
                'children' => [WidgetIconDocumentiAclSharedWithMe::className()]
            ],
            [
                'name' => WidgetIconDocumentiAclSharedWithMe::className(),
                'update' => true,
                'newValues' => [
                    'removeParents' => ['DOCUMENTI_ACL_VIEWER', 'DOCUMENTI_ACL_ADMINISTRATOR']
                ]
            ]
        ];
    }
}
