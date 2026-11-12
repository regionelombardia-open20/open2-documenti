<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open20\amos\documenti\models
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

namespace open20\amos\documenti\models;

use open20\amos\community\models\Community;
use open20\amos\documenti\AmosDocumenti;

/**
 * Class ReportNode
 * @package open20\amos\documenti\models
 */
class ReportNode extends \yii\base\Model
{
    const COMMUNITY = 'area-stanza';
    const DIRECTORY = 'cartella';
    const FILE = 'file';

    /** @var array Is the full path that is bulit */
    public static $lastDir = [''];
    public static $filenameLog = [''];
    public $logfile = '';
    public $type;
    public $id;
    public $name;
    public $humanReadablePath;
    public $error = false;
    public $errorMessage = '';

    /**
     *
     */
    public function init()
    {
        parent::init();

        if (!empty($this->logfile)) {
            self::$filenameLog = $this->logfile;
        }

        if ($this->type == self::COMMUNITY) {
            $community = Community::findOne($this->id);
            $this->humanReadablePath = self::getStringPathLastDir() . '\\' . $this->name . "\\";
            self::$lastDir[] = $this->name;
            if (empty($community)) {
                $this->error = true;
                $this->errorMessage = 'Community not created';
            }
        } else if ($this->type == self::DIRECTORY) {
            /** @var Documenti $documentiModel */
            $documentiModel = AmosDocumenti::instance()->createModel('Documenti');
            $documenti = $documentiModel::findOne($this->id);
            $this->humanReadablePath = self::getStringPathLastDir() . '\\' . $this->name . "\\";
            self::$lastDir[] = $this->name;
            if (empty($documenti)) {
                $this->error = true;
                $this->errorMessage = 'Directory not created';
            }
        } else {
            /** @var Documenti $documentiModel */
            $documentiModel = AmosDocumenti::instance()->createModel('Documenti');
            $documenti = $documentiModel::findOne($this->id);
            if ($documenti) {
                $this->name = $documenti->titolo;
                $file = $documenti->getDocumentMainFile();
                if ($file) {
                    $file->getPath();
                    if (!file_exists($file->getPath())) {
                        $this->error = true;
                        $this->errorMessage = 'File non found';
                    }
                }

                if (empty($file)) {
                    $this->error = true;
                    $this->errorMessage = 'File not created';
                }
            } else {
                $this->error = true;
                $this->errorMessage = 'Document not created';
            }

            $this->humanReadablePath = self::getStringPathLastDir() . '\\' . $this->name;
        }

//        $this->writeLog();
    }

    /**
     *
     */
    public function writeLog()
    {
        $logPath = self::$filenameLog;
        $fp = fopen($logPath, 'a+');
        fwrite($fp, $this->type . "\t" . $this->humanReadablePath . "\t" . $this->error . "\t" . $this->errorMessage . "\t\n");
        fclose($fp);
    }

    /**
     * @return string
     */
    public static function getStringPathLastDir()
    {
        return implode("\\", self::$lastDir);
    }

    /**
     *
     */
    public static function popDirectoryPathOneLevel()
    {
        if (count(self::$lastDir) > 0) {
            array_pop(self::$lastDir);
        }

        return true;
    }

    /**
     * @param $currentName
     * @return bool
     */
    public static function popDirectoryPath($currentName)
    {
        if (end(self::$lastDir) == $currentName && !empty($currentName) && is_array(self::$lastDir)) {
            array_pop(self::$lastDir);
            return true;
        } elseif (is_array(self::$lastDir) && count(self::$lastDir)) {
            array_pop(self::$lastDir);
            self::popDirectoryPath($currentName);
        }
    }

    /**
     *
     * @param type $filename
     */
    public static function generateExcellFromFile($filename)
    {
        $nameFile = 'Report_import_aree.xls';
        //array per il file
        $xlsData = [];
        $xlsData[] = ['Type', 'Path', 'Error', 'Error message'];

        $handle = fopen($filename, "r");
        $reports = [];
        if ($handle) {
            while (($line = fgets($handle)) !== false) {
                $xlsData [] = preg_split('/[\t]/', $line);
            }

            fclose($handle);
        } else {
            // error opening the file.
        }

        //inizializza l'oggetto excel
        $objPHPExcel = new \PHPExcel();

        //li pone nella tab attuale del file xls
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('d')->setAutoSize(true);

        $objPHPExcel->getActiveSheet()->fromArray($xlsData, NULL, 'A1');
        $objPHPExcel->getActiveSheet()->getStyle('A1:D1')->applyFromArray(
            [
                'font' => ['bold' => true],
                'alignment' => ['horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,],
                'borders' => [
                    'top' => [
                        'style' => \PHPExcel_Style_Border::BORDER_DOUBLE
                    ]
                ],
                'fill' => [
                    'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                    'startcolor' => [
                        'rgb' => 'cccccc'
                    ],
                    'endcolor' => [
                        'rgb' => 'cccccc'
                    ]
                ]
            ]
        );

        header('Content-type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="' . $nameFile . '"');

        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
    }
}
