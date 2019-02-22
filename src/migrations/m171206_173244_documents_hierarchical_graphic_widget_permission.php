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
use lispa\amos\documenti\widgets\graphics\WidgetGraphicsHierarchicalDocuments;
use yii\rbac\Permission;

/**
 * Class m171206_173244_documents_hierarchical_graphic_widget_permission
 */
class m171206_173244_documents_hierarchical_graphic_widget_permission extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        $prefixStr = 'Permissions for the dashboard for the widget ';
        return [
            [
                'name' => WidgetGraphicsHierarchicalDocuments::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => $prefixStr . 'WidgetGraphicsHierarchicalDocuments',
                'parent' => ['LETTORE_DOCUMENTI']
            ]
        ];
    }
}
