<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open20\amos\documenti\assets
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

namespace open20\amos\documenti\assets;

use yii\web\AssetBundle;

/**
 * Class ModuleDocumentiDocumentsExplorerAsset
 * @package open20\amos\documenti\assets
 */
class ModuleDocumentiDocumentsExplorerAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@vendor/open20/amos-documenti/src/assets/web';

    /**
     * @inheritdoc
     */
    public $css = [
        //'less/documents.less',
        //'less/hierarchical-documents.less',
        //'css/materialize/materialize.min.css',
        //'css/materialize/sidenav.css',
        'less/documents-explorer.less',
        'css/jquery.modal.min.css',
        'css/jquery.contextMenu.min.css',
    ];

    /**
     * @inheritdoc
     */
    public $js = [
        'js/documents-explorer/lajax.js',
        'js/documents.js',
        'js/documents-explorer/mustache.min.js',
        'js/documents-explorer/jquery.modal.min.js',
        'js/documents-explorer/jquery.ui.position.min.js',
        'js/documents-explorer/jquery.contextMenu.min.js',
        'js/documents-explorer/underscore-min.js',
        'js/documents-explorer/documents-explorer.js',
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
        // TODO Disable force copy
        \Yii::$app->assetManager->forceCopy = true;
        $moduleL = \Yii::$app->getModule('layout');
        if (!empty($moduleL)) {
            $this->depends [] = 'open20\amos\layout\assets\BaseAsset';
        } else {
            $this->depends [] = 'open20\amos\core\views\assets\AmosCoreAsset';
        }
        parent::init();
    }
}
