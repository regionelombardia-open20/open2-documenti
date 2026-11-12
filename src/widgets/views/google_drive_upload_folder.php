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
<div class="col-xs-12 form-group">
    <div class="col-xs-12">
        <label class="control-label"><?= AmosDocumenti::t('amosdocumenti','Sincronizza cartella con Google Drive')?></label>
    </div>
    <div class="col-xs-12">
        <div class="form-control fake-form-google-drive">
            <span id="drive-folder-filename"><?= AmosDocumenti::t('amosdocumenti','Seleziona la cartella ...')?></span>
            <?= Html::button(AmosIcons::show('google-drive'), ['id' => 'auth', 'class' => 'btn btn-primary']); ?>
        </div>
    </div>
</div>