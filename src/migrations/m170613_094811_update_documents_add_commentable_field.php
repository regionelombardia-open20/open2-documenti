<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open20\amos\documenti\migrations
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

use open20\amos\core\migration\libs\common\MigrationCommon;
use open20\amos\documenti\models\Documenti;
use yii\db\Migration;

/**
 * Class m170613_094811_update_documents_add_commentable_field
 */
class m170613_094811_update_documents_add_commentable_field extends Migration
{
    private $tablename;
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->tablename = Documenti::tableName();
    }
    
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        try {
            $this->addColumn($this->tablename, 'comments_enabled', $this->boolean()->defaultValue(0)->after('status'));
            $this->addColumn(Documenti::tableName(), 'parent_id', $this->integer()->null()->defaultValue(null)->after('comments_enabled'));
            $this->addColumn(Documenti::tableName(), 'is_folder', $this->boolean()->notNull()->defaultValue(0)->after('parent_id'));
            $this->addColumn(Documenti::tableName(), 'version', $this->integer()->null()->defaultValue(null)->after('is_folder'));
        } catch (\Exception $exception) {
            MigrationCommon::printConsoleMessage("Error while add column 'comments_enabled' to " . $this->tablename . " table");
            return false;
        }
        return true;
    }
    
    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        try {
            $this->dropColumn($this->tablename, 'comments_enabled');
            $this->dropColumn(Documenti::tableName(), 'parent_id');
            $this->dropColumn(Documenti::tableName(), 'is_folder');
            $this->dropColumn(Documenti::tableName(), 'version');
        } catch (\Exception $exception) {
            MigrationCommon::printConsoleMessage("Error while drop column 'comments_enabled' from " . $this->tablename . " table");
            return false;
        }
        return true;
    }
}
