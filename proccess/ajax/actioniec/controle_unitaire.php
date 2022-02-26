<?php
// Require des données
require_once "../../../config/checkConfig.php";

try {
    $formData = json_decode($_POST['myData']);
    $result[] = "success";

    $champs_selected = "";
    foreach ($formData->list_champs as $key => $value) {
        $champs_selected .=  ",a.$value";
    }

    //Exécution de la requête envoyée
    $qry = $bdd->prepare("  SELECT af.id_lot,a.id_acte $champs_selected
                            from acte a  
                            inner join affectationregistre af on af.id_tome_registre = a.id_tome_registre  
                            where af.id_lot in ($formData->id_lot)
                            order by af.id_lot,a.num_acte,a.imagepath");

    $qry->execute();
    $liste_acte = $qry->fetchAll(PDO::FETCH_OBJ);

    $result[] = $liste_acte;

    echo (json_encode($result));
} catch (Exception $e) {
    $fail[] = "fail";
    $fail[] = $e->getMessage();
    echo (json_encode($fail));
}
