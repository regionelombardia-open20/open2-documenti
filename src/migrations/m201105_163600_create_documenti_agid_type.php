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
class m201105_163600_create_documenti_agid_type extends AmosMigrationTableCreation {


    /**
     * set table name
     *
     * @return void
     */
    protected function setTableName() {

        $this->tableName = '{{%documenti_agid_type%}}';
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
            'name' => $this->string()->null()->defaultValue(null)->comment('Name'),
            'description' => $this->text()->null()->defaultValue(null)->comment('Description'),
        ];
    }


    /**
     * Timestamp
     */
    protected function beforeTableCreation() {
        
        parent::beforeTableCreation();
        $this->setAddCreatedUpdatedFields(true);
    }


    /**
     * Insert default value
     *
     * @return void
     */
    protected function afterTableCreation(){

        $this->insert('documenti_agid_type', [
            'name' => 'Documenti albo pretorio',
        ]);

        $this->insert('documenti_agid_type', [
            'name' => 'Modulistica',
        ]);

        $this->insert('documenti_agid_type', [
            'name' => 'Documenti funzionamento interno',
        ]);

        $this->insert('documenti_agid_type', [
            'name' => 'Atti normativi',
        ]);

        $this->insert('documenti_agid_type', [
            'name' => 'Accordi tra enti',
        ]);

        $this->insert('documenti_agid_type', [
            'name' => 'Documenti attività politica',
        ]);

        $this->insert('documenti_agid_type', [
            'name' => 'Documenti (tecnici) di supporto',
        ]);

        $this->insert('documenti_agid_type', [
            'name' => 'Istanze',
        ]);
    }
    
}
