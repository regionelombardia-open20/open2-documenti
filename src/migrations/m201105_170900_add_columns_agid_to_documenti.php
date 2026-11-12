<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   svilupposostenibile\enti
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

use yii\db\Migration;


class m201105_170900_add_columns_agid_to_documenti extends Migration
{

    public function up()
    {
        // add columns to documenti

        $this->addColumn( 'documenti', 'object', $this->text()->null()->defaultValue(null)->comment('Object') );
        $this->addColumn( 'documenti', 'extended_description', $this->text()->null()->defaultValue(null)->comment('Extended Description') );
        $this->addColumn( 'documenti', 'distribution_license', $this->text()->null()->defaultValue(null)->comment('Distribution license') );
        $this->addColumn( 'documenti', 'start_date', $this->date()->null()->defaultValue(null)->comment('Initial date and stage. For example, opening date for participation in a call') );
        $this->addColumn( 'documenti', 'dates_and_intermediate_stages', $this->text()->null()->defaultValue(null)->comment('Dates and intermediate stages') );
        $this->addColumn( 'documenti', 'end_date', $this->date()->null()->defaultValue(null)->comment('Provide an expiration date for the document content. Example date of communication of the winners of the competition') );
        $this->addColumn( 'documenti', 'further_information', $this->text()->null()->defaultValue(null)->comment('Learn more about the document') );
        $this->addColumn( 'documenti', 'regulatory_requirements', $this->text()->null()->defaultValue(null)->comment('List of links with normative references useful for the document') );
        $this->addColumn( 'documenti', 'protocol', $this->text()->null()->defaultValue(null)->comment('Protocol number of the document') );
        $this->addColumn( 'documenti', 'protocol_date', $this->date()->null()->defaultValue(null)->comment('Protocol date of the document') );
        $this->addColumn( 'documenti', 'help_box', $this->text()->null()->defaultValue(null)->comment('Any support contacts for the user. For example the references of the URP') );

    }

    public function down()
    {

        // dropColumn to documenti

        $this->dropColumn('documenti', 'object');
        $this->dropColumn('documenti', 'extended_description');
        $this->dropColumn('documenti', 'distribution_license');
        $this->dropColumn('documenti', 'start_date');
        $this->dropColumn('documenti', 'dates_and_intermediate_stages');
        $this->dropColumn('documenti', 'end_date');
        $this->dropColumn('documenti', 'further_information');
        $this->dropColumn('documenti', 'regulatory_requirements');
        $this->dropColumn('documenti', 'protocol');
        $this->dropColumn('documenti', 'protocol_date');
        $this->dropColumn('documenti', 'help_box');
    }
}