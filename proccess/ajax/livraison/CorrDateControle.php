<?php
    // Require des données
    require_once "../../../config/checkConfig.php";    

    try 
    {
        $result[] = "success" ;

        // Récupération des id_lots concernés        
        $qry = $bdd->prepare("SELECT lt.id_lot,max(ac.date_fin_action) as ctr1_last_date  
                                from actionec ac 
                                inner join acte a on a.id_acte = ac.id_acte  
                                inner join affectationregistre af on af.id_tome_registre = a.id_tome_registre 
                                inner join lot lt on lt.id_lot = af.id_lot
                                where af.id_lot = lt.id_lot and id_type_actionec = 7 and lt.status_lot = 'A'
                                group by  lt.id_lot
                                order by lt.id_lot");

        $qry->execute();
        $listeLotCtrl1Date = $qry->fetchAll(PDO::FETCH_OBJ);

        // Récupération des id_lots concernés        
        $qry = $bdd->prepare("SELECT lt.id_lot,min(ac.date_debut_action) as ctr2_first_date  
                                from actionec ac 
                                inner join acte a on a.id_acte = ac.id_acte  
                                inner join affectationregistre af on af.id_tome_registre = a.id_tome_registre 
                                inner join lot lt on lt.id_lot = af.id_lot
                                where af.id_lot = lt.id_lot and id_type_actionec = 11 and lt.status_lot = 'A'
                                group by  lt.id_lot
                                order by lt.id_lot");

        $qry->execute();
        $listeLotCtrl2Date = $qry->fetchAll(PDO::FETCH_OBJ);

        $liste_el_select;
        $nbRowAffected = 0;

        foreach ($listeLotCtrl1Date as $lot1)
        {
            $lot2 = array_filter($listeLotCtrl2Date,function($e) use($lot1){ return $e->id_lot == $lot1->id_lot; });
            if (count($lot2) > 0)
            {
                $lot2 = array_values($lot2);
                if ($lot1->ctr1_last_date >= $lot2[0]->ctr2_first_date)
                {              
                    $qry = $bdd->prepare("UPDATE actionec set date_debut_action = :newdatedebut ,date_fin_action = :newdatefin
                                            where id_actionec in (  
                                            select id_actionec 
                                            from actionec ac  
                                            inner join acte a on a.id_acte = ac.id_acte  
                                            inner join affectationregistre af on af.id_tome_registre = a.id_tome_registre  
                                            where af.id_lot = cast(:lot as bigint) and id_type_actionec = 11)");
                    
                    $new_date = new DateTime($lot1->ctr1_last_date);
                    $new_date->add(new DateInterval('P1D'));
                    $new_date_format = $new_date->format('Y-m-d h:i:s').'';
                    $qry->bindParam(":newdatedebut",$new_date_format);                                        
                    $qry->bindParam(":newdatefin",$new_date_format);                                         
                    $qry->bindParam(":lot",$lot1->id_lot);                                         
                    $nbLignes_aff = $qry->execute();                         
                    $nbRowAffected++;
                    $liste_el_select[] = ["id_lot" => $lot1->id_lot,"ctrl1_last" => $lot1->ctr1_last_date , "ctrl2_first" => $lot2[0]->ctr2_first_date
                    ,"nb_ligne_modif" =>  $nbLignes_aff,"observation" => ''];                                       
                }
            }
            else
            {
                $liste_el_select[] = ["id_lot" => $lot1->id_lot,"ctrl1_last" => $lot1->ctr1_last_date , "ctrl2_first" => "NULL"
                ,"nb_ligne_modif" =>  "0","observation" => 'DATE CTRL2 NULL']; 
            }
        }

        foreach ($listeLotCtrl2Date as $lot2)
        {
            $lot1 = array_filter($listeLotCtrl1Date,function($e)use($lot2){ return $e->id_lot == $lot2->id_lot;});
            if (count($lot1) == 0)
            {
                $lot1 = array_values($lot1);
                $liste_el_select[] = ["id_lot" => $lot2->id_lot,"ctrl1_last" => "NULL" , "ctrl2_first" => $lot2->ctr2_first_date
                ,"nb_ligne_modif" =>  "0","observation" => 'DATE CTRL1 NULL']; 
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