<?php
// Require des données
require_once "../../../config/checkConfig.php";

try {
    $formData = json_decode($_POST["myData"]);
    $result[] = "success";

    $liste_bd = $formData->list_bd;

    foreach ($liste_bd as $bd) {

        //Vérification/Création de la sauvegarde        
        $nameFinal = $formData->chemin_final_bd . "\\" . $bd;

        // Création de la sauvegarde
        $cmd_dump = "\"{$path_bin_pgsql}\\pg_dump.exe\" --dbname=postgresql://{$utilisateur}:{$mot_passe}@Localhost:5432/{$bd} -F c -b -f \"{$nameFinal}\"";
        exec($cmd_dump, $res_dump, $res_status);

        if ($formData->select_choix_save == 1) {
            if (file_exists($nameFinal) && filesize($nameFinal) >= 1024) {
                $bdextra->exec("DROP DATABASE \"{$bd}\";");
            } else {
                $result[] = ["db" => $bd, "chemin" => $nameFinal, "status" => "fail"];
                continue;
            }
        }
        $result[] = ["db" => $bd, "chemin" => $nameFinal, "status" => "success"];
    }

    echo (json_encode($result));
} catch (Exception $e) {
    $fail[] = "fail";
    $fail[] = $e->getMessage();
    echo (json_encode($fail));
}
