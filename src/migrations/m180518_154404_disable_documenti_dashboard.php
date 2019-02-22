<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\documenti\migrations
 * @category   CategoryName
 */

use lispa\amos\documenti\models\Documenti;
use yii\db\Migration;

/**
 * Class m171214_162104_add_documenti_fields_2
 */
class m180518_154404_disable_documenti_dashboard extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->update('amos_widgets', ['status' => 0], ['classname' => 'lispa\amos\documenti\widgets\icons\WidgetIconDocumentiDashboard', 'module' => 'documenti']);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->update('amos_widgets', ['status' => 1], ['classname' => 'lispa\amos\documenti\widgets\icons\WidgetIconDocumentiDashboard', 'module' => 'documenti']);
    }
}
