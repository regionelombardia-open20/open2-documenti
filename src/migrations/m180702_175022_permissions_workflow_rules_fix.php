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
 * Class m170719_122922_permissions_community
 */
class m180702_175022_permissions_workflow_rules_fix extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => 'DocumentiWorkflow/DAVALIDARE',
                'update' => true,
                'newValues' => [
                    'removeParents' => [
                        \lispa\amos\news\rules\workflow\NewsToValidateWorkflowRule::className()
                    ],
                    'addParents' => [
                        \lispa\amos\documenti\rules\workflow\DocumentiToValidateWorkflowRule::className()
                    ],
                ],
            ],

        ];
    }
}
