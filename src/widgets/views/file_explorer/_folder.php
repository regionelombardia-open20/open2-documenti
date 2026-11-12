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
use yii\helpers\Url;

//$activeClass = ($isActive) ? 'active' : '';
//$cardBG=($activeClass)? '' : 'bg-card-preference-bg'; 
//$isActionDisabledClass = ($isActionDisabled) ? 'action-disabled' : '';
//$isEmptyCard = (isset($emptyCard));
//$textEmptyTitleMessage = ($isEmptyCard) ? $emptyCard['emptyTitleMessage'] : '';
//$textEmptyMessage = ($isEmptyCard) ? $emptyCard['emptyMessage'] : '';

//$idWithCounter = $baseId . $counter;
//$anchorId = $baseId . "anchor-id-" . $cardId;

$url = Url::to(['/documenti/documenti-ajax/file-explorer']);
?>

<div class="card-wrapper card-preference pb-3" id="<?= $model['id'] ?> "> 
    
    <div class="card rounded">
        <div class="card-body">
            <div class="categoryicon-top">            
                  <svg class="icon icon-folder" role="img" aria-label="Icona <?= $model['titolo'] ?>">  
                      <use xlink:href="<?= $iconBaseUrl . '/sprite/material-sprite.svg#folder' ?>"></use>
                  </svg>
                  <span class="h6"><?= Yii::t('amosdocumenti','#folder') ?></span>
            </div>

            <?= Html::a(Html::tag('h6',$model['titolo'],['class'=>'card-title mb-2 title-three-line']),'#',['class'=>'link-list-title','onclick'=>"

                    event.preventDefault();
                    var url = '$url'; 
                    var id = '{$model['id']}'; 
                    sendData(url,id);

                "]) 
            ?>

            <div class="ml-auto doc-actions d-flex">           
                <div>
                    <?php /*ContextMenuWidget::widget([
                        'model' => $model,
                        'actionModify' => "/documenti/documenti/update?id=" . $model->id,
                        'actionDelete' => "/documenti/documenti/delete?id=" . $model->id,
                        'modelValidatePermission' => 'DocumentValidate',
                        //'additionalButtons' => $additionalButtons,
                        'mainDivClasses' => 'manage-documents'
                    ]) */?>
                </div>
            </div>
        </div>
                    
    </div>
  
</div>