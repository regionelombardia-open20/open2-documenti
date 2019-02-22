<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\documenti\widgets\icons
 * @category   CategoryName
 */

namespace lispa\amos\documenti\widgets\icons;

use lispa\amos\core\widget\WidgetIcon;
use lispa\amos\documenti\AmosDocumenti;
use lispa\amos\documenti\models\Documenti;
use lispa\amos\documenti\models\search\DocumentiSearch;
use yii\helpers\ArrayHelper;

/**
 * Class WidgetIconDocumentiCreatedBy
 * @package lispa\amos\documenti\widgets\icons
 */
class WidgetIconDocumentiCreatedBy extends WidgetIcon
{
    /**
     * @inheritdoc
     */
    public function getOptions()
    {
        $options = parent::getOptions();

        //aggiunge all'oggetto container tutti i widgets recuperati dal controller del modulo
        return ArrayHelper::merge($options, ["children" => []]);
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->setLabel(AmosDocumenti::tHtml('amosdocumenti', 'Documenti creati da me'));
        $this->setDescription(AmosDocumenti::t('amosdocumenti', "Visualizza i documenti creati dall'utente"));
        $this->setIcon('file-text-o');
        $this->setUrl(['/documenti/documenti/own-documents']);

        $DocumentiSearch = new DocumentiSearch();
        $query = $DocumentiSearch->searchCreatedByMeQuery([]);
        $count = $query->andWhere([Documenti::tableName() . '.status' => Documenti::DOCUMENTI_WORKFLOW_STATUS_BOZZA])->count();
        $this->setBulletCount($count);

        $this->setCode('DOCUMENTI_CREATEDBY');
        $this->setModuleName('documenti');
        $this->setNamespace(__CLASS__);

        $this->setClassSpan(ArrayHelper::merge($this->getClassSpan(), [
            'bk-backgroundIcon',
            'color-primary'
        ]));
    }
}
