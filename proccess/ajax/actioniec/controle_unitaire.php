<?php
// Require des données
require_once "../../../config/checkConfig.php";

try {
    $formData = json_decode($_POST['myData']);
    $result[] = "success";

    $liste_champs_actes = ["", "num_acte", "annee_acte", "jd_naissance_h", "md_naissance_h", "ad_naissance_h", "jd_naissance_g", "md_naissance_g", "ad_naissance_g", "heure_naissance", "minute_naissance", "lieu_naissance", "jumeaux", "prenom_ar", "prenom_fr", "nom_ar", "nom_fr", "nom_marge_ar", "prenom_marge_fr", "nom_marge_fr", "prenom_marge_ar", "sexe", "id_nationlite", "decede_pere", "prenom_pere_ar", "prenom_pere_fr", "ascendant_pere_ar", "ascendant_pere_fr", "id_nationalite_pere", "id_profession_pere", "jd_naissance_pere_h", "md_naissance_pere_h", "ad_naissance_pere_h", "jd_naissance_pere_g", "md_naissance_pere_g", "ad_naissance_pere_g", "lieu_naissance_pere", "decede_mere", "prenom_mere_ar", "prenom_mere_fr", "ascendant_mere_ar", "ascendant_mere_fr", "id_nationalite_mere", "id_profession_mere", "jd_naissance_mere_h", "md_naissance_mere_h", "ad_naissance_mere_h", "jd_naissance_mere_g", "md_naissance_mere_g", "ad_naissance_mere_g", "lieu_naissance_mere", "adresse_residence_parents", "jd_etabli_acte_h", "ad_etabli_acte_h", "md_etabli_acte_h", "jd_etabli_acte_g", "ad_etabli_acte_g", "md_etabli_acte_g", "id_officier", "annee_acte_g", "lieu_naissance_fr", "lieu_naissance_pere_fr", "lieu_naissance_mere_fr", "adresse_residence_parents_fr", "sign_officier", "sceau_officier", "status_acte", "heure_etabli_acte", "minute_etabli_acte", "date_creation", "status_acteechantillon", "langue_acte", "id_ville_naissance", "id_ville_naissance_mere", "id_ville_naissance_pere", "id_ville_residence_parents", "date_statut_oec", "imagepath", "remarque", "nom_pere_ar", "nom_pere_fr", "ascendant_pere_nom_ar", "ascendant_pere_nom_fr", "nom_mere_ar", "nom_mere_fr", "ascendant_mere_nom_ar", "ascendant_mere_nom_fr", "info_pere_marge_ar", "info_pere_marge_fr", "info_mere_marge_ar", "info_mere_marge_fr"];
    $liste_champs_deces = ["", "jd_deces_h", "md_deces_h", "ad_deces_h", "jd_deces_g", "md_deces_g", "ad_deces_g", "heure_deces", "minute_deces", "lieu_deces", "id_profession", "statutfamilialle", "lieuresidence", "lieuresidence_fr", "lieu_deces_fr", "lieu_residence_pere_ar", "lieu_residence_pere_fr", "lieu_residence_mere_ar", "lieu_residence_mere_fr", "id_ville_deces", "id_ville_adresse_mere", "id_ville_adresse_pere", "id_ville_adresse"];

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

    if ($champs_deces_selected == "") {
        //Exécution de la requête envoyée
        $qry = $bdd->prepare("  SELECT af.id_lot,a.id_acte,a.imagepath $champs_acte_selected
                                from acte a  
                                inner join affectationregistre af on af.id_tome_registre = a.id_tome_registre  
                                where af.id_lot in ($formData->id_lot)
                                order by af.id_lot,a.num_acte,a.imagepath");
        $qry->execute();
        $liste_acte = $qry->fetchAll(PDO::FETCH_OBJ);
    } else {
        //Exécution de la requête envoyée
        $qry = $bdd->prepare("  SELECT af.id_lot,a.id_acte,a.imagepath $champs_acte_selected $champs_deces_selected
                                from acte a  
                                inner join affectationregistre af on af.id_tome_registre = a.id_tome_registre  
                                left join deces d on d.id_acte = a.id_acte
                                where af.id_lot in ($formData->id_lot)
                                order by af.id_lot,a.num_acte,a.imagepath");
        $qry->execute();
        $liste_acte = $qry->fetchAll(PDO::FETCH_OBJ);
    }


    $result[] = isset($liste_acte) ? $liste_acte : [];

    echo (json_encode($result));
} catch (Exception $e) {
    $fail[] = "fail";
    $fail[] = $e->getMessage();
    echo (json_encode($fail));
}
