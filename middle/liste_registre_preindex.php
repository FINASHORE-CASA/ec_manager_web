<?php
    require_once "./config/checkConfig.php";

    // Récupération registre scannée en indexation
    $qry = $bdd->prepare("SELECT * FROM {$base_de_donnees}.dbo.Registres WHERE StatutActuel = 0");
    $qry->execute();
    $registres_index = $qry->fetchAll(PDO::FETCH_OBJ);
    $qry->closeCursor();
    $maxY = 100;
    $maxX = 0;

    foreach($registres_index as $registre)
    {
        // Récupération de l'agent Traitant
        $qry = $bdd->prepare("SELECT ag.*
                              FROM {$base_de_donnees}.dbo.Agents ag 
                              LEFT JOIN {$base_de_donnees}.dbo.Traitements tr ON ag.AgentID = tr.AgentID
                              WHERE tr.TableSelect = 'Registres' AND tr.TypeTraitement = 3 AND tr.TableID = ?");
        $qry->execute(array($registre->RegistreID));        
        $agent = $qry->fetch(PDO::FETCH_OBJ);
        $loginAgent = "ND";

        // Pourcentage Index à calculer !!!
        // Récupération du nombre d'image du registre terminé
        $qry = $bdd->prepare("SELECT COUNT(*) FROM {$base_de_donnees}.dbo.Images 
        WHERE RegistreID = {$registre->RegistreID}");
        $qry->execute();
        $nb_imagesTermineRegistre = $qry->fetch();        

        $nb_imagesRegistre = $registre->NombrePage;

        if($nb_imagesRegistre > 0)
        {
            $percent_preindex = number_format($nb_imagesTermineRegistre[0] / $nb_imagesRegistre * 100,2);
        }
        else
        {
            $percent_preindex =  number_format(0,2);
        }


        if($agent != null) 
        {
            $loginAgent = $agent->Login;       
        }

        echo "  
            <tr>
                <td> {$loginAgent} </td>
                <td> {$registre->QrCode} </td>
                <td> {$registre->Numero} </td>
                <td> {$registre->NombrePage} </td>
                <td> {$nb_imagesTermineRegistre[0]} </td>
                <td> {$registre->Type} </td>
                <td> <strong> {$percent_preindex} % </strong></td>
                <td> ".date('d/m/Y à H:i',strtotime($registre->DateModif))." </td>
                <td> 
                    <a class=\"btn btn-primary btn-icon-split\" 
                    href=\"details_registre.php?id_registre={$registre->RegistreID}&page_sender=etatPreindex\">
                    <span class=\"icon text-white-50\">
                        <i class=\"fas fa-eye\"></i>
                    </span>
                    <span class=\"text\"> voir (+) </span>
                    </a>  
                </td>
            </tr>";
        $maxX = $maxX + 1;   
     
        // Remplissage des indicateurs de la bar grahique
        $Labels[] = "Registre : ({$registre->Numero})";
        $Data[] = $percent_preindex;
        
        if($percent_preindex < 25.00)
        {
            $Data_color[] = '#4e73df'; // blue
        }
        else if($percent_preindex < 50.00)
        {
            $Data_color[] = '#f6c23e'; // yellow
        }
        else if($percent_preindex < 75.00)
        {
            $Data_color[] = '#fd7e14'; // orange
        }
        else if($percent_preindex < 100.00)
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