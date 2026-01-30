<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\comments\migrations
 * @category   CategoryName
 */



use open20\amos\core\migration\AmosMigrationTableCreation;

class m241217_104400_create_table_documenti_ms_teams extends AmosMigrationTableCreation
{
    /**
     * @inheritdoc
     */
    protected function setTableName()
    {
        $this->tableName = '{{%documenti_ms_teams}}';
    }

    /**
     * @inheritdoc
     */
    protected function setTableFields()
    {
        $this->tableFields = [
            'id' => $this->primaryKey(),
            'documenti_id' => $this->integer()->notNull()->comment('Document id'),
            'msgraph_drive_item_id' => $this->string(255)->unique()->comment('Teams drive file id'),
        ];
    }

    /**
     * @inheritdoc
     */
    protected function beforeTableCreation()
    {
        parent::beforeTableCreation();
        $this->setAddCreatedUpdatedFields(true);
    }

    /**
     * @inheritdoc
     */
    protected function addForeignKeys()
    {
        $this->addForeignKey('fk_documenti_ms_teams_documenti_id', $this->tableName, 'documenti_id', '{{%documenti}}', 'id');
    }
}
