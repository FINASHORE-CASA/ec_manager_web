<?php
    // Require des données
    require_once "../../../config/checkConfig.php";    

    try 
    {
        $result[] = "success" ;

        // Récupération des id_lots concernés        
        $qry = $bdd->prepare("  SELECT af.id_lot,iv.id_type_registre,iv.annee,iv.numero_tome,iv.id_bureau,(CASE WHEN iv.indice_num_tome is null THEN 0 ELSE iv.indice_num_tome END) as indice_num_tome
                                ,iv.id_commune,iv.annee_h
                                ,iv.nbre_acte_naissance,iv.nbre_acte_deces
                                ,iv.nbre_acte_naissance + iv.nbre_acte_deces as total,iv.id_inventaire
                                from affectationregistre af
                                inner join tomeregistre tr on tr.id_tome_registre = af.id_tome_registre
                                inner join registre rg on rg.id_registre = tr.id_registre
                                inner join inventaire_bec iv on iv.id_bureau = tr.id_bureau
                                inner join lot lt on lt.id_lot = af.id_lot
                                where iv.id_type_registre = rg.id_type_registre and iv.annee = rg.annee_registre_greg and iv.numero_tome = tr.num_tome 
                                and iv.id_bureau = tr.id_bureau and iv.id_commune =  tr.id_commune 
                                and (CASE WHEN iv.indice_num_tome is null THEN 0 ELSE iv.indice_num_tome END)  = (CASE WHEN tr.indice_num_tome is null THEN 0 ELSE tr.indice_num_tome END)
                                and lt.status_lot = 'A'");

        $qry->execute();
        $liste_db_inventaires = $qry->fetchAll(PDO::FETCH_OBJ);

        // Récupération des id_lots concernés        
        $qry = $bdextra->prepare('  SELECT lot,tome,(CASE WHEN indice is null THEN 0 ELSE indice END) as indice,"g" as annee_g,h as annee_h,naissance,deces,total
                                    from lotstats');

        $qry->execute();
        $liste_extra_inventaires = $qry->fetchAll(PDO::FETCH_OBJ);

        $liste_el_select;

        foreach ($liste_db_inventaires as $db_inv) 
        {
            $searchedValue = $db_inv->id_lot;
            $find = array_filter(
                            $liste_extra_inventaires,
                            function ($e) use ($searchedValue) {
                                return $e->lot == $searchedValue;
                            },ARRAY_FILTER_USE_BOTH
                        );
           if(count($find) > 0)
           {
               $find = array_values($find);

               if($db_inv->indice_num_tome != $find[0]->indice || $db_inv->numero_tome != $find[0]->tome || $db_inv->annee != $find[0]->annee_g
                || $db_inv->annee_h != $find[0]->annee_h || $db_inv->nbre_acte_naissance != $find[0]->naissance || $db_inv->nbre_acte_deces != $find[0]->deces
                || $db_inv->total != $find[0]->total)
               {
                    $liste_el_select[] = ["id_inventaire" => $db_inv->id_inventaire,"id_lot" => $db_inv->id_lot,"indice_db" => $db_inv->indice_num_tome,"indice_inv" => $find[0]->indice,"tome_db" => $db_inv->numero_tome,"tome_inv" => $find[0]->tome
                                          ,"annee_g_db" => $db_inv->annee,"annee_g_inv" => $find[0]->annee_g,"annee_h_db" => $db_inv->annee_h, "annee_h_inv" => $find[0]->annee_h
                                          ,"naissance_db" => $db_inv->nbre_acte_naissance,"naissance_inv" => $find[0]->naissance,"deces_db" => $db_inv->nbre_acte_deces,
                                          "deces_inv" => $find[0]->deces,"acte_db" => $db_inv->total, "acte_inv" => $find[0]->total];

                    // Correction automatique
                    $qry = $bdd->prepare("UPDATE inventaire_bec set annee = :annee_g, annee_h = :annee_h,nbre_acte_naissance = :nb_naiss, nbre_acte_deces = :nb_deces where id_inventaire = :id_inventaire;");
                    $qry->bindParam(':id_inventaire',$db_inv->id_inventaire);          
                    $qry->bindParam(':annee_g', $find[0]->annee_g);   
                    $qry->bindParam(':annee_h', $find[0]->annee_h);   
                    $qry->bindParam(':nb_naiss',$find[0]->naissance);   
                    $qry->bindParam(':nb_deces',$find[0]->deces);  
                    $qry->execute();
               }
           }
           else
           {               
                $liste_el_select[] = ["id_inventaire" => $db_inv->id_inventaire,"id_lot" => $db_inv->id_lot,"indice_db" => $db_inv->indice_num_tome,"indice_inv" => "ND","tome_db" => $db_inv->numero_tome,"tome_inv" => "ND"
                ,"annee_g_db" => $db_inv->annee,"annee_g_inv" => "ND","annee_h_db" => $db_inv->annee_h, "annee_h_inv" => "ND"
                ,"naissance_db" => $db_inv->nbre_acte_naissance,"naissance_inv" => "ND","deces_db" => $db_inv->nbre_acte_deces,
                "deces_inv" => "ND","acte_db" => $db_inv->total, "acte_inv" => "ND"];
           }
        }

        $result[] = isset($liste_el_select) ? $liste_el_select : [] ;                                              

        echo(json_encode($result));
    }
    catch(Exception $e)
    {
        $fail[] = "fail";
        $fail[] = $e->getMessage();
        echo(json_encode($fail));
    }
?>