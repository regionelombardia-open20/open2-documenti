<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open20\amos\documenti\views\email
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
use open20\amos\documenti\AmosDocumenti;
use open20\amos\notificationmanager\utility\NotifyUtility;
use open20\amos\notificationmanager\widgets\ItemAndCardWidgetEmailSummaryWidget;
use open20\amos\notificationmanager\AmosNotify;

/**
 * @var \open20\amos\admin\models\UserProfile $profile
 * @var \open20\amos\documenti\models\Documenti[] $arrayModels
 * @var string $color
 */

$colors = NotifyUtility::getColorNetwork($color);
$notifyModule = AmosNotify::instance();
?>
<?php foreach ($arrayModels as $model): ?>
    <?php
    $modelTitle = $model->getTitle();
    $modelAbsoluteFullViewUrl = Yii::$app->urlManager->createAbsoluteUrl($model->getFullViewUrl());
    ?>
    <tr>
        <td colspan="2" style="padding-bottom:10px;">
            <table width="100%">
                <tr>
                    <td valigh="top" style="font-size:18px; font-weight:bold; font-family: sans-serif; text-align:left; vertical-align:top;">
                        <p style="margin:0 0 5px 0">
                            <?= Html::a($modelTitle, $modelAbsoluteFullViewUrl, [
                                'style' => 'color: #000; text-decoration:none;',
                                'title' => $modelTitle
                            ]) ?>
                        </p>
                        <p style="font-size:11px; color:#4b4b4b; font-weight:bold;font-family: sans-serif;"><?= Yii::$app->formatter->asDate($model->data_pubblicazione, 'yyyy-MM-dd') ?></p>
                        <p style="font-size:13px; color:#7d7d7d; padding:10px 0; font-family: sans-serif; font-weight:normal; margin:0; text-align: left;"><?= $model->getDescription(true) ?></p>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="padding:0 0 10px 0; border-bottom:1px solid #D8D8D8;">
                        <table width="100%">
                            <tr>
                                <td width="400" style="text-align:left;">
                                    <table width="100%">
                                        <tr>
                                            <?= ItemAndCardWidgetEmailSummaryWidget::widget(['model' => $model]); ?>
                                        </tr>
                                    </table>
                                </td>
                                <td align="right" width="85" valign="bottom" style="text-align: center; padding-left: 10px;">
                                    <a href="<?= $modelAbsoluteFullViewUrl ?>"
                                        style="background:<?= ($notifyModule->mailThemeColor['bgPrimary']) ? $notifyModule->mailThemeColor['bgPrimary'] : '#297A38' ?>;
                                        border:3px solid <?= ($notifyModule->mailThemeColor['bgPrimary']) ? $notifyModule->mailThemeColor['bgPrimary'] : '#297A38' ?>;
                                        color:<?= ($notifyModule->mailThemeColor['textContrastBgPrimary']) ? $notifyModule->mailThemeColor['textContrastBgPrimary'] : '#ffffff' ?>;
                                        font-family: sans-serif; font-size: 11px; line-height: 22px; text-align: center; text-decoration: none; display: block; 
                                        font-weight: bold; text-transform: uppercase; height: 20px;" class="button-a">
                                        <!--[if mso]>&nbsp;&nbsp;&nbsp;&nbsp;<![endif]--><?= AmosDocumenti::t('amosdocumenti', '#read') ?><!--[if mso]>&nbsp;&nbsp;&nbsp;&nbsp;<![endif]-->
                                    </a>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
<?php endforeach; ?>
