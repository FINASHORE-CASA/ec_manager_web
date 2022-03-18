<?php
    require_once "./config/checkConfig.php";

    // Récupération registres scannée en Phase 2
    $qry = $bdd->prepare("SELECT rg.* 
                          FROM {$base_de_donnees}.dbo.Registres rg 
                          INNER JOIN Corrections cr ON rg.RegistreID = cr.RegistreId
                          WHERE rg.StatutActuel = 5 AND StatutCorrection = 0 AND PhaseCorrection = 1
                          AND cr.ImageID is NULL AND cr.SequenceID is NULL");

    $qry->execute();
    $registres_ct_ph3 = $qry->fetchAll(PDO::FETCH_OBJ);
    $qry->closeCursor();
    $maxY = 100;
    $maxX = 0; 

    foreach($registres_ct_ph3 as $registre)
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
                              WHERE tr.TableSelect = 'Unites' AND tr.TypeTraitement = 9 AND tr.TableID = ?");
        $qry->execute(array($registre->ID_Unite));        
        $agent = $qry->fetch(PDO::FETCH_OBJ);
        $loginAgent = "ND";

        // Pourcentage Contrôle à calculer !!!
        // Récupération du nombre d'image validé lors du contrôle ph1
        $qry = $bdd->prepare("SELECT COUNT(*) 
                              FROM {$base_de_donnees}.dbo.Controles 
                              WHERE RegistreID = {$registre->RegistreID} 
                              AND StatutControle = 0 AND ImageID IS NOT NULL 
                              AND SequenceID IS NULL 
                              AND PhaseControle = 1");
        $qry->execute();
        $nb_imagesValidePh1 = $qry->fetch();

        // Récupération des images validé de la phase3
        $qry = $bdd->prepare("SELECT COUNT(*) 
                              FROM {$base_de_donnees}.dbo.Controles 
                              WHERE RegistreID = {$registre->RegistreID} 
                              AND StatutControle = 0 AND ImageID IS NOT NULL 
                              AND SequenceID IS NULL 
                              AND PhaseControle = 3");
        $qry->execute();
        $nb_imagesValidePh2 = $qry->fetch();
        
        // Récupération du nombre d'image du registre terminé  
        // $qry = $bdd->prepare("SELECT COUNT(*) FROM {$base_de_donnees}.dbo.Images 
        // WHERE RegistreID = {$registre->RegistreID} AND StatutActuel = 4");
        // $qry->execute();
        $nb_imagesTermineRegistre = $nb_imagesValidePh1[0] + $nb_imagesValidePh2[0];
        
        $qry = $bdd->prepare("SELECT COUNT(*) FROM {$base_de_donnees}.dbo.Images 
        WHERE RegistreID = {$registre->RegistreID}");
        $qry->execute();
        $nb_imagesRegistre = $qry->fetch();

        if($nb_imagesRegistre[0] > 0)
        {
            $percent_ct_ph3 = number_format($nb_imagesTermineRegistre / $nb_imagesRegistre[0] * 100,2);            
        }
        else
        {
            $percent_ct_ph3 = 0.00;
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
                <td> <strong> {$percent_ct_ph3} % </strong></td>
                <td> ".date('d/m/Y à H:i ',strtotime($registre->DateModif))." </td>
                <td> 
                    <a class=\"btn btn-primary btn-icon-split\" 
                    href=\"details_registre.php?id_registre={$registre->RegistreID}&page_sender=etatControle\">
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
        $Data[] = $percent_ct_ph3;   
        
        if($percent_ct_ph3 < 25.00)
        {
            $Data_color[] = '#4e73df'; // blue
        }
        else if($percent_ct_ph3 < 50.00)
        {
            $Data_color[] = '#f6c23e'; // yellow
        }
        else if($percent_ct_ph3 < 75.00)
        {
            $Data_color[] = '#fd7e14'; // orange
        }
        else if($percent_ct_ph3 < 100.00)
        {   
            $Data_color[] = '#5fe385'; // lightgreen
        }        
        else 
        {
            $Data_color[] = '#1cc88a'; // green
        }
    }
        
    $maxX_json = json_encode($maxX, JSON_NUMERIC_CHECK);
    $maxY_json = json_encode($maxY, JSON_NUMERIC_CHECK);

    if(isset($Labels))
    {
        $Labels_Json = json_encode($Labels);
        $Data_Json = json_encode($Data, JSON_NUMERIC_CHECK);
        $Data_color_Json = json_encode($Data_color, JSON_NUMERIC_CHECK);
    }