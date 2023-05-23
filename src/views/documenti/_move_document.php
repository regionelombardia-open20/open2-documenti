<?php

/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Proscriptions/proscription-default.txt to change this proscription
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */
use yii\helpers\Html;
use open20\amos\documenti\AmosDocumenti;
use open20\amos\cwh\models\CwhPubblicazioniCwhNodiEditoriMm;
use open20\amos\documenti\models\search\DocumentiSearch;
use open20\amos\core\forms\ActiveForm;
use yii\helpers\ArrayHelper;

$documentsModule = AmosDocumenti::instance();
if($documentsModule->enableMoveDoc){
  $jsMove = <<<JS
          
	
	$(document).on("click", ".open-modalMove", function () {
            var myDocId = $(this).data('id');
          console.log($(this).data('id'));
            $(".modal-body #docId").val( myDocId );
        })
JS;
  $this->registerJs($jsMove);
}


$moduleCwh = \Yii::$app->getModule('cwh');
                        isset($moduleCwh) ? $showReceiverSection = true : null;
                        isset($moduleCwh) ? $scope = $moduleCwh->getCwhScope() : null;
                      
?>

<div class="modal" id="modalMove" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content" style="background-color:#fff !important">
                <div class="modal-header flexbox" style="justify-content:space-between">
                        <h5 class="modal-title"> <?= AmosDocumenti::t('amoscommunity', 'Spostamento Documento') ?>:</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">
                       <?php $form = ActiveForm::begin([
                               'action' => '/'.AmosDocumenti::getModuleName().'/documenti/move',
                               'id' => 'form-move-group',

                       ]); 
                     if($scope['community']){
                         $documentiSearch = new DocumentiSearch();
                         
                            $communityId = $scope['community'];
                            $editorsNode = CwhPubblicazioniCwhNodiEditoriMm::find()->andWhere(['cwh_network_id' => $communityId])->all();
                           // $query = DocumentiSearch::find()->andWhere(['is_folder'=>1]);
                            $data =  \yii\helpers\ArrayHelper::map($documentiSearch->getFoldersList($communityId,$parentId),'id', 'titolo');
                            if($parent){
                                $data = ArrayHelper::merge(['0'=>'Radice'],$data);
                            }
                        }
                       ?>

                       <p><?php echo \Yii::t('app', "Cartella di destinazione: ");


                               echo \kartik\select2\Select2::widget([
                                       'name' => 'destinationFolder',
                                       'data' => $data,
                                       'options' => [
                                               'id' => 'destination-select-id',
                                               'placeholder' => \Yii::t('app', "Seleziona la destinazione ..."),
                                               'multiple' => false,
                                               'title' => 'Cartella di destinazione',
                                       ],
                                       'pluginOptions' => ['allowClear' => true]
                               ]) ?>
                       <div class="modal-body">
                           <input type="hidden" name="docId" id="docId" />
                       </div>
                          
                       </p>

                </div>
                <div class="modal-footer">
                      <div class="col-xs-12 m-t-10">
                              <?php echo Html::button(
                                      Yii::t('amoscommunity', 'Conferma'),
                                      [
                                              'class' => 'btn btn-success pull-right',
                                              'value' => 'conferma',
                                              'type' => 'submit',
                                              'name' => 'submit-conferma',
                                              'id' => 'submitConferma'
                                      ]
                              ); ?>
                              <?php echo Html::button(
                                      Yii::t('amoscommunity', 'Annulla'),
                                      [
                                            'class' => 'btn btn-danger pull-left',
                                            'value' => 'Annulla',
                                            'data-dismiss' => 'modal',
                                      ]
                              ); ?>
                      </div>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>