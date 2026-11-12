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
use Yii;

/**
 * This is the base-model class for table "uploader_import_list".
 *
 * @property integer $id
 * @property string $name_file
 * @property string $path_log
 * @property integer $successfull
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 */

class UploaderImportList extends Record
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'uploader_import_list';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name_file'], 'required'],
            [['successfull', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['name_file', 'path_log'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('amosdocumenti', 'ID'),
            'name_file' => Yii::t('amosdocumenti', 'File'),
            'path_log' => Yii::t('amosdocumenti', 'File log'),
            'successfull' => Yii::t('amosdocumenti', 'Successo'),
            'created_at' => Yii::t('amosdocumenti', 'Creato il'),
            'updated_at' => Yii::t('amosdocumenti', 'Updated at'),
            'deleted_at' => Yii::t('amosdocumenti', 'Deleted at'),
            'created_by' => Yii::t('amosdocumenti', 'Created by'),
            'updated_by' => Yii::t('amosdocumenti', 'Updated at'),
            'deleted_by' => Yii::t('amosdocumenti', 'Deleted at'),
        ];
    }
    
}
