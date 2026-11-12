<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open20\amos\documenti\views\documenti
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
use open20\amos\core\icons\AmosIcons;
use open20\amos\documenti\AmosDocumenti;
use open20\amos\documenti\utility\DocumentsUtility;

/**
 * @var yii\web\View $this
 * @var open20\amos\documenti\models\Documenti $model
 */

$this->title = $model->titolo;
$ruolo = Yii::$app->authManager->getRolesByUser(Yii::$app->getUser()->getId());
$documentMainFile = $model->documentMainFile;


/** @var \open20\amos\documenti\controllers\DocumentiController $controller */


?>

<div class="documents-view col-xs-12 nop">
    <div class="clearfix"></div>
    <div class="col-md-8 col-xs-12">
        <div class="header col-xs-12 nop">
            <div class="title col-xs-12">
                <h2 class="title-text"><?= $model->titolo ?></h2>
                <h3 class="subtitle-text"><?= $model->sottotitolo ?></h3>
            </div>
        </div>
        <div class="col-xs-12 download-file nop">
            <div class="col-xs-12 action-document">
                <div>
                    <div>
                        <?=
                        DocumentsUtility::getDocumentIcon($model);
                        ?>
                    </div>
                    <div>
                        <?=
                        Html::tag('p', ((strlen($documentMainFile->name) > 80) ? substr($documentMainFile->name, 0, 75) . '[...]' : $documentMainFile->name) . '.' . $documentMainFile->type, ['class' => 'filename']);
                        ?>
                    </div>
                </div>
                <div>
                    <?= Html::a(/*AmosDocumenti::tHtml('amosdocumenti', 'Scarica file') . */
                        AmosIcons::show('download'), [$model->getDocumentMainFile()->getWebUrl()], [
                        'title' => AmosDocumenti::t('amosdocumenti', 'Scarica file'),
                        'class' => 'bk-btnImport pull-right btn btn-icon',
                    ]); ?>

                </div>
            </div>

        </div>
        <div class="text-content col-xs-12 nop">
            <?= $model->descrizione; ?>
        </div>
    </div>


</div>
<?= Html::a(AmosDocumenti::t('amosnews', '#enter_into_platform'), ['/documenti/documenti/view', 'id' => $model->id], [
    'class' => 'btn btn-secondary pull-left m-b-10'
])?>