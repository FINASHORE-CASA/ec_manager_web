<?php
// Require des données
require_once "../../../config/checkConfig.php";

try {
    $formData = json_decode($_POST['myData']);
    $result[] = "success";

    $bdd->beginTransaction();
    // Suppression des actes des lots à Supprimer
    $nbAff1 = $bdd->exec(" DELETE from actionec where id_acte in
                            (select acte.id_acte from lot l 
                            inner join affectationregistre ar on ar.id_lot = l.id_lot and l.id_lot in ($formData->id_lot)
                            inner join tomeregistre tr on tr.id_tome_registre = ar.id_tome_registre
                            inner join acte on acte.id_tome_registre = tr.id_tome_registre)");

    $nbAff2 = $bdd->exec(" DELETE from controle_acte  where id_acte in
                            (select acte.id_acte from lot l 
                            inner join affectationregistre ar on ar.id_lot = l.id_lot and l.id_lot in ($formData->id_lot)
                            inner join tomeregistre tr on tr.id_tome_registre = ar.id_tome_registre
                            inner join acte on acte.id_tome_registre = tr.id_tome_registre)");

    $nbAff3 = $bdd->exec(" DELETE from controle_mention  where id_mention in
                            (select mention.id_mention from lot l 
                            inner join affectationregistre ar on ar.id_lot = l.id_lot and l.id_lot in ($formData->id_lot)
                            inner join tomeregistre tr on tr.id_tome_registre = ar.id_tome_registre
                            inner join acte on acte.id_tome_registre = tr.id_tome_registre
                            inner join mention on mention.id_acte = acte.id_acte)");

    $nbAff4 = $bdd->exec(" DELETE from mention  where id_acte in
                            (select acte.id_acte from lot l 
                            inner join affectationregistre ar on ar.id_lot = l.id_lot and l.id_lot in ($formData->id_lot)
                            inner join tomeregistre tr on tr.id_tome_registre = ar.id_tome_registre
                            inner join acte on acte.id_tome_registre = tr.id_tome_registre)");

    $nbAff5 = $bdd->exec(" DELETE from deces  where id_acte in
                            (select acte.id_acte from lot l 
                            inner join affectationregistre ar on ar.id_lot = l.id_lot and l.id_lot  in ($formData->id_lot)
                            inner join tomeregistre tr on tr.id_tome_registre = ar.id_tome_registre
                            inner join acte on acte.id_tome_registre = tr.id_tome_registre)");

    $nbAff6 = $bdd->exec(" DELETE from transcription where id_acte in
                            (select acte.id_acte from lot l 
                            inner join affectationregistre ar on ar.id_lot = l.id_lot and l.id_lot  in ($formData->id_lot)
                            inner join tomeregistre tr on tr.id_tome_registre = ar.id_tome_registre
                            inner join acte on acte.id_tome_registre = tr.id_tome_registre)");

    $nbAff7 = $bdd->exec(" DELETE from jugement where id_acte in
                            (select acte.id_acte from lot l 
                            inner join affectationregistre ar on ar.id_lot = l.id_lot and l.id_lot  in ($formData->id_lot)
                            inner join tomeregistre tr on tr.id_tome_registre = ar.id_tome_registre
                            inner join acte on acte.id_tome_registre = tr.id_tome_registre)");

    $nbAff8 = $bdd->exec(" DELETE from declaration where id_acte in
                            (select acte.id_acte from lot l 
                            inner join affectationregistre ar on ar.id_lot = l.id_lot and l.id_lot  in ($formData->id_lot)
                            inner join tomeregistre tr on tr.id_tome_registre = ar.id_tome_registre
                            inner join acte on acte.id_tome_registre = tr.id_tome_registre)");

    $nbAff9 = $bdd->exec("DELETE from acte where id_acte in 
                            (select a.id_acte from acte a 
                                inner join affectationregistre af on af.id_tome_registre = a.id_tome_registre 
                                and af.id_lot in ($formData->id_lot))");

    // Suppression du planning_agent des lots
    $nbAff10 = $bdd->exec(" DELETE from planning_agent where id_affectationregistre in 
                            (select ar.id_affectationregistre from lot l 
                            inner join affectationregistre ar on ar.id_lot = l.id_lot and l.id_lot  in ($formData->id_lot))");

    // Récupération des id_tome_registre des lots
    $qry = $bdd->query("SELECT ar.id_tome_registre from lot l 
                        inner join affectationregistre ar on ar.id_lot = l.id_lot and l.id_lot in ($formData->id_lot)");
    $id_tome_registres = $qry->fetchAll(PDO::FETCH_OBJ);

    // suppression affectationregistre 
    $nbAff11 = $bdd->exec(" DELETE from affectationregistre where id_tome_registre in
                            (select ar.id_tome_registre from lot l 
                            inner join affectationregistre ar on ar.id_lot = l.id_lot and l.id_lot in ($formData->id_lot))");

    // Suppression des lots    
    $nbAff12 = $bdd->exec("DELETE from lot where id_lot in ($formData->id_lot)");

    // Suppression des id_tome_registre
    $id_tome_registres_list = "";
    foreach ($id_tome_registres as $key => $tome) {
        $id_tome_registres_list .= ($key + 1 == count($id_tome_registres)) ? $tome->id_tome_registre : "$tome->id_tome_registre,";
    }
    $nbAff13 = $bdd->exec("DELETE from tomeregistre where id_tome_registre in ($id_tome_registres_list)");

    $bdd->commit();

    // Récupération du nombre id_lots restant après purge des lots        
    $qry = $bdd->prepare("SELECT count(id_lot) from lot");
    $qry->execute();
    $nbRestant = $qry->fetch();

    $result[] = $nbAff12;
    $result[] = $nbRestant[0];
    $result[] = "req 1 : " . $nbAff1 . ", req 2 : " . $nbAff2 . ", req 3 : " . $nbAff3 . ", req 4 : " . $nbAff4 . ", req 5 : " . $nbAff5 . ", req 6 : " . $nbAff6
        . ", req 7 : " . $nbAff7 . ", req 8 : " . $nbAff8 . ", req 9 : " . $nbAff9 . ", req 10 : " . $nbAff10 . ", req 11 : " . $nbAff11 . ", req 12 : " . $nbAff12
        . ", req 13 : " . $nbAff13;

    echo (json_encode($result));
} catch (Exception $e) {
    $bdd->rollback();
    $fail[] = "fail";
    $fail[] = $e->getMessage();
    echo (json_encode($fail));
}
