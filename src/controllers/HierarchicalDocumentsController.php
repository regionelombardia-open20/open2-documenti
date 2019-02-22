<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\documenti\controllers
 * @category   CategoryName
 */

namespace lispa\amos\documenti\controllers;

use lispa\amos\core\controllers\CrudController;
use lispa\amos\core\helpers\Html;
use lispa\amos\core\icons\AmosIcons;
use lispa\amos\documenti\AmosDocumenti;
use lispa\amos\documenti\assets\ModuleDocumentiAsset;
use lispa\amos\documenti\models\Documenti;
use lispa\amos\documenti\models\search\DocumentiSearch;
use lispa\amos\documenti\widgets\graphics\WidgetGraphicsHierarchicalDocuments;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/**
 * Class HierarchicalDocumentsController
 *
 * @property \lispa\amos\documenti\models\Documenti $model
 *
 * @package lispa\amos\documenti\controllers
 */
class HierarchicalDocumentsController extends CrudController
{
    /**
     * @var string $layout
     */
    public $layout = 'list';

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->setModelObj(new Documenti());
        $this->setModelSearch(new DocumentiSearch());

        ModuleDocumentiAsset::register(Yii::$app->view);

        $this->setAvailableViews([
            'icon' => [
                'name' => 'icon',
                'label' => AmosIcons::show('grid') . Html::tag('p', AmosDocumenti::tHtml('amosdocumenti', 'Icon')),
                'url' => '?currentView=icon'
            ],
            'grid' => [
                'name' => 'grid',
                'label' => AmosIcons::show('view-list-alt') . Html::tag('p', AmosDocumenti::tHtml('amosdocumenti', 'Table')),
                'url' => '?currentView=grid'
            ],
        ]);

        parent::init();
        $this->layout = false;
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = ArrayHelper::merge(parent::behaviors(), [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => [
                            'render-hierarchical-documents-widget',
                        ],
                        'roles' => [
                            'LETTORE_DOCUMENTI',
                            'AMMINISTRATORE_DOCUMENTI',
                            'CREATORE_DOCUMENTI',
                            'FACILITATORE_DOCUMENTI',
                            'VALIDATORE_DOCUMENTI'
                        ]
                    ]
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post', 'get'],
                ]
            ]
        ]);
        return $behaviors;
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function actionRenderHierarchicalDocumentsWidget()
    {
        Url::remember();
        $this->layout = false;
        return WidgetGraphicsHierarchicalDocuments::widget();
    }
}
