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

use open20\amos\core\migration\AmosMigrationWorkflow;

/**
 * Class m180601_153734_add_documenti_workflow_metadata_for_folder
 */
class m180601_153734_add_documenti_workflow_metadata_for_folder extends AmosMigrationWorkflow
{
    const WORKFLOW_NAME = 'DocumentiWorkflow';
    const WORKFLOW_DRAFT = 'BOZZA';
    const WORKFLOW_TOVALIDATE = 'DAVALIDARE';
    const WORKFLOW_VALIDATED = 'VALIDATO';

    /**
     * @inheritdoc
     */
    protected function setWorkflow()
    {
        return [
            // DOCUMENTI WORKFLOW
            // "DRAFT" status
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => self::WORKFLOW_DRAFT,
                'key' => 'labelFolder',
                'value' => '#' . self::WORKFLOW_DRAFT . '_labelFolder'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => self::WORKFLOW_DRAFT,
                'key' => self::WORKFLOW_VALIDATED . '_descriptionFolder',
                'value' => '#' . self::WORKFLOW_DRAFT . '_' . self::WORKFLOW_VALIDATED . '_descriptionFolder'
            ],
            // TOVALIDATE
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => self::WORKFLOW_TOVALIDATE,
                'key' => 'labelFolder',
                'value' => '#' . self::WORKFLOW_TOVALIDATE . '_labelFolder'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => self::WORKFLOW_TOVALIDATE,
                'key' => 'descriptionFolder',
                'value' => '#' . self::WORKFLOW_TOVALIDATE . '_descriptionFolder'
            ],
            // VALIDATED
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => self::WORKFLOW_VALIDATED,
                'key' => 'labelFolder',
                'value' => '#' . self::WORKFLOW_VALIDATED . '_labelFolder'
            ],
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_METADATA,
                'workflow_id' => self::WORKFLOW_NAME,
                'status_id' => self::WORKFLOW_VALIDATED,
                'key' => 'descriptionFolder',
                'value' => '#' . self::WORKFLOW_VALIDATED . '_descriptionFolder'
            ],
            // -----------------------------------------------------------
        ];
    }
}
