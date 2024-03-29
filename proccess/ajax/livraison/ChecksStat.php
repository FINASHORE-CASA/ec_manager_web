<?php
// Require des données
require_once "../../../config/checkConfig.php";

try {
    $result[] = "success";
    
    // Récupération des id_lots concernés        
    $qry = $bdd->prepare("SELECT data1.id_lot,count(data1.id_acte) as nb_actes
                                ,count(CASE WHEN data1.acte_ctrl1 <> 0 THEN data1.acte_ctrl1 END) as nb_ctrl1
                                ,count(CASE WHEN data1.acte_ctrl2 <> 0 THEN data1.acte_ctrl2 END) as nb_ctrl2
                                ,sum(naiss) as nb_naiss
                                ,sum(deces) as nb_deces
                                ,sum(mariage) as nb_mariage
                                ,sum(divorce) as nb_divorce
                                from (
                                    select af.id_lot,a.id_acte,count(CASE WHEN id_type_actionec = 7 THEN ac.id_acte END) as acte_ctrl1,
                                    count(CASE WHEN id_type_actionec = 11 THEN ac.id_acte END) as acte_ctrl2
                                    ,(CASE WHEN imagepath like '%NA%' THEN 1 ELSE 0 END) as naiss
                                    ,(CASE WHEN imagepath like '%DE%' THEN 1 ELSE 0 END) as deces
                                    ,0 as mariage
                                    ,0 as divorce
                                    from acte a
                                    inner join affectationregistre af on af.id_tome_registre = a.id_tome_registre
                                    left join actionec ac on a.id_acte = ac.id_acte
                                    where af.id_lot in (select id_lot from lot where status_lot = 'A') 
                                    group by af.id_lot,a.id_acte 
                                    union
                                    select af.id_lot,id_evenement as id_acte,count(CASE WHEN id_type_actionec = 8 THEN ac.id_acte END) as acte_ctrl1
                                    ,count(CASE WHEN id_type_actionec = 12 THEN ac.id_acte END) as acte_ctrl2
                                    ,0 as naiss
                                    ,0 as deces
                                    ,(CASE WHEN imagepath like '%MA%' THEN 1 ELSE 0 END) as mariage
                                    ,(CASE WHEN imagepath like '%DI%' THEN 1 ELSE 0 END) as divorce
                                    from mariage_divorce_etranger mde
                                    inner join affectationregistre af on af.id_tome_registre = mde.id_tome_registre
                                    left join actionec ac on mde.id_evenement = ac.id_acte
                                    where af.id_lot in (select id_lot from lot where status_lot = 'A')  
									group by af.id_lot,id_evenement									
                                ) as data1                                    

                                group by data1.id_lot");

    $qry->execute();
    $listeLots_bd = $qry->fetchAll(PDO::FETCH_OBJ);

    // Récupération des id_lots concernés        
    $qry = $bdextra->prepare("SELECT lot as id_lot,total,naissance,deces,mariage,divorce from lotstats");

    $qry->execute();
    $listeLots_extra = $qry->fetchAll(PDO::FETCH_OBJ);

    $liste_el_select;
    $nbRowAff = 0;

    // Comparaison des éléments
    foreach ($listeLots_bd as $lot) {
        $lotToChecks = array_filter($listeLots_extra, function ($e) use ($lot) {
            return $e->id_lot == $lot->id_lot;
        });

        if (count($lotToChecks) > 0) {
            $lotToChecks = array_values($lotToChecks);
            if (
                $lot->nb_actes != $lotToChecks[0]->total || $lot->nb_ctrl1 != $lotToChecks[0]->total
                || $lot->nb_ctrl2 != $lotToChecks[0]->total || $lot->nb_naiss != $lotToChecks[0]->naissance
                || $lot->nb_deces != $lotToChecks[0]->deces || $lot->nb_mariage != $lotToChecks[0]->mariage
                || $lot->nb_divorce != $lotToChecks[0]->divorce
            ) {
                $nbRowAff++;
                // ajout à la liste de rejet
                $liste_el_select[] = [
                    "id_lot" => $lot->id_lot, "nb_naiss_bd" => $lot->nb_naiss, "nb_naiss_inv" => $lotToChecks[0]->naissance, "nb_deces_bd" => $lot->nb_deces, "nb_deces_inv" => $lotToChecks[0]->deces, "nb_acte_bd"  => $lot->nb_actes, "nb_acte_inv"  => $lotToChecks[0]->total, "nb_mariage_bd" => $lot->nb_mariage, "nb_mariage_inv" => $lotToChecks[0]->mariage, "nb_mdivorce_bd" => $lot->nb_divorce, "nb_divorce_nv" => $lotToChecks[0]->divorce, "nb_ctrl1_bd" => $lot->nb_ctrl1, "nb_ctrl1_inv" => $lotToChecks[0]->total, "nb_ctrl2_bd" => $lot->nb_ctrl2, "nb_ctrl2_inv" => $lotToChecks[0]->total, "observation" => ''
                ];
            }
        } else {
            $nbRowAff++;
            // ajout à la liste de rejet
            $liste_el_select[] = [
                "id_lot" => $lot->id_lot, "nb_acte_bd" => $lot->nb_actes, "nb_acte_inv" => '', "nb_mariage_bd" => $lot->nb_mariage, "nb_mariage_inv" => '', "nb_mdivorce_bd" => $lot->nb_divorce, "nb_divorce_nv" => '', "nb_ctrl1_bd" =>  $lot->nb_ctrl1, "nb_ctrl1_inv" => '', "nb_ctrl2_bd" => $lot->nb_ctrl2, "nb_ctrl2_inv" => '', "observation" => 'INVENTAIRE INTROUVABLE'
            ];
        }
    }

    $result[] = isset($liste_el_select) ? $liste_el_select : [];

    echo (json_encode($result));
} catch (Exception $e) {
    $fail[] = "fail";
    $fail[] = $e->getMessage();
    echo (json_encode($fail));
}
