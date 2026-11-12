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

use open20\amos\documenti\AmosDocumenti;
use open20\amos\documenti\models\Documenti;
use open20\amos\core\record\Record;
use yii\helpers\StringHelper;
/**
 * This is the model class for table "admin_storage_folder".
 *
 * @property int $id
 * @property string $name
 * @property int $parent_id
 * @property int $timestamp_create
 * @property int $is_deleted
 *
 */
final class DocumentiCartellePath extends Record 
{
    
    const PATH_SEPARATOR = '/';
    const MAX_CHARACTER = 15;
    const START_LEVEL = 1;

    /**
     * @inheritdoc
     */
    public function init() {
        // call parent
        parent::init();
    }

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'documenti_cartelle_path';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id_doc_folder', 'level'], 'required'],
            [['id_doc_folder', 'level','id_folder'], 'integer'],
        ];
    }
    
    /**
     * funzione che crea il path dei documenti già inseriti a db
     *
     * @param [type] $doc
     * @param [type] $level
     * @param [type] $parent_array
     * @return void
     */
    public static function generatePath($doc,$level,$parent_array){
        if ($doc) {
	        $parent_array[$doc->id] = $doc->parent_id;
            if($doc->parent_id){
                $documento = Documenti::findOne($doc->parent_id);
                $level++;
                return self::generatePath($documento,$level,$parent_array);
            }
        }

        return $parent_array;
    }
	
    /**
     *
     * @param [type] $result
     * @param [type] $doc_id
     * @return void
     */
    public static function savePath($result,$doc_id){
        $level = count($result);
        try{
            DocumentiCartellePath::deleteAll(['id_doc_folder' => $doc_id]);
            foreach ($result as $key => $value){
                $documentoPath = new DocumentiCartellePath();
                $documentoPath->id_doc_folder = $doc_id;
                $documentoPath->level = $level;
                $documentoPath->id_folder = $value;
                $documentoPath->save();
                $level--;
            }
        } catch (\Exception $e){
            Yii::$app->getSession()->addFlash(
                'danger',
                AmosDocumenti::tHtml(
                    'amosdocumenti',
                    'Si &egrave; verificato un errore durante il salvataggio'
                )
            );
        }
    }
    
    /**
     *
     * @param [type] $document
     * @return void
     */
    public static function getPath($document){
        $result = self::generatePath($document,self::START_LEVEL,[]);
        $stringa = self::PATH_SEPARATOR;
        $documenti = AmosDocumenti::Instance();
        ksort($result);
        foreach ($result as $key => $value){
            if($value){
                $path = Documenti::findOne($value);
                if ($documenti->truncateGetPath) {
                    $stringa .= StringHelper::truncate(
                            $path->titolo,
                            self::MAX_CHARACTER,
                            '...',
                            null,
                            false
                        )
                        . self::PATH_SEPARATOR;
                } else {
                    $stringa .= $path->titolo . self::PATH_SEPARATOR;
                }
            }
        }
        return $stringa;
    }

}
