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

use yii\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\documenti\AmosDocumenti;

?>

<div class="col-xs-12">
<!--        --><?php //echo Html::button(AmosIcons::show('google-drive'), ['id' => 'auth', 'class' => 'btn btn-tools-secondary']); ?>
    <p id="loaded-filename"></p>
</div>

<?php echo Html::hiddenInput('authtoken', null, ['id' => 'auth-token']) ?>
<?php echo Html::hiddenInput('fileid', null, ['id' => 'file-id']) ?>
<?php echo Html::hiddenInput('filename', null, ['id' => 'file-name']) ?>

<div hidden>
    <?= $form->field($model, 'drive_file_id')->hiddenInput(['id' => 'drive-file-id']) ?>
</div>

<?php //\yii\bootstrap\Modal::begin([
//    'id' => 'modal-choose-upload'
//]) ?>
<!--<div class="col-xs-12">-->
<!--    --><?php //Html::button(AmosIcons::show('google-drive'), ['id' => 'auth', 'class' => 'btn btn-tools-secondary']); ?>
<!--    --><?php //Html::button(AmosDocumenti::t('amosdocumenti', 'Sfoglia da pc'), ['id' => 'browse-id', 'class' => 'btn btn-tools-secondary']); ?>
<!--</div>-->
<?php //\yii\bootstrap\Modal::end() ?>
