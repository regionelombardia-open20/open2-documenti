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
use open20\amos\documenti\widgets\icons\WidgetIconDocumentiAclSharedWithMe;
use open20\amos\documenti\widgets\icons\WidgetIconDocumentiDashboard;

/**
 * Class m211216_222932_add_documenti_acl_widget_shared_with_me
 */
class m211216_222932_add_documenti_acl_widget_shared_with_me extends AmosMigrationWidgets
{
    const MODULE_NAME = 'documenti';
    
    /**
     * @inheritdoc
     */
    protected function initWidgetsConfs()
    {
        $this->widgets = [
            [
                'classname' => WidgetIconDocumentiAclSharedWithMe::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'child_of' => WidgetIconDocumentiDashboard::className(),
                'default_order' => 90,
                'dashboard_visible' => 0
            ]
        ];
    }
}
