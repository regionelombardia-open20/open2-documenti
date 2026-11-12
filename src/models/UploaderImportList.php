<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open20\amos\documenti\models
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

/**
 * This is the model class for table "uploader_import_list".
 */
class UploaderImportList extends \open20\amos\documenti\models\base\UploaderImportList
{
    /**
     * @return array|null
     */
    public function representingColumn()
    {
        return [
            //inserire il campo o i campi rappresentativi del modulo
        ];
    }

    /**
     * @return array
     */
    public function attributeHints()
    {
        return [
        ];
    }

    /**
     * Returns the text hint for the specified attribute.
     * @param string $attribute the attribute name
     * @return string the attribute hint
     */
    public function getAttributeHint($attribute)
    {
        $hints = $this->attributeHints();

        return isset($hints[$attribute]) ? $hints[$attribute] : null;
    }

    /**
     * @return array
     */
    public function getEditFields()
    {
        $labels = $this->attributeLabels();

        return [
            [
                'slug' => 'name_file',
                'label' => $labels['name_file'],
                'type' => 'string'
            ],
            [
                'slug' => 'path_log',
                'label' => $labels['path_log'],
                'type' => 'string'
            ],
            [
                'slug' => 'successfull',
                'label' => $labels['successfull'],
                'type' => 'integer'
            ],
        ];
    }

    /**
     * @return string
     */
    public function getPathForLog()
    {
        $date = new \DateTime();
        $basePath = \Yii::getAlias('@backend/web/') . '/reports_import/';
        if (!file_exists($basePath)) {
            mkdir($basePath, 0777, true);
        }
        return $basePath . $date->getTimestamp() . '.txt';
    }
}
