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
use lispa\amos\dashboard\models\AmosUserDashboards;
use lispa\amos\documenti\AmosDocumenti;
use lispa\amos\core\widget\WidgetAbstract;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Application as Web;

/**
 * Class WidgetIconDocumentiDashboard
 * @package lispa\amos\documenti\widgets\icons
 */
class WidgetIconDocumentiDashboard extends WidgetIcon
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

        $this->setLabel(AmosDocumenti::tHtml('amosdocumenti', '#documenti_widget_label_dashboard'));
        $this->setDescription(AmosDocumenti::t('amosdocumenti', '#documenti_widget_description_dashboard'));
        $this->setIcon('file-text-o');
        $this->setUrl(['/documenti']);
        $this->setCode('DOCUMENTI_MODULE');
        $this->setModuleName('documenti-dashboard');
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

        if (Yii::$app instanceof Web) {
            $this->setBulletCount(
                $this->makeBulletCounter(\Yii::$app->user->id)
            );
        }
    }

    /**
     * 
     * @return type
     */
    public function makeBulletCounter($user_id = null)
    {
        return $this->getBulletCountChildWidgets($user_id);
    }

    /**
     * 
     * @param type $user_id
     * @return int - the sum of bulletCount internal widget
     */
    private function getBulletCountChildWidgets($user_id = null)
    {
        $count = 0;
        try {
            /** @var AmosUserDashboards $userModuleDashboard */
            $userModuleDashboard = AmosUserDashboards::findOne([
                'user_id' => user_id,
                'module' => AmosDocumenti::getModuleName()
            ]);

            if (is_null($userModuleDashboard)) {
                return 0;
            }

            $widgetAll = \Yii::createObject(WidgetIconAllDocumenti::className());
            $widgetCreatedBy = \Yii::createObject(WidgetIconDocumentiCreatedBy::className());

            $count = $widgetAll->getBulletCount() + $widgetCreatedBy->getBulletCount();
        } catch (\Exception $ex) {
            Yii::getLogger()->log($ex->getMessage(), \yii\log\Logger::LEVEL_ERROR);
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
     *  TEMPORANEA
     * 
     * @return type
     */
    public function getWidgetsIcon()
    {
        $widgets = [];

        $WidgetIconDocumentiCategorie = new WidgetIconDocumentiCategorie();
        if ($WidgetIconDocumentiCategorie->isVisible()) {
            $widgets[] = $WidgetIconDocumentiCategorie->getOptions();
        }

        $WidgetIconDocumentiCreatedBy = new WidgetIconDocumentiCreatedBy();
        if ($WidgetIconDocumentiCreatedBy->isVisible()) {
            $widgets[] = $WidgetIconDocumentiCreatedBy->getOptions();
        }

        return $widgets;
    }

}
