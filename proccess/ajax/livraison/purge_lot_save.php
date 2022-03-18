<?php
    // Require des données
    require_once "../../../config/checkConfig.php";    

    try 
    {
        $formData = json_decode($_POST['myData']);
        $result[] = "success" ;

        // Initialisation des lots
        $nbAff1 = $bdd->exec(" DELETE from actionec where id_acte not in
                                (select acte.id_acte from lot l 
                                inner join affectationregistre ar on ar.id_lot = l.id_lot and l.id_lot in ($formData->id_lot)
                                inner join tomeregistre tr on tr.id_tome_registre = ar.id_tome_registre
                                inner join acte on acte.id_tome_registre = tr.id_tome_registre);

                                delete from controle_acte  where id_acte not in
                                (select acte.id_acte from lot l 
                                inner join affectationregistre ar on ar.id_lot = l.id_lot and l.id_lot in ($formData->id_lot)
                                inner join tomeregistre tr on tr.id_tome_registre = ar.id_tome_registre
                                inner join acte on acte.id_tome_registre = tr.id_tome_registre);

                                delete from controle_mention  where id_mention not in
                                (select mention.id_mention from lot l 
                                inner join affectationregistre ar on ar.id_lot = l.id_lot and l.id_lot in ($formData->id_lot)
                                inner join tomeregistre tr on tr.id_tome_registre = ar.id_tome_registre
                                inner join acte on acte.id_tome_registre = tr.id_tome_registre
                                inner join mention on mention.id_acte = acte.id_acte);

                                delete from mention  where id_acte not in
                                (select acte.id_acte from lot l 
                                inner join affectationregistre ar on ar.id_lot = l.id_lot and l.id_lot in ($formData->id_lot)
                                inner join tomeregistre tr on tr.id_tome_registre = ar.id_tome_registre
                                inner join acte on acte.id_tome_registre = tr.id_tome_registre);

                                delete from deces  where id_acte not in
                                (select acte.id_acte from lot l 
                                inner join affectationregistre ar on ar.id_lot = l.id_lot and l.id_lot in ($formData->id_lot)
                                inner join tomeregistre tr on tr.id_tome_registre = ar.id_tome_registre
                                inner join acte on acte.id_tome_registre = tr.id_tome_registre);

                                delete from transcription where id_acte not in
                                (select acte.id_acte from lot l 
                                inner join affectationregistre ar on ar.id_lot = l.id_lot and l.id_lot in ($formData->id_lot)
                                inner join tomeregistre tr on tr.id_tome_registre = ar.id_tome_registre
                                inner join acte on acte.id_tome_registre = tr.id_tome_registre);

                                delete from jugement where id_acte not in
                                (select acte.id_acte from lot l 
                                inner join affectationregistre ar on ar.id_lot = l.id_lot and l.id_lot in ($formData->id_lot)
                                inner join tomeregistre tr on tr.id_tome_registre = ar.id_tome_registre
                                inner join acte on acte.id_tome_registre = tr.id_tome_registre);

                                delete from declaration where id_acte not in
                                (select acte.id_acte from lot l 
                                inner join affectationregistre ar on ar.id_lot = l.id_lot and l.id_lot in ($formData->id_lot)
                                inner join tomeregistre tr on tr.id_tome_registre = ar.id_tome_registre
                                inner join acte on acte.id_tome_registre = tr.id_tome_registre);

                                delete from planning_agent where id_affectationregistre not in 
                                (select ar.id_affectationregistre from lot l 
                                inner join affectationregistre ar on ar.id_lot = l.id_lot and l.id_lot in ($formData->id_lot));

                                delete from affectationregistre where id_tome_registre not in
                                (select ar.id_tome_registre from lot l 
                                inner join affectationregistre ar on ar.id_lot = l.id_lot and l.id_lot in ($formData->id_lot));

                                delete from lot where id_lot not in
                                (select l.id_lot from lot l where l.id_lot in ($formData->id_lot));

                                delete from acte where id_acte not in ( 
                                select distinct a.id_acte from acte a
                                inner join affectationregistre af on af.id_tome_registre = a.id_tome_registre
                                where af.id_lot in ($formData->id_lot));");
        
        // Récupération des id_lots restant après purge des lots        
        $qry = $bdd->prepare("SELECT count(id_lot) from lot");

        $qry->execute();
        $nbRestant = $qry->fetch();                                   

        $result[] = $nbAff1;                                      
        $result[] = $nbRestant[0];                                      

        echo(json_encode($result));
    }
    catch(Exception $e)
    {
        $fail[] = "fail";
        $fail[] = $e->getMessage();
        echo(json_encode($fail));
    }
?>