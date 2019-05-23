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
use lispa\amos\core\widget\WidgetAbstract;
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
    public function init()
    {
        parent::init();

        $paramsClassSpan = [
            'bk-backgroundIcon',
            'color-primary'
        ];

        $this->setLabel(AmosDocumenti::tHtml('amosdocumenti', '#documenti_widget_label_created_by'));
        $this->setDescription(AmosDocumenti::t('amosdocumenti', '#documenti_widget_description_created_by'));
        $this->setIcon('file-text-o');
        $this->setUrl(['/documenti/documenti/own-documents']);
        $this->setCode('DOCUMENTI_CREATEDBY');
        $this->setModuleName('documenti');
        $this->setNamespace(__CLASS__);

        if (!empty(\Yii::$app->params['dashboardEngine']) && \Yii::$app->params['dashboardEngine'] == WidgetAbstract::ENGINE_ROWS) {
            $paramsClassSpan = [];
        }

        $this->setClassSpan(
            ArrayHelper::merge(
                $this->getClassSpan(),
                $paramsClassSpan
            )
        );

        $this->setBulletCount(
            $this->makeBulletCounter(null)
        );
    }

    /**
     * 
     * @param type $user_id
     * @return type
     */
    public function makeBulletCounter($user_id = null)
    {
        $DocumentiSearch = new DocumentiSearch();
        $query = $DocumentiSearch->searchCreatedByMeQuery([]);

        return $query
            ->andWhere([Documenti::tableName() . '.status' => Documenti::DOCUMENTI_WORKFLOW_STATUS_BOZZA])
            ->asArray()
            ->count();
    }

    /**
     * Aggiunge all'oggetto container tutti i widgets recuperati dal controller del modulo
     *  
     * @inheritdoc
     */
    public function getOptions()
    {
        return ArrayHelper::merge(
            $options = parent::getOptions(),
            ['children' => []]
        );
    }

}
