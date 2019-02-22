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
    public function getOptions()
    {
        $options = parent::getOptions();

        //aggiunge all'oggetto container tutti i widgets recuperati dal controller del modulo
        return ArrayHelper::merge($options, ["children" => $this->getWidgetsIcon()]);
    }

    public function getWidgetsIcon()
    {
        return AmosWidgets::find()
            ->andWhere([
                'child_of' => self::className()
            ])->all();
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->setLabel(AmosDocumenti::tHtml('amosdocumenti', 'Documenti di mio interesse'));
        $this->setDescription(AmosDocumenti::t('amosdocumenti', 'Visualizza i documenti di mio interesse'));
        $this->setIcon('file-text-o');
        $this->setUrl(['/documenti/documenti/own-interest-documents']);
        $this->setCode('DOCUMENTI');
        $this->setModuleName('documenti');
        $this->setNamespace(__CLASS__);

        $DocumentiSearch = new DocumentiSearch();
        $notifier = \Yii::$app->getModule('notify');
        $count = 0;
        if ($notifier) {
            $count = $notifier->countNotRead(Yii::$app->getUser()->id, Documenti::class, $DocumentiSearch->searchOwnInterestsQuery([], 'own-interest'));
        }
        $this->setBulletCount($count);

        $this->setClassSpan(ArrayHelper::merge($this->getClassSpan(), [
            'bk-backgroundIcon',
            'color-primary'
        ]));
    }
}
