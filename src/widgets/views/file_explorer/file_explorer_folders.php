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
use open20\design\assets\BootstrapItaliaDesignAsset;
use open20\amos\core\record\CachedActiveQuery;
use yii\helpers\Url;

$currentAsset = BootstrapItaliaDesignAsset::register($this);   

?>

<div class="folders">
    
    <div class="it-carousel-wrapper owl-carousel-design">
        <div class="it-carousel-all owl-carousel">
        
        <?php foreach($folders as $model): 
            
            $relationQuery = $model->getCreatedUserProfile();
            $relationCreated = CachedActiveQuery::instance($relationQuery);
            $relationCreated->cache(60);
            $createdUserProfile = $relationCreated->one();
            
            $documentMainFile = $model->getDocumentMainFile();
            $category = $model->documentiCategorie;
            $nameCategory = null;
            if(!empty($category)){
              $nameCategory= $category->titolo;
            }
        ?>
        
          <div class="it-single-slide-wrapper">
            <!--start card-->
            <?php
            $url = Url::to(['/documenti/documenti-ajax/file-explorer']);
            $linkOptions = ['href' => '#', 'title' => $model->titolo, 'alt' => $model->titolo, 'class' => 'read-more','onclick'=>"

                event.preventDefault();
                var url = '$url'; 
                var id = '{$model['id']}'; 
                sendData(url,id);

            "];
            
            echo $this->render('@vendor/open20/design/src/components/bootstrapitalia/views/bi-document-card',[
              'title' => $model->titolo,
              //'url' => '/documenti/documenti/view?id=' . $model->id,
              'nameSurname' => $createdUserProfile->nomeCognome,
              'fileName' => $documentMainFile->name,
              'nameFile' =>  $documentMainFile->name,
              'type' => $documentMainFile->type,
              'typeFolder' => $model->is_folder,
              'description' =>  $model->descrizione_breve,
              'size' => $model->documentMainFile->size%1024,
              'date' => $model->created_at,
              'model' => $model,
              'fileUrl' => $linkOptions,
              'link_document' => $model->link_document,
              'allegatiNum' => null, //TODO inserire numero di allegati interni (se esistono)
              'actionModify' => '/documenti/documenti/update?id='.$model->id,
              'actionDelete' => '/documenti/documenti/delete?id='.$model->id,
              'widthColumn' => 'col-xs-12',
              'category' => $nameCategory,

            ]
          );
              /*echo $this->render('_folder',
                [
                    'iconBaseUrl' => $currentAsset->baseUrl,
                    'model' => $model, 
         
                ]
              );*/
              ?>
            <!--end card-->
          </div>
            
        <?php endforeach; ?>
          
        </div>
  </div>
    
</div>
