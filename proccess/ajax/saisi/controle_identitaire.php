<?php
    // Require des données
    require_once "../../../config/checkConfig.php";    

    try 
    {
        $formData = json_decode($_POST['myData']);
        $result[] = "success" ;

        //Récupération des acte des id_lots concernés        
        $qry = $bdd->prepare("SELECT id_acte,af.id_lot,num_acte,imagepath
                              ,nom_fr,prenom_fr,nom_marge_fr,prenom_marge_fr,nom_ar,prenom_ar
                              ,nom_marge_ar,prenom_marge_ar,sexe
                              ,CASE WHEN TRIM(nom_fr) <> TRIM(nom_marge_fr) THEN 1 ELSE 0 END AS nom_mg_fr_s
                              ,CASE WHEN TRIM(prenom_fr) <> TRIM(prenom_marge_fr) THEN 1 ELSE 0 END AS prenom_mg_fr_s
                              ,CASE WHEN TRIM(nom_ar) <> TRIM(nom_marge_ar) THEN 1 ELSE 0 END AS nom_mg_ar_s
                              ,CASE WHEN TRIM(prenom_ar) <> TRIM(prenom_marge_ar) THEN 1 ELSE 0 END AS prenom_mg_ar_s
                              ,jd_naissance_g,md_naissance_g,ad_naissance_g
                              ,jd_naissance_h,md_naissance_h,ad_naissance_h
                              ,prenom_pere_fr,prenom_pere_ar,prenom_mere_fr,prenom_mere_ar
                              ,ascendant_pere_fr,ascendant_pere_ar,ascendant_mere_fr,ascendant_mere_ar
                              ,CASE WHEN jd_naissance_g <> '' AND try_cast_int(jd_naissance_g) >= 1 AND try_cast_int(jd_naissance_g) <= 31 THEN 0 ELSE 1 END AS jn_g_s
                              ,CASE WHEN md_naissance_g <> '' AND try_cast_int(md_naissance_g) >= 1 AND try_cast_int(md_naissance_g) <= 12 THEN 0 ELSE 1 END AS mn_g_s
                              ,CASE WHEN ad_naissance_g <> '' AND try_cast_int(ad_naissance_g) >= 1900 AND try_cast_int(ad_naissance_g) <= 2022 THEN 0 ELSE 1 END AS an_g_s
                              ,CASE WHEN jd_naissance_h <> '' AND try_cast_int(jd_naissance_h) >= 1 AND try_cast_int(jd_naissance_h) <= 31 THEN 0 ELSE 1 END AS jn_h_s
                              ,CASE WHEN md_naissance_h <> '' AND try_cast_int(md_naissance_h) >= 1 AND try_cast_int(md_naissance_h) <= 12 THEN 0 ELSE 1 END AS mn_h_s
                              ,CASE WHEN ad_naissance_h <> '' AND try_cast_int(ad_naissance_h) >= 1317 AND try_cast_int(ad_naissance_h) <= 1443 THEN 0 ELSE 1 END AS an_h_s
                              ,(select count(*) from mention m where m.id_acte = a.id_acte and LENGTH(txtmention) = 0) as mention_vide
                              from acte a  
                              inner join affectationregistre af on af.id_tome_registre = a.id_tome_registre 
                              where af.id_lot in ($formData->id_lot) ".(($formData->mode_ech == true) ? " order by random() " : " order by id_lot")
                            );
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
        $finds = array_filter($actes_lots ,function($a) use($identifiants) 
        {         
            // Prenom et genre personne            
            if((count(array_values(array_filter($identifiants,function($e) use($a) { return trim($e->prenom_fr) == trim($a->prenom_fr) && $e->genre_prenom == $a->sexe && trim($e->prenom_ar) == trim($a->prenom_ar) ;}))) == 0))
            {$a->prenom_nofound = 1;}else{$a->prenom_nofound = 0;}

            // Prenom père 
            if((count(array_values(array_filter($identifiants,function($e) use($a) { return (trim($a->prenom_pere_fr) == "" || trim($e->prenom_fr) == trim($a->prenom_pere_fr)) && trim($e->prenom_ar) == trim($a->prenom_pere_ar) ;}))) == 0))
            {$a->prenom_pere_nofound = 1;}else{$a->prenom_pere_nofound = 0;}

            // Prenom mère
            if((count(array_values(array_filter($identifiants,function($e) use($a) { return (trim($a->prenom_mere_fr) == "" || trim($e->prenom_fr) == trim($a->prenom_mere_fr)) && trim($e->prenom_ar) == trim($a->prenom_mere_ar) ;}))) == 0))
            {$a->prenom_mere_nofound = 1;}else{$a->prenom_mere_nofound = 0;}

            return ($a->prenom_pere_nofound == 1 && $a->prenom_mere_nofound == 1 || $a->prenom_nofound == 1 || $a->nom_mg_fr_s == 1 
            || $a->prenom_mg_fr_s == 1 || $a->nom_mg_ar_s == 1 || $a->prenom_mg_ar_s == 1 || $a->jn_g_s == 1
            || $a->mn_g_s == 1 || $a->an_g_s == 1 || $a->jn_h_s == 1 || $a->mn_h_s == 1 || $a->an_h_s == 1 
            || $a->mention_vide > 0);
        });

        if($formData->mode_ech == true)
        {
            $nb_actes = ceil(count($finds) * 20 / 100);
            $find_keep = [];
            $i = 1;
            foreach (array_values($finds) as $f) 
            { 
                $find_keep[] = $f;              
                if($i == $nb_actes) break;  
                $i++;
            }               
            $finds = $find_keep;    
        }

        // $result[] = $notFoundArray;                                      
        $result[] = array_values($finds);                                      

        echo(json_encode($result));
    }
    catch(Exception $e)
    {
        $fail[] = "fail";
        $fail[] = $e->getMessage();
        echo(json_encode($fail));
    }
?>