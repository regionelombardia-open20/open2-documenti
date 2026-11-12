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
use open20\amos\documenti\AmosDocumenti;
use open20\amos\documenti\models\Documenti;
use yii\db\ActiveQuery;
use yii\db\Migration;

/**
 * Class m181004_164409_delete_documenti_folder_contained_documents
 */
class m181004_164409_delete_documenti_folder_contained_documents extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $deletedFolders = $this->findDeletedFolders();
        $allOk = true;

        foreach ($deletedFolders as $deletedFolder) {
            try {
                $ok = $deletedFolder->deleteAllChildren(false);
            } catch (\Exception $exception) {
                MigrationCommon::printConsoleMessage("Errore cancellazione contenuto della cartella con id '" . $deletedFolder->id . "': '" . $deletedFolder->titolo . "'");
                MigrationCommon::printConsoleMessage($exception->getMessage());
                die();
            }
            if (!$ok) {
                MigrationCommon::printConsoleMessage("Errore cancellazione contenuto della cartella con id '" . $deletedFolder->id . "': '" . $deletedFolder->titolo . "'");
                $allOk = false;
            } else {
                MigrationCommon::printConsoleMessage("Cancellato contenuto della cartella con id '" . $deletedFolder->id . "': '" . $deletedFolder->titolo . "'");
            }
        }

        return $allOk;
    }

    /**
     * @return Documenti[]
     */
    private function findDeletedFolders()
    {
        /** @var Documenti $documentiModel */
        $documentiModel = AmosDocumenti::instance()->createModel('Documenti');

        /** @var ActiveQuery $query */
        $query = $documentiModel::basicFind();
        $query->andWhere(['is_folder' => Documenti::IS_FOLDER]);
        $query->andWhere(['is not', 'deleted_at', null]);
        $deletedFolders = $query->all();
        return $deletedFolders;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m181004_164409_delete_documenti_folder_contained_documents cannot be reverted.\n";
        return false;
    }
}
