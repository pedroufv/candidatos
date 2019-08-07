<?php

require 'vendor/autoload.php';

use Stringy\StaticStringy as S;

$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();

$inputFileName = './docs/mg/Excel-MG-Q-Z.xlsx';
$spreadsheet = $reader->load($inputFileName);

$sheet = $spreadsheet->getSheet(0);
$rowIterator = $sheet->getRowIterator();

foreach ($rowIterator as $row) {
	$rowIndex = $row->getRowIndex();

	if($rowIndex == 1) continue;

	$ignore = ['da', 'de', 'do', 'e'];
    $currencyCandidate = S::titleize($sheet->getCell('B' . $rowIndex)->getCalculatedValue(), $ignore);

    if(empty($currencyCandidate)) continue;

    $currencyPhone = preg_replace("/[^0-9]/","", $sheet->getCell('D' . $rowIndex)->getCalculatedValue());

	if(empty($currencyPhone)) continue;
	
    echo "Nome: $currencyCandidate e Telefone: $currencyPhone".PHP_EOL;
   
}


die(PHP_EOL);