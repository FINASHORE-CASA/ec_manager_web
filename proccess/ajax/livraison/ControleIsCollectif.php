<?php
// Require des données
require_once "../../../config/checkConfig.php";

try {
    $result[] = "success";

    // Récupération des id_lots concernés        
    $qry = $bdd->prepare("SELECT af.id_lot,a.id_acte,a.num_acte,j.is_collectif
                            from jugement j
                            inner join acte a on a.id_acte = j.id_acte
                            inner join affectationregistre af on af.id_tome_registre = a.id_tome_registre
                            where is_collectif <> 'N'
                            order by id_lot");

    $qry->execute();
    $liste_is_collectif = $qry->fetchAll(PDO::FETCH_OBJ);

    $result[] = isset($liste_is_collectif) ? $liste_is_collectif : [];

    echo (json_encode($result));
} catch (Exception $e) {
    $fail[] = "fail";
    $fail[] = $e->getMessage();
    echo (json_encode($fail));
}
