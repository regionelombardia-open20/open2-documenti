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

/**
 * Created by PhpStorm.
 * User: michele.lafrancesca
 * Date: 10/06/2019
 * Time: 15:52
 */

namespace open20\amos\documenti\widgets;

use open20\amos\documenti\AmosDocumenti;
use open20\amos\documenti\models\Documenti;
use yii\base\Widget;


class DocumentiTreeInfo extends Widget
{

    public $parentId;
    public $isTeams = false;

    public function init()
    {
        parent::init();
       if (is_null($this->parentId)) {
            throw new \Exception(AmosDocumenti::t('amosdocumenti', 'Missing model'));
        }
       \Yii::$app->params['twoColorIcons'] = true;
    }

    public function run()
    {
        $model = Documenti::findOne($this->parentId);
        $document_tree = [];
		if($model){

			$document_tree []= [
				'id' => $model->id,
				'titolo' => $model->titolo,
			];
			
			while ($model->parent_id){
				$model = Documenti::findOne($model->parent_id);
				$document_tree []= [
					'id' => $model->id,
					'titolo' => $model->titolo,
				];
			   
			}
            if($this->isTeams){
                $document_tree []= [
                    'titolo' => 'Teams',
                    'type' => 'root_teams'
                ];
            }
		}
        
        return $this->render('document_subtitle_tree_widget', ['document_tree' => $document_tree]);

    }
    
    
    

}