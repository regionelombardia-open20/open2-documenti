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

use yii\db\Migration;
use open20\amos\documenti\models\Documenti;

/**
 * Class m161130_085025_create_documenti_workflow
 */
class m161130_085025_create_documenti_workflow extends Migration
{
    const TABLE_WORKFLOW = '{{%sw_workflow}}';
    const TABLE_WORKFLOW_STATUS = '{{%sw_status}}';
    const TABLE_WORKFLOW_TRANSITIONS = '{{%sw_transition}}';

    private $fieldsToSkip = ['tableName', 'fieldsToCheck'];
    private $newConfs;

    public function safeUp()
    {
        $this->setNewConfigurations();
        return $this->addConfs();
    }

    /**
     * Metodo in cui settare tutte le nuove configurazioni da inserire a db.
     */
    private function setNewConfigurations()
    {
        $this->newConfs = [
            [
                'id' => Documenti::DOCUMENTI_WORKFLOW,
                'initial_status_id' => 'BOZZA',
                'tableName' => self::TABLE_WORKFLOW,
                'fieldsToCheck' => ['id']
            ],
            [
                'id' => 'BOZZA',
                'workflow_id' => Documenti::DOCUMENTI_WORKFLOW,
                'label' => 'Modifica in corso',
                'sort_order' => '0',
                'tableName' => self::TABLE_WORKFLOW_STATUS,
                'fieldsToCheck' => ['id', 'workflow_id']
            ],
            [
                'id' => 'DAVALIDARE',
                'workflow_id' => Documenti::DOCUMENTI_WORKFLOW,
                'label' => 'Da validare',
                'sort_order' => '1',
                'tableName' => self::TABLE_WORKFLOW_STATUS,
                'fieldsToCheck' => ['id', 'workflow_id']
            ],
            [
                'id' => 'VALIDATO',
                'workflow_id' => Documenti::DOCUMENTI_WORKFLOW,
                'label' => 'Validato',
                'sort_order' => '2',
                'tableName' => self::TABLE_WORKFLOW_STATUS,
                'fieldsToCheck' => ['id', 'workflow_id']
            ],
            [
                'id' => 'NONVALIDATO',
                'workflow_id' => Documenti::DOCUMENTI_WORKFLOW,
                'label' => 'Non validato',
                'sort_order' => '3',
                'tableName' => self::TABLE_WORKFLOW_STATUS,
                'fieldsToCheck' => ['id', 'workflow_id']
            ],
            [
                'workflow_id' => Documenti::DOCUMENTI_WORKFLOW,
                'start_status_id' => 'BOZZA',
                'end_status_id' => 'DAVALIDARE',
                'tableName' => self::TABLE_WORKFLOW_TRANSITIONS,
                'fieldsToCheck' => ['workflow_id', 'start_status_id', 'end_status_id']
            ],
            [
                'workflow_id' => Documenti::DOCUMENTI_WORKFLOW,
                'start_status_id' => 'DAVALIDARE',
                'end_status_id' => 'VALIDATO',
                'tableName' => self::TABLE_WORKFLOW_TRANSITIONS,
                'fieldsToCheck' => ['workflow_id', 'start_status_id', 'end_status_id']
            ],
            [
                'workflow_id' => Documenti::DOCUMENTI_WORKFLOW,
                'start_status_id' => 'DAVALIDARE',
                'end_status_id' => 'NONVALIDATO',
                'tableName' => self::TABLE_WORKFLOW_TRANSITIONS,
                'fieldsToCheck' => ['workflow_id', 'start_status_id', 'end_status_id']
            ],
            [
                'workflow_id' => Documenti::DOCUMENTI_WORKFLOW,
                'start_status_id' => 'NONVALIDATO',
                'end_status_id' => 'BOZZA',
                'tableName' => self::TABLE_WORKFLOW_TRANSITIONS,
                'fieldsToCheck' => ['workflow_id', 'start_status_id', 'end_status_id']
            ],
            [
                'workflow_id' => Documenti::DOCUMENTI_WORKFLOW,
                'start_status_id' => 'VALIDATO',
                'end_status_id' => 'BOZZA',
                'tableName' => self::TABLE_WORKFLOW_TRANSITIONS,
                'fieldsToCheck' => ['workflow_id', 'start_status_id', 'end_status_id']
            ],
            [
                'workflow_id' => Documenti::DOCUMENTI_WORKFLOW,
                'start_status_id' => 'VALIDATO',
                'end_status_id' => 'DAVALIDARE',
                'tableName' => self::TABLE_WORKFLOW_TRANSITIONS,
                'fieldsToCheck' => ['workflow_id', 'start_status_id', 'end_status_id']
            ]
        ];
    }

    /**
     * Metodo che aggiunge tutte le configurazioni settate nell'array. Verifica la presenza di ciascuna e la crea se
     * non esiste già, altrimenti passa oltre.
     *
     * @return  boolean
     */
    private function addConfs()
    {
        foreach ($this->newConfs as $newCompleteConf) {
            $newConf = [];

            if (isset($newCompleteConf['withTime'])) {
                $now = time();
                $newConf['created_at'] = $now;
                $newConf['updated_at'] = $now;
            }

            foreach ($newCompleteConf as $fieldName => $fieldValue) {
                if (!in_array($fieldName, $this->fieldsToSkip)) {
                    $newConf[$fieldName] = $fieldValue;
                }
            }

            $this->createConf($newCompleteConf['tableName'], $newCompleteConf['fieldsToCheck'], $newConf);
        }

        return true;
    }

    /**
     * Metodo privato per la creazione di un singolo permesso
     *
     * @param string $tablename Nome tabella
     * @param array $fieldsToCheck Campi da verificare sulla tabella
     * @param array $newConf Array chiave => valore contenente i dati da inserire nella tabella.
     */
    private function createConf($tablename, $fieldsToCheck, $newConf)
    {
        $confName = $this->composeConsoleConfName($fieldsToCheck, $newConf);

        if ($this->checkConfExist($tablename, $fieldsToCheck, $newConf)) {
            echo "Configurazione $confName per il modulo documenti esistente. Skippo...\n";
        } else {
            $this->insert($tablename, $newConf);
            echo "Configurazione $confName per il modulo documenti creata.\n";
        }
    }

    /**
     * Ritorna il nome della configurazione da visualizzare nel messaggio in console.
     *
     * @param   array $fieldsToCheck Array contenente i campi da verificare.
     * @param   array $newConf Array contenente i valori da inserire nella tabella.
     *
     * @return  string
     */
    private function composeConsoleConfName($fieldsToCheck, $newConf)
    {
        $confName = '';
        foreach ($fieldsToCheck as $fieldName) {
            if (strlen($confName) > 0) {
                $confName .= ' - ';
            }
            $confName .= $newConf[$fieldName];
        }
        return $confName;
    }

    /**
     * Metodo per la verifica dell'esistenza del permesso in base al nome, che è univoco.
     *
     * @param   string $tablename Nome tabella
     * @param   array $fieldsToCheck Campi da verificare sulla tabella
     * @param   array $fieldsValues Valori della configurazione da verificare
     *
     * @return bool
     */
    private function checkConfExist($tableName, $fieldsToCheck, $fieldsValues)
    {
        $whereCondition = "";
        foreach ($fieldsToCheck as $fieldName) {
            $whereCondition .= ((strlen($whereCondition) > 0) ? " AND " : "") . $fieldName . " LIKE '" . $fieldsValues[$fieldName] . "'";
        }
        $sql = "SELECT COUNT(*) FROM " . $tableName . " WHERE " . $whereCondition;

        $cmd = $this->db->createCommand();
        $cmd->setSql($sql);
        $valuesCount = $cmd->queryScalar();
        return ($valuesCount > 0);
    }

    public function safeDown()
    {
        $this->setNewConfigurations();
        return $this->removeConfs();
    }

    /**
     * Metodo per la rimozione di tutte le configurazioni inserite in precedenza.
     *
     * @return  boolean
     */
    private function removeConfs()
    {
        foreach ($this->newConfs as $newCompleteConf) {
            $where = "";
            foreach ($newCompleteConf['fieldsToCheck'] as $fieldName) {
                $where .= ((strlen($where) > 0) ? " AND " : "") . $fieldName . " LIKE '" . $newCompleteConf[$fieldName] . "'";
            }
            $this->delete($newCompleteConf['tableName'], $where);
        }

        return true;
    }
}
