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

        // Validation des lots
        // Mettre tous les status actes I des lot à V
        // $bdd->exec("UPDATE acte set status_acte='V' where id_tome_registre in (
        //             select id_tome_registre from affectationregistre where id_lot in($formData->id_lot))
        //             and status_acte='I';");

        // $bdd->exec("UPDATE acte SET status_acte = 'I' WHERE id_acte in (
        //             select id_acte 
        //             from acte a
        //             inner join (
        //                 select id_lot,Max(id_acte) as max_id_acte
        //                 from acte a
        //                 inner join affectationregistre af on a.id_tome_registre = af.id_tome_registre
        //                 where af.id_lot in ($formData->id_lot)
        //                 and a.status_acte = 'V'
        //                 group by id_lot)
        //             as MaxActeLot on a.id_acte = MaxActeLot.max_id_acte);");

        // Remettre le plus grand id acte de status V de chaque lot à I

        $result[] = $listLotTrouves;                                      

        echo(json_encode($result));
    }
    catch(Exception $e)
    {
        echo $e->getMessage();
    }
?>