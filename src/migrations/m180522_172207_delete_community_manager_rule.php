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
 * Class m170914_135007_add_validatore_news_to_validator_role
 */
class m180522_172207_delete_community_manager_rule extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => \lispa\amos\documenti\rules\DeleteCommunityManagerDocumentiRule::className(),
                'type' => \yii\rbac\Permission::TYPE_PERMISSION,
                'description' => 'Regola per cancellare un documento se sei CM',
                'ruleName' => \lispa\amos\documenti\rules\DeleteCommunityManagerDocumentiRule::className(),
                'parent' => ['CREATORE_DOCUMENTI'],
                'children' => ['DOCUMENTI_DELETE']
            ]
        ];
    }
}
