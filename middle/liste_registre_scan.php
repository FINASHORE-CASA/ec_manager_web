<?php
    require_once "./config/checkConfig.php";

    // Récupération registre scannée en indexation
    $qry = $bdd->prepare("SELECT * FROM {$base_de_donnees}.dbo.Registres WHERE StatutActuel = 2");
    $qry->execute();
    $registres_scan = $qry->fetchAll(PDO::FETCH_OBJ);
    $qry->closeCursor();
    $maxY = 100;
    $maxX = 0;

    foreach($registres_scan as $registre)
    {
        // Récupération de l'agent Traitant
        $qry = $bdd->prepare("SELECT ag.*, tr.DateCreation as DateScanRegistre
                              FROM {$base_de_donnees}.dbo.Agents ag 
                              LEFT JOIN {$base_de_donnees}.dbo.Traitements tr ON ag.AgentID = tr.AgentID
                              WHERE tr.TableSelect = 'Registres' AND tr.TypeTraitement = 5 AND tr.TableID = ?");
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
        $date_fin = "";
        if($agent != null) 
        {
            $loginAgent = $agent->Login;       
            $date_fin = $agent->DateScanRegistre;
        }
        else
        {
            // Récupération du statut de Scan
            $qry = $bdd->prepare("SELECT sr.*
                                  FROM {$base_de_donnees}.dbo.Registres rg
                                  LEFT JOIN {$base_de_donnees}.dbo.StatutRegistres sr ON rg.RegistreID = sr.RegistreID
                                  WHERE sr.Code = 2");
            $qry->execute(array($registre->RegistreID));        
            $statutScan = $qry->fetch(PDO::FETCH_OBJ);
            $date_fin = $statutScan->DateDebut;            
        }

        if(!empty($date_fin))
        {
            // Nombre de jour écoulé pour la fin de l'indexation
            $date1 = date_create($registre->DateCreation);
            $date2 = date_create($date_fin);
            $nb_jours_scan = $date2->diff($date1)->format("%a");
            // $date_fin = date('d/m/Y', strtotime($tranche->date_fin_controle));
        }
        else
        {
            // Nombre de jour écoulé jusques à aujourd'hui
            $date1 = date_create($registre->DateCreation);
            $date2 = date_create(date('Y-m-d h:i:s'));
            $nb_jours_scan = $date2->diff($date1)->format("%a");
            // $date_fin = 'ND';
        }

        echo "  
            <tr>
                <td> {$loginAgent} </td>
                <td> {$registre->QrCode} </td>
                <td> {$registre->Numero} </td>
                <td> {$registre->NombrePage} </td>
                <td> {$registre->Type} </td>
                <td> {$nb_jours_scan} </td>
                <td> ".date('d/m/Y à H:i ',strtotime($date_fin))." </td>
                <td> 
                    <a class=\"btn btn-primary btn-icon-split\" 
                    href=\"details_registre.php?id_registre={$registre->RegistreID}&page_sender=etatScan\">
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
        $Data[] = $nb_jours_scan;
        $Data_color[] = '#4e73df'; // blue
    }
        
    $maxX_json = json_encode($maxX, JSON_NUMERIC_CHECK);
    $maxY_json = json_encode($maxY, JSON_NUMERIC_CHECK);

    if(isset($Labels))
    {
        $Labels_Json = json_encode($Labels);
        $Data_Json = json_encode($Data, JSON_NUMERIC_CHECK);
        $Data_color_Json = json_encode($Data_color, JSON_NUMERIC_CHECK);
    }