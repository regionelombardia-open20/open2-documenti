<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open20\amos\documenti\widgets
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

namespace open20\amos\documenti\widgets;

use open20\amos\core\forms\AmosOwlCarouselWidget;
use open20\amos\documenti\AmosDocumenti;
use open20\amos\documenti\models\Documenti;
use yii\db\ActiveQuery;

/**
 * Class DocumentsOwlCarouselWidget
 * @package open20\amos\documenti\widgets
 */
class DocumentsOwlCarouselWidget extends AmosOwlCarouselWidget
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->setItems($this->getDocumentsItems());

        parent::init();
    }

    /**
     * @return array
     */
    protected function getDocumentsItems()
    {
        $documentsHighlights = [];
        $highlightsModule = \Yii::$app->getModule('highlights');

        /** @var AmosDocumenti $documentsModule */
        $documentsModule = AmosDocumenti::instance();

        if (!is_null($highlightsModule)) {
            /** @var \amos\highlights\Module $highlightsModule */
            $documentsHighlightsIds = $highlightsModule->getHighlightedContents($documentsModule->model('Documenti'));
            /** @var Documenti $documentiModel */
            $documentiModel = $documentsModule->createModel('Documenti');
            /** @var ActiveQuery $query */
            $query = $documentiModel::find();
            $query->distinct();
            $query->andWhere(['id' => $documentsHighlightsIds]);
            $query->andWhere(['status' => Documenti::DOCUMENTI_WORKFLOW_STATUS_VALIDATO]);
            $query->andWhere(['or',
                ['data_rimozione' => null],
                ['>=', 'data_rimozione', date('Y-m-d')]
            ]);
            $documentsHighlights = $query->all();
        }

        return $documentsHighlights;
    }
}
