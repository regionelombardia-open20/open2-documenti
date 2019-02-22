<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\documenti\widgets
 * @category   CategoryName
 */

namespace lispa\amos\documenti\widgets;

use lispa\amos\core\forms\AmosOwlCarouselWidget;
use lispa\amos\documenti\models\Documenti;
use yii\db\ActiveQuery;

/**
 * Class DocumentsOwlCarouselWidget
 * @package lispa\amos\documenti\widgets
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

        if (!is_null($highlightsModule)) {
            /** @var \amos\highlights\Module $highlightsModule */
            $documentsHighlightsIds = $highlightsModule->getHighlightedContents(Documenti::className());
            /** @var ActiveQuery $query */
            $query = Documenti::find();
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
