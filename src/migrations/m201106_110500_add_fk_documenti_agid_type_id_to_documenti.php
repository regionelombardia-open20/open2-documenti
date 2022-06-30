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
 * Class m201106_110500_add_fk_documenti_agid_type_id_to_documenti
 */
class m201106_110500_add_fk_documenti_agid_type_id_to_documenti extends Migration
{
    /**
     * @inheritDoc
     */
    public function safeUp()
    {
        $this->addColumn('documenti', 'documenti_agid_type_id', $this->integer()->null()->defaultValue(null));
        return true;
    }
    
    /**
     * @inheritDoc
     */
    public function safeDown()
    {
        $this->dropColumn('documenti', 'documenti_agid_type_id');
        return true;
    }
}
