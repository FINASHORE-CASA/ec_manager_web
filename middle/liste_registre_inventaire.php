<?php
    require_once "./config/checkConfig.php";

    // Récupération registre scannée en indexation
    $qry = $bdd->prepare("SELECT * FROM {$base_de_donnees}.dbo.Registres");
    $qry->execute();
    $registres_index = $qry->fetchAll(PDO::FETCH_OBJ);
    $qry->closeCursor();
    $maxY = 100;
    $maxX = 0;

    foreach($registres_index as $registre)
    {
        // Pourcentage Index à calculer !!!
        // Récupération du nombre d'image du registre terminé
        $qry = $bdd->prepare("SELECT COUNT(*) FROM {$base_de_donnees}.dbo.Images 
        WHERE RegistreID = {$registre->RegistreID}");
        $qry->execute();
        $nb_imagesTermineRegistre = $qry->fetch();
        
        if($nb_imagesTermineRegistre == null)
            $nb_imagesTermineRegistre[0] = 0;

        $nb_imagesRegistre = $registre->NombrePage;

        // Détermination de la durée du registre de la création jusques à la fin de l'indexation
        $date_fin = "";
        if($registre->StatutActuel >= 3)
        {
            // Récupération de la date de fin d'indexation            
            $qry = $bdd->prepare("SELECT DateDebut FROM {$base_de_donnees}.dbo.StatutRegistres 
            WHERE RegistreID = {$registre->RegistreID} AND Code = 3");
            $qry->execute();
            $date_fin = $qry->fetch()[0];
        }

        if(!empty($date_fin))
        {
            // Nombre de jour écoulé pour la fin de l'indexation
            $date1 = date_create($registre->DateCreation);
            $date2 = date_create($date_fin);
            $nb_jours_index = $date2->diff($date1)->format("%a");
            // $date_fin = date('d/m/Y', strtotime($tranche->date_fin_controle));
        }
        else
        {
            // Nombre de jour écoulé jusques à aujourd'hui
            $date1 = date_create($registre->DateCreation);
            $date2 = date_create(date('Y-m-d'));
            $nb_jours_index = $date2->diff($date1)->format("%a");
            // $date_fin = 'ND';
        }


        echo "  
            <tr>
                <td> {$registre->QrCode} </td>
                <td> {$registre->Numero} </td>
                <td> {$registre->NombrePage} </td>
                <td> {$registre->Type} </td>
                <td> ".getStatutRegistre($registre->StatutActuel)." </td>
                <td> ".date('d/m/Y', strtotime($registre->DateCreation))." </td>
                <td> ".date('d/m/Y', strtotime($registre->DateModif))." </td>
                <td> {$nb_jours_index} </td>
                <td> 
                    <a class=\"btn btn-primary btn-icon-split\" 
                    href=\"details_registre.php?id_registre={$registre->RegistreID}&page_sender=etatInventaire\">
                    <span class=\"icon text-white-50\">
                        <i class=\"fas fa-eye\" style=\"color:#fff;\"></i>
                    </a>  
                </td>
            </tr>";
        $maxX = $maxX + 1;   
     
        // Remplissage des indicateurs de la bar grahique
        $Labels[] = "Registre : ({$registre->Numero})";
        $Data[] = $nb_jours_index;
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