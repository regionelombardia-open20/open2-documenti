<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open20\amos-documenti
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

namespace open20\amos\documenti\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "admin_storage_file".
 */
final class StorageFile extends ActiveRecord
{

    /**
     * @inheritdoc
     */
    public function init()
    {
        // call parent
        parent::init();

    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'admin_storage_file';
    }
    
    /**
     * @inheritdoc
     */
    public static function find()
    {
        return parent::find();
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name_original', 'name_new', 'mime_type', 'name_new_compound', 'extension', 'hash_file', 'hash_name'], 'required'],
            [['folder_id', 'file_size', 'is_deleted'], 'safe'],
            [['is_hidden'], 'boolean'],
            [['inline_disposition', 'upload_timestamp', 'upload_user_id'], 'integer'],
            [['caption'], 'string'],
        ];
    }
}
