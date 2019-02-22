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
use lispa\amos\documenti\models\Documenti;

/**
 * Class m171114_133632_documents_remove_workflow_active_lettore
 */
class m171114_133632_documents_remove_workflow_active_lettore extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => Documenti::DOCUMENTI_WORKFLOW_STATUS_VALIDATO,
                'update' => true,
                'newValues' => [
                    'removeParents' => ['LETTORE_DOCUMENTI']
                ]
            ]
        ];
    }
}
