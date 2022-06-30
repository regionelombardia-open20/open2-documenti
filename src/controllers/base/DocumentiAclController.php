<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti\controllers\base
 * @category   CategoryName
 */

namespace open20\amos\documenti\controllers\base;

use open20\amos\core\controllers\CrudController;
use open20\amos\core\forms\CreateNewButtonWidget;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\module\BaseAmosModule;
use open20\amos\dashboard\controllers\TabDashboardControllerTrait;
use open20\amos\documenti\AmosDocumenti;
use open20\amos\documenti\assets\ModuleDocumentiAsset;
use open20\amos\documenti\models\Documenti;
use open20\amos\documenti\models\DocumentiAcl;
use open20\amos\documenti\widgets\icons\WidgetIconDocumentiAclDashboard;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/**
 * Class DocumentiAclController
 * DocumentiAclController implements the CRUD actions for DocumentiAcl model.
 *
 * @property \open20\amos\documenti\models\DocumentiAcl $model
 * @property \open20\amos\documenti\models\search\DocumentiAclSearch $modelSearch
 *
 * @package open20\amos\documenti\controllers\base
 */
abstract class DocumentiAclController extends CrudController
{
    /**
     * Trait used for initialize the tab dashboard
     */
    use TabDashboardControllerTrait;
    
    /**
     * @var string $layout
     */
    public $layout = 'main';
    
    /**
     * @var AmosDocumenti $documentsModule
     */
    public $documentsModule = null;
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->documentsModule = AmosDocumenti::instance();
    
        $this->initDashboardTrait();
        
        $this->setModelObj($this->documentsModule->createModel('DocumentiAcl'));
        $this->setModelSearch($this->documentsModule->createModel('DocumentiAclSearch'));
    
        ModuleDocumentiAsset::register(Yii::$app->view);
    
        $this->viewList = [
            'name' => 'list',
            'label' => AmosIcons::show('view-list') . Html::tag('p', AmosDocumenti::tHtml('amosdocumenti', 'Lista')),
            'url' => '?currentView=list'
        ];
    
        $this->viewGrid = [
            'name' => 'grid',
            'label' => AmosIcons::show('view-list-alt') . Html::tag('p', AmosDocumenti::tHtml('amosdocumenti', 'Tabella')),
            'url' => '?currentView=grid'
        ];
    
        $defaultViews = [
            'list' => $this->viewList,
            'grid' => $this->viewGrid
        ];
    
        $availableViews = [];
        foreach ($this->documentsModule->defaultListViews as $view) {
            if (isset($defaultViews[$view])) {
                $availableViews[$view] = $defaultViews[$view];
            }
        }
    
        $this->setAvailableViews($availableViews);
        
        parent::init();
        
        $this->setUpLayout();
    }
    
    /**
     * Used for set page title and breadcrumbs.
     * @param string $pageTitle
     */
    public function setTitleAndBreadcrumbs($documentiPageTitle)
    {
        $parentId = Yii::$app->request->getQueryParam('parentId');
        if (isset($parentId)) {
            /** @var DocumentiAcl $documentiAclModel */
            $documentiAclModel = $this->documentsModule->createModel('DocumentiAcl');
            $folder = $documentiAclModel::findOne($parentId);
            if (!is_null($folder)) {
                $documentiPageTitle = $folder->getTitle();
            }
        }
        Yii::$app->session->set('previousTitle', $documentiPageTitle);
        Yii::$app->session->set('previousUrl', Url::previous());
        Yii::$app->view->title = $documentiPageTitle;
        Yii::$app->view->params['breadcrumbs'][] = ['label' => $documentiPageTitle];
    }
    
    /**
     * Set a view param used in \open20\amos\core\forms\CreateNewButtonWidget
     */
    private function setCreateNewBtnLabel()
    {
        $parentId = null;
        $isDriveFolder = false;
        
        if (!is_null(Yii::$app->request->getQueryParam('parentId'))) {
            $parentId = Yii::$app->request->getQueryParam('parentId');
        }
        
        $createNewBtnParams = [
            'createNewBtnLabel' => AmosDocumenti::t('amosdocumenti', 'Aggiungi nuovo documento'),
            'urlCreateNew' => ['/documenti/documenti/create', 'isAcl' => 1, 'parentId' => $parentId],
            'otherOptions' => ['title' => AmosDocumenti::t('amosdocumenti', 'Aggiungi nuovo documento')]
        ];
        
        if ($this->documentsModule->enableFolders) {
            $btnBack = '';
            // find Url to navigate previous folder
            if (!is_null($parentId)) {
                /** @var DocumentiAcl $documentiModel */
                $documentiModel = $this->documentsModule->createModel('DocumentiAcl');
                $parent = $documentiModel::findOne($parentId);
                if (!is_null($parent)) {
                    $url = [$this->action->id, 'parentId' => $parent->parent_id];
                    $btnBack = Html::a(AmosDocumenti::tHtml('amosdocumenti', '#btn_back_prev_folder'), $url, ['class' => 'btn btn-secondary']);
                    if ($parent->drive_file_id) {
                        $isDriveFolder = true;
                    }
                }
            } else {
                /** @var DocumentiAcl $parent */
                $parent = $this->documentsModule->createModel('DocumentiAcl');
                $parent->is_folder = Documenti::IS_FOLDER;
            }
    
            $btnNewFolder = '';
            if ((Yii::$app->controller->action->id != 'shared-with-me') && Yii::$app->user->can('DOCUMENTI_ACL_ADMINISTRATOR')) {
                $btnNewFolder = CreateNewButtonWidget::widget([
                    'createNewBtnLabel' => AmosDocumenti::t('amosdocumenti', '#btn_new_folder'),
                    'urlCreateNew' => ['/documenti/documenti/create', 'isFolder' => true, 'parentId' => $parentId],
                    'otherOptions' => ['title' => AmosDocumenti::t('amosdocumenti', '#btn_new_folder')]
                ]);
            }
            
            if (!Yii::$app->user->can('DOCUMENTIACL_CREATE', ['model' => $parent])) {
                $this->view->params['forceCreateNewButtonWidget'] = true;
                $layout = $btnBack;
            } else {
                $layout = $btnBack;
                if (!$isDriveFolder) {
                    $layout .= (!is_null($parentId) ? "{buttonCreateNew}" : '') . $btnNewFolder;
                }
            }
            
            $createNewBtnParams = ArrayHelper::merge($createNewBtnParams, ['layout' => $layout]);
        }
        
        Yii::$app->view->params['createNewBtnParams'] = $createNewBtnParams;
    }
    
    /**
     * This method is useful to set all common params for all list views.
     */
    protected function setListViewsParams()
    {
        $this->child_of = WidgetIconDocumentiAclDashboard::className();
        $this->setCreateNewBtnLabel();
        $this->setUpLayout('list');
        $this->view->params['currentDashboard'] = $this->getCurrentDashboard();
        Yii::$app->session->set(AmosDocumenti::beginCreateNewSessionKey(), Url::previous());
        Yii::$app->session->set(AmosDocumenti::beginCreateNewSessionKeyDateTime(), date('Y-m-d H:i:s'));
    }
    
    /**
     * Lists all DocumentiAcl models.
     * @return mixed
     */
    public function actionIndex($layout = null)
    {
        if (Yii::$app->user->can('DOCUMENTI_ACL_ADMINISTRATOR')) {
            return $this->redirect(['/documenti/documenti-acl/manage']);
        } else {
            return $this->redirect(['/documenti/documenti-acl/shared-with-me']);
        }
    }
    
    /**
     * Displays a single DocumentiAcl model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        Url::remember();
        $this->model = $this->findModel($id);
        return $this->render('view', ['model' => $this->model]);
    }
    
    /**
     * Deletes an existing DocumentiAcl model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->model = $this->findModel($id);
        if ($this->model) {
            $this->model->delete();
            if (!$this->model->hasErrors()) {
                Yii::$app->getSession()->addFlash('success', BaseAmosModule::t('amoscore', 'Element deleted successfully.'));
            } else {
                Yii::$app->getSession()->addFlash('danger', BaseAmosModule::t('amoscore', 'You are not authorized to delete this element.'));
            }
        } else {
            Yii::$app->getSession()->addFlash('danger', BaseAmosModule::tHtml('amoscore', 'Element not found.'));
        }
        return $this->redirect(['index']);
    }
}
