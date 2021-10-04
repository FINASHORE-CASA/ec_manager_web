<?php
    require_once "./config/checkConfig.php";

    // Récupération registre scannée en indexation
    $qry = $bdd->prepare("SELECT * 
                          FROM {$base_de_donnees}.dbo.Registres 
                          WHERE StatutActuel = 7");
    $qry->execute();
    $registres_ct_ph1 = $qry->fetchAll(PDO::FETCH_OBJ);
    $qry->closeCursor();
    $maxY = 100;
    $maxX = 0;

    foreach($registres_ct_ph1 as $registre)
    {
        // Récupération de la tranche du registre 
        $qry = $bdd->prepare("SELECT *
                              FROM {$base_de_donnees}.dbo.Tranches t
                              INNER JOIN {$base_de_donnees}.dbo.Unites u ON u.TrancheID = t.TrancheID
                              WHERE u.UniteID = ?");
        $qry->execute(array($registre->ID_Unite));        
        $tranche = $qry->fetch(PDO::FETCH_OBJ);

        // Récupération de l'agent Traitant
        $qry = $bdd->prepare("SELECT ag.*
                              FROM {$base_de_donnees}.dbo.Agents ag 
                              LEFT JOIN {$base_de_donnees}.dbo.Traitements tr ON ag.AgentID = tr.AgentID
                              WHERE tr.TableSelect = 'Unites' AND tr.TypeTraitement = 9 AND tr.TableID = ?");
        $qry->execute(array($registre->ID_Unite));        
        $agent = $qry->fetch(PDO::FETCH_OBJ);
        $loginAgent = "ND";

        $qry = $bdd->prepare("SELECT COUNT(DISTINCT im.ImageID) 
                            FROM {$base_de_donnees}.dbo.Images im 
                            INNER JOIN Controles ct ON im.ImageID = ct.ImageID                                            
                            WHERE im.RegistreID = {$registre->RegistreID} 
                            AND ct.SequenceID is null AND ct.StatutControle = 0 
                            AND ct.PhaseControle IN (1,3,4)");
        $qry->execute();
        $nb_imagesValideRegistre = $qry->fetch();        
        
        $qry = $bdd->prepare("SELECT COUNT(*) FROM {$base_de_donnees}.dbo.Images 
        WHERE RegistreID = {$registre->RegistreID}");
        $qry->execute();
        $nb_imagesRegistre = $qry->fetch();

        if($nb_imagesRegistre[0] > 0)
        {
            $percent_ct_termine = number_format($nb_imagesValideRegistre[0] / $nb_imagesRegistre[0] * 100,2);            
        }
        else
        {
            $percent_ct_termine = 0.00;
        }

        if($agent != null) 
        {
            $loginAgent = $agent->Login;       
        }

        echo "  
            <tr>
                <td> {$tranche->TrancheID} </td>
                <td> {$registre->ID_Unite} </td>
                <td> {$loginAgent} </td>
                <td> {$registre->QrCode} </td>
                <td> {$registre->Numero} </td>
                <td> <strong> {$percent_ct_termine} % </strong></td>
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
        $Data[] = $percent_ct_termine;  
        
        if($percent_ct_termine < 25.00)
        {
            $Data_color[] = '#4e73df'; // blue
        }
        else if($percent_ct_termine < 50.00)
        {
            $Data_color[] = '#f6c23e'; // yellow
        }
        else if($percent_ct_termine < 75.00)
        {
            $Data_color[] = '#fd7e14'; // orange
        }
        else if($percent_ct_termine < 100.00)
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