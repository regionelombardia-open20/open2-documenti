<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open20\amos\documenti\widgets\graphics\views\fullsize
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
use open20\amos\documenti\AmosDocumenti;
use open20\amos\documenti\models\DocumentiCategorie;
use open20\amos\documenti\widgets\graphics\WidgetGraphicsDocumentsByCategory;

/**
 * @var yii\web\View $this
 * @var WidgetGraphicsDocumentsByCategory $widget
 * @var DocumentiCategorie $documentCategory
 */

$documentsByCategoryUrl = $widget->makeDocumentsByCategoryUrl($documentCategory->id);
$categoryImage = Html::img($documentCategory->getAvatarUrl('square_small'), [
    'class' => 'gridview-image',
    'alt' => AmosDocumenti::t('amosdocumenti', 'Immagine della categoria')
]);

?>

<div class="widget-listbox-option" role="option">
    <article class="wrap-item-box">
        <div class="item-box_header">
            <div class="item-box_img"><?= Html::a($categoryImage, $documentsByCategoryUrl); ?></div>
            <div class="item-box_title"><?= Html::a($documentCategory->titolo, $documentsByCategoryUrl); ?></div>
        </div>
        <!-- <div class="item-box_desc"><p>< ?= $documentCategory->descrizione_breve; ?></p></div> -->
        <div class="item-box_desc"><?= Html::a($documentCategory->descrizione_breve, $documentsByCategoryUrl); ?></p></div>
        <div class="item-box_count"><p>Documenti Presenti: <strong><?= $widget->documentsCountByCategories[$documentCategory->id]; ?></strong></p></div>
    </article>
</div>
