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
 * Class m201116_155000_add_fk_to_agid_document_type
 */
class m201116_155000_add_fk_to_agid_document_type extends Migration
{
    /**
     * @inheritDoc
     */
    public function safeUp()
    {
        $this->addColumn('documenti_agid_type', 'agid_document_content_type_id', $this->integer()->null()->defaultValue(null));
        return true;
    }
    
    /**
     * @inheritDoc
     */
    public function safeDown()
    {
        $this->dropColumn('documenti_agid_type', 'agid_document_content_type_id');
        return true;
    }
}
