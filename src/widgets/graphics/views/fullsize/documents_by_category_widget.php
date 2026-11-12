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
use open20\amos\core\icons\AmosIcons;
use open20\amos\documenti\AmosDocumenti;
use open20\amos\documenti\assets\ModuleDocumentiAsset;
use open20\amos\documenti\widgets\graphics\WidgetGraphicsDocumentsByCategory;

/**
 * @var yii\web\View $this
 * @var WidgetGraphicsDocumentsByCategory $widget
 */

ModuleDocumentiAsset::register($this);

?>

<div class="box-widget-header">
    <div class="box-widget-wrapper">
        <h2 class="box-widget-title">
            <?= AmosIcons::show('file-text-o', [], AmosIcons::DASH) ?>
            <span class="language-item"><?= AmosDocumenti::t('amosdocumenti', '#widget_graphic_documents_by_category_label') ?></span>
        </h2>
    </div>
    <div class="read-all"><?= Html::a(
            AmosDocumenti::t('amosdocumenti', 'Visualizza Tutti') . AmosIcons::show('chevron-right'),
            $widget->getBasicUrl(),
            ['class' => ''],
            false
        ); ?></div>
</div>
<div class="box-widget box-widget-column documents-by-category-widget">
    <section>
        <div class="list-items">
            <?php if (count($widget->documentsCategories) == 0): ?>
                <div class="list-items list-empty">
                    <p><?= AmosDocumenti::t('amosdocumenti', '#widget_graphic_documents_by_category_list_no_categories') ?></p>
                </div>
            <?php endif; ?>
            <?php foreach ($widget->documentsCategories as $documentCategory): ?>
                <?= $this->render('_list_element', [
                    'widget' => $widget,
                    'documentCategory' => $documentCategory,
                ]) ?>
            <?php endforeach; ?>
        </div>
    </section>
</div>
