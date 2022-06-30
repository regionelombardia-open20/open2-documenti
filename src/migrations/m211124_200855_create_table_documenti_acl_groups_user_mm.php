<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\AmosMigrationTableCreation;

/**
 * Class m211124_200855_create_table_documenti_acl_groups_user_mm
 */
class m211124_200855_create_table_documenti_acl_groups_user_mm extends AmosMigrationTableCreation
{
    /**
     * @inheritdoc
     */
    protected function setTableName()
    {
        $this->tableName = '{{%documenti_acl_groups_user_mm}}';
    }
    
    /**
     * @inheritdoc
     */
    protected function setTableFields()
    {
        $this->tableFields = [
            'id' => $this->primaryKey(),
            'group_id' => $this->integer()->null()->defaultValue(null),
            'user_id' => $this->integer()->null()->defaultValue(null),
            'document_id' => $this->integer()->null()->defaultValue(null),
            'update_folder_content' => $this->boolean()->notNull()->defaultValue(0),
            'upload_folder_files' => $this->boolean()->notNull()->defaultValue(0),
            'read_folder_files' => $this->boolean()->notNull()->defaultValue(0),
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
    protected function afterTableCreation()
    {
        $this->createIndex('doc_acl_mm_index_group_id', $this->tableName, ['group_id']);
        $this->createIndex('doc_acl_mm_index_user_id', $this->tableName, ['user_id']);
        $this->createIndex('doc_acl_mm_index_folder_id', $this->tableName, ['document_id']);
        $this->createIndex('doc_acl_mm_index_group_id_user_id', $this->tableName, ['group_id', 'user_id']);
        $this->createIndex('doc_acl_mm_index_user_id_folder_id', $this->tableName, ['user_id', 'document_id']);
        $this->createIndex('doc_acl_mm_index_group_id_user_id_folder_id', $this->tableName, ['group_id', 'user_id', 'document_id']);
    }
}
