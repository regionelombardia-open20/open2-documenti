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
use open20\amos\dashboard\utility\DashboardUtility;
use open20\amos\documenti\widgets\icons\WidgetIconDocumentiAclSharedWithMe;

/**
 * Class m220114_175907_update_documenti_acl_widget_shared_with_me
 */
class m220114_175907_update_documenti_acl_widget_shared_with_me extends AmosMigrationWidgets
{
    const MODULE_NAME = 'documenti';
    
    /**
     * @inheritdoc
     */
    public function afterAddWidgets()
    {
        return DashboardUtility::resetAllDashboards();
    }
    
    /**
     * @inheritdoc
     */
    public function afterRemoveWidgets()
    {
        return DashboardUtility::resetAllDashboards();
    }
    
    /**
     * @inheritdoc
     */
    protected function initWidgetsConfs()
    {
        $this->widgets = [
            [
                'classname' => WidgetIconDocumentiAclSharedWithMe::className(),
                'module' => self::MODULE_NAME,
                'update' => true,
                'child_of' => null,
                'dashboard_visible' => 1
            ]
        ];
    }
}
