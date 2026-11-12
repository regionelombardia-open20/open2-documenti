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
<script id="documents-explorer-files" type="text/template">
    <div class="col-xs-12 header">
        <h3>Files ({{count}})</h3>
        {{#canCreate}}
        <button id="upload-new-files" class="btn btn-administration-primary"><span class="am am-plus"></span>
            <?= AmosDocumenti::t('amosdocumenti', 'Caricamento File'); ?>
        </button>
        <button id="upload-multi-files" class="btn btn-administration-primary"><span class="am am-plus"></span>
            <?= AmosDocumenti::t('amosdocumenti', 'Caricamento Multiplo'); ?>
        </button>
        {{/canCreate}}
    </div>
    {{#available}}
    <div class="col-xs-12 nop">
        {{#files}}
        <div class="col-xs-12 col-sm-3 file-item">
            <div class="file">
                <a href="{{url}}" title="Visualizza {{name}}">
                    <div class="file-preview" data-model-id="{{model-id}}"
                         data-model-hash="{{model-hash}}" data-file-id="{{model-file-id}}">
                        <span class="icon_widget_graph dash dash-{{icon-class}}"> </span>
                        <span class="file-date">{{date}}</span>
                        <span class="file-name">{{name}}</span>
                        <span class="file-link">{{link}}</span>
                        <span class="file-size">{{size}}</span>
                    </div>
                    <span class="pull-right am am-menu context-menu-documents" data-model-id="{{model-id}}"
                          data-model-hash="{{model-hash}}" data-file-id="{{model-file-id}}"></span>
                </a>
            </div>
        </div>
        {{/files}}
    </div>
    {{/available}}
</script>