<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\admin\migrations
 * @category   CategoryName
 */

use yii\db\Migration;

/**
 * Class m181012_162615_add_user_profile_area_field_1
 */
class m240613_102115_change_default_order_widget_UltimiDocumenti extends Migration
{



    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->update('amos_widgets', ['default_order' => 4], ['classname' => 'open20\amos\documenti\widgets\graphics\WidgetGraphicsCmsUltimiDocumenti','module' => 'community']);


    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        return true;
    }
}
