<?php

require_once('C:/Users/Evgenia/vendor/autoload.php');
require_once('C:/MAMP/htdocs/vendor/autoload.php');
use \PhpOffice\PhpSpreadsheet\Shared\Date;

$file = 'SMILE_RU_customer_list final.xlsx'; // файл для получения данных
$excel = \PhpOffice\PhpSpreadsheet\IOFactory::load($file); // подключить Excel-файл
$excel->setActiveSheetIndex(0); // получить данные из указанного листа

$client = new \RetailCrm\ApiClient(
    'https://smaximillian.retailcrm.ru',
    'XH4gE0V9rLhccT1MWVe3YINYyAWmrV1Y',
    \RetailCrm\ApiClient::V5
);

function getxlsx($excel)
{
    foreach ($excel->getWorksheetIterator() as $worksheet) {
        $highestRow = $worksheet->getHighestRow();
        $highestColumn = $worksheet->getHighestColumn();
        $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);

        //echo '<table border="1">';

        for ($row = 1; $row <= $highestRow; $row++) {
            //echo '<tr>';


            for ($col = 1; $col <= $highestColumnIndex - 1; $col++) {
                $value = $worksheet->getCellByColumnAndRow($col, $row)->getValue();

                switch ($col) {
                    case 1: //id
                        $data[$col][$row] = $value;
                        //echo '<td>' . $value . '</td>';
                        break;
                    case 2: // пол
                        $str = mb_strtolower($value, 'UTF-8'); // перевести строку к нижнему регистру
                        $data[$col][$row] = $str;
                        //echo '<td>' . $str . '</td>';
                        break;
                    case 3: //имя

                        $str = mb_strtolower($value, 'UTF-8'); // перевести строку к нижнему регистру
                        $zagl = mb_convert_case($str, MB_CASE_TITLE, "UTF-8"); // сделать первую букву заглавной
                        $str2 = preg_replace('/[^ a-zа-яё\d]/ui', '', $zagl); //оставить только буквы и цифры

                        if (strpos($str2, ' ') !== false) {
                            $probel = substr($str2, 0, strpos($str2, ' ')); // обрезка до первого пробела, чтобы исключить отчества
                            $data[$col][$row] = $probel;
                            //echo '<td>' . $probel . '</td>';
                        } else {
                            $data[$col][$row] = $str2;
                            //echo '<td>' . $str2 . '</td>';
                        }
                        break;
                    case 4: //фамилия

                        $str = mb_strtolower($value, 'UTF-8'); // перевести строку к нижнему регистру
                        $zagl = mb_convert_case($str, MB_CASE_TITLE, "UTF-8"); // сделать первую букву заглавной
                        $str2 = preg_replace('/\d/', '', $zagl); //оставить только буквы
                        $str2 = preg_replace('/[^ a-zа-яё\d]/ui', '', $str2); //оставить только буквы и цифры

                        if (strpos($str2, ' ') !== false) {
                            $probel = substr($str2, 0, strpos($str2, ' ')); // обрезка до первого пробела, чтобы исключить отчества
                            $data[$col][$row] = $probel;
                            //echo '<td>' . $probel . '</td>';
                        } else {
                            $data[$col][$row] = $str2;
                            //echo '<td>' . $str2 . '</td>';
                        }
                        break;
                    case 5: // почта

                        $str = mb_strtolower($value, 'UTF-8'); // перевести строку к нижнему регистру

                        // валидация почты
                        if (filter_var($str, FILTER_VALIDATE_EMAIL) !== false) {
                            $data[$col][$row] = $str;
                            //echo '<td>' . $str . '</td>';
                        } else {
                            $data[$col][$row] = null;
                            //echo '<td>' . $data[$col][$row] . '</td>';
                        }
                        break;
                    case 6: // домашний телефон
                        $numbers = preg_replace('/[^0-9]/', '', $value); //оставить только числа
                        $abs = strlen($numbers);

                        if ($abs == 11) {
                            $data[$col][$row] = str_pad($numbers, 12, "+", STR_PAD_LEFT);
                            //echo '<td>' . $data[$col][$row] . '</td>';
                        } else {
                            $data[$col][$row] = NULL;
                            //echo '<td>' . $data[$col][$row] . '</td>';
                        }
                        break;
                    case 7: // телефон
                        $numbers = preg_replace('/[^0-9]/', '', $value);
                        $abs = strlen($numbers);

                        if ($abs == 11) {
                            $data[$col][$row] = str_pad($numbers, 12, "+", STR_PAD_LEFT);
                            //echo '<td>' . $data[$col][$row] . '</td>';
                        } else {
                            $data[$col][$row] = NULL;
                            //echo '<td>' . $data[$col][$row] . '</td>';
                        }
                        break;

                    case 11: // бонусы
                        $data[$col][$row] = $value;
                        //echo '<td>' . $value . '</td>';
                        break;
                    case 12: // id магазина
                        $data[$col][$row] = $value;
                        //echo '<td>' . $value . '</td>';
                        break;
                    case 13: // название магазина
                        $str = mb_strtolower($value, 'UTF-8');
                        $obmen = str_replace(' ', '_', $str);
                        $data[$col][$row] = $obmen;
                        //echo '<td>' . $obmen . '</td>';
                        break;

                }


            }
            // дата рождения
            if ($col = 8 && $col = 9 && $col = 10) {
                $valueDay = $worksheet->getCellByColumnAndRow(8, $row)->getValue();
                $valueMonth = $worksheet->getCellByColumnAndRow(9, $row)->getValue();
                $valueYear = $worksheet->getCellByColumnAndRow(10, $row)->getValue();

                if ($valueDay != 'NULL' && $valueMonth != 'NULL' && $valueYear != 'NULL') {
                    $data[8][$row] = $valueYear . '-' . $valueMonth . '-' . $valueDay;
                    //echo '<td>' .$data[8][$row]. '</td>';
                } else {
                    $data[8][$row] = NULL;
                    //echo '<td>' .$data[8][$row]. '</td>';
                }
            }


            //echo '</tr>';
        }
        //echo '</table>';
    }
    return $data;
}

function convert_arr($data, $field, $n, $mas = NULL){
    $i=0;
    foreach ($data[$n] as &$item){
        $i++;
        $mas[$i][$field] = $item;
    }
    return $mas;
}

$data = getxlsx($excel);

$data_res = convert_arr($data, 'id', 1);
$data_res = convert_arr($data, 'sex', 2, $data_res);
$data_res = convert_arr($data, 'name', 3, $data_res);
$data_res = convert_arr($data, 'surname', 4, $data_res);
$data_res = convert_arr($data, 'mail', 5, $data_res);
$data_res = convert_arr($data, 'telephonedom', 6, $data_res);
$data_res = convert_arr($data, 'telephone', 7, $data_res);
$data_res = convert_arr($data, 'date', 8, $data_res);
$data_res = convert_arr($data, 'bonus', 11, $data_res);
$data_res = convert_arr($data, 'posid', 12, $data_res);
$data_res = convert_arr($data, 'posname', 13, $data_res);

foreach ( $data_res as $item ) {
    //print(" ".$item['id']);
    try {
        $response = $client->request->customersCreate(array(
            'sex' => $item['sex'],
            'firstName' => $item['name'],
            'lastName' => $item['surname'],
            'email' => $item['mail'],
            'customFields' => ['pos_id' => $item['posid'],  'smile_id' => $item['id'], 'bonus_account' => $item['bonus']],
            'birthday' => $item['date'],
            'phones' => [[
                "number" => $item['telephonedom']], [
                "number" => $item['telephone']],
            ],

        ), $site = $item['posname']);
    } catch (\RetailCrm\Exception\CurlException $e) {
        echo "Connection error: " . $e->getMessage();
    }

    if ($response->isSuccessful() && 201 === $response->getStatusCode()) {
        echo 'Customer successfully created. Customer ID into RetailCRM = ' . $response->id;
        // or $response['id'];
        // or $response->getId();
    } else {
        echo sprintf(
            "Error: [HTTP-code %s] %s",
            $response->getStatusCode(),
            $response->getErrorMsg()
        );
    }
}

 //для проверки массива
/*
foreach($data_res as $item){
    echo '<pre>';
    //echo " » " . $data_res;
    var_dump($item);
    echo '</pre>';
}*/

