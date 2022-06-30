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
use open20\amos\documenti\widgets\icons\WidgetIconDocumentiAclGroups;
use yii\helpers\ArrayHelper;
use yii\rbac\Permission;

/**
 * Class m211130_113153_add_documenti_acl_groups_permissions
 */
class m211130_113153_add_documenti_acl_groups_permissions extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return ArrayHelper::merge(
            $this->setPluginRoles(),
            $this->setModelPermissions(),
            $this->setWidgetsPermissions()
        );
    }
    
    private function setPluginRoles()
    {
        return [
            [
                'name' => 'DOCUMENTI_ACL_ADMINISTRATOR',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Role to manage ACL documents',
                'parent' => ['AMMINISTRATORE_DOCUMENTI']
            ]
        ];
    }
    
    private function setModelPermissions()
    {
        return [
            [
                'name' => 'DOCUMENTIACLGROUPS_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model DocumentiAclGroups',
                'parent' => ['DOCUMENTI_ACL_ADMINISTRATOR']
            ],
            [
                'name' => 'DOCUMENTIACLGROUPS_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model DocumentiAclGroups',
                'parent' => ['DOCUMENTI_ACL_ADMINISTRATOR']
            ],
            [
                'name' => 'DOCUMENTIACLGROUPS_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model DocumentiAclGroups',
                'parent' => ['DOCUMENTI_ACL_ADMINISTRATOR']
            ],
            [
                'name' => 'DOCUMENTIACLGROUPS_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model DocumentiAclGroups',
                'parent' => ['DOCUMENTI_ACL_ADMINISTRATOR']
            ],
        ];
    }
    
    private function setWidgetsPermissions()
    {
        $prefixStr = 'Permissions for the dashboard for the widget ';
        return [
            [
                'name' => WidgetIconDocumentiAclGroups::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => $prefixStr . 'WidgetIconDocumentiAclGroups',
                'parent' => ['DOCUMENTI_ACL_ADMINISTRATOR']
            ]
        ];
    }
}
