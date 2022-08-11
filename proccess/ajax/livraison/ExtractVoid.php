<?php
// Require des données
require_once "../../../config/checkConfig.php";

try {
    $result[] = "success";

    // Récupération des id_lots concernés        
    $qry = $bdd->prepare("SELECT af.id_lot,id_acte,num_acte,imagepath,af.id_tome_registre,status_acte  
                            from acte a  
                            inner join affectationregistre af on af.id_tome_registre = a.id_tome_registre  
                            where af.id_lot in (select id_lot from lot where status_lot = 'A')   
                            and ((a.imagepath is null or a.imagepath = '')
                            or (a.num_acte is null or a.num_acte = ''))");

    $qry->execute();
    $liste_vides = $qry->fetchAll(PDO::FETCH_OBJ);

    $liste_el_select;

    foreach ($liste_vides as $acte) {
        $liste_el_select[] = [
            "id_lot" => $acte->id_lot, "id_acte" => $acte->id_acte, "num_acte" => $acte->num_acte, "imagepath" =>  $acte->imagepath, "id_tome_registre" =>  $acte->id_tome_registre, "status_acte" =>  $acte->status_acte
        ];
    }

    $result[] = isset($liste_el_select) ? $liste_el_select : [];

    echo (json_encode($result));
} catch (Exception $e) {
    $fail[] = "fail";
    $fail[] = $e->getMessage();
    echo (json_encode($fail));
}
