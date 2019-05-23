<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\documenti\assets
 * @category   CategoryName
 */

namespace lispa\amos\documenti\assets;

use yii\web\AssetBundle;
use lispa\amos\core\widget\WidgetAbstract;

/**
 * Class ModuleDocumentiAsset
 * @package lispa\amos\documenti\assets
 */
class ModuleDocumentiAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@vendor/lispa/amos-documenti/src/assets/web';

    /**
     * @inheritdoc
     */
    public $css = [
        'less/documents.less',
        'less/hierarchical-documents.less'
    ];

    /**
     * @inheritdoc
     */
    public $js = [
        'js/documents.js'
    ];

    /**
     * @inheritdoc
     */
    public $depends = [
    ];

    /**
     * @inheritdoc
     */
    public function init()
    {
        $moduleL = \Yii::$app->getModule('layout');

        if(!empty(\Yii::$app->params['dashboardEngine']) && \Yii::$app->params['dashboardEngine'] == WidgetAbstract::ENGINE_ROWS){
            $this->css = ['less/documents_fullsize.less'];
        }

        if (!empty($moduleL)) {
            $this->depends [] = 'lispa\amos\layout\assets\BaseAsset';
        } else {
            $this->depends [] = 'lispa\amos\core\views\assets\AmosCoreAsset';
        }
        parent::init();
    }
}
