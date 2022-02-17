<?php
    // Require des données
    require_once "../../../config/checkConfig.php";    

    try 
    {
        $formData = json_decode($_POST["myData"]);
        $result[] = "success";

        //Vérification/Création de la sauvegarde
        if($bdd_status != "undefined") 
        {
            $nameSave = $Base_url."\\fichier\\cache\\bd_save\\".$bdd_status."_".date("d_m_Y").".backup";                    

            // lancement de la sauvegarde
            if(!file_exists($nameSave))
            {
                // Création de la sauvegarde
                $cmd_dump = "\"{$path_bin_pgsql}\\pg_dump.exe\" --dbname=postgresql://{$utilisateur}:{$mot_passe}@Localhost:5432/{$bdd_status} -F c -b -f \"{$nameSave}\"";
                $res_dump = shell_exec($cmd_dump);
            }

            //restauration de la sauvegarde sur le newNom
            //-- Création de la new BD
            $bdextra->exec("CREATE DATABASE \"{$formData->name_bd}\";");
            $cmd_rest = "\"{$path_bin_pgsql}\\pg_restore.exe\" --dbname=postgresql://{$utilisateur}:{$mot_passe}@Localhost:5432/{$formData->name_bd} \"{$nameSave}\"";
            $res_rest = shell_exec($cmd_rest);

            //purge des lots en plus
            $nbLotRestant = purgeLotSave($nom_serveur,$formData->name_bd,$utilisateur,$mot_passe,$formData->id_lot); 

            if($nbLotRestant == count(explode(",",$formData->id_lot)))   
            {                                                                
                $result[] = "Base de données purgée";            
            }      
        }                                           
        else
        {
            // Lancement d'une notification
            $result[0] = "fail";    
            $result[1] = "BD_INEXISTANTE";
        }                 

        echo(json_encode($result));
    }
    catch(Exception $e)
    {
        $fail[] = "fail";
        $fail[] = $e->getMessage();
        echo(json_encode($fail));
    }

    function purgeLotSave($nom_serveur,$datname,$utilisateur,$mot_passe,$listLot) 
    {
        try
        {
            $bdd1 = new PDO("pgsql:host={$nom_serveur};port=5432;dbname={$datname}",$utilisateur,$mot_passe);	
            
            // Récupération des id_lot hors de la liste à garder
            $qry = $bdd1->prepare("SELECT id_lot from lot where id_lot not in ($listLot)");

            $qry->execute();
            $IdLotASup = $qry->fetchAll(PDO::FETCH_OBJ);   
            $listLot = "";
            $i = 1;

            foreach($IdLotASup as $lot)
            {
                $listLot .= (count($IdLotASup) > $i) ? $lot->id_lot.", " : $lot->id_lot ;  
                $i++;                                         
            }    

            // Purge des lots
            $nbAff1 = $bdd1->exec(" DELETE from actionec where id_acte in
                            (select acte.id_acte from lot l 
                            inner join affectationregistre ar on ar.id_lot = l.id_lot and l.id_lot in ($listLot)
                            inner join tomeregistre tr on tr.id_tome_registre = ar.id_tome_registre
                            inner join acte on acte.id_tome_registre = tr.id_tome_registre)");

            $nbAff2 = $bdd1->exec("    DELETE from controle_acte  where id_acte in
                            (select acte.id_acte from lot l 
                            inner join affectationregistre ar on ar.id_lot = l.id_lot and l.id_lot in ($listLot)
                            inner join tomeregistre tr on tr.id_tome_registre = ar.id_tome_registre
                            inner join acte on acte.id_tome_registre = tr.id_tome_registre)");
            
            $nbAff3 = $bdd1->exec("    DELETE from controle_mention  where id_mention in
                            (select mention.id_mention from lot l 
                            inner join affectationregistre ar on ar.id_lot = l.id_lot and l.id_lot in ($listLot)
                            inner join tomeregistre tr on tr.id_tome_registre = ar.id_tome_registre
                            inner join acte on acte.id_tome_registre = tr.id_tome_registre
                            inner join mention on mention.id_acte = acte.id_acte)");

            $nbAff4 = $bdd1->exec("    DELETE from mention  where id_acte in
                            (select acte.id_acte from lot l 
                            inner join affectationregistre ar on ar.id_lot = l.id_lot and l.id_lot in ($listLot)
                            inner join tomeregistre tr on tr.id_tome_registre = ar.id_tome_registre
                            inner join acte on acte.id_tome_registre = tr.id_tome_registre)");

            $nbAff5 = $bdd1->exec("    DELETE from deces  where id_acte in
                            (select acte.id_acte from lot l 
                            inner join affectationregistre ar on ar.id_lot = l.id_lot and l.id_lot  in ($listLot)
                            inner join tomeregistre tr on tr.id_tome_registre = ar.id_tome_registre
                            inner join acte on acte.id_tome_registre = tr.id_tome_registre)");
            
            $nbAff6 = $bdd1->exec("    DELETE from transcription where id_acte in
                            (select acte.id_acte from lot l 
                            inner join affectationregistre ar on ar.id_lot = l.id_lot and l.id_lot  in ($listLot)
                            inner join tomeregistre tr on tr.id_tome_registre = ar.id_tome_registre
                            inner join acte on acte.id_tome_registre = tr.id_tome_registre)");

            $nbAff7 = $bdd1->exec("    DELETE from jugement where id_acte in
                            (select acte.id_acte from lot l 
                            inner join affectationregistre ar on ar.id_lot = l.id_lot and l.id_lot  in ($listLot)
                            inner join tomeregistre tr on tr.id_tome_registre = ar.id_tome_registre
                            inner join acte on acte.id_tome_registre = tr.id_tome_registre)");

            $nbAff8 = $bdd1->exec("    DELETE from declaration where id_acte in
                            (select acte.id_acte from lot l 
                            inner join affectationregistre ar on ar.id_lot = l.id_lot and l.id_lot  in ($listLot)
                            inner join tomeregistre tr on tr.id_tome_registre = ar.id_tome_registre
                            inner join acte on acte.id_tome_registre = tr.id_tome_registre)");

            $nbAff9 = $bdd1->exec("    DELETE from planning_agent where id_affectationregistre in 
                            (select ar.id_affectationregistre from lot l 
                            inner join affectationregistre ar on ar.id_lot = l.id_lot and l.id_lot  in ($listLot))");

            $nbAff10 = $bdd1->exec("    DELETE from affectationregistre where id_tome_registre in
                            (select ar.id_tome_registre from lot l 
                            inner join affectationregistre ar on ar.id_lot = l.id_lot and l.id_lot in ($listLot))");
            
            $nbAff11 = $bdd1->exec("DELETE from lot where id_lot in ($listLot)");

            $nbAff12 = $bdd1->exec("    DELETE from acte where id_acte in 
                            (select acte.id_acte from lot l 
                            inner join affectationregistre ar on ar.id_lot = l.id_lot and l.id_lot in ($listLot)
                            inner join tomeregistre tr on tr.id_tome_registre = ar.id_tome_registre
                            inner join acte on acte.id_tome_registre = tr.id_tome_registre)");

            // Suppression des actes supplémentaires
            $nbAff1 =   $bdd1->exec("DELETE from transcription where id_acte in (
                SELECT id_acte
                    from acte a  
                    left join affectationregistre af on af.id_tome_registre = a.id_tome_registre  
                    and af.id_lot in (select id_lot from Lot where status_lot = 'A')
                    where  af.id_tome_registre is null)");

            $nbAff2 =   $bdd1->exec("DELETE FROM controle_mention WHERE id_mention in 
                        (select id_mention from mention where id_acte in 
                            (SELECT id_acte
                                from acte a  
                                left join affectationregistre af on af.id_tome_registre = a.id_tome_registre  
                                and af.id_lot in (select id_lot from Lot where status_lot = 'A')
                                where  af.id_tome_registre is null);");

            $nbAff3 =   $bdd1->exec("DELETE from mention where id_acte in (
                        SELECT id_acte
                            from acte a  
                            left join affectationregistre af on af.id_tome_registre = a.id_tome_registre  
                            and af.id_lot in (select id_lot from Lot where status_lot = 'A')
                            where  af.id_tome_registre is null)");

            $nbAff4 =   $bdd1->exec("DELETE from deces where id_acte in (
                        SELECT id_acte
                            from acte a  
                            left join affectationregistre af on af.id_tome_registre = a.id_tome_registre  
                            and af.id_lot in (select id_lot from Lot where status_lot = 'A')
                            where  af.id_tome_registre is null)");

            $nbAff5 =   $bdd1->exec("DELETE from jugement where id_acte in (
                        SELECT id_acte
                            from acte a  
                            left join affectationregistre af on af.id_tome_registre = a.id_tome_registre  
                            and af.id_lot in (select id_lot from Lot where status_lot = 'A')
                            where  af.id_tome_registre is null)");

            $nbAff6 =   $bdd1->exec("DELETE from controle_acte where id_acte in (
                        SELECT id_acte
                            from acte a  
                            left join affectationregistre af on af.id_tome_registre = a.id_tome_registre  
                            and af.id_lot in (select id_lot from Lot where status_lot = 'A')
                            where  af.id_tome_registre is null)");        

            $nbAff7 =   $bdd1->exec("DELETE from declaration where id_acte in (
                        SELECT id_acte
                            from acte a  
                            left join affectationregistre af on af.id_tome_registre = a.id_tome_registre  
                            and af.id_lot in (select id_lot from Lot where status_lot = 'A')
                            where  af.id_tome_registre is null)");

            $nbAff8 =   $bdd1->exec("DELETE FROM acte WHERE id_acte in (
                            SELECT id_acte
                            from acte a  
                            left join affectationregistre af on af.id_tome_registre = a.id_tome_registre  
                            and af.id_lot in (select id_lot from Lot where status_lot = 'A')
                            where  af.id_tome_registre is null)");

            // Récupération des id_lots restant après purge des lots        
            $qry = $bdd1->prepare("SELECT count(id_lot) from lot");

            $qry->execute();
            $nbRestant = $qry->fetch()[0];
            return $nbRestant;
        }   
        catch(Exception $e)
        {
            return $e;
        }             
    }
?>