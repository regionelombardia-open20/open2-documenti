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
use Yii;
use yii\helpers\ArrayHelper;

/**
 * Class WidgetIconDocumentiDaValidare
 * @package lispa\amos\documenti\widgets\icons
 */
class WidgetIconDocumentiDaValidare extends WidgetIcon
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

        $this->setLabel(AmosDocumenti::tHtml('amosdocumenti', 'Documenti da validare'));
        $this->setDescription(AmosDocumenti::t('amosdocumenti', 'Visualizza i documenti da validare da parte dell\'utente'));

        $this->setIcon('file-text-o');

        $this->setUrl(['/documenti/documenti/to-validate-documents']);

        $DocumentiSearch = new DocumentiSearch();
        $notifier = \Yii::$app->getModule('notify');
        $count = 0;
        if ($notifier) {
            $count = $notifier->countNotRead(Yii::$app->getUser()->id, Documenti::class, $DocumentiSearch->searchToValidateQuery([]));
        }
        $this->setBulletCount($count);

        $this->setCode('DOCUMENTI_VALIDATE');
        $this->setModuleName('documenti');
        $this->setNamespace(__CLASS__);

        $this->setClassSpan(ArrayHelper::merge($this->getClassSpan(), [
            'bk-backgroundIcon',
            'color-primary'
        ]));
    }
}
