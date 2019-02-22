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
use lispa\amos\dashboard\models\AmosWidgets;

/**
 * Class m161130_102133_add_documenti_all_widget
 */
class m161130_102133_add_documenti_all_widget extends AmosMigrationWidgets
{
    const MODULE_NAME = 'documenti';

    /**
     * @inheritdoc
     */
    protected function initWidgetsConfs()
    {
        $this->widgets = [
            [
                'classname' => lispa\amos\documenti\widgets\icons\WidgetIconDocumentiDashboard::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED
            ],
            [
                'classname' => \lispa\amos\documenti\widgets\icons\WidgetIconAllDocumenti::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'child_of' => \lispa\amos\documenti\widgets\icons\WidgetIconDocumentiDashboard::className()
            ],

            [
                'classname' => \lispa\amos\documenti\widgets\icons\WidgetIconDocumenti::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'child_of' => \lispa\amos\documenti\widgets\icons\WidgetIconDocumentiDashboard::className()
            ],
            [
                'classname' => lispa\amos\documenti\widgets\icons\WidgetIconDocumentiCategorie::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'child_of' => \lispa\amos\documenti\widgets\icons\WidgetIconDocumentiDashboard::className()
            ],
            [
                'classname' => lispa\amos\documenti\widgets\icons\WidgetIconDocumentiCreatedBy::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'child_of' => \lispa\amos\documenti\widgets\icons\WidgetIconDocumentiDashboard::className()
            ],
            [
                'classname' => lispa\amos\documenti\widgets\icons\WidgetIconDocumentiDaValidare::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'child_of' => \lispa\amos\documenti\widgets\icons\WidgetIconDocumentiDashboard::className()
            ],
            [
                'classname' => \lispa\amos\documenti\widgets\graphics\WidgetGraphicsUltimiDocumenti::className(),
                'type' => AmosWidgets::TYPE_GRAPHIC,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'child_of' => \lispa\amos\documenti\widgets\icons\WidgetIconDocumentiDashboard::className()
            ]
        ];
    }
}
