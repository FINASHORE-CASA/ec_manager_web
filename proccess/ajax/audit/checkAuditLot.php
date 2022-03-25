<?php
// Require des données
require_once "../../../config/checkConfig.php";

try {
    $formData = json_decode($_POST['myData']);
    $result[] = "success";

    $liste_champs_actes = ["", "num_acte", "annee_acte", "jd_naissance_h", "md_naissance_h", "ad_naissance_h", "jd_naissance_g", "md_naissance_g", "ad_naissance_g", "heure_naissance", "minute_naissance", "lieu_naissance", "jumeaux", "prenom_ar", "prenom_fr", "nom_ar", "nom_fr", "nom_marge_ar", "prenom_marge_fr", "nom_marge_fr", "prenom_marge_ar", "sexe", "id_nationlite", "decede_pere", "prenom_pere_ar", "prenom_pere_fr", "ascendant_pere_ar", "ascendant_pere_fr", "id_nationalite_pere", "id_profession_pere", "jd_naissance_pere_h", "md_naissance_pere_h", "ad_naissance_pere_h", "jd_naissance_pere_g", "md_naissance_pere_g", "ad_naissance_pere_g", "lieu_naissance_pere", "decede_mere", "prenom_mere_ar", "prenom_mere_fr", "ascendant_mere_ar", "ascendant_mere_fr", "id_nationalite_mere", "id_profession_mere", "jd_naissance_mere_h", "md_naissance_mere_h", "ad_naissance_mere_h", "jd_naissance_mere_g", "md_naissance_mere_g", "ad_naissance_mere_g", "lieu_naissance_mere", "adresse_residence_parents", "jd_etabli_acte_h", "ad_etabli_acte_h", "md_etabli_acte_h", "jd_etabli_acte_g", "ad_etabli_acte_g", "md_etabli_acte_g", "id_officier", "annee_acte_g", "lieu_naissance_fr", "lieu_naissance_pere_fr", "lieu_naissance_mere_fr", "adresse_residence_parents_fr", "sign_officier", "sceau_officier", "status_acte", "heure_etabli_acte", "minute_etabli_acte", "status_acteechantillon", "langue_acte", "id_ville_naissance", "id_ville_naissance_mere", "id_ville_naissance_pere", "id_ville_residence_parents", "date_statut_oec", "imagepath", "remarque"];
    $liste_champs_deces = ["", "jd_deces_h", "md_deces_h", "ad_deces_h", "jd_deces_g", "md_deces_g", "ad_deces_g", "heure_deces", "minute_deces", "lieu_deces", "id_profession", "statutfamilialle", "lieuresidence", "lieuresidence_fr", "lieu_deces_fr", "lieu_residence_pere_ar", "lieu_residence_pere_fr", "lieu_residence_mere_ar", "lieu_residence_mere_fr", "id_ville_deces", "id_ville_adresse_mere", "id_ville_adresse_pere", "id_ville_adresse"];
    $liste_champs_jugement = ["", "num_jugement", "num_dossier", "annee_dossier", "jd_etablissement_jugement_h", "md_etablissement_jugement_h", "ad_etablissement_jugement_h", "jd_etablissement_jugement_g", "md_etablissement_jugement_g", "ad_etablissement_jugement_g", "j_prononciation_jugement_g", "md_prononciation_jugement_g", "ad_prononciation_jugement_g", "j_prononciation_jugement_h", "md_prononciation_jugement_h", "ad_prononciation_jugement_h", "j_reception_jugement_g", "md_reception_jugement_g", "ad_reception_jugement_g", "j_reception_jugement_h", "md_reception_jugement_h", "ad_reception_jugement_h", "denominationjugement", "dispositifjugement", "objetjugement", "signofficier", "objetjugement_fr", "is_collectif"];
    $liste_champs_mention = ["", "jd_memtion_h", "md_memtion_h", "a_memtion_h", "jd_memtion_g", "md_memtion_g", "ad_memtion_g", "txtmention", "status_mention", "sign_officier", "txtmention_fr", "nouvelle_valeur", "ancienne_valeur", "nouvelle_valeur_fr", "ancienne_valeur_fr", "modifmention"];

    //Récupération des id_lots concernés     
    $qry = $bdextra->prepare(" SELECT count(*) from audit_lot
                               where id_lot = ? and id_user_audit = ? and 
                               type_audit = ? and status_audit = 0
                            ");
    $qry->execute([
        $formData->id_lot, $formData->id_user_audit, $formData->type_audit
    ]);

    if ($qry->fetch()[0] == 0) {

        $qry = $bdextra->prepare(" SELECT MAX(id_passage_audit_type) from audit_lot
                                   where id_lot = ? and id_user_audit = ? and 
                                   type_audit = ?");
        $qry->execute([
            $formData->id_lot, $formData->id_user_audit, $formData->type_audit
        ]);
        $val_passage = $qry->fetch()[0];
        $id_passage = ($val_passage == null) ? 1 : ($val_passage + 1);

        $qry =  $bdextra->prepare("INSERT into audit_lot(id_lot,id_user_audit,id_passage_audit_type,type_audit,date_audit,percent_ech_audit,status_audit) 
                                   values (?,?,?,?,NOW(),?,0);");
        $qry->execute([$formData->id_lot, $formData->id_user_audit, $id_passage, $formData->type_audit, $formData->percent_ech_audit]);
        $result[1] = "add";
    } else {
        $result[1] = "exist";
    }

    // ----------------------------------------------------------------        
    // Récupération de l'échantillonnage
    $champs_acte_selected = "";
    foreach ($formData->list_champs as $key => $value) {
        if (array_search($value, $liste_champs_actes)) {
            $champs_acte_selected .=  ",a.$value";
        }
    }

    $champs_deces_selected = "";
    foreach ($formData->list_champs as $key => $value) {
        if (array_search($value, $liste_champs_deces)) {
            $champs_deces_selected .=  ",d.$value";
        }
    }

    $champs_jugement_selected = "";
    foreach ($formData->list_champs as $key => $value) {
        if (array_search($value, $liste_champs_jugement)) {
            $champs_jugement_selected .=  ",j.$value";
        }
    }

    $champs_mention_selected = "";
    foreach ($formData->list_champs as $key => $value) {
        if (array_search($value, $liste_champs_mention)) {
            $champs_mention_selected .=  ",m.$value";
        }
    }

    $qry = $bdd->prepare("  SELECT af.id_lot,a.id_acte,a.imagepath $champs_acte_selected $champs_deces_selected $champs_jugement_selected $champs_mention_selected 
                            from acte a  
                            inner join affectationregistre af on af.id_tome_registre = a.id_tome_registre  
                            left join deces d on d.id_acte = a.id_acte
                            left join jugement j on j.id_acte = a.id_acte
                            left join mention m on m.id_acte = a.id_acte
                            where af.id_lot in ($formData->id_lot)
                            order by random()");
    $qry->execute();
    $liste_acte = $qry->fetchAll(PDO::FETCH_OBJ);

    $result[] = $liste_acte;

    echo (json_encode($result));
} catch (Exception $e) {
    $fail[] = "fail";
    $fail[] = $e->getMessage();
    echo (json_encode($fail));
}
