<?php

session_start();
// Require des données
require_once "../../../config/checkConfig.php";
require_once "./schema_acte.php";

try {
    $formData = json_decode($_POST['myData']);
    $result[] = "success";
    $update_statement_acte;
    $update_statement_deces;
    $update_statement_jugement;
    $not_update = $champs_gris;

    // Acte update statement
    foreach ($formData as $key => $value) {
        if (!array_search($key, $not_update) && !array_search($key, $liste_champs_deces) && !array_search($key, $liste_champs_Jugement)) {
            if (strlen($value) == 0 || $value == 'null') {
                $update_statement_acte[] = "{$key} = null";
            } else {
                $update_statement_acte[] = "{$key} = '" . str_replace("'", "''", $value) . "'";
            }
        }
    }

    // Deces update statement
    foreach ($formData as $key => $value) {
        if (array_search($key, $liste_champs_deces)) {
            if (strlen($value) == 0 || $value == 'null') {
                $update_statement_deces[] = "{$key} = null";
            } else {
                $update_statement_deces[] = "{$key} = '" . str_replace("'", "''", $value) . "'";
            }
        }
    }

    // Jugement update statement
    foreach ($formData as $key => $value) {
        if (array_search($key, $liste_champs_Jugement)) {
            if (strlen($value) == 0 || $value == 'null') {
                $update_statement_jugement[] = "{$key} = null";
            } else {
                $update_statement_jugement[] = "{$key} = '" . str_replace("'", "''", $value) . "'";
            }
        }
    }

    $update_statement_acte = isset($update_statement_acte) ? join(", ", $update_statement_acte) : "";
    $update_statement_deces = isset($update_statement_deces) ? join(", ", $update_statement_deces) : "";
    $update_statement_jugement = isset($update_statement_jugement) ? join(", ", $update_statement_jugement) : "";

    // Correction des num_acte
    if ($update_statement_acte != "") {
        $nbAff_acte = $bdd->exec("UPDATE acte SET $update_statement_acte WHERE id_acte = $formData->id_acte;");
    }

    // Correction des deces
    if ($update_statement_deces != "") {
        $nbAff_deces = $bdd->exec("UPDATE deces SET $update_statement_deces WHERE id_acte = $formData->id_acte;");
    }

    // Correction des jugements
    if ($update_statement_jugement != "") {
        $nbAff_jugement = $bdd->exec("UPDATE jugement SET $update_statement_jugement WHERE id_acte = $formData->id_acte;");
    }

    // Enregistrement de ce Contrôle;
    $qry = $bdextra->prepare("insert into acte_ctr_fina(id_acte,id_user,date_ctr) values(?,?,NOW());");
    $qry->execute(array($formData->id_acte, $_SESSION["user"]->id_user));

    $result[] = isset($nbAff_acte) ? $nbAff_acte : "";
    $result[] = isset($nbAff_deces) ? $nbAff_deces : "";
    $result[] = isset($nbAff_jugement) ? $nbAff_jugement : "";
    $result[] = $update_statement_acte;
    $result[] = $liste_champs_actes;

    echo (json_encode($result));
} catch (Exception $e) {
    $fail[] = "fail";
    $fail[] = $e->getMessage();
    echo (json_encode($fail));
}
