<?php
// Require des données
require_once "../../../config/checkConfig.php";
require_once "../../../function/handle_function.php";

try {
    $FormData = json_decode($_POST["myData"]);
    $result[] = "success";

    // Transfert du lot
    // Récupération du Chemin
    $BaseCheminLot = getPathLot($FormData->id_lot, $bdextra, null, true);
    $cheminLotDestination = trim($FormData->destination) . "\\" . $BaseCheminLot;
    $cheminLotSource = "";

    $SourceTable = explode(",", $FormData->source);

    foreach ($SourceTable as $src) {
        if (is_dir(trim($src) . "\\" . substr($BaseCheminLot, 3))) {
            $cheminLotSource = trim($src) . "\\" . substr($BaseCheminLot, 3);
            break;
        }
    }

    if (is_dir($cheminLotSource)) {
        rcopy($cheminLotSource, $cheminLotDestination);
        $result[] = "Copie terminee";
    } else {
        $result[0] = "fail";
        $result[1] = "Lot introuvable ";
        $result[2] = $cheminLotSource;
        $result[3] = substr($BaseCheminLot, 3);
    }

    echo (json_encode($result));
} catch (Exception $e) {
    $fail[] = "fail";
    $fail[] = $e->getMessage();
    echo (json_encode($fail));
}
