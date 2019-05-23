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
use lispa\amos\dashboard\models\AmosWidgets;
use lispa\amos\documenti\AmosDocumenti;
use lispa\amos\documenti\models\Documenti;
use lispa\amos\documenti\models\search\DocumentiSearch;
use lispa\amos\core\widget\WidgetAbstract;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * Class WidgetIconDocumenti
 * @package lispa\amos\documenti\widgets\icons
 */
class WidgetIconDocumenti extends WidgetIcon
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

        $this->setLabel(AmosDocumenti::tHtml('amosdocumenti', '#documenti_widget_label_own_interest'));
        $this->setDescription(AmosDocumenti::t('amosdocumenti', '#documenti_widget_description_own_interest'));
        $this->setIcon('file-text-o');
        $this->setUrl(['/documenti/documenti/own-interest-documents']);
        $this->setCode('DOCUMENTI');
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
            $this->makeBulletCounter(Yii::$app->getUser()->id)
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
        $notifier = \Yii::$app->getModule('notify');

        $count = 0;
        if ($notifier) {
            $count = $notifier->countNotRead(
                $user_id,
                Documenti::class,
                $DocumentiSearch->searchOwnInterestsQuery([], 'own-interest')
            );
        }

        return $count;
    }

    /**
     * Aggiunge all'oggetto container tutti i widgets recuperati dal controller del modulo
     * 
     * @inheritdoc
     */
    public function getOptions()
    {
        return ArrayHelper::merge(
            parent::getOptions(),
            ['children' => $this->getWidgetsIcon()]
        );
    }

    /**
     * 
     * @return type
     */
    public function getWidgetsIcon()
    {
        return AmosWidgets::find()
            ->andWhere(['child_of' => self::className()])
            ->all();
    }

}
