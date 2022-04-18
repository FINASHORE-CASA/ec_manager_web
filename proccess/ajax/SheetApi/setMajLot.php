<?php

require '../../../vendor/autoload.php';
require '../../../function/handle_function.php';

// require 'C:/laragon/www/ec_manager_web/vendor/autoload.php';
// require 'C:/laragon/www/ec_manager_web/function/handle_function.php';
try {

    $formData = json_decode($_POST['myData']);
    $client = new \Google_Client();
    $client->setApplicationName('Google Sheets and PHP');
    $client->setScopes([\Google_Service_Sheets::SPREADSHEETS]);
    $client->setAccessType('offline');
    $client->setAuthConfig('../../../config/credentials.json');
    // $client->setAuthConfig('C:/laragon/www/ec_manager_web/config/credentials.json');
    $service = new Google_Service_Sheets($client);

    $spreadsheetId = "1j64r2CTH-KjTMI8vBuwGnEVVNhvyK2LYhoZTeww29Ao"; //It is present in your URL    

    // get List of sheet Names
    $response = $service->spreadsheets->get($spreadsheetId);
    foreach ($response->getSheets() as $s) {
        $sheets[] = $s['properties']['title'];
    }

    $sheets = isset($sheets) ? array_filter($sheets, function ($s) {
        return str_contains($s, "DEPOT");
    }) : [];

    $nbAff = 0;
    foreach ($sheets as $s) {
        // Récupération des lots compris dans cette feuille
        $strInterval = explode("-", str_replace("DEPOT ", "", explode("_", $s)[0]));

        $has_modifier = array_filter($formData, function ($e) use ($strInterval) {
            return isset($e->numlivraison) && intval($strInterval[0]) <= intval($e->numlivraison)
                && intval($strInterval[1]) >= intval($e->numlivraison);
        });
        // var_dump($has_modifier);
        //Request to get data from spreadsheet.
        $res = $service->spreadsheets_values->get($spreadsheetId, "$s!A2:F");
        $cellValues =  $res->getValues();

        $row = 2;
        $data = [];
        foreach ($cellValues as $val) {
            $find = array_values(array_filter($has_modifier, function ($r) use ($val) {
                return $r->id_lot == $val[0];
            }));
            if (count($find) > 0) {
                // modification de l'élément 
                $values = [[strtoupper($find[0]->statut_lot)]];
                $range = "$s!F$row";
                $data[]  = new Google_Service_Sheets_ValueRange(["values" => $values, "range" => $range]);
                $nbAff++;
            }
            $row++;
        }

        // batch insertion
        $body = new Google_Service_Sheets_BatchUpdateValuesRequest(["valueInputOption" => "RAW", "data" => $data]);
        $res = $service->spreadsheets_values->batchUpdate($spreadsheetId, $body);
    }

    $result[] = "success";
    $result[] = $nbAff;
    echo (json_encode($result));
} catch (Exception $e) {
    $fail[] = "fail";
    $fail[] = $e->getMessage();
    echo (json_encode($fail));
}
