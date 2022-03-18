<?php

require './function/googleSheetApiStart.php';

$spreadsheetId = "1j64r2CTH-KjTMI8vBuwGnEVVNhvyK2LYhoZTeww29Ao"; //It is present in your URL
$get_range = "DEPOT 123-130_17022022_BEC!A1:F2";

//Request to get data from spreadsheet.
$response = $service->spreadsheets_values->get($spreadsheetId, $get_range);
$values = $response->getValues();

var_dump($values);
