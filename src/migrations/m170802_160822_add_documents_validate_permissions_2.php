<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\documenti\migrations
 * @category   CategoryName
 */

use lispa\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;
use lispa\amos\documenti\models\Documenti;

class m170802_160822_add_documents_validate_permissions_2 extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => 'DocumentValidateOnDomain',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permission to validate at least one document in a domain with cwh permission',
                'ruleName' => \lispa\amos\core\rules\UserValidatorContentRule::className(),
                'parent' => ['VALIDATORE_DOCUMENTI', 'VALIDATED_BASIC_USER']
            ],
            [
                'name' => 'DocumentValidate',
                'update' => true,
                'newValues' => [
                    'addParents' => ['VALIDATED_BASIC_USER']
                ]
            ],
            [
                'name' => lispa\amos\documenti\widgets\icons\WidgetIconDocumentiDaValidare::className(),
                'update' => true,
                'newValues' => [
                    'addParents' => ['DocumentValidateOnDomain']
                ]
            ],
            [
                'name' => Documenti::DOCUMENTI_WORKFLOW_STATUS_BOZZA,
                'update' => true,
                'newValues' => [
                    'addParents' => ['DocumentValidate']
                ]
            ],
            [
                'name' => Documenti::DOCUMENTI_WORKFLOW_STATUS_DAVALIDARE,
                'update' => true,
                'newValues' => [
                    'addParents' => ['DocumentValidate']
                ]
            ],
            [
                'name' => Documenti::DOCUMENTI_WORKFLOW_STATUS_VALIDATO,
                'update' => true,
                'newValues' => [
                    'addParents' => ['DocumentValidate']
                ]
            ]
        ];
    }
}
