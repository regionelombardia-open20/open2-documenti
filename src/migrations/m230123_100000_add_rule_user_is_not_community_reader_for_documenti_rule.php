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

use open20\amos\core\migration\AmosMigrationPermissions;
use open20\amos\documenti\rules\UserIsNotCommunityReaderDocumentiRule;
use yii\rbac\Permission;

/**
 * Class m230123_100000_add_rule_user_is_not_community_reader_for_documenti_rule
 */
class m230123_100000_add_rule_user_is_not_community_reader_for_documenti_rule extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => UserIsNotCommunityReaderDocumentiRule::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Regola che controlla se un utente non ha il ruolo READER',
                'ruleName' => UserIsNotCommunityReaderDocumentiRule::className(),
                'parent' => [
                    'CREATORE_DOCUMENTI'
                ]
            ],
            [
                'name' => 'DOCUMENTI_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model Documenti',
                'update' => true,
                'newValues' => [
                    'addParents' => [
                        UserIsNotCommunityReaderDocumentiRule::className()
                    ],
                    'removeParents' => [
                        'CREATORE_DOCUMENTI'
                    ]
                ]
            ]
        ];
    }
}
