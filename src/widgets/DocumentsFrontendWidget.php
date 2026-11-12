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
 * Date: 15/11/2018
 * Time: 16:06
 */

namespace open20\amos\documenti\widgets;

use open20\amos\documenti\AmosDocumenti;
use open20\amos\documenti\models\Documenti;
use yii\base\Widget;
use yii\data\ActiveDataProvider;

class DocumentsFrontendWidget extends Widget {

    const TYPE_HIGHLIGHTS = 'highlights';
    const TYPE_FRONTEND = 'frontend';
    const TYPE_ALL = 'all';

    public $tags = [];
    public $andWhereInIds = [];
    public $category;
    public $type = DocumentsFrontendWidget::TYPE_ALL;
    public $statuses = [];
    public $validated_at_least_once = false;
    public $queryOrderBy;
    public $view_path = '@vendor/open20/amos-documenti/src/widgets/views/documents_frontend_item';
    public $paginationPageSize = 20;
    public $showPageSummary = true;

    /**
     * @inheritdoc
     */
    public function init() {
        parent::init();
    }

    /**
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function run() {
        /** @var AmosDocumenti $documentsModule */
        $documentsModule = AmosDocumenti::instance();

        /** @var Documenti $documentiModel */
        $documentiModel = $documentsModule->createModel('Documenti');
        $query = $documentiModel::find();

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

        if (!empty($this->tags) && count($this->tags) > 0) {
            $newsClassname = $documentsModule->model('Documenti');
            $newsClassname = addslashes($newsClassname);
            $query->leftJoin('entitys_tags_mm', "entitys_tags_mm.record_id=news.id AND entitys_tags_mm.classname='$newsClassname'")
                ->andFilterWhere(['entitys_tags_mm.tag_id' => $this->tags]);
        }

        if (!empty($this->andWhereInIds)) {
            $query->andFilterWhere(['documenti.id' => $this->andWhereInIds]);
        }

        if (!empty($this->category)) {
            $query->andWhere(['documenti_categorie_id' => $this->category]);
        }

        if (!empty($this->queryOrderBy)) {
            $query->orderBy("{$this->queryOrderBy}");
        }


        /** @var  $dataProvider ActiveDataProvider */
        $dataProvider = new ActiveDataProvider([
            'query' => $query
        ]);

        $dataProvider->pagination->pageSize = $this->paginationPageSize;

        return $this->render(
            'documents_frontend_index',
            [
                'dataProvider' => $dataProvider,
                'view_item' => $this->view_path,
                'widget' => $this
            ]
        );
    }
}
