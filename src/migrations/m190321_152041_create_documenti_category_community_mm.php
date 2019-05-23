<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\news\migrations
 * @category   CategoryName
 */

use lispa\amos\core\migration\AmosMigrationTableCreation;

/**
 * Class m180109_135841_create_news_category_roles_mm
 */
class m190321_152041_create_documenti_category_community_mm extends AmosMigrationTableCreation
{
    /**
     * @inheritdoc
     */
    protected function setTableName()
    {
        $this->tableName = '{{%documenti_category_community_mm}}';
    }

    /**
     * @inheritdoc
     */
    protected function setTableFields()
    {
        $this->tableFields = [
            'id' => $this->primaryKey(),
            'documenti_categorie_id' => $this->integer()->notNull()->comment('Documenti Category ID'),
            'community_id' => $this->string()->notNull()->comment('Community')
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
        $this->addForeignKey('fk_documenti_category_community_mm_doc_categorie_id1', $this->getRawTableName(),
            'documenti_categorie_id', '{{%documenti_categorie}}', 'id');
    }

}

