<?php
// Require des données
require_once "../../../config/checkConfig.php";

try {
    $result[] = "success";

    // Récupération des id_lots concernés        
    $qry = $bdd->prepare(" SELECT af.id_lot,tr.num_tome,(CASE WHEN tr.indice_num_tome is null THEN 0 ELSE  tr.indice_num_tome END) 
                            as indice_num_tome,rg.annee_registre_greg,rg.annee_registre_hegire,tr.nbre_acte_naissance,tr.nbre_acte_deces
                            ,tr.nbre_acte_mariage,tr.nbre_acte_divorce,tr.nbre_acte_naissance + tr.nbre_acte_deces + tr.nbre_acte_mariage + tr.nbre_acte_divorce as total,tr.id_tome_registre
                            from affectationregistre af
                            inner join tomeregistre tr on tr.id_tome_registre = af.id_tome_registre
                            inner join registre rg on rg.id_registre = tr.id_registre 
                            where id_lot in (select id_lot from lot where status_lot = 'A')");

    $qry->execute();
    $liste_db_tomeregistre = $qry->fetchAll(PDO::FETCH_OBJ);

    // Récupération des id_lots concernés        
    $qry = $bdextra->prepare(' SELECT lot,tome,(CASE WHEN indice is null THEN 0 ELSE indice END) as indice,"g" as annee_g,h as annee_h,naissance,deces,mariage,divorce,total
                               from lotstats');

    $qry->execute();
    $liste_extra_inventaires = $qry->fetchAll(PDO::FETCH_OBJ);

    $liste_el_select;

    foreach ($liste_db_tomeregistre as $db_tr) {
        $searchedValue = $db_tr->id_lot;
        $find = array_filter(
            $liste_extra_inventaires,
            function ($e) use ($searchedValue) {
                return $e->lot == $searchedValue;
            },
            ARRAY_FILTER_USE_BOTH
        );
        if (count($find) > 0) {
            $find = array_values($find);

            if (
                $db_tr->indice_num_tome != $find[0]->indice || $db_tr->num_tome != $find[0]->tome || $db_tr->annee_registre_greg != $find[0]->annee_g
                || $db_tr->annee_registre_hegire != $find[0]->annee_h || $db_tr->nbre_acte_naissance != $find[0]->naissance || $db_tr->nbre_acte_deces != $find[0]->deces
                || $db_tr->nbre_acte_mariage != $find[0]->mariage || $db_tr->nbre_acte_divorce != $find[0]->divorce || $db_tr->total != $find[0]->total
            ) {
                $liste_el_select[] = [
                    "id_tome_registre" => $db_tr->id_tome_registre, "id_lot" => $db_tr->id_lot, "indice_db" => $db_tr->indice_num_tome, "indice_inv" => $find[0]->indice, "tome_db" => $db_tr->num_tome, "tome_inv" => $find[0]->tome, "annee_g_db" => $db_tr->annee_registre_greg, "annee_g_inv" => $find[0]->annee_g, "annee_h_db" => $db_tr->annee_registre_hegire, "annee_h_inv" => $find[0]->annee_h, "naissance_db" => $db_tr->nbre_acte_naissance, "naissance_inv" => $find[0]->naissance, "deces_db" => $db_tr->nbre_acte_deces,
                    "mariage_db" => $db_tr->nbre_acte_mariage, "mariage_inv" => $find[0]->mariage, "divorce_db" =>  $db_tr->nbre_acte_divorce, "divorce_inv" => $find[0]->divorce,
                    "deces_inv" => $find[0]->deces, "acte_db" => $db_tr->total, "acte_inv" => $find[0]->total
                ];

                // Correction automatique
                $qry = $bdd->prepare("UPDATE tomeregistre set nbre_acte_naissance = :nb_naiss,nbre_acte_deces = :nb_deces,nbre_acte_mariage = :nb_mariage,nbre_acte_divorce = :nb_divorce where id_tome_registre = :tome_registre;");
                $qry->bindParam(':tome_registre', $db_tr->id_tome_registre);
                $qry->bindParam(':nb_naiss', $find[0]->naissance);
                $qry->bindParam(':nb_deces', $find[0]->deces);
                $qry->bindParam(':nb_mariage', $find[0]->mariage);
                $qry->bindParam(':nb_divorce', $find[0]->divorce);
                $qry->execute();

                $qry = $bdd->prepare("UPDATE registre set annee_registre_greg = :annee_greg, annee_registre_hegire = :annee_hegire where id_registre in (select rg.id_registre from registre rg inner join tomeregistre tr on tr.id_registre = rg.id_registre where tr.id_tome_registre = :tome_registre);");
                $qry->bindParam(':tome_registre', $db_tr->id_tome_registre);
                $qry->bindParam(':annee_greg', $find[0]->annee_g);
                $qry->bindParam(':annee_hegire', $find[0]->annee_h);
                $qry->execute();
            }
        } else {
            $liste_el_select[] = [
                "id_tome_registre" => $db_tr->id_tome_registre, "id_lot" => $db_tr->id_lot, "indice_db" => $db_tr->indice_num_tome, "indice_inv" => "ND", "tome_db" => $db_tr->num_tome, "tome_inv" => "ND", "annee_g_db" => $db_tr->annee_registre_greg, "annee_g_inv" => "ND", "annee_h_db" => $db_tr->annee_registre_hegire, "annee_h_inv" => "ND", "naissance_db" => $db_tr->nbre_acte_naissance, "naissance_inv" => "ND", "deces_db" => $db_tr->nbre_acte_deces,
                "deces_inv" => "ND", "mariage_db" => $db_tr->nbre_acte_mariage, "mariage_inv" => "ND", "divorce_db" =>  $db_tr->nbre_acte_divorce, "divorce_inv" => "ND",
                "acte_db" => $db_tr->total, "acte_inv" => "ND"
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
