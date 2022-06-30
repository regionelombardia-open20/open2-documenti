<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti\controllers
 * @category   CategoryName
 */

namespace open20\amos\documenti\controllers;

use open20\amos\documenti\AmosDocumenti;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/**
 * Class DocumentiAclController
 * This is the class for controller "DocumentiAclController".
 * @package open20\amos\documenti\controllers
 */
class DocumentiAclController extends \open20\amos\documenti\controllers\base\DocumentiAclController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => [
                            'shared-with-me',
                        ],
                        'roles' => [
                            'DOCUMENTI_ACL_ADMINISTRATOR',
                            'DOCUMENTI_ACL_VIEWER'
                        ]
                    ],
                    [
                        'allow' => true,
                        'actions' => [
                            'manage',
                        ],
                        'roles' => [
                            'DOCUMENTI_ACL_ADMINISTRATOR',
                        ]
                    ],
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post', 'get']
                ]
            ]
        ]);
    }
    
    /**
     * @param null string|null $currentView
     * @param int|null $parentId
     * @return string
     */
    public function actionSharedWithMe($currentView = null, $parentId = null)
    {
        Url::remember();
        
        $params = \Yii::$app->request->getQueryParams();
        if (!is_null($parentId)) { //set parent Id to filter documents within a folder
            $modelSearch = $this->getModelSearch();
            $modelSearch->parentId = $parentId;
            $this->setModelSearch($modelSearch);
        }
        
        $this->setDataProvider($this->modelSearch->searchSharedWithMe($params));
        
        $this->setTitleAndBreadcrumbs(AmosDocumenti::t('amosdocumenti', '#documents_acl_shared_with_me'));
        $this->setAvailableViews(['grid' => $this->viewGrid]);
        $this->setCurrentView($this->getAvailableView('grid'));
        $this->setListViewsParams();
        
        return $this->render('index', [
            'dataProvider' => $this->getDataProvider(),
            'model' => $this->getModelSearch(),
            'currentView' => $this->getCurrentView(),
            'availableViews' => $this->getAvailableViews(),
            'url' => ($this->url) ? $this->url : null,
            'parametro' => ($this->parametro) ? $this->parametro : null
        ]);
    }
    
    /**
     * @param null string|null $currentView
     * @param int|null $parentId
     * @return string
     */
    public function actionManage($currentView = null, $parentId = null)
    {
        Url::remember();
        
        $params = \Yii::$app->request->getQueryParams();
        if (!is_null($parentId)) { //set parent Id to filter documents within a folder
            $modelSearch = $this->getModelSearch();
            $modelSearch->parentId = $parentId;
            $this->setModelSearch($modelSearch);
        }
        
        $this->setDataProvider($this->modelSearch->searchManage($params));
        
        $this->setTitleAndBreadcrumbs(AmosDocumenti::t('amosdocumenti', '#documents_acl_manage'));
        $this->setListViewsParams();
        
        return $this->render('index', [
            'dataProvider' => $this->getDataProvider(),
            'model' => $this->getModelSearch(),
            'currentView' => $this->getCurrentView(),
            'availableViews' => $this->getAvailableViews(),
            'url' => ($this->url) ? $this->url : null,
            'parametro' => ($this->parametro) ? $this->parametro : null
        ]);
    }
}
