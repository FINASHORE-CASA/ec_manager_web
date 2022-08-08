<?php
// Require des données
require_once "../../../config/checkConfig.php";

try {
    $result[] = "success";

    // Récupération des id_lots concernés        
    $qry = $bdd->prepare("SELECT id_lot,nbre_acte_mariage,nbre_acte_divorce
                            from tomeregistre tm
                            inner join affectationregistre af on af.id_tome_registre = tm.id_tome_registre
                            where nbre_acte_mariage <> 0 and nbre_acte_divorce <> 0");

    $qry->execute();
    $liste_mariage_divorce = $qry->fetchAll(PDO::FETCH_OBJ);

    $result[] = isset($liste_mariage_divorce) ? $liste_mariage_divorce : [];

    echo (json_encode($result));
} catch (Exception $e) {
    $fail[] = "fail";
    $fail[] = $e->getMessage();
    echo (json_encode($fail));
}
