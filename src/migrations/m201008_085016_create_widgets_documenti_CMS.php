<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open20\amos\news
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
use open20\amos\dashboard\models\AmosWidgets;

class m201008_085016_create_widgets_documenti_CMS extends Migration
{
    const MODULE_NAME = 'news';
    private $widgets;
    
    private function initWidgetsConfs()
    {
        $this->widgets = [
            [
                'classname' => \open20\amos\documenti\widgets\graphics\WidgetGraphicsCmsUltimiDocumenti::className(),
                'type' => AmosWidgets::TYPE_GRAPHIC,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'dashboard_visible' => 1,
            ],
           
        ];
    }
    
    public function safeUp()
    {
        $this->initWidgetsConfs();
        
        foreach ( $this->widgets as $singleWidget )
        {
            $this->insertNewWidget( $singleWidget );
        }
    
        return true;
    }
    
    /**
     * Metodo privato per l'inserimento della configurazione per un nuovo widget.
     *
     * @param array $newWidget    Array chiave => valore contenente i dati da inserire nella tabella.
     */
    private function insertNewWidget( $newWidget )
    {
        if ( $this->checkWidgetExist( $newWidget['classname'] ) )
        {
            echo "Widget news ".$newWidget['classname']." esistente. Skippo...\n";
        }
        else
        {
            $this->insert( AmosWidgets::tableName(), $newWidget );
            echo "Widget news ".$newWidget['classname']." creato.\n";
        }
    }
    
    private function checkWidgetExist( $classname )
    {
        $sql = "SELECT COUNT(classname) FROM ".AmosWidgets::tableName()." WHERE classname LIKE '".addslashes(addslashes($classname))."'";
        $cmd = $this->db->createCommand();
        $cmd->setSql( $sql );
        $oldWidgets = $cmd->queryScalar();
        return ( $oldWidgets > 0 );
    }
    
    public function safeDown()
    {
        $this->initWidgetsConfs();
        $this->execute("SET foreign_key_checks = 0;");
        foreach ( $this->widgets as $singleWidget )
        {
            $where = " classname LIKE '".addslashes(addslashes($singleWidget['classname']))."'";
            $this->delete( AmosWidgets::tableName(), $where );
        }
        $this->execute("SET foreign_key_checks = 1;");
        
        return true;
    }
}
