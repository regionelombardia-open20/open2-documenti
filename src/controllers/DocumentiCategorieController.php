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
use lispa\amos\dashboard\controllers\TabDashboardControllerTrait;
use lispa\amos\documenti\AmosDocumenti;
use lispa\amos\documenti\models\DocumentiCategorie;
use lispa\amos\documenti\models\search\DocumentiCategorieSearch;
use Yii;
use yii\helpers\Url;

/**
 * DocumentiCategorieController implements the CRUD actions for DocumentiCategorie model.
 */

/**
 * Class DocumentiCategorieController
 * DocumentiCategorieController implements the CRUD actions for DocumentiCategorie model.
 *
 * @property \lispa\amos\documenti\models\DocumentiCategorie $model
 *
 * @package lispa\amos\documenti\controllers
 */
class DocumentiCategorieController extends CrudController
{
    /**
     * Trait used for initialize the news dashboard
     */
    use TabDashboardControllerTrait;

    /**
     * @var string $layout
     */
    public $layout = 'list';

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->initDashboardTrait();

        $this->setModelObj(new DocumentiCategorie());
        $this->setModelSearch(new DocumentiCategorieSearch());

        $this->setAvailableViews([
            'grid' => [
                'name' => 'grid',
                'label' => AmosIcons::show('view-list-alt') . Html::tag('p', AmosDocumenti::tHtml('amosdocumenti', 'Tabella')),
                'url' => '?currentView=grid'
            ],
            /* 'map' => [
              'name' => 'map',
              'label' => AmosDocumenti::t('amosdocumenti', '{iconaMappa}'.Html::tag('p',AmosDocumenti::tHtml('amosdocumenti', 'Mappa')), [
              'iconaMappa' => AmosIcons::show('map-alt')
              ]),
              'url' => '?currentView=map'
              ], */
        ]);

        parent::init();

        $this->setUpLayout();
    }

    /**
     * Lists all DocumentiCategorie models.
     * @param string|null $layout
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionIndex($layout = NULL)
    {
        Url::remember();

        $this->setUpLayout('list');

        $this->view->params['currentDashboard'] = $this->getCurrentDashboard();

        $this->setDataProvider($this->getModelSearch()->search(Yii::$app->request->getQueryParams()));
        return parent::actionIndex();
    }

    /**
     * Displays a single DocumentiCategorie model.
     * @param integer $id
     * @return string|\yii\web\Response
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('view', ['model' => $model]);
        }
    }

    /**
     * Creates a new DocumentiCategorie model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $this->setUpLayout('form');

        $model = new DocumentiCategorie;

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                if ($model->save()) {
                    Yii::$app->getSession()->addFlash('success', AmosDocumenti::tHtml('amosdocumenti', 'Categoria documenti salvata con successo.'));
                    return $this->redirect(['/documenti/documenti-categorie/update', 'id' => $model->id]);
                } else {
                    Yii::$app->getSession()->addFlash('danger', AmosDocumenti::tHtml('amosdocumenti', 'Si &egrave; verificato un errore durante il salvataggio'));
                }
            } else {
                Yii::$app->getSession()->addFlash('danger', AmosDocumenti::tHtml('amosdocumenti', 'Modifiche non salvate. Verifica l\'inserimento dei campi'));
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing DocumentiCategorie model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return string|\yii\web\Response
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $this->setUpLayout('form');

        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                if ($model->save()) {
                    Yii::$app->getSession()->addFlash('success', AmosDocumenti::tHtml('amosdocumenti', 'Categoria documenti aggiornata con successo.'));
                    return $this->redirect(['/documenti/documenti-categorie/update', 'id' => $model->id]);
                } else {
                    Yii::$app->getSession()->addFlash('danger', AmosDocumenti::tHtml('amosdocumenti', 'Si &egrave; verificato un errore durante il salvataggio'));
                }
            } else {
                Yii::$app->getSession()->addFlash('danger', AmosDocumenti::tHtml('amosdocumenti', 'Modifiche non salvate. Verifica l\'inserimento dei campi'));
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing DocumentiCategorie model.
     * If deletion is successful, the browser will be redirected to the previous list page.
     * @param int $id
     * @return \yii\web\Response
     * @throws \Exception
     * @throws \yii\db\StaleObjectException
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionDelete($id)
    {
        $this->model = $this->findModel($id);
        if ($this->model) {
            $this->model->delete();
            if (!$this->model->hasErrors()) {
                Yii::$app->getSession()->addFlash('success', AmosDocumenti::t('amosdocumenti', 'Elemento cancellato correttamente.'));
            } else {
                Yii::$app->getSession()->addFlash('danger', AmosDocumenti::t('amosdocumenti', 'Non sei autorizzato a cancellare questo elemento.'));
            }
        } else {
            Yii::$app->getSession()->addFlash('danger', AmosDocumenti::tHtml('amosdocumenti', 'Elemento non trovato.'));
        }
        return $this->redirect(['index']);
    }

    /**
     * @param null $layout
     * @return bool
     */
    public function setUpLayout($layout = null)
    {
        if ($layout === false) {
            $this->layout = false;
            return true;
        }
        $this->layout = (!empty($layout)) ? $layout : $this->layout;
        $module = \Yii::$app->getModule('layout');
        if (empty($module)) {
            if (strpos($this->layout, '@') === false) {
                $this->layout = '@vendor/lispa/amos-core/views/layouts/'.(!empty($layout) ? $layout : $this->layout);
            }
            return true;
        }
        return true;
    }
}
