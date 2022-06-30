<?php
use \open20\amos\documenti\AmosDocumenti;
?>
<script id="documents-explorer-rename-document-modal" type="text/template">
    <div id="documents-explorer-rename-document-modal-content" class="modal modal-document-explorer">
        <div class="row">
            <div class="col-xs-12">
                <h2><?= AmosDocumenti::t('amosdocumenti', 'Rinomina File'); ?></h2>
                <input id="documents-explorer-rename-document-name" class="form-control" maxlength="255" type="text">
                <div id="form-actions" class="bk-btnFormContainer">
                    <button class="btn btn-navigation-primary" id="documents-explorer-rename-document-modal-save"><?= AmosDocumenti::t('amosdocumenti', 'Salva'); ?></button>
                    <a class="btn btn-secondary undo-edit" id="documents-explorer-rename-document-modal-close" rel="modal:close"><?= AmosDocumenti::t('amosdocumenti', 'Annulla'); ?></a>
                </div>
                <div class="errors">
                </div>
            </div>
        </div>
    </div>
</script>