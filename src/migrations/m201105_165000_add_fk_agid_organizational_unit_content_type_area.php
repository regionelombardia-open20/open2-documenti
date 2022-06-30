<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti\migrations
 * @category   CategoryName
 */

use yii\db\Migration;

/**
 * Class m201105_165000_add_fk_agid_organizational_unit_content_type_area
 */
class m201105_165000_add_fk_agid_organizational_unit_content_type_area extends Migration
{
    /**
     * @inheritDoc
     */
    public function safeUp()
    {
        $this->addColumn('documenti', 'agid_organizational_unit_content_type_area_id', $this->integer()->null()->defaultValue(null));
        return true;
    }
    
    /**
     * @inheritDoc
     */
    public function safeDown()
    {
        $this->dropColumn('documenti', 'agid_organizational_unit_content_type_area_id');
        return true;
    }
}
