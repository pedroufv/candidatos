<?php

require 'vendor/autoload.php';

use Stringy\StaticStringy as S;
use Helpers\Phone;

$formated = false;
if(isset($_SERVER['argv'][1]) AND  $_SERVER['argv'][1] == 'formatted')
    $formated = true;

$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx(); // trocar para IO para usar escrita

$inputFileName = './docs/mg/Excel-MG-Q-Z.xlsx'; // ler o diretorio docs e iterar sobre ele
$spreadsheet = $reader->load($inputFileName);

$sheet = $spreadsheet->getSheet(0);
$rowIterator = $sheet->getRowIterator();

$count = 0;
$countPhone = 0;
$countEmptyName = 0;
$countEmptyPhone = 0;
foreach ($rowIterator as $row) {
    $count++;
    $rowIndex = $row->getRowIndex();

    if ($rowIndex == 1) {
        continue;
    }

    $ignore = ['da', 'de', 'do', 'e'];
    $currencyCandidate = S::titleize($sheet->getCell('B'.$rowIndex)->getCalculatedValue(), $ignore);

    // remove nomes vazios
    if (empty($currencyCandidate)) {
        $countEmptyName++;
        $currencyCandidate = "Sem Nome";
    }

    $currencyPhone = Phone::onlyDigits($sheet->getCell('D'.$rowIndex)->getCalculatedValue());

    if (empty($currencyPhone)) {
        $countEmptyPhone++;
        continue;
    }

    $string = $currencyPhone;
    $ddd = substr($currencyPhone, 0, 2);
    while (strlen($string)%11 == 0 AND strlen($string) != 0) {
        $currencyPhone = substr($string, 0, 11);
        $currencyFormatedPhone = $formated ? ", ".Phone::formatPhone($currencyPhone) : '';
        $string = substr($string, 11);

        if(strlen($string) == 9)
            $string = $ddd.substr($string, 11);

        $countPhone++;

        echo "$currencyCandidate, $currencyPhone$currencyFormatedPhone". PHP_EOL;
    }

    while (strlen($string)%10 == 0 AND strlen($string) != 0) {
        $currencyPhone = substr($string, 0, 2) . 9 . substr($string, 2);
        $currencyFormatedPhone = $formated ? ", ".Phone::formatPhone($currencyPhone) : '';
        $string = substr($string, 10);

        $countPhone++;

        echo "$currencyCandidate, $currencyPhone$currencyFormatedPhone". PHP_EOL;
    }
}

echo PHP_EOL."Resumo: ".PHP_EOL;
echo "total celulas: ". $count . PHP_EOL;
echo "total números: ". $countPhone . PHP_EOL;
echo "total celulas sem nome: ". $countEmptyName . PHP_EOL;
echo "total celulas sem telefone: ". $countEmptyPhone . PHP_EOL;

die(PHP_EOL);
