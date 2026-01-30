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


class m240916_170900_add_column_msgraph_drive_item_id extends Migration
{

    public function up()
    {
        // add columns to documenti
        $this->addColumn('documenti', 'msgraph_drive_item_id', $this->string()->defaultValue(null)->comment('Id file teams')->after('type'));
        $this->addColumn('documenti', 'msgraph_channel_id', $this->string()->defaultValue(null)->comment('Id channel')->after('type'));
    }


    public function down()
    {

        // dropColumn to documenti

        $this->dropColumn('documenti', 'msgraph_drive_item_id');
        $this->dropColumn('documenti', 'msgraph_channel_id');

    }
}