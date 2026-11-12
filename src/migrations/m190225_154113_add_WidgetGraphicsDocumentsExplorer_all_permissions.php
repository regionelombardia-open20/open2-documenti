<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open20\amos\documenti
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
use open20\amos\documenti\rules\DeleteOwnDocumentiRule;
use open20\amos\documenti\rules\DeleteFacilitatorOwnDocumentiRule;
use open20\amos\documenti\rules\UpdateFacilitatorOwnDocumentiRule;
use open20\amos\documenti\rules\UpdateOwnDocumentiRule;
use open20\amos\documenti\models\Documenti;
use yii\rbac\Permission;

class m190225_154113_add_WidgetGraphicsDocumentsExplorer_all_permissions extends AmosMigrationPermissions
{
    /**
     * Use this function to map permissions, roles and associations between permissions and roles. If you don't need to
     * to add or remove any permissions or roles you have to delete this method.
     */
    protected function setAuthorizations()
    {
        $this->authorizations = [
            [
                'name' => open20\amos\documenti\widgets\graphics\WidgetGraphicsDocumentsExplorer::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso per il widget WidgetGraphicsDocumentsExplorer',
                'ruleName' => null,
                'parent' => ['ADMIN', 'BASIC_USER']
            ],
        ];
    }
}
