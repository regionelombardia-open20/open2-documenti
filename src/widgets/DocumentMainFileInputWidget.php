<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open20\amos-documenti
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

/**
 * Created by PhpStorm.
 * User: michele.lafrancesca
 * Date: 16/12/2019
 * Time: 15:39
 */

namespace open20\amos\documenti\widgets;


use yii\base\Widget;

class DocumentMainFileInputWidget extends Widget
{
    public $model;
    public $form;
    public $isFolder;

    /**
     * @return string
     */
    public function run()
    {
        $moduleDocumenti = \Yii::$app->getModule('documenti');
        return $this->render('@vendor/open20/amos-documenti/src/views/documenti/_input_document_main_file',[
            'model' => $this->model,
            'form' => $this->form,
            'isFolder' => $this->isFolder,
            'module' => $moduleDocumenti
        ]);
    }

}