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

use lispa\amos\admin\models\UserProfile;
use lispa\amos\core\controllers\CrudController;
use lispa\amos\core\forms\CreateNewButtonWidget;
use lispa\amos\core\helpers\BreadcrumbHelper;
use lispa\amos\core\helpers\Html;
use lispa\amos\core\icons\AmosIcons;
use lispa\amos\dashboard\controllers\TabDashboardControllerTrait;
use lispa\amos\documenti\AmosDocumenti;
use lispa\amos\documenti\assets\ModuleDocumentiAsset;
use lispa\amos\documenti\models\Documenti;
use lispa\amos\documenti\models\search\DocumentiSearch;
use lispa\amos\documenti\utility\DocumentsUtility;
use kartik\grid\GridView;
use raoul2000\workflow\base\WorkflowException;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/**
 * Class DocumentiController
 *
 * @property \lispa\amos\documenti\models\Documenti $model
 * @property \lispa\amos\documenti\models\search\DocumentiSearch $modelSearch
 *
 * @package lispa\amos\documenti\controllers
 */
class DocumentiController extends CrudController {

    /**
     * Uso il trait per inizializzare la dashboard a tab
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
    public function init() {
        $this->initDashboardTrait();

        $this->setModelObj(AmosDocumenti::instance()->createModel('Documenti'));
        $this->setModelSearch(AmosDocumenti::instance()->createModel('DocumentiSearch'));

        ModuleDocumentiAsset::register(Yii::$app->view);

        $this->documentsModule = Yii::$app->getModule(AmosDocumenti::getModuleName());

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
            'grid' => $this->viewGrid,
        ];

        $availableViews = [];

        foreach ($this->documentsModule->defaultListViews as $view) {
            if (isset($defaultViews[$view])) {
                $availableViews[$view] = $defaultViews[$view];
            }
        }

        $this->setAvailableViews($availableViews);

        $this->setUpLayout();

        parent::init();
    }

    /**
     * @inheritdoc
     */
    public function behaviors() {
        $behaviors = ArrayHelper::merge(parent::behaviors(), [
                    'access' => [
                        'class' => AccessControl::className(),
                        'rules' => [
                            [
                                'allow' => true,
                                'actions' => [
                                    'download-documento-principale',
                                    'download',
                                    'index',
                                    'documenti',
                                    'all-documents',
                                    'own-interest-documents',
                                    'new-document-version',
                                    'delete-new-document-version',
                                    'list-only'
                                ],
                                'roles' => [
                                    'LETTORE_DOCUMENTI',
                                    'AMMINISTRATORE_DOCUMENTI',
                                    'CREATORE_DOCUMENTI',
                                    'FACILITATORE_DOCUMENTI',
                                    'VALIDATORE_DOCUMENTI'
                                ]
                            ],
                            [
                                'allow' => true,
                                'actions' => [
                                    'own-documents',
                                ],
                                'roles' => ['CREATORE_DOCUMENTI', 'AMMINISTRATORE_DOCUMENTI', 'FACILITATORE_DOCUMENTI']
                            ],
                            [
                                'allow' => true,
                                'actions' => [
                                    'validate-document',
                                    'reject-document',
                                ],
                                'roles' => [
                                    'AMMINISTRATORE_DOCUMENTI',
                                    'FACILITATORE_DOCUMENTI',
                                    'FACILITATOR',
                                    'DocumentValidateOnDomain',
                                    'VALIDATORE_DOCUMNENTI'
                                ]
                            ],
                            [
                                'allow' => true,
                                'actions' => [
                                    'to-validate-documents'
                                ],
                                'roles' => [
                                    'VALIDATORE_DOCUMENTI',
                                    'FACILITATORE_DOCUMENTI',
                                    'AMMINISTRATORE_DOCUMENTI',
                                    'DocumentValidateOnDomain'
                                ]
                            ],
                            [
                                'allow' => true,
                                'actions' => [
                                    'admin-all-documents'
                                ],
                                'roles' => ['AMMINISTRATORE_DOCUMENTI']
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
        return $behaviors;
    }

    /**
     * @inheritdoc
     */
    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * @param int $id Document id.
     * @return \yii\web\Response
     */
    public function actionValidateDocument($id) {
        $this->model = Documenti::findOne($id);
        try {
            $this->model->sendToStatus(Documenti::DOCUMENTI_WORKFLOW_STATUS_VALIDATO);
            $ok = $this->model->save(false);
            if ($ok) {
                Yii::$app->session->addFlash('success', AmosDocumenti::t('amosdocumenti', 'Document validated!'));
            } else {
                Yii::$app->session->addFlash('danger', AmosDocumenti::t('amosdocumenti', '#ERROR_WHILE_VALIDATING_DOCUMENT'));
            }
        } catch (WorkflowException $e) {
            Yii::$app->session->addFlash('danger', $e->getMessage());
            return $this->redirect(Url::previous());
        }
        return $this->redirect(Url::previous());
    }

    /**
     * @param int $id Document id.
     * @return \yii\web\Response
     */
    public function actionRejectDocument($id) {
//        $this->model = Documenti::findOne($id);
        $modelClassname =  AmosDocumenti::instance()->model('Documenti');
        $this->model = $modelClassname::findOne($id);

        try {
            $this->model->sendToStatus(Documenti::DOCUMENTI_WORKFLOW_STATUS_BOZZA);
            $ok = $this->model->save(false);
            if ($ok) {
                Yii::$app->session->addFlash('success', AmosDocumenti::t('amosdocumenti', 'Document rejected!'));
            } else {
                Yii::$app->session->addFlash('danger', AmosDocumenti::t('amosdocumenti', '#ERROR_WHILE_REJECTING_DOCUMENT'));
            }
        } catch (WorkflowException $e) {
            Yii::$app->session->addFlash('danger', $e->getMessage());
            return $this->redirect(Url::previous());
        }
        $this->model->save(false);
        Yii::$app->session->addFlash('success', AmosDocumenti::t('amosdocumenti', 'Document rejected!'));
        return $this->redirect(Url::previous());
    }

    /**
     * Lists all Documenti models.
     * @return mixed
     */
    public function actionIndex($layout = null) {
        $parentId = Yii::$app->request->getQueryParam('parentId');
        return $this->redirect(['/documenti/documenti/all-documents', 'parentId' => $parentId]);
    }

    /**
     * Used for set page title and breadcrumbs.
     *
     * @param string $documentiPageTitle Documenti page title (ie. Created by documenti, ...)
     */
    private function setTitleAndBreadcrumbs($documentiPageTitle) {
        $this->setNetworkDashboardBreadcrumb();

        $parentId = Yii::$app->request->getQueryParam('parentId');
        if (isset($parentId)) {
            $folder = Documenti::findOne($parentId);
            if (!is_null($folder)) {
                $documentiPageTitle = $folder->getTitle();
            }
        }
        Yii::$app->session->set('previousTitle', $documentiPageTitle);
        Yii::$app->session->set('previousUrl', Url::previous());
        Yii::$app->view->title = $documentiPageTitle;
        Yii::$app->view->params['breadcrumbs'][] = ['label' => $documentiPageTitle];
    }

    public function setNetworkDashboardBreadcrumb() {
        /** @var \lispa\amos\cwh\AmosCwh $moduleCwh */
        $moduleCwh = Yii::$app->getModule('cwh');
        $scope = null;
        if (!empty($moduleCwh)) {
            $scope = $moduleCwh->getCwhScope();
        }
        if (!empty($scope)) {
            if (isset($scope['community'])) {
                $communityId = $scope['community'];
                $community = \lispa\amos\community\models\Community::findOne($communityId);
                $dashboardCommunityTitle = AmosDocumenti::t('amosdocumenti', "Dashboard") . ' ' . $community->name;
                $dasbboardCommunityUrl = Yii::$app->urlManager->createUrl(['community/join', 'id' => $communityId]);
                Yii::$app->view->params['breadcrumbs'][] = [
                    'label' => $dashboardCommunityTitle,
                    'url' => $dasbboardCommunityUrl
                ];
            }
        }
    }

    /**
     * Set a view param used in \lispa\amos\core\forms\CreateNewButtonWidget
     */
    private function setCreateNewBtnLabel() {
        $module = \Yii::$app->getModule(AmosDocumenti::getModuleName());
        $hideWidard = $module->hideWizard;
        $parentId = null;
        if (!is_null(Yii::$app->request->getQueryParam('parentId'))) {
            $parentId = Yii::$app->request->getQueryParam('parentId');
        }

        $linkCreateNewButton = (array_key_exists("noWizardNewLayout", Yii::$app->params)) ? ['/documenti/documenti/create', 'parentId' => $parentId] : ['/documenti/documenti-wizard/introduction', 'parentId' => $parentId];

        if ($hideWidard) {
            $linkCreateNewButton = ['/documenti/documenti/create', 'parentId' => $parentId];
        }

        $createNewBtnParams = [
            'createNewBtnLabel' => AmosDocumenti::t('amosdocumenti', 'Aggiungi nuovo documento'),
            'urlCreateNew' => $linkCreateNewButton,
            'otherOptions' => ['title' => AmosDocumenti::t('amosdocumenti', 'Aggiungi nuovo documento')]
        ];
        if ($this->documentsModule->enableFolders) {

            $btnBack = '';
            // find Url to navigate previous folder
            if (!is_null($parentId)) {
                $parent = Documenti::findOne($parentId);
                if (!is_null($parent)) {
                    $url = [$this->action->id, 'parentId' => $parent->parent_id];
                    $btnBack = Html::a(AmosDocumenti::tHtml('amosdocumenti', '#btn_back_prev_folder'), $url, ['class' => 'btn btn-secondary']);
                }
            }
            $btnNewFolder = CreateNewButtonWidget::widget([
                        'createNewBtnLabel' => AmosDocumenti::t('amosdocumenti', '#btn_new_folder'),
                        'urlCreateNew' => ['/documenti/documenti/create', 'isFolder' => true, 'parentId' => $parentId],
                        'otherOptions' => ['title' => AmosDocumenti::t('amosdocumenti', '#btn_new_folder')]
            ]);
            if (!Yii::$app->user->can('DOCUMENTI_CREATE')) {
                
                $this->view->params['forceCreateNewButtonWidget'] = true;
                $layout = $btnBack;
            } else {
                $layout = $btnBack . "{buttonCreateNew}" . $btnNewFolder;
            }
            $createNewBtnParams = ArrayHelper::merge($createNewBtnParams, [
                        'layout' => $layout
            ]);
        }
        
        Yii::$app->view->params['createNewBtnParams'] = $createNewBtnParams;
    }

    /**
     * This method is useful to set all common params for all list views.
     */
    protected function setListViewsParams() {
        $this->setCreateNewBtnLabel();
        Yii::$app->session->set(AmosDocumenti::beginCreateNewSessionKey(), Url::previous());
    }

    /**
     * @param Documenti $model
     * @return mixed|\yii\web\Response
     */
    public function getFormCloseUrl($model) {
        $isNewVersion = false;
        if (!empty(\Yii::$app->request->get('isNewVersion')) && \Yii::$app->request->get('isNewVersion') == 1) {
            $isNewVersion = true;
        }
        if ($this->documentsModule->enableDocumentVersioning && !$model->isNewRecord && !is_null($model->version) && ($model->version > 1) && $isNewVersion) {
            return ['/documenti/documenti/delete-new-document-version', 'id' => (!is_null($model) ? $model->id : $this->model->id)];
        } else {
            return Yii::$app->session->get('previousUrl');
        }
    }

    /**
     * @param Documenti $model
     * @return string
     */
    public function getFormCloseLabel($model) {
        $label = '';
        if ($this->documentsModule->enableDocumentVersioning && !$model->isNewRecord && !is_null($model->version) && ($model->version > 1)) {
            $label = AmosDocumenti::t('amosdocumenti', '#CANCEL_NEW_VERSION');
        }
        return $label;
    }

    /**
     * Displays a single Documenti model.
     *
     * @param integer $id
     * @return string|\yii\web\Response
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionView($id) {
        $this->model = $this->findModel($id);

        if ($this->model->load(Yii::$app->request->post()) && $this->model->save()) {
            return $this->redirect(['view', 'id' => $this->model->id, 'idDocumenti' => $id]);
        } else {
            return $this->render('view', ['model' => $this->model]);
        }
    }

    /**
     * Creates a new Documenti model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->setUpLayout('form');
//        $this->model = new Documenti();
        $module = \Yii::$app->getModule(AmosDocumenti::getModuleName());
        $moduleGroups = \Yii::$app->getModule('groups');
        $enableGroupNotification = $module->enableGroupNotification;

        if ($module->hidePubblicationDate) {
//            $this->model = new Documenti(['scenario' => Documenti::SCENARIO_CREATE_HIDE_PUBBLICATION_DATE]);
            $this->model = $module->createModel('Documenti', ['scenario' => Documenti::SCENARIO_CREATE_HIDE_PUBBLICATION_DATE]);
        } else {
//            $this->model = new Documenti(['scenario' => Documenti::SCENARIO_CREATE]);
            $this->model = $module->createModel('Documenti', ['scenario' => Documenti::SCENARIO_CREATE_HIDE_PUBBLICATION_DATE]);
        }
        $params = Yii::$app->request->getQueryParams();
        if (isset($params['isFolder'])) {
            $this->model->setScenario(Documenti::SCENARIO_FOLDER);
            $this->model->is_folder = Documenti::IS_FOLDER;
        }
        if (isset($params['parentId'])) {
            $this->model->parent_id = $params['parentId'];
        }
        if ($this->model->load(Yii::$app->request->post())) {
            if ($this->model->validate()) {
                $validateOnSave = true;
                if ($this->model->status == Documenti::DOCUMENTI_WORKFLOW_STATUS_DAVALIDARE) {
                    $this->model->status = Documenti::DOCUMENTI_WORKFLOW_STATUS_BOZZA;
                    $ok = $this->model->save();
                    if ($ok) {
                        if ($this->documentIsFolder($this->model)) {
                            $this->model->setScenario(Documenti::SCENARIO_FOLDER);
                        } else {
                            $this->model->setScenario(Documenti::SCENARIO_UPDATE);
                        }
                    }
                    $this->model->status = Documenti::DOCUMENTI_WORKFLOW_STATUS_DAVALIDARE;
                    $validateOnSave = false;
                }
                if ($this->model->status == Documenti::DOCUMENTI_WORKFLOW_STATUS_VALIDATO) {
                    $this->model->status = Documenti::DOCUMENTI_WORKFLOW_STATUS_BOZZA;
                    $ok = $this->model->save();
                    if ($ok) {
                        if ($this->documentIsFolder($this->model)) {
                            $this->model->setScenario(Documenti::SCENARIO_FOLDER);
                        } else {
                            $this->model->setScenario(Documenti::SCENARIO_UPDATE);
                        }
                    }
                    $this->model->status = Documenti::DOCUMENTI_WORKFLOW_STATUS_VALIDATO;
                    $validateOnSave = false;
                }
                if ($this->model->save($validateOnSave)) {
                    Yii::$app->getSession()->addFlash('success', AmosDocumenti::tHtml('amosdocumenti', 'Documenti salvata con successo.'));
                    if ($enableGroupNotification && !empty($moduleGroups)) {
                        $this->sendNotificationEmail();
                    }
                    $this->redirectOnCreate($this->model);
                } else {
                    Yii::$app->getSession()->addFlash('danger', AmosDocumenti::tHtml('amosdocumenti', 'Si &egrave; verificato un errore durante il salvataggio'));
                    return $this->render('create', [
                                'model' => $this->model,
                    ]);
                }
            } else {
                Yii::$app->getSession()->addFlash('danger', AmosDocumenti::tHtml('amosdocumenti', 'Modifiche non salvate. Verifica l\'inserimento dei campi'));
            }
        }
        return $this->render('create', [
                    'model' => $this->model,
        ]);
    }

    /**
     * @param int $id
     * @return \yii\web\Response
     * @throws WorkflowException
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionNewDocumentVersion($id) {
        $this->model = $this->findModel($id);
        $ok = $this->model->makeNewDocumentVersion();
        $this->model->status = $this->model->getWorkflowSource()->getWorkflow(Documenti::DOCUMENTI_WORKFLOW)->getInitialStatusId();
        $this->model->save(false);
        $url = ['update', 'id' => $this->model->id, 'isNewVersion' => 1];
        if (!$ok) {
            $url = Yii::$app->session->get(AmosDocumenti::beginCreateNewSessionKey());
            Yii::$app->getSession()->addFlash('danger', AmosDocumenti::tHtml('amosdocumenti', 'Errore durante la creazione della nuova versione.'));
        }
        return $this->redirect($url);
    }

    /**
     * @param int $id
     * @return \yii\web\Response
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionDeleteNewDocumentVersion($id) {
        $this->model = $this->findModel($id);
        $ok = $this->model->deleteNewDocumentVersion();
        if (!$ok) {
            Yii::$app->getSession()->addFlash('danger', AmosDocumenti::tHtml('amosdocumenti', 'Errore durante la cancellazione della nuova versione.'));
        }
        return $this->redirect(Yii::$app->session->get(AmosDocumenti::beginCreateNewSessionKey()));
    }

    /**
     * Updates an existing Documenti model.
     *
     * @param integer $id
     * @param bool|false $backToEditStatus Save the model with status Editing in progress before form rendering
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionUpdate($id, $backToEditStatus = false) {
        Url::remember();
        $moduleGroups = \Yii::$app->getModule('groups');
        $module = \Yii::$app->getModule(AmosDocumenti::getModuleName());
        $enableGroupNotification = $module->enableGroupNotification;


        $this->setUpLayout('form');

        $this->model = $this->findModel($id);
        
        if ($this->documentIsFolder($this->model)) {
            $this->model->setScenario(Documenti::SCENARIO_FOLDER);
        } else {
            $this->model->setScenario(Documenti::SCENARIO_UPDATE);
        }

        if (Yii::$app->request->post()) {
            $previousStatus = $this->model->status;
            if ($this->model->load(Yii::$app->request->post())) {
                if ($this->model->validate()) {
                    if ($this->model->save()) {
                        Yii::$app->getSession()->addFlash('success', AmosDocumenti::tHtml('amosdocumenti', 'Documento aggiornato con successo.'));
                        ;
                        if (!$this->model->is_folder) {
                            if ($enableGroupNotification && !empty($moduleGroups)) {
                                $this->sendNotificationEmail();
                            }
                        }
                        return $this->redirectOnUpdate($this->model, $previousStatus);
                    } else {
                        Yii::$app->getSession()->addFlash('danger', AmosDocumenti::tHtml('amosdocumenti', 'Si &egrave; verificato un errore durante il salvataggio'));
                        return $this->render('update', [
                                    'model' => $this->model,
                        ]);
                    }
                } else {
                    Yii::$app->getSession()->addFlash('danger', AmosDocumenti::tHtml('amosdocumenti', 'Modifiche non salvate. Verifica l\'inserimento dei campi'));
                }
            }
        } else {
            if ($backToEditStatus && ($this->model->status != $this->model->getDraftStatus() && !Yii::$app->user->can('DocumentValidate', ['model' => $this->model]))) {
                $this->model->status = $this->model->getDraftStatus();
                $ok = $this->model->save(false);
                if (!$ok) {
                    Yii::$app->getSession()->addFlash('danger', AmosDocumenti::tHtml('amosdocumenti', 'Si &egrave; verificato un errore durante il salvataggio'));
                }
            }
        }

        return $this->render('update', [
                    'model' => $this->model,
        ]);
    }

    /**
     * Private method to download a file.
     *
     * @param string $path A path to a file.
     * @param string $file A filename
     * @param array $extensions
     * @param string $titolo
     * @return bool
     */
    private function downloadFile($path, $file, $extensions = [], $titolo = null) {
        if (is_file($path)) {
            $file_info = pathinfo($path);
            $extension = $file_info["extension"];

            if (is_array($extensions)) {
                foreach ($extensions as $e) {
                    if ($e === $extension) {
                        header('Content-Description: File Transfer');
                        header('Content-Type: application/octet-stream');
                        $titolo = $titolo ? $titolo : 'Allegato_documenti';
                        header('Content-Disposition: attachment; filename="' . $titolo . '.' . $extension . '"');
                        header('Content-Transfer-Encoding: binary');
                        header('Expires: 0');
                        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                        header('Pragma: public');
                        header('Content-Length: ' . filesize($path));
                        readfile($path);
                        ob_clean();
                        flush();

                        return true; //Yii::$app->response->sendFile($path);
                    }
                }
            }
        }
        return false;
    }

    /**
     * Deletes an existing Documenti model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     * @return \yii\web\Response
     * @throws \Exception
     * @throws \yii\db\StaleObjectException
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionDelete($id) {
        $this->model = $this->findModel($id);
        if ($this->documentsModule->enableFolders) {
            return $this->enabledFoldersDelete();
        } else {
            return $this->standardDelete();
        }
    }

    /**
     * @return \yii\web\Response
     * @throws \yii\db\StaleObjectException
     */
    protected function standardDelete() {
        $this->model->delete();
        $isFolder = $this->model->isFolder();
        if (!$this->model->getErrors()) {
            $successMessage = ($isFolder ?
                    AmosDocumenti::tHtml('amosdocumenti', 'Cartella cancellata correttamente.') :
                    AmosDocumenti::tHtml('amosdocumenti', 'Documento cancellato correttamente.'));
            Yii::$app->getSession()->addFlash('success', $successMessage);
        } else {
            $errorMessage = ($isFolder ?
                    AmosDocumenti::tHtml('amosdocumenti', 'Non sei autorizzato a cancellare la cartella.') :
                    AmosDocumenti::tHtml('amosdocumenti', 'Non sei autorizzato a cancellare il documento.'));
            Yii::$app->getSession()->addFlash('danger', $errorMessage);
        }
        return $this->redirect(Url::previous());
    }

    /**
     * @return \yii\web\Response
     * @throws \yii\db\StaleObjectException
     */
    protected function enabledFoldersDelete() {
        $allOk = $this->model->deleteAllChildren();
        if (!$allOk) {
            return $this->redirect(Url::previous());
        }
        return $this->standardDelete();
    }

    /**
     * Action to search only for own documents
     *
     * @param int|null $parentId - id of document folder
     * @return string
     */
    public function actionOwnDocuments($parentId = null) {
        Url::remember();

        if (!is_null($parentId)) { //set parent Id to filter documents within a folder
            $modelSearch = $this->getModelSearch();
            $modelSearch->parentId = $parentId;
            $this->setModelSearch($modelSearch);
        }

        $this->setDataProvider($this->getModelSearch()->searchOwnDocuments(Yii::$app->request->getQueryParams()));
        $this->setTitleAndBreadcrumbs(AmosDocumenti::t('amosdocumenti', 'Documenti creati da me'));

        $this->setAvailableViews([
            'grid' => [
                'name' => 'grid',
                'label' => AmosIcons::show('view-list-alt') . Html::tag('p', AmosDocumenti::tHtml('amosdocumenti', 'Tabella')),
                'url' => '?currentView=grid'
            ]
        ]);
        $this->setCurrentView($this->getAvailableView('grid'));
        $this->setListViewsParams();

        $this->setUpLayout('list');
        $this->view->params['currentDashboard'] = $this->getCurrentDashboard();

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
     * Action to search only for own interest documents
     *
     * @param int|null $parentId - id of document folder
     * @return string
     */
    public function actionOwnInterestDocuments($currentView = null, $parentId = null) {
        Url::remember();

        if (empty($currentView)) {
            $currentView = reset($this->documentsModule->defaultListViews);
        }
        if (!is_null($parentId)) { //set parent Id to filter documents within a folder
            $modelSearch = $this->getModelSearch();
            $modelSearch->parentId = $parentId;
            $this->setModelSearch($modelSearch);
        }

        $this->setDataProvider($this->getModelSearch()->searchOwnInterest(Yii::$app->request->getQueryParams()));

        $this->setTitleAndBreadcrumbs(AmosDocumenti::t('amosdocumenti', 'Documenti di mio interesse'));
        $this->setCurrentView($this->getAvailableView($currentView));
        $this->setListViewsParams();

        $this->setUpLayout('list');
        $this->view->params['currentDashboard'] = $this->getCurrentDashboard();

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
     * Action to search to validate documents.
     *
     * @param int|null $parentId - id of document folder
     * @return string
     */
    public function actionToValidateDocuments($parentId = null) {
        Url::remember();

        if (!is_null($parentId)) { //set parent Id to filter documents within a folder
            $modelSearch = $this->getModelSearch();
            $modelSearch->parentId = $parentId;
            $this->setModelSearch($modelSearch);
        }

        $this->setDataProvider($this->getModelSearch()->searchToValidateDocuments(Yii::$app->request->getQueryParams()));
        $this->setTitleAndBreadcrumbs(AmosDocumenti::t('amosdocumenti', 'Documenti da validare'));

        $this->setAvailableViews([
            'grid' => [
                'name' => 'grid',
                'label' => AmosIcons::show('view-list-alt') . Html::tag('p', AmosDocumenti::tHtml('amosdocumenti', 'Tabella')),
                'url' => '?currentView=grid'
            ]
        ]);
        $this->setCurrentView($this->getAvailableView('grid'));
        $this->setListViewsParams();

        $this->setUpLayout('list');
        $this->view->params['currentDashboard'] = $this->getCurrentDashboard();

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
     * Action for search all documenti.
     *
     * @param int|null $parentId - id of document folder
     * @return string
     */
    public function actionAllDocuments($currentView = null, $parentId = null) {
        Url::remember();

        if (empty($currentView)) {
            $currentView = reset($this->documentsModule->defaultListViews);
        }

        if (!is_null($parentId)) { //set parent Id to filter documents within a folder
            $modelSearch = $this->getModelSearch();
            $modelSearch->parentId = $parentId;
            $this->setModelSearch($modelSearch);
        }

        $this->setDataProvider($this->getModelSearch()->searchAll(Yii::$app->request->getQueryParams()));

        $this->setTitleAndBreadcrumbs(AmosDocumenti::t('amosdocumenti', 'Tutti i documenti'));
        $this->setCurrentView($this->getAvailableView($currentView));
        $this->setListViewsParams();

        $this->setUpLayout('list');
        $this->view->params['currentDashboard'] = $this->getCurrentDashboard();

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
     * Get all the documents without any visibility/status filters
     *
     * @param null $currentView
     * @param int|null $parentId - id of document folder
     * @return string
     */
    public function actionAdminAllDocuments($currentView = null, $parentId = null) {
        Url::remember();

        if (empty($currentView)) {
            $currentView = reset($this->documentsModule->defaultListViews);
        }
        if (!is_null($parentId)) { //set parent Id to filter documents within a folder
            $modelSearch = $this->getModelSearch();
            $modelSearch->parentId = $parentId;
            $this->setModelSearch($modelSearch);
        }
        $this->setDataProvider($this->getModelSearch()->searchAdminAll(Yii::$app->request->getQueryParams()));

        $this->setTitleAndBreadcrumbs(AmosDocumenti::t('amosdocumenti', 'Amministra documenti'));
        $this->setCurrentView($this->getAvailableView($currentView));
        $this->setListViewsParams();

        $this->setUpLayout('list');
        $this->view->params['currentDashboard'] = $this->getCurrentDashboard();

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
     * If a given document model is a folder or not
     * @param Documenti $model
     * @return bool
     */
    public function documentIsFolder($model) {
        return (isset($model->is_folder) && $model->is_folder);
    }

    /**
     * Render the sub-table of version, it's called with ajax
     * @return string
     * @throws \Exception
     */
    public function actionListOnly() {
        $expandRowKey = \Yii::$app->request->post('expandRowKey');
        $actionId = $this->action->id;
        $hidePubblicationDate = $this->documentsModule->hidePubblicationDate;

//        $queryParams = \Yii::$app->request->getQueryParams();
        $queryParams['parent_id'] = $expandRowKey;
        $dataProvider = $this->getModelSearch()->searchVersions($queryParams);
//        return ($dataProvider->query->createCommand()->rawSql);
        $dataProvider->sort = false;
        $canUpdate = Yii::$app->user->can('DOCUMENTI_UPDATE', ['model' => $this->model]);
        $btnCreate = '';
        if ($canUpdate) {
            $btnCreate = CreateNewButtonWidget::widget([
                        'model' => $this->model,
                        'createNewBtnLabel' => AmosDocumenti::t('amosdocumenti', 'Create new version'),
                        'urlCreateNew' => ['/documenti/documenti/new-document-version', 'id' => $expandRowKey],
                        'btnClasses' => 'btn btn-success pull-right',
                        'otherOptions' => ['title' => AmosDocumenti::t('amosdocumenti', 'Create new version')]
//                            'checkPermWithNewMethod' => true
            ]);
        }

        try {
            return GridView::widget([
                        'id' => 'product-gridview',
                        'dataProvider' => $dataProvider,
                        'responsive' => true,
                        'export' => false,
                        'pjax' => true,
                        'pjaxSettings' => [
                            'options' => [
                                'id' => 'product-grid',
                                'timeout' => (isset(\Yii::$app->params['timeout']) ? \Yii::$app->params['timeout'] : 1000),
                                'enablePushState' => false
                            ]
                        ],
                        'columns' => [
                            [
                                'label' => AmosDocumenti::t('amosdocumenti', '#type'),
                                'format' => 'html',
                                'value' => function ($model) {
                                    $icon = DocumentsUtility::getDocumentIcon($model, true);
                                    return AmosIcons::show($icon, [], 'dash');
                                },
                            ],
                            [
                                'attribute' => 'titolo',
                                'format' => 'html',
                                'value' => function ($model) use ($actionId) {
                                    /** @var Documenti $model */
                                    $title = $model->titolo;
                                    if ($model->is_folder) {
                                        $url = [$actionId, 'parentId' => $model->id];
                                    } else {
                                        $url = $model->getDocumentMainFile()->getUrl();
                                    }
                                    return Html::a($title, $url, ['title' => AmosDocumenti::t('amosdocumenti', 'Scarica il documento') . '"' . $model->titolo . '"']);
                                }
                            ],
                            [
                                'attribute' => 'createdUserProfile',
                                'label' => AmosDocumenti::t('amosdocumenti', '#uploaded_by'),
                                'value' => function ($model) {
                                    return Html::a($model->createdUserProfile->nomeCognome, ['/admin/user-profile/view', 'id' => $model->createdUserProfile->id], [
                                                'title' => AmosDocumenti::t('amosdocumenti', 'Apri il profilo di {nome_profilo}', ['nome_profilo' => $model->createdUserProfile->nomeCognome])
                                    ]);
                                },
                                'format' => 'html'
                            ],
//                    [
//                        'attribute' => 'updatedUserProfile',
//                        'label' => AmosDocumenti::t('amosdocumenti', '#updated_by'),
//                        'value' => function($model){
//                            return Html::a($model->updatedUserProfile->nomeCognome, ['/admin/user-profile/view', 'id' => $model->updatedUserProfile->id ], [
//                                'title' => AmosDocumenti::t('amosdocumenti', 'Apri il profilo di {nome_profilo}', ['nome_profilo' => $model->updatedUserProfile->nomeCognome])
//                            ]);
//                        },
//                        'format' => 'html'
//                    ],
                            'data_pubblicazione' => [
                                'attribute' => 'data_pubblicazione',
                                'value' => function ($model) {
                                    /** @var Documenti $model */
                                    return (is_null($model->data_pubblicazione)) ? 'Subito' : Yii::$app->formatter->asDate($model->data_pubblicazione);
                                },
                                'label' => AmosDocumenti::t('amosdocumenti', '#uploaded_at'),
                            ],
                            'version',
                            [
                                'class' => 'lispa\amos\core\views\grid\ActionColumn',
                                'template' => '{view}',
                            ],
                        ],
                        'panelHeadingTemplate' => '<div class="pull-right">
                    </div>
                    <h3 class="panel-title">
                        {heading}
                    </h3>
                    <div class="clearfix"></div>',
                        'panel' => [
                            'before' => false,
                            'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-list"></i>&nbsp;' . AmosDocumenti::t('amosdocumenti', 'Old versions') . '</h3>'
                            . $btnCreate,
                            'type' => 'success',
                            'after' => false,
                            'footer' => false,
                        ],
            ]);
        } catch (\Exception $e) {
            pr($e->getTraceAsString());
            return $e->getMessage();
        }
    }

    public function sendNotificationEmail() {
        $idGroupsToMail = [];
        $idUserToMail = [];
        if (!empty(Yii::$app->request->post('selection-groups'))) {
            $idGroupsToMail = Yii::$app->request->post('selection-groups');
        }
        if (!empty(Yii::$app->request->post('selection-profiles'))) {
            $user_profiles_ids = Yii::$app->request->post('selection-profiles');
            foreach ($user_profiles_ids as $id) {
                $profile = UserProfile::findOne($id);
                if ($profile) {
                    $idUserToMail [] = $profile->user->id;
                }
            }
        }

        foreach ($idGroupsToMail as $idGroup) {
            $group = \lispa\amos\groups\models\Groups::findOne($idGroup);
            if ($group) {
                $members = $group->groupsMembers;
                /** @var  $member \lispa\amos\groups\models\GroupsMembers */
                foreach ($members as $member) {
                    $idUserToMail [] = $member->user_id;
                }
            }
        }

        // if you have not selected  any groups or users, send the notification to all member of community
//        if(empty(Yii::$app->request->post('selection-groups')) && empty(Yii::$app->request->post('selection-profiles'))) {
//            $cwh = Yii::$app->getModule("cwh");
//            $community = Yii::$app->getModule("community");
//            if (isset($cwh) && isset($community)) {
//                $cwh->setCwhScopeFromSession();
//                if (!empty($cwh->userEntityRelationTable)) {
//                    $entityId = $cwh->userEntityRelationTable['entity_id'];
//                    $community = \lispa\amos\community\models\Community::findOne($entityId);
//                    if(!empty($community)) {
//                        $usersMms = $community->communityUserMms;
//                        foreach ($usersMms as $memberComm){
//                            $idUserToMail []= $memberComm->user_id;
//                        }
//                    }
//                }
//            }
//        }
        // deleted duplicated id
        $idUserToMail = array_unique($idUserToMail);
        $controller = \Yii::$app->controller;
        $modelCreator = UserProfile::find()->andWhere(['user_id' => \Yii::$app->user->id])->one();
        $ris = $controller->renderMailPartial('email' . DIRECTORY_SEPARATOR . 'content', [
            'modelCreator' => $modelCreator,
            'modelDocument' => $this->model,
        ]);
        DocumentsUtility::sendEmail($idUserToMail, AmosDocumenti::t('amosdocumenti', 'Document uploaded'), $ris, []);
    }

    /**
     * @param $model
     * @param null $previousStatus
     * @return \yii\web\Response
     */
    protected function redirectOnUpdate($model, $previousStatus = null) {
        // if you have the permission of update or you can validate the content you will be redirected on the update page
        // otherwise you will be redirected on the index page
        $redirectToUpdatePage = false;
        if (Yii::$app->getUser()->can('DOCUMENTI_UPDATE', ['model' => $model])) {
            $redirectToUpdatePage = true;
        }
        if (Yii::$app->getUser()->can('DocumentValidate', ['model' => $model])) {
            $redirectToUpdatePage = true;
        }
        if ($redirectToUpdatePage) {
            if ($model->status == Documenti::DOCUMENTI_WORKFLOW_STATUS_VALIDATO) {
                return $this->redirect(BreadcrumbHelper::lastCrumbUrl());
            } elseif (($model->status == Documenti::DOCUMENTI_WORKFLOW_STATUS_BOZZA) && ($previousStatus == Documenti::DOCUMENTI_WORKFLOW_STATUS_DAVALIDARE)) {
                return $this->redirect(BreadcrumbHelper::lastCrumbUrl());
            } else {
                return $this->redirect(['/documenti/documenti/update', 'id' => $model->id]);
            }
        } else {
            return $this->redirect('/documenti/documenti/own-interest-documents');
        }
    }

    /**
     * @param $model
     * @return \yii\web\Response
     */
    protected function redirectOnCreate($model) {
        // if you have the permission of update or you can validate the content you will be redirected on the update page
        // otherwise you will be redirected on the index page with the contents created by you
        $redirectToUpdatePage = false;

        if (Yii::$app->getUser()->can('DOCUMENTI_UPDATE', ['model' => $model])) {
            $redirectToUpdatePage = true;
        }

        if (Yii::$app->getUser()->can('DocumentValidate', ['model' => $model])) {
            $redirectToUpdatePage = true;
        }

        if ($redirectToUpdatePage) {
            return $this->redirect(['/documenti/documenti/update', 'id' => $model->id]);
        } else {
            return $this->redirect('/documenti/documenti/own-documents');
        }
    }

}
