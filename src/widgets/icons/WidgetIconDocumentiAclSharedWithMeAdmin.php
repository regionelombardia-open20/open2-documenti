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

use open20\amos\core\widget\WidgetAbstract;
use open20\amos\core\widget\WidgetIcon;
use open20\amos\documenti\AmosDocumenti;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * Class WidgetIconDocumentiAclSharedWithMeAdmin
 * @package open20\amos\documenti\widgets\icons
 */
class WidgetIconDocumentiAclSharedWithMeAdmin extends WidgetIcon
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        
        $this->setLabel(AmosDocumenti::t('amosdocumenti', '#documenti_widget_label_acl_shared_with_me'));
        $this->setDescription(AmosDocumenti::t('amosdocumenti', '#documenti_widget_description_acl_shared_with_me'));
        $this->setIcon('folder-open-o');
        $this->setUrl(['/documenti/documenti-acl/shared-with-me']);
        $this->setCode('DOCUMENTI_ACL_SHARED_WITH_ME_ADMIN');
        $this->setModuleName('documenti');
        $this->setNamespace(__CLASS__);
        
        $paramsClassSpan = (
        (!empty(Yii::$app->params['dashboardEngine']) && Yii::$app->params['dashboardEngine'] == WidgetAbstract::ENGINE_ROWS) ?
            [] :
            ['bk-backgroundIcon']
        );
        
        $this->setClassSpan(ArrayHelper::merge($this->getClassSpan(), $paramsClassSpan));
    }
}
