<?php

require '../../../vendor/autoload.php';
require '../../../function/handle_function.php';

// require 'C:/laragon/www/ec_manager_web/vendor/autoload.php';
// require 'C:/laragon/www/ec_manager_web/function/handle_function.php';
try {

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

    $listLot = array();
    foreach ($sheets as $s) {
        //Request to get data from spreadsheet.
        $res = $service->spreadsheets_values->get($spreadsheetId, "$s!A2:F");
        $listLot = array_merge($listLot, $res->getValues());
    }

    $lots_rejete = array_filter($listLot, function ($l) {
        return $l[5] == "REJETE";
    });

    $lots_en_cours = array_filter($listLot, function ($l) {
        return $l[5] == "EN COURS";
    });

    $lots_accepte = array_filter($listLot, function ($l) {
        return $l[5] == "ACCEPTE";
    });

    // Récupération des lots
    $formData = json_decode($_POST['myData']);
    $listNewLot = explode(",", $formData->id_lot);

    $listNewLot = array_values(array_filter($listNewLot, function ($l) use ($lots_accepte) {
        return (count(array_filter($lots_accepte, function ($la) use ($l) {
            return trim($la[0]) == trim($l);
        })) == 0);
    }));

    $listNewLot = array_values(array_filter($listNewLot, function ($l) use ($lots_en_cours) {
        return (count(array_filter($lots_en_cours, function ($lc) use ($l) {
            return trim($lc[0]) == trim($l);
        })) == 0);
    }));

    $result[] = "success";
    $result[] = $listNewLot;
    $result[] = $sheets;

    echo (json_encode($result));
} catch (Exception $e) {
    $fail[] = "fail";
    $fail[] = $e->getMessage();
    echo (json_encode($fail));
}
