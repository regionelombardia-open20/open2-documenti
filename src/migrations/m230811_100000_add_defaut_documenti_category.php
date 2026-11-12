<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open20\amos\admin\migrations
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
use yii\db\Query;

/**
 * Class m210326_095400_update_fields_color_category
 */
class m230811_100000_add_defaut_documenti_category extends Migration
{



    /**
     * se checkNumberCategory restituisce true allora inserisco la categoria generica
     * @inheritdoc
     */
    public function safeUp()
    {
        if($this->checkNumberCategory()){
            $this->insert('documenti_categorie', [
                'titolo' => 'Default',
                'descrizione_breve' => 'categoria creata in automatico alla prima installazione',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
                'created_by' => 1,
                'updated_by' => 1,
            ]);
        }
        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        return true;
    }
    /**
     * check se la tabella contiene categorie attive
     * @return bool
     */

    private function checkNumberCategory(){
        
        $sql = 'SELECT COUNT(id) FROM documenti_categorie WHERE deleted_at is null';
        
        $cmd = $this->db->createCommand();
        $cmd->setSql( $sql );
        $newsCategory = $cmd->queryScalar();
        return ( $newsCategory == 0 );
    }
}
