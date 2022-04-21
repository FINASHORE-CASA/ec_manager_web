<?php
// Require des données
require_once "../../../config/checkConfig.php";

try {
    $formData = json_decode($_POST['myData']);
    $result[] = "success";

    //Récupération des id_lots concernés        
    $qry = $bdd->prepare("SELECT l.id_lot,l.id_commune,l.id_bureau,l.date_modification as date_saisie,l.status_lot,tr.status as status_tomeregistre,count(distinct id_acte) as nb_actes
                            from lot l
                            inner join affectationregistre af on af.id_lot = l.id_lot 
                            inner join acte a on a.id_tome_registre = af.id_tome_registre
                            inner join tomeregistre tr on tr.id_tome_registre = af.id_tome_registre
                            where tr.status =  '{$formData->status}'
                            group by l.id_lot,l.date_modification,l.status_lot,l.id_commune,l.id_bureau,tr.status
                            order by date_saisie");

    // old statut request
    // SELECT l.id_lot,l.id_commune,l.id_bureau,l.date_modification as date_saisie,l.status_lot,count(distinct id_acte) as nb_actes
    // from lot l
    // inner join affectationregistre af on af.id_lot = l.id_lot 
    // inner join acte a on a.id_tome_registre = af.id_tome_registre
    // where l.status_lot = '{$formData->status}'
    // group by l.id_lot,l.date_modification,l.status_lot,l.id_commune,l.id_bureau
    // order by date_saisie

    $qry->execute();
    $liste_lots = $qry->fetchAll(PDO::FETCH_OBJ);

    $result[] = $liste_lots;

    echo (json_encode($result));
} catch (Exception $e) {
    $fail[] = "fail";
    $fail[] = $e->getMessage();
    echo (json_encode($fail));
}
