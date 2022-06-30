<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationWidgets;
use open20\amos\dashboard\models\AmosWidgets;
use open20\amos\documenti\widgets\icons\WidgetIconDocumentiAclAdmin;
use open20\amos\documenti\widgets\icons\WidgetIconDocumentiAclDashboard;
use open20\amos\documenti\widgets\icons\WidgetIconDocumentiAclGroups;
use open20\amos\documenti\widgets\icons\WidgetIconDocumentiAclSharedWithMeAdmin;

/**
 * Class m220316_113837_add_documenti_acl_new_widgets
 */
class m220316_113837_add_documenti_acl_new_widgets extends AmosMigrationWidgets
{
    const MODULE_NAME = 'documenti';
    
    /**
     * @inheritdoc
     */
    protected function initWidgetsConfs()
    {
        $this->widgets = [
            [
                'classname' => WidgetIconDocumentiAclDashboard::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'child_of' => null,
                'default_order' => 100,
                'dashboard_visible' => 1
            ],
            [
                'classname' => WidgetIconDocumentiAclAdmin::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'child_of' => WidgetIconDocumentiAclDashboard::className(),
                'default_order' => 10,
                'dashboard_visible' => 0
            ],
            [
                'classname' => WidgetIconDocumentiAclGroups::className(),
                'module' => self::MODULE_NAME,
                'update' => true,
                'child_of' => WidgetIconDocumentiAclDashboard::className(),
                'default_order' => 20
            ],
            [
                'classname' => WidgetIconDocumentiAclSharedWithMeAdmin::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'child_of' => WidgetIconDocumentiAclDashboard::className(),
                'default_order' => 30,
                'dashboard_visible' => 0
            ]
        ];
    }
}
