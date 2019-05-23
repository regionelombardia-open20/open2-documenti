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
use Yii;
use yii\helpers\ArrayHelper;

/**
 * Class WidgetIconAdminAllDocumenti
 * @package lispa\amos\documenti\widgets\icons
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
            'color-primary'
        ];

        $this->setLabel(AmosDocumenti::tHtml('amosdocumenti', '#documenti_widget_label_all_admin'));
        $this->setDescription(AmosDocumenti::t('amosdocumenti', '#documenti_widget_description_all_admin'));
        $this->setIcon('file-text-o');
        $this->setUrl(['/documenti/documenti/admin-all-documents']);
        $this->setCode('ADMIN-ALL-DOCUMENTI');
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
                $DocumentiSearch->buildQuery([], 'admin-all')
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
            ['children' => []]
        );
    }

}
