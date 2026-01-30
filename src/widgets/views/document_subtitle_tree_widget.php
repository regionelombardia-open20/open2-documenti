<?php

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