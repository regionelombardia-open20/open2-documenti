<?php
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