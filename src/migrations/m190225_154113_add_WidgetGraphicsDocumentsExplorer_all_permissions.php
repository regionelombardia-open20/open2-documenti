<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\documenti
 * @category   CategoryName
 */

use lispa\amos\core\migration\AmosMigrationPermissions;
use lispa\amos\documenti\rules\DeleteOwnDocumentiRule;
use lispa\amos\documenti\rules\DeleteFacilitatorOwnDocumentiRule;
use lispa\amos\documenti\rules\UpdateFacilitatorOwnDocumentiRule;
use lispa\amos\documenti\rules\UpdateOwnDocumentiRule;
use lispa\amos\documenti\models\Documenti;
use yii\rbac\Permission;

class m190225_154113_add_WidgetGraphicsDocumentsExplorer_all_permissions extends AmosMigrationPermissions
{
    /**
     * Use this function to map permissions, roles and associations between permissions and roles. If you don't need to
     * to add or remove any permissions or roles you have to delete this method.
     */
    protected function setAuthorizations()
    {
        $this->authorizations = [
            [
                'name' => lispa\amos\documenti\widgets\graphics\WidgetGraphicsDocumentsExplorer::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso per il widget WidgetGraphicsDocumentsExplorer',
                'ruleName' => null,
                'parent' => ['ADMIN', 'BASIC_USER']
            ],
        ];
    }
}
