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

use open20\amos\admin\models\UserProfileArea;
use yii\db\Migration;

/**
 * Class m181012_162615_add_user_profile_area_field_1
 */
class m181107_172115_remove_widgets extends Migration
{



    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->update('amos_widgets', ['dashboard_visible' => 0], ['classname' => 'open20\amos\documenti\widgets\graphics\WidgetGraphicsUltimiDocumenti', 'module' =>'documenti']);
        $this->update('amos_widgets', ['status' => 0], ['classname' => 'open20\amos\documenti\widgets\graphics\WidgetGraphicsHierarchicalDocuments']);


    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {

        $this->update('amos_widgets', ['dashboard_visible' => 1], ['classname' => 'open20\amos\documenti\widgets\graphics\WidgetGraphicsUltimiDocumenti']);
        $this->update('amos_widgets', ['status' => 1], ['classname' => 'open20\amos\documenti\widgets\graphics\WidgetGraphicsHierarchicalDocuments']);
    }
}
