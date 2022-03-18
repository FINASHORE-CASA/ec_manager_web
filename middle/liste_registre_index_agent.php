<?php
    require_once "./config/checkConfig.php";

    // Récupération registre scannée en indexation
    $qry = $bdd->prepare("SELECT * FROM {$base_de_donnees}.dbo.Agents WHERE Affectation = 2");
    $qry->execute();
    $agents_index = $qry->fetchAll(PDO::FETCH_OBJ);
    $qry->closeCursor();
    $maxY = 100;
    $maxX = 0;

    foreach($agents_index as $agent)
    {
        // Récupération de registres attribués 
        $qry = $bdd->prepare("SELECT re.*
                                FROM {$base_de_donnees}.dbo.Registres re 
                                INNER JOIN {$base_de_donnees}.dbo.Traitements tr ON tr.TableID = re.RegistreID
                                INNER JOIN {$base_de_donnees}.dbo.Agents ag ON ag.AgentID = tr.AgentID
                                WHERE tr.TableSelect = 'Registres' AND tr.TypeTraitement = 6 AND tr.AgentID = ?");
        $qry->execute(array($agent->AgentID));        
        $registres_attribue = $qry->fetchAll(PDO::FETCH_OBJ);
        
        // Récupération de registres en indexation 
        $qry = $bdd->prepare("SELECT re.*
                                FROM {$base_de_donnees}.dbo.Registres re 
                                INNER JOIN {$base_de_donnees}.dbo.Traitements tr ON tr.TableID = re.RegistreID
                                INNER JOIN {$base_de_donnees}.dbo.Agents ag ON ag.AgentID = tr.AgentID
                                WHERE tr.TableSelect = 'Registres' AND tr.TypeTraitement = 7 AND tr.AgentID = ?");
        $qry->execute(array($agent->AgentID));        
        $registres_en_index = $qry->fetchAll(PDO::FETCH_OBJ);
        
        // Récupération de registres Indexés 
        $qry = $bdd->prepare("SELECT re.*
                              FROM {$base_de_donnees}.dbo.Registres re 
                              INNER JOIN {$base_de_donnees}.dbo.Traitements tr ON tr.TableID = re.RegistreID
                              INNER JOIN {$base_de_donnees}.dbo.Agents ag ON ag.AgentID = tr.AgentID
                              WHERE tr.TableSelect = 'Registres' AND tr.TypeTraitement = 8 AND tr.AgentID = ?");
        $qry->execute(array($agent->AgentID));        
        $registres_index = $qry->fetchAll(PDO::FETCH_OBJ);    
        
        // Récupération du Nombre de Page à Indexer
        $qry = $bdd->prepare("SELECT COUNT(DISTINCT Im.ImageID)
                              FROM {$base_de_donnees}.dbo.Images Im 
                              INNER JOIN {$base_de_donnees}.dbo.Registres re ON Im.RegistreID = re.RegistreID
                              INNER JOIN {$base_de_donnees}.dbo.Traitements tr ON tr.TableID = re.RegistreID
                              INNER JOIN {$base_de_donnees}.dbo.Agents ag ON ag.AgentID = tr.AgentID
                              WHERE tr.TableSelect = 'Registres' AND tr.TypeTraitement = 7 AND tr.AgentID = ?");
        $qry->execute(array($agent->AgentID));        
        $nb_images = $qry->fetch();      
        
        // Récupération du Nombre de Page Indexé
        $qry = $bdd->prepare("SELECT COUNT(DISTINCT Im.ImageID)
                              FROM {$base_de_donnees}.dbo.Images Im 
                              INNER JOIN {$base_de_donnees}.dbo.Registres re ON Im.RegistreID = re.RegistreID
                              INNER JOIN {$base_de_donnees}.dbo.Traitements tr ON tr.TableID = re.RegistreID
                              INNER JOIN {$base_de_donnees}.dbo.Agents ag ON ag.AgentID = tr.AgentID
                              WHERE tr.TableSelect = 'Registres' AND tr.TypeTraitement = 7 AND tr.AgentID = ? AND Im.StatutActuel >= 3");
        $qry->execute(array($agent->AgentID));        
        $nb_images_index = $qry->fetch();    


        if($nb_images[0] > 0)
        {
            $percent_registre_index = number_format($nb_images_index[0]/$nb_images[0] * 100,2);
        }
        else
        {
            $percent_registre_index = 0;
        }

        echo "  
            <tr>
                <td> {$agent->Nom} </td>
                <td> {$agent->Prenom} </td>
                <td> {$agent->Login} </td>
                <td> ".count($registres_attribue)." </td>
                <td> ".count($registres_en_index)." </td>
                <td> ".count($registres_index)." </td>
                <td> ".$nb_images[0]." </td>
                <td> ".$nb_images_index[0]." </td>
                <td> {$percent_registre_index} % </td>
                <td> 
                    <a class=\"btn btn-primary btn-icon-split\" 
                    href=\"details_agent.php?id_agent={$agent->AgentID}&page_sender=etatIndex\">
                    <span class=\"icon text-white-50\">
                        <i class=\"fas fa-eye\"></i>
                    </span>
                    </a>  
                </td>
            </tr>";
        $maxX = $maxX + 1;   
     
        // Remplissage des indicateurs de la bar grahique
        $Labels[] = "{$agent->Login}";
        $Data[] = $percent_registre_index;

        if($percent_registre_index < 25.00)
        {
            $Data_color[] = '#4e73df'; // blue
        }
        else if($percent_registre_index < 50.00)
        {
            $Data_color[] = '#f6c23e'; // yellow
        }
        else if($percent_registre_index < 75.00)
        {
            $Data_color[] = '#fd7e14'; // orange
        }
        else if($percent_registre_index < 100.00)
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