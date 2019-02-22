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

/**
 * Class m180913_134451_add_document_read_rule
 */
class m180913_134451_add_document_read_rule extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => 'DocumentRead',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permission to read a Document',
                'ruleName' => \lispa\amos\core\rules\ReadContentRule::className(),
                'parent' => ['AMMINISTRATORE_DOCUMENTI', 'CREATORE_DOCUMENTI', 'VALIDATORE_DOCUMENTI', 'LETTORE_DOCUMENTI', 'FACILITATORE_DOCUMENTI']
            ],
            [
                'name' => 'DOCUMENTI_READ',
                'type' => Permission::TYPE_PERMISSION,
                'update' => true,
                'newValues' => [
                    'removeParents' =>  ['AMMINISTRATORE_DOCUMENTI', 'CREATORE_DOCUMENTI', 'VALIDATORE_DOCUMENTI', 'LETTORE_DOCUMENTI', 'FACILITATORE_DOCUMENTI'],
                    'addParents' => ['DocumentRead']
                ]
            ],
        ];
    }
}
