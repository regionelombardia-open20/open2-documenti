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


class m240403_120900_add_column_documenti_type extends Migration
{

    public function up()
    {
        // add columns to documenti

        $this->addColumn( 'documenti', 'type', $this->integer()->defaultValue(0)->comment('tipologia di documento')->after('id') );
     
    }


    public function down()
    {

        // dropColumn to documenti

        $this->dropColumn('documenti', 'type');

    }
}