<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    svilupposostenibile\enti
 * @category   CategoryName
 */

use yii\db\Migration;
use open20\amos\documenti\models\Documenti;


class m220725_147900_fix_documenti_titolo_multiple_spaces extends Migration
{

    public function up()
    {
        $documenti = Documenti::find()
                ->andWhere(['deleted_at'=>null])
                ->andWhere(['like', 'titolo', '  '])->all();

        foreach($documenti as $doc){
            $titolo = preg_replace("/ {2,}/", " ", $doc->titolo); 
            \Yii::$app->db->createCommand("UPDATE documenti SET titolo =:titolo WHERE id =:id")
                    ->bindValue(':titolo', $titolo)
                    ->bindValue(':id', $doc->id)
                    ->execute();      
        }
     
    }
    public function down()
    {
        return true;
    }
}