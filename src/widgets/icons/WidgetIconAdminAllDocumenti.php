<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti\widgets\icons
 * @category   CategoryName
 */

namespace open20\amos\documenti\widgets\icons;

use open20\amos\core\widget\WidgetIcon;
use open20\amos\core\widget\WidgetAbstract;
use open20\amos\core\icons\AmosIcons;
use open20\amos\documenti\AmosDocumenti;
use open20\amos\documenti\models\Documenti;
use open20\amos\documenti\models\search\DocumentiSearch;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * Class WidgetIconAdminAllDocumenti
 * @package open20\amos\documenti\widgets\icons
 */
class WidgetIconAdminAllDocumenti extends WidgetIcon
{

    /**
     * @inheritdoc
     */
    public function init()
    {
        
        parent::init();

        $paramsClassSpan = [
            'bk-backgroundIcon',
        ];

        $this->setLabel(AmosDocumenti::tHtml('amosdocumenti', '#documenti_widget_label_all_admin'));
        $this->setDescription(AmosDocumenti::t('amosdocumenti', '#documenti_widget_description_all_admin'));
        $this->setIcon('file-text-o');
        $this->setUrl(['/documenti/documenti/admin-all-documents']);
        $this->setCode('ADMIN-ALL-DOCUMENTI');
        $this->setModuleName('documenti');
        $this->setNamespace(__CLASS__);

        if (!empty(Yii::$app->params['dashboardEngine']) && Yii::$app->params['dashboardEngine'] == WidgetAbstract::ENGINE_ROWS) {
            $paramsClassSpan = [];
        }

        $this->setClassSpan(
            ArrayHelper::merge(
                $this->getClassSpan(),
                $paramsClassSpan
            )
        );

//        if ($this->disableBulletCounters == false) {
//            /** @var DocumentiSearch $search */
//            $search = AmosDocumenti::instance()->createModel('DocumentiSearch');
//            $this->setBulletCount(
//                $this->makeBulletCounter(
//                    Yii::$app->getUser()->getId(),
//                    Documenti::className(),
//                    $search->buildQuery([], 'admin-all')
//                )
//            );
//                        $this->trigger(self::EVENT_AFTER_COUNT);

//        }
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
                ['children' => []]
        );
    }

}
