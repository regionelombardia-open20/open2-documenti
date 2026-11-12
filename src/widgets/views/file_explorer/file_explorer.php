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

use Yii;
use yii\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\documenti\AmosDocumenti;
use open20\amos\core\forms\ContextMenuWidget;
use open20\design\assets\BootstrapItaliaDesignAsset;

$currentAsset = BootstrapItaliaDesignAsset::register($this);

$data_aggiornamento = '';
$latestDate = reset($files)->data_pubblicazione;
foreach($files as $file){
    if (strtotime($file->data_pubblicazione) > strtotime($latestDate)) {
        $latestDate = $file->data_pubblicazione;
    }
}
$data_aggiornamento = $latestDate;

?>
<div class="file-explorer-container">
    
    <div class="loading" style="display:none"><span class="mdi mdi-loading mdi-spin" style="font-size:60px"></span></div>
    
    <?php if(!$params['hideBreadcrumb']): ?>
    <div class="col-xs-12 breadcrumbs">
        <?= $this->render('file_explorer_breadcrumbs',['breadcrumbs'=>$breadcrumbs]); ?>
    </div>
    <?php endif; ?>
    
    <div class="col-xs-12 main-file d-flex">
        <p><h3><?= AmosDocumenti::t('amosdocumenti', 'Sei in:') ?> <b><?= empty($model) ? Yii::t('amosdocumenti','Home') : '<span class="mdi mdi-folder-outline" />'.$model->titolo ?></b></h3></p>     
    </div>
    
    <?php if($params['showUpdateDate'] && !empty($data_aggiornamento)): ?>
    <div class="col-xs-12">
        <p class="h5"><b><?= AmosDocumenti::t('amosdocumenti', 'Contenuti aggiornati al:') ?></b> <?= date('d/m/Y',strtotime($data_aggiornamento)) ?></p>     
    </div>
    <?php endif; ?>
    
    <hr>
    
    <?php if($params['withSearch']): ?>
    <div class="col-xs-12 filter">
        <?= $this->render('file_explorer_filter',['folder'=>$model,'filters'=>$filters,'params'=>$params]); ?>
        <hr>
    </div>
    <?php endif; ?>
    
    <?php if(!$params['hideFolder']): ?>
    <div class="col-xs-12 folders">
        <?= $this->render('file_explorer_folders',['folders'=>$folders, 'defaultFolderColumns' => $defaultFolderColumns]); ?>
    </div>
    <?php endif; ?>

    <div class="col-xs-12 list">
        <?= $this->render('file_explorer_list',['files'=>$files,'params'=>$params]); ?>
    </div>
</div>