<?php
    // Require des données
    require_once "../../../config/checkConfig.php";    

    try 
    {
        $formData = json_decode($_POST['myData']);
        $result[] = "success" ;

        // Récupération des id_lots concernés        
        $qry = $bdd->prepare("  SELECT id_lot 
                                from lot
                                where id_lot in ($formData->id_lot)");

        $qry->execute();
        $listLotTrouves = $qry->fetchAll(PDO::FETCH_OBJ);   

        // Initialisation des lots
        // $bdd->exec("UPDATE acte SET status_acte='I', status_acteechantillon='I' where 
        //             id_tome_registre in (
        //             select tr.id_tome_registre from tomeregistre tr inner join registre r on r.id_registre=tr.id_registre
        //             inner join affectationregistre ar on ar.id_tome_registre=tr.id_tome_registre
        //             inner join lot l on l.id_lot=ar.id_lot
        //                 where l.id_lot in ($formData->id_lot)
        //             );

        //             update tomeregistre SET status='I' where 
        //             id_tome_registre in (
        //             select tr.id_tome_registre from tomeregistre tr inner join registre r on r.id_registre=tr.id_registre
        //             inner join affectationregistre ar on ar.id_tome_registre=tr.id_tome_registre
        //             inner join lot l on l.id_lot=ar.id_lot
        //                 where l.id_lot in ($formData->id_lot)
        //             );

        //             update lot SET status_lot='I',num_echantillon=0 where id_lot in ($formData->id_lot);");

        $result[] = $listLotTrouves;                                      

        echo(json_encode($result));
    }
    catch(Exception $e)
    {
        echo $e->getMessage();
    }
?>