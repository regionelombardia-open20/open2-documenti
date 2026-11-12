<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open20\amos\documenti\widgets\graphics
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

namespace open20\amos\documenti\widgets\graphics;

use open20\amos\core\widget\WidgetGraphic;
use open20\amos\documenti\AmosDocumenti;
use open20\amos\documenti\models\Documenti;
use open20\amos\documenti\models\search\DocumentiSearch;
use open20\amos\notificationmanager\base\NotifyWidgetDoNothing;
use yii\helpers\ArrayHelper;

/**
 * Class WidgetGraphicsCmsUltimiDocumenti
 * @package open20\amos\documenti\widgets\graphics
 */
class WidgetGraphicsCmsUltimiDocumenti extends WidgetGraphic {

    /**
     * @var array $filterDocumentCategoryId
     */
    public $filterDocumentCategoryId = [];

    /**
     * @var string $widgetTitle
     */
    public $widgetTitle = '';

    /**
     * @var string|array $linkReadAll
     */
    public $linkReadAll = '';

    /**
     * @var bool
     */
    public $excludeFolders = false;

    /**
     * @inheritdoc
     */
    public function init() {
        $this->widgetTitle = AmosDocumenti::tHtml('amosdocumenti', 'Documenti');
        $this->linkReadAll = ['/documenti'];
        /** @var AmosDocumenti $documentsModule */
        $documentsModule = AmosDocumenti::instance();
        $this->excludeFolders = $documentsModule->excludeFoldersInWGCmsUltimiDocumenti;

        parent::init();

        $this->setCode('ULTIME_DOCUMENTI_GRAPHIC');
        $this->setLabel(AmosDocumenti::t('amosdocumenti', '#widget_graphic_cms_last_documents_label'));
        $this->setDescription(AmosDocumenti::t('amosdocumenti', '#widget_graphic_cms_last_documents_description'));
    }

    /**
     * @inheritdoc
     */
    public function getHtml() {
        $listaDocumenti = $this->getDataProvider();

        if (isset(\Yii::$app->params['showWidgetEmptyContent']) && \Yii::$app->params['showWidgetEmptyContent'] == false) {
            if ($listaDocumenti->getTotalCount() == 0) {
                return false;
            }
        }

        return $this->render('@vendor/open20/amos-documenti/src/widgets/graphics/views/ultimi_documenti_cms', [
                    'listaDocumenti' => $listaDocumenti,
                    'widget' => $this,
                    'toRefreshSectionId' => 'widgetGraphicLatestDocumenti'
        ]);
    }

    /**
     * Returns the widget data provider.
     * @return \yii\data\ActiveDataProvider
     */
    protected function getDataProvider() {
        /** @var DocumentiSearch $search */
        $search = AmosDocumenti::instance()->createModel('DocumentiSearch');
        $search->setNotifier(new NotifyWidgetDoNothing());

        $documentsModule = \Yii::$app->getModule(AmosDocumenti::getModuleName());
        if ($documentsModule->showAllStatusesAllDocument) {
            $params = ArrayHelper::merge(
                            $_GET, [
                        'DocumentiSearch' => ['ricerca_sottocartelle' => 1],
            ]);
            $listaDocumenti = $search->searchAllInAllStatuses($params, 3);
        } else {
            $listaDocumenti = $search->lastDocuments($_GET, 3);
        }
        if (!empty($this->filterDocumentCategoryId)) {
            $listaDocumenti->query->andWhere([Documenti::tableName() . '.documenti_categorie_id' => $this->filterDocumentCategoryId]);
        }

        if ($this->excludeFolders) {
            $listaDocumenti->query->andWhere([Documenti::tableName() . '.is_folder' => false]);
        }

        return $listaDocumenti;
    }

}
