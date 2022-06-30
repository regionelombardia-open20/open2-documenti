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
 * Class m211028_151520_alter_documenti_publication_dates
 */
class m211028_151520_alter_documenti_publication_dates extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->alterColumn(Documenti::tableName(), 'data_pubblicazione', $this->dateTime()->null()->comment('Data pubblicazione'));
        $this->alterColumn(Documenti::tableName(), 'data_rimozione', $this->dateTime()->null()->comment('Data fine pubblicazione'));
        return true;
    }
    
    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->alterColumn(Documenti::tableName(), 'data_pubblicazione', $this->date()->null()->comment('Data pubblicazione'));
        $this->alterColumn(Documenti::tableName(), 'data_rimozione', $this->date()->null()->comment('Data fine pubblicazione'));
        return true;
    }
}
