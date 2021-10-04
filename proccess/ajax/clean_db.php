<?php
    // Require des données
    require_once "../../config/checkConfig.php";    

    try 
    {
        $formData = json_decode($_POST['myData']);
        $lignesAff[] = "success" ;

        // Récupération de la date
        $date_gen_deb = date("d/m/Y");
        $date_gen_fin = date("d/m/Y");

        if(isset($formData->dateDebut) && isset($formData->dateFin))
        {
            $date_gen_deb = date("d/m/Y",strtotime($formData->dateDebut));
            $date_gen_fin = date("d/m/Y",strtotime($formData->dateFin));
        }

        // Suppression saisi
        if ($formData->checkActe == true) 
        {
            $lignesAff[] = $bdd->exec(" DELETE from actionec where id_acte in (select id_acte from acte a where a.date_creation >= '{$date_gen_deb}' and a.date_creation <= '{$date_gen_fin}')");
            $lignesAff[] = $bdd->exec(" DELETE from controle_acte where id_acte in (select id_acte from acte a where a.date_creation >= '{$date_gen_deb}' and a.date_creation <= '{$date_gen_fin}')");
            $lignesAff[] = $bdd->exec(" DELETE from mention where id_acte in (select id_acte from acte a where a.date_creation >= '{$date_gen_deb}' and a.date_creation <= '{$date_gen_fin}')");
            $lignesAff[] = $bdd->exec(" DELETE from deces where id_acte in (select id_acte from acte a where a.date_creation >= '{$date_gen_deb}' and a.date_creation <= '{$date_gen_fin}')");
            $lignesAff[] = $bdd->exec(" DELETE from declaration where id_acte in (select id_acte from acte a where a.date_creation >= '{$date_gen_deb}' and a.date_creation <= '{$date_gen_fin}')");
            $lignesAff[] = $bdd->exec(" DELETE from jugement where id_acte in (select id_acte from acte a where a.date_creation >= '{$date_gen_deb}' and a.date_creation <= '{$date_gen_fin}')");
            $lignesAff[] = $bdd->exec(" DELETE from transcription where id_acte in (select id_acte from acte  where date_creation >= '{$date_gen_deb}' and date_creation <= '{$date_gen_fin}')");
            $lignesAff[] = $bdd->exec(" DELETE from acte where date_creation >= '{$date_gen_deb}' and date_creation <= '{$date_gen_fin}'");
        }

        // Suppression Mention
        if ($formData->checkMention == true) 
        {
            $lignesAff[] = $bdd->exec(" DELETE from controle_mention where id_mention in (select id_mention from mention where  date_creation >= '{$date_gen_deb}' and date_creation <= '{$date_gen_fin}');");
            $lignesAff[] = $bdd->exec(" DELETE from mention where  date_creation >= '{$date_gen_deb}' and date_creation <= '{$date_gen_fin}';");
        }

        // Suppressfion Contrôle 1
        if ($formData->checkControle1 == true) 
        {
            $lignesAff[] = $bdd->exec(" DELETE from controle_acte where id_acte IS NULL");
            $lignesAff[] = $bdd->exec(" DELETE from controle_acte where date_creation < '{$date_gen_deb}' or date_creation > '{$date_gen_fin}'");
            $lignesAff[] = $bdd->exec(" DELETE from controle_acte where date_creation < '{$date_gen_deb}' or date_creation > '{$date_gen_fin}'
                                        and id_acte in
                                        (select distinct id_acte from controle_acte where 
                                        cast(date_creation as varchar) like '2021-04%' 
                                        or cast(date_creation as varchar) like '2021-05%' 
                                        or cast(date_creation as varchar) like '2021-06%'
                                        or cast(date_creation as varchar) like '2021-07%'
                                        )");                                        
            $lignesAff[] = $bdd->exec(" DELETE from controle_acte where id_controle_acte  in 
                                        (
                                            select id_controle_acte FROM controle_acte
                                            LEFT OUTER JOIN (
                                                    SELECT max(id_controle_acte) as id, id_acte
                                                    FROM controle_acte
                                                    GROUP BY id_acte
                                                ) as t1 
                                                ON controle_acte.id_controle_acte = t1.id
                                            WHERE t1.id IS NULL
                                        )");
            $lignesAff[] = $bdd->exec(" DELETE from controle_acte where date_creation < '{$date_gen_deb}' or date_creation > '{$date_gen_fin}'");                                                
        }

        // Suppressfion Contrôle 2
        if ($formData->checkControle2 == true) 
        {
            $lignesAff[] = $bdd->exec(" DELETE  from actionec where id_type_actionec <> 11");
            $lignesAff[] = $bdd->exec(" DELETE  from actionec where id_acte IS NULL");
            $lignesAff[] = $bdd->exec(" DELETE  from actionec where date_debut_action < '{$date_gen_deb}' or date_debut_action > '{$date_gen_fin}'");
            $lignesAff[] = $bdd->exec(" DELETE  from actionec where 
                                            date_debut_action < '{$date_gen_deb}' or date_debut_action > '{$date_gen_fin}' 
                                            and id_acte  in
                                            (select distinct id_acte from actionec where 
                                            cast(date_debut_action as varchar) like '2021-04%' 
                                            or cast(date_debut_action as varchar) like '2021-05%' 
                                            or cast(date_debut_action as varchar) like '2021-06%'
                                            or cast(date_debut_action as varchar) like '2021-07%')");
            $lignesAff[] = $bdd->exec(" DELETE from actionec where id_actionec  in 
                                        (
                                            select id_actionec FROM actionec
                                            LEFT OUTER JOIN (
                                                    SELECT max(id_actionec) as id, id_acte
                                                    FROM actionec
                                                    GROUP BY id_acte
                                                ) as t1 
                                                ON actionec.id_actionec = t1.id
                                            WHERE t1.id IS NULL)");                                        
            $lignesAff[] = $bdd->exec(" DELETE from actionec where date_debut_action < '{$date_gen_deb}' or date_debut_action > '{$date_gen_fin}'");                                        
        }

        echo(json_encode($lignesAff));
    }
    catch(Exception $e)
    {
        echo $e->getMessage();
    }
?>