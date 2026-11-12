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

use open20\amos\core\helpers\Html;

$controller = \Yii::$app->controller;
$actionId = $controller->action->id;
krsort($document_tree);
$numItems = count($document_tree);
$i = 0;
$check = false;
foreach ($document_tree as $tree) {
    if ($i === 0 || $i == ($numItems - 2)) {
        if($numItems != 1){
            if(!empty($tree['type']) && $tree['type'] == 'root_teams'){
                echo Html::a(ucfirst($tree['titolo']), [
                        '/documenti/documenti/' . $actionId
                    ], [
                        'title' => ucfirst($tree['titolo']),
                    ]) . '<span class= "mdi mdi-chevron-right"></span>';
            } else {
                echo Html::a(ucfirst($tree['titolo']), [
                        '/documenti/documenti/' . $actionId, 'parentId' => $tree['id']
                    ], [
                        'title' => ucfirst($tree['titolo']),
                    ]) . '<span class= "mdi mdi-chevron-right"></span>';
            }
        }else{
            if(!empty($tree['type']) && $tree['type'] == 'root_teams') {
                echo Html::a(ucfirst($tree['titolo']), [
                    '/documenti/documenti/' . $actionId
                ], [
                    'title' => ucfirst($tree['titolo']),
                ]);
            } else {
                echo Html::a(ucfirst($tree['titolo']), [
                    '/documenti/documenti/' . $actionId, 'parentId' => $tree['id']
                ], [
                    'title' => ucfirst($tree['titolo']),
                ]);
            }
        }
    } elseif ($i == $numItems - 1) {
        echo ucfirst($tree['titolo']);
    } else {
        if (!$check) {
            echo '.....<span class= "mdi mdi-chevron-right"></span>';
            $check = true;
        }
    }
    $i++;
}