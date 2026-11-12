<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open20\amos\documenti\widgets\icons
 * @author    Aria S.p.A. <protocollo@pec.ariaspa.it>
 * @license   GNU General Public License v3.0 <https://www.gnu.org/licenses/gpl-3.0.html>
 * @link      https://www.ariaspa.it/
 *
 * This file is part of OPEN 2.0.
 *
 * OPEN 2.0 is free software: you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * OPEN 2.0 is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with OPEN 2.0. If not, see <https://www.gnu.org/licenses/>.
 */

namespace open20\amos\documenti\widgets\icons;

use open20\amos\core\widget\WidgetIcon;
use open20\amos\uploader\Module;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * Class WidgetIconUploader
 * @package open20\amos\documenti\widgets\icons
 */
class WidgetIconImport extends WidgetIcon
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        
        $isEdge = false;
        if (preg_match("/Edge/i", $_SERVER['HTTP_USER_AGENT'], $output_array)) {
            $isEdge = true;
        }

        if (preg_match("/Explorer/i", $_SERVER['HTTP_USER_AGENT'], $output_array)) {
            $isEdge = true;
        }

        $url = '/dashboard';
        
        if (!$isEdge) {
            $url = ['/uploader/upload/index', 'callbackUrl' => '/import/default/import'];
        } else {
            $js = <<<JS
            $('.alert-import').click(function(event){
                event.preventDefault();
                alert("L'importazione delle aree di lavoro è supportata completamente utilizzando il web browser Chrome; perciò per eseguirla è necessario usare questo browser: apri Chrome, accedi alla piattaforma ed esegui di nuovo questa funzione.");
            });
JS;
            $controller = Yii::$app->controller;
            $view = $controller->getView();
            $view->registerJs($js);
        }

        $this->setLabel(AmosDocumenti::t('amosdocumenti', 'Import Workspace'));
        $this->setDescription(AmosDocumenti::t('amosdocumenti', 'Upload files of great size'));
        $this->setIcon('linentita');
        $this->setUrl($url);
        $this->setCode('UPLOADER');
        $this->setModuleName('uploader');
        $this->setNamespace(__CLASS__);
        $this->setClassSpan(
            ArrayHelper::merge(
                $this->getClassSpan(), 
                [
                    'bk-backgroundIcon',
                    'color-darkGrey',
                    'alert-import'
                ]
            )
        );
    }

}