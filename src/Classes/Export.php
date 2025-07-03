<?php

namespace PWI\ContaoNewsletterExportBundle\Classes;

use Contao\BackendUser;
use Contao\Config;
use Contao\Database;
use Contao\Input;
use Contao\System;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Csv;

class Export
{

    public function run(): void
    {

        $arrExport = [];
        $strId = Input::get('id') ?: '';
        $arrValues = [];

        if ($strId) {
            $arrValues[] = $strId;
        }

        System::loadLanguageFile('tl_newsletter_recipients');

        $objRecipients = Database::getInstance()->prepare('SELECT * FROM tl_newsletter_recipients' . (!empty($arrValues) ? ' WHERE pid=?' : ''))->execute(...$arrValues);

        while ($objRecipients->next()) {

            $arrData = [];

            foreach ($objRecipients->row() as $strField => $strValue) {

                if (\in_array($strField, ['id', 'pid', 'tstamp'])) {
                    continue;
                }

                if ($strField == 'addedOn' && \is_numeric($strValue)) {
                    $strValue = \date(Config::get('datimFormat'), $strValue);
                }

                $strLabel = $GLOBALS['TL_LANG']['tl_newsletter_recipients'][$strField][0] ?? $strField;
                $arrData[$strLabel] = $strValue;
            }

            $arrExport[] = $arrData;
        }

        $objSpreadsheet = new Spreadsheet();
        $objSpreadsheet->getProperties()
            ->setTitle('Newsletter-Export')
            ->setCreator('Contao CMS')
            ->setLastModifiedBy(BackendUser::getInstance()->email);

        $objSheet = $objSpreadsheet->getActiveSheet();

        $numRows = 1;
        $arrFields = \array_keys(($arrExport[0] ?? []));

        foreach ($arrFields as $numCols => $strField) {
            $objSheet->setCellValue([$numCols + 1, $numRows], $strField);
        }

        $numRows++;
        foreach ($arrExport as $arrMember) {
            $numCols = 1;
            foreach ($arrMember as $strValue) {
                $objSheet->setCellValue([$numCols, $numRows], $strValue);
                $numCols++;
            }
            $numRows++;
        }

        \header('Content-Disposition: attachment;filename="export-' . \date('Y_m_d_Hi'). '-' . \uniqid() . '.csv"');
        \header('Cache-Control: max-age=0');
        \header('Content-Type: application/vnd.ms-excel');

        $objCsv = new Csv($objSpreadsheet);
        $objCsv->setDelimiter(';');
        $objCsv->setEnclosure('"');
        $objCsv->save('php://output');
    }
}