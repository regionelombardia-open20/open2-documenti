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
use yii\helpers\ArrayHelper;
use yii\rbac\Permission;

/**
 * Class m211219_183838_add_documenti_acl_viewer_role
 */
class m211219_183838_add_documenti_acl_viewer_role extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return ArrayHelper::merge(
            $this->setPluginRoles(),
            $this->setWidgetsPermissions()
        );
    }
    
    private function setPluginRoles()
    {
        return [
            [
                'name' => 'DOCUMENTI_ACL_VIEWER',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Role to view ACL documents',
                'parent' => ['DOCUMENTI_ACL_ADMINISTRATOR']
            ]
        ];
    }
    
    private function setWidgetsPermissions()
    {
        $prefixStr = 'Permissions for the dashboard for the widget ';
        return [
            [
                'name' => WidgetIconDocumentiAclSharedWithMe::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => $prefixStr . 'WidgetIconDocumentiAclSharedWithMe',
                'parent' => ['DOCUMENTI_ACL_VIEWER']
            ]
        ];
    }
}
