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

/**
 * Class m170717_094844_add_documents_facilitator_to_platform_facilitator
 */
class m170717_094844_add_documents_facilitator_to_platform_facilitator extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => 'FACILITATORE_DOCUMENTI',
                'update' => true,
                'newValues' => [
                    'addParents' => ['FACILITATOR']
                ]
            ],
        ];
    }
}