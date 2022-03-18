<?php
    require_once "./config/checkConfig.php";

    // Récupération registre scannée en indexation
    $qry = $bdd->prepare("SELECT * FROM {$base_de_donnees}.dbo.Registres rg
                          INNER JOIN Corrections cr ON cr.RegistreID = rg.RegistreId
                          WHERE StatutActuel = 4 AND StatutCorrection = 1 AND PhaseCorrection = 1
                          AND cr.ImageID is null AND cr.SequenceID is null");
    $qry->execute();
    $registres_cr_ph1 = $qry->fetchAll(PDO::FETCH_OBJ);
    $qry->closeCursor();
    $maxY = 100;
    $maxX = 0;

    foreach($registres_cr_ph1 as $registre)
    {
        // Récupération de la tranche du registre 
        $qry = $bdd->prepare("SELECT *
                              FROM {$base_de_donnees}.dbo.Tranches t
                              INNER JOIN {$base_de_donnees}.dbo.Unites u ON u.TrancheID = t.TrancheID
                              WHERE u.UniteID = ?");
        $qry->execute(array($registre->ID_Unite));        
        $tranche = $qry->fetch(PDO::FETCH_OBJ);

        if($tranche != null)
        {
            $trancheID = $tranche->TrancheID;
        }
        else
        {
            $trancheID = 'ND';
        }

        // Récupération de l'agent Traitant
        $qry = $bdd->prepare("SELECT ag.*
                              FROM {$base_de_donnees}.dbo.Agents ag 
                              LEFT JOIN {$base_de_donnees}.dbo.Traitements tr ON ag.AgentID = tr.AgentID
                              WHERE tr.TableSelect = 'Unites' AND tr.TypeTraitement = 12 AND tr.TableID = ?");
        $qry->execute(array($registre->ID_Unite));        
        $agent = $qry->fetch(PDO::FETCH_OBJ);
        $loginAgent = "ND";

        // Pourcentage Contrôle à calculer !!!
        // Récupération du nombre d'image du registre terminé  
        $qry = $bdd->prepare("SELECT COUNT(*) FROM {$base_de_donnees}.dbo.Images 
        WHERE RegistreID = {$registre->RegistreID} AND StatutActuel = 5");
        $qry->execute();
        $nb_imagesTermineRegistre = $qry->fetch();
        
        $qry = $bdd->prepare("SELECT COUNT(*) FROM {$base_de_donnees}.dbo.Images 
        WHERE RegistreID = {$registre->RegistreID}");
        $qry->execute();
        $nb_imagesRegistre = $qry->fetch();

        if($nb_imagesRegistre[0] > 0)
        {
            $percent_cr_ph1 = number_format($nb_imagesTermineRegistre[0] / $nb_imagesRegistre[0] * 100,2);            
        }
        else
        {
            $percent_cr_ph1 = 0.00;
        }

        if($agent != null) 
        {
            $loginAgent = $agent->Login;       
        }

        echo "  
            <tr>
                <td> {$trancheID} </td>
                <td> {$registre->ID_Unite} </td>
                <td> {$loginAgent} </td>
                <td> {$registre->QrCode} </td>
                <td> {$registre->Numero} </td>
                <td> <strong> {$percent_cr_ph1} % </strong></td>
                <td> ".date('d/m/Y à H:i ',strtotime($registre->DateModif))." </td>
                <td> 
                    <a class=\"btn btn-primary btn-icon-split\" 
                    href=\"details_registre.php?id_registre={$registre->RegistreID}&page_sender=etatCorrection\">
                    <span class=\"icon text-white-50\">
                        <i class=\"fas fa-eye\"></i>
                    </span>
                    <span class=\"text\"> voir (+) </span>
                    </a>  
                </td>
            </tr>";
        $maxX = $maxX + 1;   
     
        // Remplissage des indicateurs de la bar grahique
        $Labels[] = "Registre N° : ({$registre->Numero})";
        $Data[] = $percent_cr_ph1;
        
        if($percent_cr_ph1 < 25.00)
        {
            $Data_color[] = '#4e73df'; // blue
        }
        else if($percent_cr_ph1 < 50.00)
        {
            $Data_color[] = '#f6c23e'; // yellow
        }
        else if($percent_cr_ph1 < 75.00)
        {
            $Data_color[] = '#fd7e14'; // orange
        }
        else if($percent_cr_ph1 < 100.00)
        {   
            $Data_color[] = '#5fe385'; // lightgreen
        }        
        else 
        {
            $Data_color[] = '#1cc88a'; // green
        }

        //Indication de la couleur de progression
        //Définition des couleurs de progression 
        // -25% = #4e73df
        // -25 à -50% = #f6c23e
        // -50 à 75% = #fd7e14
        // -75 à 100% = #20c9a6
        // à 100% = #1cc88a
    }
        
    $maxX_json = json_encode($maxX, JSON_NUMERIC_CHECK);
    $maxY_json = json_encode($maxY, JSON_NUMERIC_CHECK);

    if(isset($Labels))
    {
        $Labels_Json = json_encode($Labels);
        $Data_Json = json_encode($Data, JSON_NUMERIC_CHECK);
        $Data_color_Json = json_encode($Data_color, JSON_NUMERIC_CHECK);
    }