<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open20\amos\documenti\widgets\icons
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

namespace open20\amos\documenti\widgets\icons;

use open20\amos\core\icons\AmosIcons;
use open20\amos\core\widget\WidgetIcon;
use open20\amos\core\widget\WidgetAbstract;
use open20\amos\dashboard\models\AmosUserDashboards;
use open20\amos\documenti\AmosDocumenti;
//use open20\amos\documenti\models\Documenti;
//use open20\amos\documenti\models\search\DocumentiSearch;
use open20\amos\core\record\Record;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Application as Web;

/**
 * Class WidgetIconDocumentiDashboard
 * @package open20\amos\documenti\widgets\icons
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
        ];

        $this->setLabel(AmosDocumenti::tHtml('amosdocumenti', '#documenti_widget_label_dashboard'));
        $this->setDescription(AmosDocumenti::t('amosdocumenti', '#documenti_widget_description_dashboard'));
        $this->setIcon('file-text-o');
        $this->setUrl(['/documenti/documenti/all-documents']);
        $this->setCode('DOCUMENTI_MODULE');
        $this->setModuleName('documenti');
        $this->setNamespace(__CLASS__);

        if(!empty(Yii::$app->params['twoColorIcons'])){
            $this->setIconFramework(AmosIcons::TWOTONES);
            $this->setIcon('file_copy');
        }else if (!empty(Yii::$app->params['dashboardEngine']) && Yii::$app->params['dashboardEngine'] == WidgetAbstract::ENGINE_ROWS) {
            $paramsClassSpan = [];
        }

        $this->setClassSpan(
            ArrayHelper::merge(
                $this->getClassSpan(), $paramsClassSpan
            )
        );

        // Read and reset counter from bullet_counters table, bacthed calculated!
        if ($this->disableBulletCounters == false) {
            $this->setBulletCount(Record::getStaticBullet(Record::BULLET_TYPE_ALL, false, 'documenti', false, true));
        }
    }

    /**
     * Aggiunge all'oggetto container tutti i widgets recuperati dal controller del modulo
     * 
     * @inheritdoc
     */
    public function getOptions()
    {
        return ArrayHelper::merge(
                parent::getOptions(), ['children' => $this->getWidgetsIcon()]
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