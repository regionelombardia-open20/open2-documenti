<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\documenti\widgetRs\graphics
 * @category   CategoryName
 */

namespace lispa\amos\documenti\widgets\graphics;

use lispa\amos\core\widget\WidgetGraphic;
use lispa\amos\documenti\AmosDocumenti;
use lispa\amos\documenti\models\search\DocumentiSearch;
use lispa\amos\notificationmanager\base\NotifyWidgetDoNothing;

class WidgetGraphicsDocumentsExplorer extends WidgetGraphic
{
    /**
     * @inheritdocF
     */
    public function init()
    {
        parent::init();

        $this->setCode('DOCUMENTS_EXPLORER_GRAPHIC');
        $this->setLabel(AmosDocumenti::tHtml('amosdocumenti', 'Documenti'));
        $this->setDescription(AmosDocumenti::t('amosdocumenti', 'Naviga tra i documenti'));
    }

    public function getHtml()
    {
            $viewToRender = 'documents-explorer/documents_explorer';
            return $this->render($viewToRender);
    }
}