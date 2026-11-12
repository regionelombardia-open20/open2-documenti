<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   svilupposostenibile\enti
 * @author    Aria S.p.A. <protocollo@pec.ariaspa.it>
 * @license   GNU General Public License v3.0 <https://www.gnu.org/licenses/gpl-3.0.html>
 * @link      https://www.ariaspa.it/
 *
 * This file is part of OPEN 2.0.
 *
 * OPEN 2.0 is free software: you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * OPEN 2.0 is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with OPEN 2.0. If not, see <https://www.gnu.org/licenses/>.
 */
use open20\amos\core\migration\AmosMigrationTableCreation;

/**
 * Class m201105_163600_create_documenti_agid_type
 */
class m220712_144800_create_documenti_cartelle_path extends AmosMigrationTableCreation {


    /**
     * set table name
     *
     * @return void
     */
    protected function setTableName() {

        $this->tableName = '{{%documenti_cartelle_path%}}';
    }


    /**
     * set table fields
     *
     * @return void
     */
    protected function setTableFields() {

        $this->tableFields = [

            // PK
            'id' => $this->primaryKey(),

            // COLUMNS
            'id_doc_folder' => $this->integer()->null()->defaultValue(null)->comment('id_doc_folder'),
            'level' => $this->integer()->null()->defaultValue(null)->comment('level'),
            'id_folder' => $this->integer()->null()->defaultValue(null)->comment('id_folder'),
        ];
    }


    /**
     * Timestamp
     */
    protected function beforeTableCreation() {
        
        parent::beforeTableCreation();
        $this->setAddCreatedUpdatedFields(true);
    }
    
     protected function addForeignKeys()
    {
        $this->addForeignKey('fk_id_doc_folder_documenti', $this->getRawTableName(), 'id_doc_folder', '{{%documenti}}', 'id');
        $this->addForeignKey('fk_id_folder_documenti', $this->getRawTableName(), 'id_folder', '{{%documenti}}', 'id');
    }
}
