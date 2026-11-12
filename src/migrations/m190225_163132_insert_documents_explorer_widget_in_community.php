<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open2\amos\ticket
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

use open20\amos\core\migration\AmosMigrationWidgets;
use open20\amos\dashboard\models\AmosWidgets;

/**
 * Class m190225_163132_insert_documents_explorer_widget_in_community
 */
class m190225_163132_insert_documents_explorer_widget_in_community extends \open20\amos\core\migration\AmosMigration
{
    const MODULE_NAME = 'documenti';
    const COMMUNITY_MODULE_NAME = 'community';

    public function safeUp()
    {
        $communityModule = \Yii::$app->getModule('community');
        if(isset($communityModule)) {
            $this->insert('amos_widgets', [
                'classname' => \open20\amos\documenti\widgets\graphics\WidgetGraphicsDocumentsExplorer::className(),
                'type' => AmosWidgets::TYPE_GRAPHIC,
                'module' => self::COMMUNITY_MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'default_order' => 1,
                'sub_dashboard' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => date('Y-m-d H:m:s'),
                'updated_at' => date('Y-m-d H:m:s'),
            ]);
        }

        return true;
    }

    public function safeDown()
    {
        $communityModule = \Yii::$app->getModule('community');
        if(isset($communityModule)) {
            $this->delete('amos_widgets', [
                'classname' => \open20\amos\documenti\widgets\graphics\WidgetGraphicsDocumentsExplorer::className(),
                'type' => AmosWidgets::TYPE_GRAPHIC,
                'module' => self::COMMUNITY_MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'default_order' => 1,
                'sub_dashboard' => 1,
            ]);
        }
        return true;
    }

}
