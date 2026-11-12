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

use open20\amos\core\icons\AmosIcons;
use open20\amos\core\utilities\ModalUtility;
use open20\amos\documenti\AmosDocumenti;
use open20\amos\documenti\controllers\DocumentiController;
use yii\helpers\Url;

/**
 * @var yii\web\View $this
 * @var open20\amos\documenti\models\Documenti $model
 * @var bool $isInIndex
 * @var string $customClasses
 */

/** @var DocumentiController $appController */
$appController = Yii::$app->controller;
$documentsModule = $appController->documentsModule;

// TODO non rimuovere la riga commentata. Sostituire la riga commentata alla riga successiva quando e se sarà implementata la duplicazione delle cartelle.
//if ($documentsModule->enableContentDuplication && Yii::$app->user->can('DOCUMENTI_UPDATE', ['model' => $model])) {
if ($documentsModule->enableContentDuplication && Yii::$app->user->can('DOCUMENTI_UPDATE', ['model' => $model]) && !$model->isFolder()) {
    if ($documentsModule->enableFolders && $model->isFolder()) {
        $modalDescriptionText = AmosDocumenti::t('amosdocumenti', '#duplicate_content_folder_modal_text');
        $btnText = AmosDocumenti::t('amosdocumenti', '#duplicate_content_folder');
    } else {
        $modalDescriptionText = AmosDocumenti::t('amosdocumenti', '#duplicate_content_document_modal_text');
        $btnText = AmosDocumenti::t('amosdocumenti', '#duplicate_content_document');
    }
    $title = $btnText;
    if ($isInIndex) {
        $btnText = AmosIcons::show('copy', ['class' => '']);
        $classes = (isset($customClasses) ? $customClasses : 'btn btn-tools-secondary');
    } else {
        $classes = (isset($customClasses) ? $customClasses : 'bk-btnImport pull-right btn btn-secondary');
    }
    echo ModalUtility::addConfirmRejectWithModal([
        'modalId' => 'duplicate-content-modal-id-' . $model->id,
        'modalDescriptionText' => $modalDescriptionText,
        'btnText' => $btnText,
        'btnLink' => Url::to(['/documenti/documenti/duplicate-content', 'id' => $model->id]),
        'btnOptions' => [
            'title' => $title,
            'class' => $classes
        ]
    ]);
}

?>
