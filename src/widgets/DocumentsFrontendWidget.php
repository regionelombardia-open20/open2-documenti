<?php
/**
 * Created by PhpStorm.
 * User: michele.lafrancesca
 * Date: 15/11/2018
 * Time: 16:06
 */

namespace lispa\amos\documenti\widgets;

use lispa\amos\documenti\models\Documenti;
use yii\base\Widget;
use yii\data\ActiveDataProvider;

class DocumentsFrontendWidget extends Widget
{
    const TYPE_HIGHLIGHTS = 'highlights';
    const TYPE_FRONTEND   = 'frontend';
    const TYPE_ALL        = 'all';

    public $category;
    public $type                    = DocumentsFrontendWidget::TYPE_ALL;
    public $statuses                = [];
    public $validated_at_least_once = false;
    public $queryOrderBy;
    public $view_path               = '@vendor/lispa/amos-documenti/src/widgets/views/documents_frontend_item';
    public $paginationPageSize = 20;

    /**
     *
     */
    public function init()
    {
        parent::init();
    }

    public function run()
    {
        $query = Documenti::find();

        if ($this->type == DocumentsFrontendWidget::TYPE_FRONTEND) {
            $query->andWhere(['primo_piano' => 1]);
        } else if ($this->type == DocumentsFrontendWidget::TYPE_HIGHLIGHTS) {
            $query->andWhere(['in_evidenza' => 1]);
        }

        if ($this->validated_at_least_once) {
            $query->innerJoin('amos_workflow_transitions_log', 'owner_primary_key = documenti.id')
                ->andWhere(['end_status' => Documenti::DOCUMENTI_WORKFLOW_STATUS_VALIDATO]);
        } else {
            if (!empty($this->statuses)) {
                $query->andWhere(['status' => $this->statuses]);
            }
        }


        if (!empty($this->category)) {
            $query->andWhere(['documenti_categorie_id' => $this->category]);
        }

        if (!empty($this->queryOrderBy)) {
            $query->orderBy("{$this->queryOrderBy}");
        }


        /** @var  $dataProvider ActiveDataProvider*/
        $dataProvider = new ActiveDataProvider([
            'query' => $query
        ]);
        $dataProvider->pagination->pageSize = $this->paginationPageSize;

        return $this->render('documents_frontend_index', ['dataProvider' => $dataProvider, 'view_item' => $this->view_path]);
    }
}