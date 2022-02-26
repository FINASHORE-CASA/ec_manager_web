<?php
// Require des données
require_once "../../../config/checkConfig.php";

try {
    $formData = json_decode($_POST['myData']);
    $result[] = "success";

    //Récupération des acte des id_lots concernés        
    $qry = $bdd->prepare("SELECT id_acte,af.id_lot,num_acte,imagepath
                            ,nom_fr,prenom_fr,nom_marge_fr,prenom_marge_fr,nom_ar,prenom_ar
                            ,nom_marge_ar,prenom_marge_ar,sexe
                            ,CASE WHEN TRIM(nom_fr) <> TRIM(nom_marge_fr) THEN 1 ELSE 0 END AS nom_mg_fr_s
                            ,CASE WHEN TRIM(prenom_fr) <> TRIM(prenom_marge_fr) THEN 1 ELSE 0 END AS prenom_mg_fr_s
                            ,CASE WHEN TRIM(nom_ar) <> TRIM(nom_marge_ar) THEN 1 ELSE 0 END AS nom_mg_ar_s
                            ,CASE WHEN TRIM(prenom_ar) <> TRIM(prenom_marge_ar) THEN 1 ELSE 0 END AS prenom_mg_ar_s
                            ,CASE WHEN trim(replace(prenom_fr,'İ','I'))!=trim(replace(prenom_marge_fr,'İ','I')) and trim(replace(prenom_fr,'İ','I'))!='' and trim(replace(prenom_marge_fr,'İ','I'))!='' THEN 1 ELSE 0 END AS  prenom_with_i
                            ,CASE WHEN trim(replace(nom_fr,'İ','I'))!=trim(replace(nom_marge_fr,'İ','I')) and trim(replace(prenom_fr,'İ','I'))!='' and trim(replace(nom_marge_fr,'İ','I'))!='' THEN 1 ELSE 0 END AS nom_with_i
                            ,CASE WHEN trim(nom_ar)!=trim(replace(nom_marge_ar,'أ','ا')) and nom_ar!='' and nom_marge_ar!='' THEN 1 ELSE 0 END AS nom_ar_with_error
                            ,CASE WHEN trim(replace(prenom_fr,'Ä','A'))!=trim(replace(prenom_marge_fr,'Ä','A')) and prenom_fr!='' and prenom_marge_fr!='' THEN 1 ELSE 0 END AS prenom_with_a
                            ,CASE WHEN trim(prenom_mere_ar)='' and trim(ascendant_mere_ar)!='' THEN 1 ELSE 0 END AS  asc_prenom_mere
                            ,CASE WHEN trim(prenom_ar)=trim(prenom_pere_ar) and trim(nom_ar)=trim(ascendant_pere_ar) THEN 1 ELSE 0 END AS crt_pren_1
                            ,CASE WHEN trim(prenom_ar)=trim(prenom_mere_ar) and trim(nom_ar)=trim(ascendant_mere_ar) THEN 1 ELSE 0 END AS ctr_pren_2
                            ,CASE WHEN trim(nom_ar)=trim(prenom_pere_ar) and trim(prenom_ar)=trim(ascendant_pere_ar) THEN 1 ELSE 0 END AS ctr_pren_3
                            ,CASE WHEN trim(nom_ar)=trim(prenom_mere_ar) and trim(prenom_ar)=trim(ascendant_mere_ar) THEN 1 ELSE 0 END AS ctr_pren_4
                            ,CASE WHEN trim(prenom_marge_ar)=trim(prenom_pere_ar) and trim(nom_marge_ar)=trim(ascendant_pere_ar) THEN 1 ELSE 0 END AS ctr_pren_5
                            ,CASE WHEN trim(prenom_marge_ar)=trim(prenom_mere_ar) and trim(nom_marge_ar)=trim(ascendant_mere_ar) THEN 1 ELSE 0 END AS ctr_pren_6
                            ,CASE WHEN trim(nom_marge_ar)=trim(prenom_pere_ar) and trim(prenom_marge_ar)=trim(ascendant_pere_ar) THEN 1 ELSE 0 END AS ctr_pren_7
                            ,CASE WHEN trim(nom_marge_ar)=trim(prenom_mere_ar) and trim(prenom_marge_ar)=trim(ascendant_mere_ar) THEN 1 ELSE 0 END AS ctr_pren_8
                            ,CASE WHEN trim(prenom_pere_ar)=trim(prenom_mere_ar) and trim(ascendant_pere_ar)!=trim(ascendant_mere_ar) THEN 1 ELSE 0 END AS ctr_pren_9
                            ,CASE WHEN trim(prenom_pere_ar)!=trim(prenom_mere_ar) and trim(ascendant_pere_ar)=trim(ascendant_mere_ar) THEN 1 ELSE 0 END AS ctr_pren_10
                            ,jd_naissance_g,md_naissance_g,ad_naissance_g
                            ,jd_naissance_h,md_naissance_h,ad_naissance_h
                            ,prenom_pere_fr,prenom_pere_ar,prenom_mere_fr,prenom_mere_ar
                            ,ascendant_pere_fr,ascendant_pere_ar,ascendant_mere_fr,ascendant_mere_ar
                            ,jd_etabli_acte_h,md_etabli_acte_h,ad_etabli_acte_h
                            ,jd_etabli_acte_g,md_etabli_acte_g,ad_etabli_acte_g
                            ,CASE WHEN jd_naissance_g <> '' AND try_cast_int(jd_naissance_g) >= 1 AND try_cast_int(jd_naissance_g) <= 31 THEN 0 ELSE 1 END AS jn_g_s
                            ,CASE WHEN md_naissance_g <> '' AND try_cast_int(md_naissance_g) >= 1 AND try_cast_int(md_naissance_g) <= 12 THEN 0 ELSE 1 END AS mn_g_s
                            ,CASE WHEN ad_naissance_g <> '' AND try_cast_int(ad_naissance_g) >= 1900 AND try_cast_int(ad_naissance_g) <= 2018 AND try_cast_int(ad_naissance_g) > try_cast_int(ad_naissance_h) THEN 0 ELSE 1 END AS an_g_s
                            ,CASE WHEN jd_naissance_h <> '' AND try_cast_int(jd_naissance_h) >= 1 AND try_cast_int(jd_naissance_h) <= 31 THEN 0 ELSE 1 END AS jn_h_s
                            ,CASE WHEN md_naissance_h <> '' AND try_cast_int(md_naissance_h) >= 1 AND try_cast_int(md_naissance_h) <= 12 THEN 0 ELSE 1 END AS mn_h_s
                            ,CASE WHEN ad_naissance_h <> '' AND try_cast_int(ad_naissance_h) >= 1317 AND try_cast_int(ad_naissance_h) <= 1440 THEN 0 ELSE 1 END AS an_h_s
                            ,CASE WHEN (length(concat(jd_naissance_g,md_naissance_g,ad_naissance_g))<8) and (length(concat(jd_naissance_h,md_naissance_h,ad_naissance_h))=8) THEN 1 ELSE 0 END AS date_naiss_g_format
                            ,CASE WHEN (length(concat(jd_naissance_g,md_naissance_g,ad_naissance_g))=8) and (length(concat(jd_naissance_h,md_naissance_h,ad_naissance_h))<8) THEN 1 ELSE 0 END AS date_naiss_h_format
                            ,CASE WHEN jd_etabli_acte_g <> '' AND try_cast_int(jd_etabli_acte_g) >= 1 AND try_cast_int(jd_etabli_acte_g) <= 31 THEN 0 ELSE 1 END AS je_g_s
                            ,CASE WHEN md_etabli_acte_g <> '' AND try_cast_int(md_etabli_acte_g) >= 1 AND try_cast_int(md_etabli_acte_g) <= 12 THEN 0 ELSE 1 END AS me_g_s
                            ,CASE WHEN ad_etabli_acte_g <> '' AND try_cast_int(ad_etabli_acte_g) >= 1900 AND try_cast_int(ad_etabli_acte_g) <= 2018 AND try_cast_int(ad_etabli_acte_g) > try_cast_int(ad_etabli_acte_h) THEN 0 ELSE 1 END AS ae_g_s
                            ,CASE WHEN jd_etabli_acte_h <> '' AND try_cast_int(jd_etabli_acte_h) >= 1 AND try_cast_int(jd_etabli_acte_h) <= 31 THEN 0 ELSE 1 END AS je_h_s
                            ,CASE WHEN md_etabli_acte_h <> '' AND try_cast_int(md_etabli_acte_h) >= 1 AND try_cast_int(md_etabli_acte_h) <= 12 THEN 0 ELSE 1 END AS me_h_s
                            ,CASE WHEN ad_etabli_acte_h <> '' AND try_cast_int(ad_etabli_acte_h) >= 1317 AND try_cast_int(ad_etabli_acte_h) <= 1440 THEN 0 ELSE 1 END AS ae_h_s
                            ,CASE WHEN (length(concat(jd_etabli_acte_g,md_etabli_acte_g,ad_etabli_acte_g))<8) and (length(concat(jd_etabli_acte_h,md_etabli_acte_h,ad_etabli_acte_h))=8) THEN 1 ELSE 0 END AS date_etab_g_format
                            ,CASE WHEN (length(concat(jd_etabli_acte_g,md_etabli_acte_g,ad_etabli_acte_g))=8) and (length(concat(jd_etabli_acte_h,md_etabli_acte_h,ad_etabli_acte_h))<8) THEN 1 ELSE 0 END AS date_etab_h_format
                            ,(select count(*) from mention m where m.id_acte = a.id_acte and LENGTH(txtmention) = 0) as mention_vide
                            ,(select count(*) from mention m where m.id_acte = a.id_acte) as mention
                            from acte a  
                            inner join affectationregistre af on af.id_tome_registre = a.id_tome_registre  
                            where af.id_lot in ($formData->id_lot) " . (($formData->mode_ech == true) ? " order by random() " : " order by id_lot"));
    $qry->execute();
    $actes_lots = $qry->fetchAll(PDO::FETCH_OBJ);

    // Récupération des informations de comparaison
    $qry = $bdextra->prepare("SELECT prenom_fr,genre_prenom,prenom_ar
                                from base_ctr_acte
                                group by prenom_fr,genre_prenom,prenom_ar");
    $qry->execute();
    $identifiants = $qry->fetchAll(PDO::FETCH_OBJ);

    $notFoundArray = array();

    // Lancement de la comparaison
    $finds = array_filter($actes_lots, function ($a) use ($identifiants) {
        // Prenom et genre personne            
        if ((count(array_values(array_filter($identifiants, function ($e) use ($a) {
            return trim($e->prenom_fr) == trim($a->prenom_fr) && $e->genre_prenom == $a->sexe && trim($e->prenom_ar) == trim($a->prenom_ar);
        }))) == 0)) {
            $a->prenom_nofound = 1;
        } else {
            $a->prenom_nofound = 0;
        }

        // Prenom père 
        if ((count(array_values(array_filter($identifiants, function ($e) use ($a) {
            return (trim($a->prenom_pere_fr) == "" || trim($e->prenom_fr) == trim($a->prenom_pere_fr)) && trim($e->prenom_ar) == trim($a->prenom_pere_ar);
        }))) == 0)) {
            $a->prenom_pere_nofound = 1;
        } else {
            $a->prenom_pere_nofound = 0;
        }

        // Prenom mère
        if ((count(array_values(array_filter($identifiants, function ($e) use ($a) {
            return (trim($a->prenom_mere_fr) == "" || trim($e->prenom_fr) == trim($a->prenom_mere_fr)) && trim($e->prenom_ar) == trim($a->prenom_mere_ar);
        }))) == 0)) {
            $a->prenom_mere_nofound = 1;
        } else {
            $a->prenom_mere_nofound = 0;
        }

        return ($a->prenom_pere_nofound == 1 && $a->prenom_mere_nofound == 1 || $a->prenom_nofound == 1 || $a->nom_mg_fr_s == 1
            || $a->prenom_mg_fr_s == 1 || $a->nom_mg_ar_s == 1 || $a->prenom_mg_ar_s == 1 || $a->jn_g_s == 1
            || $a->mn_g_s == 1 || $a->an_g_s == 1 || $a->jn_h_s == 1 || $a->mn_h_s == 1 || $a->an_h_s == 1
            || $a->date_etab_h_format == 1 || $a->date_etab_g_format || $a->je_h_s == 1 || $a->me_h_s == 1
            || $a->ae_h_s == 1 || $a->je_g_s == 1 || $a->me_g_s == 1 || $a->ae_g_s == 1 || $a->date_naiss_h_format == 1
            || $a->date_naiss_g_format == 1 || $a->ctr_pren_10 == 1 || $a->ctr_pren_9 == 1
            || $a->ctr_pren_8 == 1 || $a->ctr_pren_7 == 1 || $a->ctr_pren_6 == 1 || $a->ctr_pren_5 == 1 || $a->ctr_pren_4 == 1
            || $a->ctr_pren_3 == 1 || $a->ctr_pren_2 == 1 || $a->crt_pren_1 == 1 || $a->asc_prenom_mere == 1 || $a->prenom_with_a == 1
            || $a->nom_ar_with_error == 1 || $a->nom_with_i == 1 || $a->prenom_with_i == 1
            || $a->mention_vide > 0);
    });

    if ($formData->mode_ech == true) {
        $nb_actes = ceil(count($finds) * $formData->ech_value / 100);
        $find_keep = [];
        $i = 1;
        foreach (array_values($finds) as $f) {
            $find_keep[] = $f;
            if ($i == $nb_actes) break;
            $i++;
        }
        $finds = $find_keep;
    }

    // $result[] = $notFoundArray;                                      
    $result[] = array_values($finds);

    echo (json_encode($result));
} catch (Exception $e) {
    $fail[] = "fail";
    $fail[] = $e->getMessage();
    echo (json_encode($fail));
}
