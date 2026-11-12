<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open20\amos-documenti
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

class m201110_155400_insert_value_documenti_agid_type extends Migration
{
    public function safeUp()
    {
        // remove old value 
        $this->execute('SET FOREIGN_KEY_CHECKS = 0;');
        \Yii::$app->db->createCommand()->truncateTable('documenti_agid_type')->execute();
        $this->execute('SET FOREIGN_KEY_CHECKS = 1;');



        $this->insert('documenti_agid_type', [
            'name' => 'Bandi di concorso'
        ]);

        $this->insert('documenti_agid_type', [
            'name' => 'Nomine in società ed enti'
        ]);

        $this->insert('documenti_agid_type', [
            'name' => 'Bandi immobiliari'
        ]);

        $this->insert('documenti_agid_type', [
            'name' => 'Bandi per contributi'
        ]);

        $this->insert('documenti_agid_type', [
            'name' => 'Altri bandi e avvisi'
        ]);

        $this->insert('documenti_agid_type', [
            'name' => 'Ordinanze'
        ]);

        $this->insert('documenti_agid_type', [
            'name' => 'Statuto comunale'
        ]);

        $this->insert('documenti_agid_type', [
            'name' => 'Regolamenti'
        ]);

        $this->insert('documenti_agid_type', [
            'name' => 'Pianificazione urbanistica'
        ]);

        $this->insert('documenti_agid_type', [
            'name' => 'Autorizzazioni paesaggistiche'
        ]);

        $this->insert('documenti_agid_type', [
            'name' => 'Pubblicazioni statistiche'
        ]);
    }

    public function safeDown()
    {
       return true;
    }
}