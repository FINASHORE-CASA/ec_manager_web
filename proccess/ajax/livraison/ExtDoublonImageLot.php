<?php
    // Require des données
    require_once "../../../config/checkConfig.php";    

    try 
    {
        $result[] = "success" ;

        // Récupération NumActeDoublon
        $qry = $bdd->prepare("  SELECT af.id_lot,id_acte,a.num_acte,imagepath
                                from acte a
                                inner join affectationregistre af on a.id_tome_registre = af.id_tome_registre
                                inner join (select af.id_lot,num_acte,count(distinct id_acte) as nb_num_acte_db
                                            from acte a
                                            inner join affectationregistre af on a.id_tome_registre = af.id_tome_registre
                                            where af.id_lot in (select id_lot from lot where status_lot = 'A') 
                                            group by af.id_lot,num_acte
                                            having (count(distinct id_acte) > 1)) data1 on data1.id_lot = af.id_lot and a.num_acte = data1.num_acte
                                where data1.nb_num_acte_db > 1
                                order by af.id_lot, a.num_acte,id_acte desc");

        $qry->execute();
        $NumActeDoublon = $qry->fetchAll(PDO::FETCH_OBJ);

        // Récupération imagePathDoublon        
        $qry = $bdd->prepare("  SELECT af.id_lot,id_acte,a.num_acte,a.imagepath
                                from acte a
                                inner join affectationregistre af on a.id_tome_registre = af.id_tome_registre
                                inner join (select af.id_lot,imagepath,count(distinct id_acte) as nb_num_acte_db
                                            from acte a
                                            inner join affectationregistre af on a.id_tome_registre = af.id_tome_registre
                                            where af.id_lot in (select id_lot from lot where status_lot = 'A') 
                                            group by af.id_lot,imagepath
                                            having (count(distinct id_acte) > 1)) data1 on data1.id_lot = af.id_lot and a.imagepath = data1.imagepath
                                where data1.nb_num_acte_db > 1
                                order by af.id_lot,a.imagepath,id_acte desc");

        $qry->execute();
        $imagePathDoublon = $qry->fetchAll(PDO::FETCH_OBJ);

        $liste_el_select;
        $nbCorr = 0;

        foreach ($NumActeDoublon as $actesup) 
        { 
            $s_id_lot = $actesup->id_lot;
            $s_id_acte = $actesup->id_acte;
            $s_num_acte = $actesup->num_acte;
            
            $find = array_filter(
                            $NumActeDoublon,
                            function ($e) use ($s_id_lot,$s_id_acte,$s_num_acte) {
                                return ($e->id_lot == $s_id_lot && trim(strtolower($e->num_acte)) == trim(strtolower($s_num_acte)) && $e->id_acte > $s_id_acte);
                            },ARRAY_FILTER_USE_BOTH
                        );

            if(count($find) > 0)
            {
                $find = array_values($find);
                
                $qry = $bdd->prepare("DELETE from transcription where id_acte = :id_acte;");                       
                $qry->bindParam(':id_acte', $actesup->id_acte); 
                $qry->execute();                        
                $qry = $bdd->prepare("DELETE from controle_mention where id_mention in (select id_mention from mention where id_acte = :id_acte);");                       
                $qry->bindParam(':id_acte', $actesup->id_acte); 
                $qry->execute();                       
                $qry = $bdd->prepare("DELETE from mention where id_acte = :id_acte;");                       
                $qry->bindParam(':id_acte', $actesup->id_acte); 
                $qry->execute();                        
                $qry = $bdd->prepare("DELETE from deces where id_acte = :id_acte;");                       
                $qry->bindParam(':id_acte', $actesup->id_acte); 
                $qry->execute();                       
                $qry = $bdd->prepare("DELETE from jugement where id_acte = :id_acte;");                       
                $qry->bindParam(':id_acte', $actesup->id_acte); 
                $qry->execute();                        
                $qry = $bdd->prepare("DELETE from controle_acte where id_acte = :id_acte;");                       
                $qry->bindParam(':id_acte', $actesup->id_acte); 
                $qry->execute();                          
                $qry = $bdd->prepare("DELETE from declaration where id_acte = :id_acte;");                       
                $qry->bindParam(':id_acte', $actesup->id_acte); 
                $qry->execute();                            
                $qry = $bdd->prepare("DELETE from acte where id_acte = :id_acte;");                       
                $qry->bindParam(':id_acte', $actesup->id_acte); 
                $qry->execute();                
                $nbCorr++;
            
                $liste_el_select[] = ["id_lot" => $actesup->id_lot,"id_acte" => $actesup->id_acte,"num_acte" => $s_num_acte
                ,"imagepath" => $actesup->imagepath ,"observation" => "SUPPRIMER DOUBLON NUM_ACTE"];                
            }
        }

        foreach ($imagePathDoublon as $actesup) 
        { 
            $s_id_lot = $actesup->id_lot;
            $s_id_acte = $actesup->id_acte;
            $s_imagepath = $actesup->imagepath;
            
            $find = array_filter(
                            $imagePathDoublon,
                            function ($e) use ($s_id_lot,$s_id_acte,$s_imagepath) {
                                return ($e->id_lot == $s_id_lot && trim(strtolower($e->imagepath)) == trim(strtolower($s_imagepath)) && $e->id_acte > $s_id_acte);
                            },ARRAY_FILTER_USE_BOTH
                        );

            if(count($find) > 0)
            {
                $find = array_values($find);
                
                $qry = $bdd->prepare("DELETE from transcription where id_acte = :id_acte;");                       
                $qry->bindParam(':id_acte', $actesup->id_acte); 
                $qry->execute();                        
                $qry = $bdd->prepare("DELETE from controle_mention where id_mention in (select id_mention from mention where id_acte = :id_acte;");                       
                $qry->bindParam(':id_acte', $actesup->id_acte); 
                $qry->execute();                       
                $qry = $bdd->prepare("DELETE from mention where id_acte = :id_acte;");                       
                $qry->bindParam(':id_acte', $actesup->id_acte); 
                $qry->execute();                        
                $qry = $bdd->prepare("DELETE from deces where id_acte = :id_acte;");                       
                $qry->bindParam(':id_acte', $actesup->id_acte); 
                $qry->execute();                       
                $qry = $bdd->prepare("DELETE from jugement where id_acte = :id_acte;");                       
                $qry->bindParam(':id_acte', $actesup->id_acte); 
                $qry->execute();                        
                $qry = $bdd->prepare("DELETE from controle_acte where id_acte = :id_acte;");                       
                $qry->bindParam(':id_acte', $actesup->id_acte); 
                $qry->execute();                          
                $qry = $bdd->prepare("DELETE from declaration where id_acte = :id_acte;");                       
                $qry->bindParam(':id_acte', $actesup->id_acte); 
                $qry->execute();                            
                $qry = $bdd->prepare("DELETE from acte where id_acte = :id_acte;");                       
                $qry->bindParam(':id_acte', $actesup->id_acte); 
                $qry->execute();                
                $nbCorr++;
            
                $liste_el_select[] = ["id_lot" => $actesup->id_lot,"id_acte" => $actesup->id_acte,"num_acte" => $actesup->num_acte
                ,"imagepath" => $actesup->imagepath ,"observation" => "SUPPRIMER DOUBLON IMAGEPATH"];                
            }
        }

        $result[] = isset($liste_el_select) ? $liste_el_select : [];                                              

        echo(json_encode($result));
    }
    catch(Exception $e)
    {
        $fail[] = "fail";
        $fail[] = $e->getMessage();
        echo(json_encode($fail));
    }
?>