<?php
    require_once "./config/checkConfig.php";

    // Récupération registre scannée en indexation
    $qry = $bdd->prepare("SELECT * FROM {$base_de_donnees}.dbo.Agents WHERE Affectation = 4");
    $qry->execute();
    $agents_controleur = $qry->fetchAll(PDO::FETCH_OBJ);
    $qry->closeCursor();
    $maxY = 100;
    $maxX = 0;

    foreach($agents_controleur as $agent)
    { 
        // Récupération de registres attribués en Correction
        $qry = $bdd->prepare("SELECT re.*
                              FROM {$base_de_donnees}.dbo.Registres re 
                              INNER JOIN {$base_de_donnees}.dbo.Unites un ON un.UniteID = re.ID_Unite
                              INNER JOIN {$base_de_donnees}.dbo.Traitements tr ON tr.TableID = un.UniteID
                              INNER JOIN {$base_de_donnees}.dbo.Agents ag ON ag.AgentID = tr.AgentID
                              WHERE tr.TableSelect = 'Unites' AND tr.TypeTraitement = 12     AND tr.AgentID = ?");
        $qry->execute(array($agent->AgentID));        
        $registres_attribue = $qry->fetchAll(PDO::FETCH_OBJ);
        
        // Récupération de registres attribués en Correction Phase 1
        $qry = $bdd->prepare("SELECT *
                              FROM {$base_de_donnees}.dbo.Traitements tr 
                              INNER JOIN {$base_de_donnees}.dbo.Agents ag ON ag.AgentID = tr.AgentID
                              WHERE tr.TableSelect = 'Registres' AND tr.TypeTraitement = 14 AND tr.AgentID = ?");
        $qry->execute(array($agent->AgentID));        
        $registres_correction_ph1 = $qry->fetchAll(PDO::FETCH_OBJ);
        
        // Récupération de registres attribués en Correction Phase 3
        $qry = $bdd->prepare("SELECT *
                              FROM {$base_de_donnees}.dbo.Traitements tr 
                              INNER JOIN {$base_de_donnees}.dbo.Agents ag ON ag.AgentID = tr.AgentID
                              WHERE tr.TableSelect = 'Registres' AND tr.TypeTraitement = 18 AND tr.AgentID = ?");
        $qry->execute(array($agent->AgentID));        
        $registres_correction_ph2 = $qry->fetchAll(PDO::FETCH_OBJ);        
                
        if(count($registres_attribue) > 0)
        {
            $nbcont_all = count($registres_correction_ph2); // On conserve les totalement terminé uniquement
            $percent_registre_correction = number_format($nbcont_all/count($registres_attribue) * 100,2);
        }
        else
        {
            $percent_registre_correction = 0;
        }

        echo "  
                <tr>
                    <td> {$agent->Nom} </td>
                    <td> {$agent->Prenom} </td>
                    <td> {$agent->Login} </td>
                    <td> ".count($registres_attribue)." </td>
                    <td> ".count($registres_correction_ph1)." </td>
                    <td> ".count($registres_correction_ph2)." </td>
                    <td> 
                        <a class=\"btn btn-primary btn-icon-split\" 
                        href=\"details_agent.php?id_agent={$agent->AgentID}&page_sender=etatCorrection\">
                        <span class=\"icon text-white-50\">
                            <i class=\"fas fa-eye\"></i>
                        </span>
                        <span class=\"text\"> voir (+) </span>
                        </a>  
                    </td>
                </tr>";
        $maxX = $maxX + 1;   
     
        // Remplissage des indicateurs de la bar grahique
        $Labels[] = "{$agent->Login}";
        $Data[] = $percent_registre_correction; 

        if($percent_registre_correction < 25.00)
        {
            $Data_color[] = '#4e73df'; // blue
        }
        else if($percent_registre_correction < 50.00)
        {
            $Data_color[] = '#f6c23e'; // yellow
        }
        else if($percent_registre_correction < 75.00)
        {
            $Data_color[] = '#fd7e14'; // orange
        }
        else if($percent_registre_correction < 100.00)
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