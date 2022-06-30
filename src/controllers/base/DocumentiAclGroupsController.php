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
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\module\BaseAmosModule;
use open20\amos\dashboard\controllers\TabDashboardControllerTrait;
use open20\amos\documenti\AmosDocumenti;
use open20\amos\documenti\widgets\icons\WidgetIconDocumentiAclDashboard;
use Yii;
use yii\helpers\Url;

/**
 * Class DocumentiAclGroupsController
 * DocumentiAclGroupsController implements the CRUD actions for DocumentiAclGroups model.
 *
 * @property \open20\amos\documenti\models\DocumentiAclGroups $model
 * @property \open20\amos\documenti\models\search\DocumentiAclGroupsSearch $modelSearch
 *
 * @package open20\amos\documenti\controllers\base
 */
abstract class DocumentiAclGroupsController extends CrudController
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
    protected $documentsModule;
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->documentsModule = AmosDocumenti::instance();
        
        $this->initDashboardTrait();
        
        $this->setModelObj($this->documentsModule->createModel('DocumentiAclGroups'));
        $this->setModelSearch($this->documentsModule->createModel('DocumentiAclGroupsSearch'));
        
        $this->setAvailableViews([
            'grid' => [
                'name' => 'grid',
                'label' => AmosIcons::show('view-list-alt') . Html::tag('p', BaseAmosModule::t('amoscore', '#view_type_table')),
                'url' => '?currentView=grid'
            ]
        ]);
        
        parent::init();
        
        $this->setUpLayout();
    }
    
    /**
     * Used for set page title and breadcrumbs.
     * @param string $pageTitle
     */
    public function setTitleAndBreadcrumbs($pageTitle)
    {
        Yii::$app->view->title = $pageTitle;
        Yii::$app->view->params['breadcrumbs'] = [
            ['label' => $pageTitle]
        ];
    }
    
    /**
     * Set a view param used in \open20\amos\core\forms\CreateNewButtonWidget
     */
    protected function setCreateNewBtnLabel()
    {
        Yii::$app->view->params['createNewBtnParams'] = [
            'createNewBtnLabel' => AmosDocumenti::t('amosdocumenti', '#add_group')
        ];
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
     * Lists all DocumentiAclGroups models.
     * @return mixed
     */
    public function actionIndex($layout = null)
    {
        Url::remember();
        
        $this->setDataProvider($this->modelSearch->search(Yii::$app->request->getQueryParams()));
        $this->setTitleAndBreadcrumbs(AmosDocumenti::t('amosdocumenti', '#documents_acl_groups'));
        $this->setListViewsParams();
        if (!is_null($layout)) {
            $this->layout = $layout;
        }
        
        return parent::actionIndex();
    }
    
    /**
     * Displays a single DocumentiAclGroups model.
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
     * Creates a new DocumentiAclGroups model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $this->setUpLayout('form');
        
        $this->model = $this->documentsModule->createModel('DocumentiAclGroups');
        
        if ($this->model->load(Yii::$app->request->post()) && $this->model->validate()) {
            if ($this->model->save()) {
                Yii::$app->getSession()->addFlash('success', BaseAmosModule::t('amoscore', 'Element successfully created.'));
                return $this->redirect(['update', 'id' => $this->model->id]);
            } else {
                Yii::$app->getSession()->addFlash('danger', BaseAmosModule::t('amoscore', 'Element not created, check the data entered.'));
            }
        }
        
        return $this->render('create', [
            'model' => $this->model,
        ]);
    }
    
    /**
     * Updates an existing DocumentiAclGroups model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id
     * @return string|\yii\web\Response
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $this->setUpLayout('form');
        
        $this->model = $this->findModel($id);
        
        if ($this->model->load(Yii::$app->request->post()) && $this->model->validate()) {
            if ($this->model->save()) {
                Yii::$app->getSession()->addFlash('success', BaseAmosModule::t('amoscore', 'Element successfully updated.'));
                return $this->redirect(['update', 'id' => $this->model->id]);
            } else {
                Yii::$app->getSession()->addFlash('danger', BaseAmosModule::t('amoscore', 'Element not updated, check the data entered.'));
            }
        }
        
        return $this->render('update', [
            'model' => $this->model,
        ]);
    }
    
    /**
     * Deletes an existing DocumentiAclGroups model.
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
