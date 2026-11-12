<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open20\amos\documenti\models\base
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

namespace open20\amos\documenti\models\base;

use open20\amos\core\record\Record;
use open20\amos\documenti\AmosDocumenti;

/**
 * Class DocumentiCategoryRolesMm
 *
 * This is the base-model class for table "documenti_category_roles_mm".
 *
 * @property integer $id
 * @property integer $documenti_categorie_id
 * @property string $role
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @property \open20\amos\documenti\models\DocumentiCategorie $documentiCategorie
 *
 * @package open20\amos\documenti\models\base
 */
class DocumentiCategoryRolesMm extends Record
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'documenti_category_roles_mm';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['documenti_categorie_id', 'role'], 'required'],
            [['documenti_categorie_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['role'], 'string', 'max' => 255],
            [
                ['documenti_categorie_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => AmosDocumenti::instance()->model('DocumentiCategorie'),
                'targetAttribute' => ['documenti_categorie_id' => 'id']
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => AmosDocumenti::t('amosdocumenti', 'ID'),
            'documenti_categorie_id' => AmosDocumenti::t('amosdocumenti', 'Documenti Category ID'),
            'role' => AmosDocumenti::t('amosdocumenti', 'Community'),
            'created_at' => AmosDocumenti::t('amosdocumenti', 'Created at'),
            'updated_at' => AmosDocumenti::t('amosdocumenti', 'Updated at'),
            'deleted_at' => AmosDocumenti::t('amosdocumenti', 'Deleted at'),
            'created_by' => AmosDocumenti::t('amosdocumenti', 'Created by'),
            'updated_by' => AmosDocumenti::t('amosdocumenti', 'Updated by'),
            'deleted_by' => AmosDocumenti::t('amosdocumenti', 'Deleted by'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocumentiCategorie()
    {
        return $this->hasOne(AmosDocumenti::instance()->model('DocumentiCategorie'), ['id' => 'documenti_categorie_id']);
    }
}
