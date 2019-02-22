<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\documenti\migrations
 * @category   CategoryName
 */

use lispa\amos\core\migration\AmosMigrationWidgets;

/**
 * Class m180202_124001_change_widget_UltimiDocumenti_dashboard_visible
 */
class m180202_124001_change_widget_UltimiDocumenti_dashboard_visible extends AmosMigrationWidgets
{
    const MODULE_NAME = 'documenti';

    /**
     * @inheritdoc
     */
    protected function initWidgetsConfs()
    {
        $this->widgets = [
            [
                'classname' => lispa\amos\documenti\widgets\graphics\WidgetGraphicsUltimiDocumenti::className(),
                'dashboard_visible' => 1,
                'update' => true
            ]
        ];
    }
}
