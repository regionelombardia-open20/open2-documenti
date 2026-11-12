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

use yii\db\Migration;
use open20\amos\documenti\models\Documenti;


class m201105_164500_add_fk_agid_organizational_unit_content_type_office extends Migration
{ 

    public function safeUp()
    {
        // addColumn to AGID ORGANIZATIONAL with AGID CONTENT TYPE "office"
        $table = $this->db->schema->getTableSchema(Documenti::tableName());
        if (!isset($table->columns['agid_organizational_unit_content_type_office_id'])) {
            $this->addColumn(Documenti::tableName(), 'agid_organizational_unit_content_type_office_id', $this->integer()->null()->defaultValue(null));
            //->comment('FK agid_organizational_unit with agid_content_type_id => Area');
        }
        // addForeignKey
        if ($this->db->schema->getTableSchema('agid_organizational_unit', true) === null) {
            $this->execute('SET FOREIGN_KEY_CHECKS=0');
            $this->addForeignKey(
                'fk-agid-organizational-unit-content-type-office-id',
                Documenti::tableName(),
                'agid_organizational_unit_content_type_office_id',
                'agid_organizational_unit',
                'id'
            );
            $this->execute('SET FOREIGN_KEY_CHECKS=1');
        }
    }

    public function safeDown()
    {
        // dropForeignKey
        $this->dropForeignKey ( 'fk-agid-organizational-unit-content-type-office-id', 'documenti' );
        // dropColumn
        $this->dropColumn('documenti', 'agid_organizational_unit_content_type_office_id');
    }

}