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

use \open20\amos\documenti\AmosDocumenti;
?>
<script id="documents-explorer-folders" type="text/template">
    <div class="col-xs-12 header">
        <h3><?= AmosDocumenti::t('amosdocumenti', 'Cartelle ') ?>({{count}})</h3> <!--TODO change with folder count-->
        {{#canCreate}}
        <button id="create-new-folder-modal" class="btn btn-administration-primary"><span class="am am-plus"></span>
            <?= AmosDocumenti::t('amosdocumenti', 'Nuova Cartella'); ?>
        </button>
        {{/canCreate}}
    </div>
    {{#available}}
    <div class="col-xs-12 nop">
        {{#folders}}
        <div class="col-sm-4 col-xs-12 folder-item folder-container">
            <div class="folder">
                <div class="folder-container-single" data-parent-id="{{model-id}}">
                    <div class="folder-name" data-model-id="{{model-id}}"> <!--TODO change tag a with div -->
                        <span class="dash dash-folder icon-folder"></span>
                        <span class="link-name" title="{{name}}">{{name}}</span> <!--TODO change tag span with a for title link-->
                    </div>
                </div>
                <span class="am am-menu context-menu-folder" data-model-id="{{model-id}}"></span>
            </div>
        </div>
        {{/folders}}
    </div>
    {{/available}}
</script>