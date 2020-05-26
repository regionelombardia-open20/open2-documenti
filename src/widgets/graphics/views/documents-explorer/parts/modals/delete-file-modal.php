<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    Open20Package
 * @category   CategoryName
 */
use \open20\amos\documenti\AmosDocumenti;
?>
<script id="documents-explorer-delete-file-modal" type="text/template">
    <div id="documents-explorer-delete-file-modal-content" class="modal modal-document-explorer">
        <div class="row">
            <div class="col-xs-12">
                <h2><?= AmosDocumenti::t('amosdocumenti', 'Vuoi davvero eliminare questo file?'); ?></h2>
                <div id="form-actions" class="bk-btnFormContainer">
                    <button class="btn btn-navigation-primary" id="documents-explorer-delete-file-modal-yes"><?= AmosDocumenti::t('amosdocumenti', 'Si'); ?></button>
                    <a class="btn btn-secondary undo-edit" id="documents-explorer-delete-file-modal-no" rel="modal:close"><?= AmosDocumenti::t('amosdocumenti', 'No'); ?></a>
                </div>
                <div class="errors">
                </div>
            </div>
        </div>
    </div>
</script>