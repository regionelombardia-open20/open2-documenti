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


class m250121_310900_add_column_doc_lock_update extends Migration
{

    public function up()
    {
        // add columns to documenti
        $this->addColumn('documenti', 'lock_update', $this->integer()->defaultValue(0)->comment('Lock update document')->after('drive_file_id'));
    }


    public function down()
    {

        // dropColumn to documenti

        $this->dropColumn('documenti', 'lock_update');

    }
}