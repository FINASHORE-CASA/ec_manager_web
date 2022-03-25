<?php
// Require des données
require_once "../../../config/checkConfig.php";

try {
    $formData = json_decode($_POST['myData']);
    $result[] = "success";

    $bdd->beginTransaction();
    // Récupération des id_lots concernés        
    $qry = $bdd->prepare("  SELECT id_lot 
                                from lot
                                where id_lot in ($formData->id_lot)");

    $qry->execute();
    $listLotTrouves = $qry->fetchAll(PDO::FETCH_OBJ);

    // Initialisation des lots
    $nbAff1 = $bdd->exec("UPDATE acte SET status_acte='I', status_acteechantillon='I' where 
                    id_tome_registre in (
                    select tr.id_tome_registre from tomeregistre tr inner join registre r on r.id_registre=tr.id_registre
                    inner join affectationregistre ar on ar.id_tome_registre=tr.id_tome_registre
                    inner join lot l on l.id_lot=ar.id_lot
                        where l.id_lot in ($formData->id_lot)
                    );");


    $nbAff2 = $bdd->exec("UPDATE tomeregistre SET status='I' where 
                                id_tome_registre in (
                                select tr.id_tome_registre from tomeregistre tr inner join registre r on r.id_registre=tr.id_registre
                                inner join affectationregistre ar on ar.id_tome_registre=tr.id_tome_registre
                                inner join lot l on l.id_lot=ar.id_lot
                                    where l.id_lot in ($formData->id_lot)
                                );");


    $nbAff3 = $bdd->exec("UPDATE lot SET status_lot='I',num_echantillon=0 where id_lot in ($formData->id_lot);");

    $result[] = $nbAff1;
    $result[] = $nbAff2;
    $result[] = $nbAff3;
    $bdd->commit();

    echo (json_encode($result));
} catch (Exception $e) {
    $bdd->rollBack();
    $fail[] = "fail";
    $fail[] = $e->getMessage();
    echo (json_encode($fail));
}
