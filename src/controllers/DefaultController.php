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

use lispa\amos\dashboard\controllers\base\DashboardController;
use yii\helpers\Url;

class DefaultController extends DashboardController
{
    /**
     * @var string $layout Layout per la dashboard interna.
     */
    public $layout = 'dashboard_interna';

    /**
     * @inheritdoc
     */
    public function init() {

        parent::init();
        $this->setUpLayout();
        // custom initialization code goes here
    }

    /**
     * Lists all Documenti models.
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->redirect(['/documenti/documenti/own-interest-documents']);
        Url::remember();
        $params = [
            'currentDashboard' => $this->getCurrentDashboard()
        ];
        return $this->render('index', $params);
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
