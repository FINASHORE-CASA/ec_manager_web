<?php
    // Require des données
    require_once "../../../config/checkConfig.php";    

    try 
    {
        $formData = json_decode($_POST['myData']);
        $result[] = "success" ;

        // Récupération des id_lots concernés        
        $qry = $bdd->prepare("  SELECT * 
                                from lot
                                where id_lot in ($formData->id_lot)");

        $qry->execute();
        $listLotTrouves = $qry->fetchAll(PDO::FETCH_OBJ);  

        foreach($listLotTrouves as $lot) 
        {
            // Vérification que le lot n'est pas encore diviser 
            $qry = $bdextra->prepare("SELECT * from mg_lot_division where id_lot_parent = ? and date_fusion is null");
            $qry->execute(array($lot->id_lot));
            $lotDiviser = $qry->fetchAll(PDO::FETCH_OBJ);                             

            if (count($lotDiviser) > 0) 
            {
                // Consolidation des id_lot en les séparant par des virgules
                $lis_id_lot_div = "";
                foreach($lotDiviser as $lotdiv)
                {
                    $lis_id_lot_div .= $lotdiv->id_lot_child.' ,';
                }
                $lis_id_lot_div = substr($lis_id_lot_div,0,(strlen($lis_id_lot_div)-1));

                // Régroupement des acte  
                $bdd->exec("UPDATE acte set id_tome_registre = (select id_tome_registre from affectationregistre where id_lot = {$lotDiviser[0]->id_lot_parent})
                                        where id_acte in (
                                            select distinct id_acte
                                            from acte a
                                            inner join affectationregistre af on af.id_tome_registre = a.id_tome_registre
                                            where id_lot in ($lis_id_lot_div)
                                        )");

                // Modification de la date de fusion    
                $bdextra->exec("UPDATE mg_lot_division set date_fusion = NOW() where id_lot_parent in ({$lotDiviser[0]->id_lot_parent})");

                // Suppression des lots enfants  
                // Récupération des id_tome_registre des lots enfants
                $qry = $bdd->prepare("SELECT id_registre 
                                        from tomeregistre tr
                                        inner join affectationregistre af on af.id_tome_registre = tr.id_tome_registre
                                        where af.id_lot in ($lis_id_lot_div)");
                $qry->execute();     
                $list_id_registre_enfant = $qry->fetchAll(PDO::FETCH_OBJ);

                // Consolidation des id_tome_registre en les séparant par des virgules
                $list_id_registre_cons = "";
                foreach($list_id_registre_enfant as $lot_enfant)
                {
                    $list_id_registre_cons .= $lot_enfant->id_registre.' ,';
                }
                $list_id_registre_cons = substr($list_id_registre_cons,0,(strlen($list_id_registre_cons)-1));

                // suppression dans la table affectationregistre
                $bdd->exec("DELETE from affectationregistre where id_tome_registre in (
                                        select id_tome_registre 
                                        from tomeregistre
                                        where id_registre in ($list_id_registre_cons)
                                    )");

                // suppression dans la table tomeregistre
                $bdd->exec("DELETE from tomeregistre where id_tome_registre in (
                                        select id_tome_registre 
                                        from tomeregistre
                                        where id_registre in ($list_id_registre_cons)
                                    )");

                // suppression dans la table registre
                $bdd->exec("DELETE from registre where id_registre in ($list_id_registre_cons)");

                //suppression dans la table lot
                $bdd->exec("DELETE from lot where id_lot in ($lis_id_lot_div)");

                $result[] = $lis_id_lot_div;
            }
            else
            {
                $result[] = $lot->id_lot.' : deja_fusionner ou non diviser';
            }                        
        }                                

        echo(json_encode($result));
    }
    catch(Exception $e)
    {
        $fail[] = "fail";
        $fail[] = $e->getMessage();
        echo(json_encode($fail));
    }
?>