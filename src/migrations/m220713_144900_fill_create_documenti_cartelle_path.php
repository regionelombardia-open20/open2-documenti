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
use open20\amos\documenti\models\DocumentiCartellePath;


class m220713_144900_fill_create_documenti_cartelle_path extends Migration
{

    public function up()
    {
        Yii::$app->db->createCommand()->truncateTable(DocumentiCartellePath::tableName())->execute();
        $documenti = Documenti::find()->andWhere(['deleted_at'=>null])->all();
        if(!empty($documenti)){
            foreach($documenti as $doc){
                $result = DocumentiCartellePath::generatePath($doc,1,[]);
                DocumentiCartellePath ::savePath($result,$doc->id);
            }
        }
     
    }


    public function down()
    {
        Yii::$app->db->createCommand()->truncateTable(DocumentiCartellePath::tableName())->execute();

    }
}