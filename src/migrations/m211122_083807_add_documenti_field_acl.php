<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti\migrations
 * @category   CategoryName
 */

use open20\amos\documenti\models\Documenti;
use yii\db\Migration;

/**
 * Class m211122_083807_add_documenti_field_acl
 */
class m211122_083807_add_documenti_field_acl extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn(Documenti::tableName(), 'is_acl', $this->boolean()->notNull()->defaultValue(0)->after('is_folder'));
        return true;
    }
    
    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn(Documenti::tableName(), 'is_acl');
        return true;
    }
}
