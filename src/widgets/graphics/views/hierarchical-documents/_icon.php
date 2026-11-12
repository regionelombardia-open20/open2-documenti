<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open20\amos\documenti\widgets\graphics\views\hierarchical-documents
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

use open20\amos\core\helpers\Html;
use open20\amos\documenti\utility\DocumentsUtility;
use open20\amos\documenti\widgets\graphics\WidgetGraphicsHierarchicalDocuments;

/**
 * @var yii\web\View $this
 * @var \open20\amos\documenti\models\Documenti $model
 */

$moduleDocuments = \Yii::$app->getModule(\open20\amos\documenti\AmosDocumenti::getModuleName());
$hidePubblicationDate = $moduleDocuments->hidePubblicationDate;
?>

<?= Html::beginTag('a', WidgetGraphicsHierarchicalDocuments::getLinkOptions($model)) ?>
<div class="card-container col-xs-12 nop<?= (!$model->is_folder ? ' file' : '') ?>">
    <div class="widget-listbox-option" role="option">
        <article class="col-xs-12 nop">
            <div class="container-icon col-xs-12">
                <?= DocumentsUtility::getDocumentIcon($model) ?>
            </div>
            <div class="icon-body col-xs-12">
                <p class="date">
                    <?= WidgetGraphicsHierarchicalDocuments::getDocumentDate($model) ?></p>
                <p class="directory-title"><?= WidgetGraphicsHierarchicalDocuments::getIconDescription($model) ?></p>
            </div>
        </article>
    </div>
</div>
<?= Html::endTag('a') ?>
