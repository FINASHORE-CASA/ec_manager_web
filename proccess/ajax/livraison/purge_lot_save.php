<?php
    // Require des données
    require_once "../../../config/checkConfig.php";    

    try 
    {
        $formData = json_decode($_POST['myData']);
        $result[] = "success" ;

        // Récupération des id_lot hors de la liste à garder
        $qry = $bdd->prepare("SELECT id_lot from lot where id_lot not in ($formData->id_lot)");

        $qry->execute();
        $IdLotASup = $qry->fetchAll(PDO::FETCH_OBJ);   
        $listLot = "";
        $i = 1;

        foreach($IdLotASup as $lot)
        {
            $listLot .= (count($IdLotASup) > $i) ? $lot->id_lot.", " : $lot->id_lot ;  
            $i++;                                         
        }    

        // Initialisation des lots
        $nbAff1 = $bdd->exec(" DELETE from actionec where id_acte in
                        (select acte.id_acte from lot l 
                        inner join affectationregistre ar on ar.id_lot = l.id_lot and l.id_lot in ($listLot)
                        inner join tomeregistre tr on tr.id_tome_registre = ar.id_tome_registre
                        inner join acte on acte.id_tome_registre = tr.id_tome_registre)");

        $nbAff2 = $bdd->exec("    DELETE from controle_acte  where id_acte in
                        (select acte.id_acte from lot l 
                        inner join affectationregistre ar on ar.id_lot = l.id_lot and l.id_lot in ($listLot)
                        inner join tomeregistre tr on tr.id_tome_registre = ar.id_tome_registre
                        inner join acte on acte.id_tome_registre = tr.id_tome_registre)");
        
        $nbAff3 = $bdd->exec("    DELETE from controle_mention  where id_mention in
                        (select mention.id_mention from lot l 
                        inner join affectationregistre ar on ar.id_lot = l.id_lot and l.id_lot in ($listLot)
                        inner join tomeregistre tr on tr.id_tome_registre = ar.id_tome_registre
                        inner join acte on acte.id_tome_registre = tr.id_tome_registre
                        inner join mention on mention.id_acte = acte.id_acte)");

        $nbAff4 = $bdd->exec("    DELETE from mention  where id_acte in
                        (select acte.id_acte from lot l 
                        inner join affectationregistre ar on ar.id_lot = l.id_lot and l.id_lot in ($listLot)
                        inner join tomeregistre tr on tr.id_tome_registre = ar.id_tome_registre
                        inner join acte on acte.id_tome_registre = tr.id_tome_registre)");

        $nbAff5 = $bdd->exec("    DELETE from deces  where id_acte in
                        (select acte.id_acte from lot l 
                        inner join affectationregistre ar on ar.id_lot = l.id_lot and l.id_lot  in ($listLot)
                        inner join tomeregistre tr on tr.id_tome_registre = ar.id_tome_registre
                        inner join acte on acte.id_tome_registre = tr.id_tome_registre)");
        
        $nbAff6 = $bdd->exec("    DELETE from transcription where id_acte in
                        (select acte.id_acte from lot l 
                        inner join affectationregistre ar on ar.id_lot = l.id_lot and l.id_lot  in ($listLot)
                        inner join tomeregistre tr on tr.id_tome_registre = ar.id_tome_registre
                        inner join acte on acte.id_tome_registre = tr.id_tome_registre)");

        $nbAff7 = $bdd->exec("    DELETE from jugement where id_acte in
                        (select acte.id_acte from lot l 
                        inner join affectationregistre ar on ar.id_lot = l.id_lot and l.id_lot  in ($listLot)
                        inner join tomeregistre tr on tr.id_tome_registre = ar.id_tome_registre
                        inner join acte on acte.id_tome_registre = tr.id_tome_registre)");

        $nbAff8 = $bdd->exec("    DELETE from declaration where id_acte in
                        (select acte.id_acte from lot l 
                        inner join affectationregistre ar on ar.id_lot = l.id_lot and l.id_lot  in ($listLot)
                        inner join tomeregistre tr on tr.id_tome_registre = ar.id_tome_registre
                        inner join acte on acte.id_tome_registre = tr.id_tome_registre)");

        $nbAff9 = $bdd->exec("    DELETE from planning_agent where id_affectationregistre in 
                        (select ar.id_affectationregistre from lot l 
                        inner join affectationregistre ar on ar.id_lot = l.id_lot and l.id_lot  in ($listLot))");

        $nbAff10 = $bdd->exec("    DELETE from affectationregistre where id_tome_registre in
                        (select ar.id_tome_registre from lot l 
                        inner join affectationregistre ar on ar.id_lot = l.id_lot and l.id_lot in ($listLot))");
        
        $nbAff11 = $bdd->exec("DELETE from lot where id_lot in ($listLot)");

        $nbAff12 = $bdd->exec("    DELETE from acte where id_acte in 
                        (select acte.id_acte from lot l 
                        inner join affectationregistre ar on ar.id_lot = l.id_lot and l.id_lot in ($listLot)
                        inner join tomeregistre tr on tr.id_tome_registre = ar.id_tome_registre
                        inner join acte on acte.id_tome_registre = tr.id_tome_registre)");
        
        // Récupération des id_lots restant après purge des lots        
        $qry = $bdd->prepare("SELECT count(id_lot) from lot");

        $qry->execute();
        $nbRestant = $qry->fetch();                                   

        $result[] = $nbAff1;                                        
        $result[] = $nbRestant[0];                                      
        $result[] = $formData->id_lot;                                                
        $result[] = "req 1 : ".$nbAff1.", req 2 : ".$nbAff2.", req 3 : ".$nbAff3.", req 4 : ".$nbAff4.", req 5 : ".$nbAff5.", req 6 : ".$nbAff6
                    .", req 7 : ".$nbAff7.", req 8 : ".$nbAff8.", req 9 : ".$nbAff9.", req 10 : ".$nbAff10.", req 11 : ".$nbAff11.", req 12 : ".$nbAff12;                                                

        echo(json_encode($result));
    }
    catch(Exception $e)
    {
        $fail[] = "fail";
        $fail[] = $e->getMessage();
        echo(json_encode($fail));
    }
?>