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
use open20\amos\documenti\models\Documenti;
use open20\amos\documenti\rules\acl\AclCreateFileRule;
use open20\amos\documenti\rules\acl\AclDeleteFileRule;
use open20\amos\documenti\rules\acl\AclReadFileRule;
use open20\amos\documenti\rules\acl\AclUpdateFileRule;
use yii\rbac\Permission;

/**
 * Class m211219_215043_add_documenti_acl_permissions
 */
class m211219_215043_add_documenti_acl_permissions extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => 'DOCUMENTIACL_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model DocumentiAcl',
                'parent' => ['DOCUMENTI_ACL_ADMINISTRATOR']
            ],
            [
                'name' => 'DOCUMENTIACL_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model DocumentiAcl',
                'parent' => ['DOCUMENTI_ACL_ADMINISTRATOR']
            ],
            [
                'name' => 'DOCUMENTIACL_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model DocumentiAcl',
                'parent' => ['DOCUMENTI_ACL_ADMINISTRATOR']
            ],
            [
                'name' => 'DOCUMENTIACL_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model DocumentiAcl',
                'parent' => ['DOCUMENTI_ACL_ADMINISTRATOR']
            ],
            [
                'name' => AclCreateFileRule::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Regola per permesso di CREATE sul model DocumentiAcl',
                'ruleName' => AclCreateFileRule::className(),
                'parent' => ['DOCUMENTI_ACL_VIEWER'],
                'children' => [
                    'DOCUMENTIACL_CREATE',
                    'DOCUMENTIACL_CREATE',
                    Documenti::DOCUMENTI_WORKFLOW_STATUS_BOZZA,
                    Documenti::DOCUMENTI_WORKFLOW_STATUS_DAVALIDARE,
                    Documenti::DOCUMENTI_WORKFLOW_STATUS_VALIDATO
                ]
            ],
            [
                'name' => AclReadFileRule::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Regola per permesso di READ sul model DocumentiAcl',
                'ruleName' => AclReadFileRule::className(),
                'parent' => ['DOCUMENTI_ACL_VIEWER'],
                'children' => ['DOCUMENTIACL_READ']
            ],
            [
                'name' => AclUpdateFileRule::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Regola per permesso di UPDATE sul model DocumentiAcl',
                'ruleName' => AclUpdateFileRule::className(),
                'parent' => ['DOCUMENTI_ACL_VIEWER'],
                'children' => [
                    'DOCUMENTIACL_UPDATE',
                    'DocumentValidate',
                    Documenti::DOCUMENTI_WORKFLOW_STATUS_BOZZA,
                    Documenti::DOCUMENTI_WORKFLOW_STATUS_DAVALIDARE,
                    Documenti::DOCUMENTI_WORKFLOW_STATUS_VALIDATO
                ]
            ],
            [
                'name' => AclDeleteFileRule::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Regola per permesso di DELETE sul model DocumentiAcl',
                'ruleName' => AclDeleteFileRule::className(),
                'parent' => ['DOCUMENTI_ACL_VIEWER'],
                'children' => ['DOCUMENTIACL_DELETE']
            ],
        ];
    }
}
