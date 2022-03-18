<?php
    require_once "./config/checkConfig.php";

    // Récupération registre scannée en indexation
    $qry = $bdd->prepare("SELECT * 
                          FROM {$base_de_donnees}.dbo.Agents
                          ");
    $qry->execute();
    $agents = $qry->fetchAll(PDO::FETCH_OBJ);
    $qry->closeCursor();
    // $maxY = 100;
    // $maxX = 0;

    foreach($agents as $agent)
    {
        // Récupération des sessions de travail de l'utilisateur
        $qry = $bdd->prepare("SELECT *
                                FROM {$base_de_donnees}.dbo.SessionTravails 
                                WHERE AgentID = ? AND DateModif >= CONVERT(date,GETDATE(),101)
                                ORDER BY DateModif");
        $qry->execute(array($agent->AgentID));        
        $sessionsUser = $qry->fetchAll(PDO::FETCH_OBJ);

        // Définition de la première connexion
        $date_first_connex = ""; 
        $date_last_connex  = ""; 
        $is_connect = "Non";
        $is_connect_color = "danger";
        if(count($sessionsUser) > 0)
        {         
            $date_first_connex = date('d/m/Y à H\h : i\m\i\n', strtotime($sessionsUser[0]->DateDebut));   

            $indexlastDate = count($sessionsUser) - 1;

            $date_last_connex = date('d/m/Y à H\h : i\m\i\n', strtotime($sessionsUser[$indexlastDate]->DateModif));
            
            if(!empty($date_last_connex))
            {
                // Nombre de temps écoulés
                $t1 = strtotime(date('Y-m-d H:i:s', strtotime($sessionsUser[$indexlastDate]->DateModif)));
                $t2 = strtotime(date('Y-m-d H:i:s'));
                $diff = $t1 - $t2;
                $min = (3600 - $diff) / 60 ;

                if($min < 5)
                {
                    $is_connect = "Oui";
                    $is_connect_color = "success";
                }
            }                     
        }

        echo "  
            <tr>
                <td> {$agent->Nom} </td>
                <td> {$agent->Prenom} </td>
                <td> {$agent->Login} </td>
                <td> ".getAffectation($agent->Affectation)." </td>
                <td> {$date_first_connex} </td>
                <td> {$date_last_connex} </td>
                <td>  <span class=\"badge badge-{$is_connect_color}\"> {$is_connect} </span></td>
                <td> 
                    <a class=\"btn btn-warning btn-icon-split\" 
                    href=\"details_activites_agent.php?id_agent={$agent->AgentID}&page_sender=etatAgent\">
                    <span class=\"icon text-warning\" style=\"background:white;\">
                        <i class=\"fas fa-eye\"></i>
                    </span>
                    </a>  
                </td>
            </tr>";
        // $maxX = $maxX + 1;   
     
        // Remplissage des indicateurs de la bar grahique
        // $Labels[] = "{$agent->Login}";
        // $Data[] = $percent_registre_index;
    }
        
    // $maxX_json = json_encode($maxX, JSON_NUMERIC_CHECK);
    // $maxY_json = json_encode($maxY, JSON_NUMERIC_CHECK);

    // if(isset($Labels))
    // {
    //     $Labels_Json = json_encode($Labels);
    //     $Data_Json = json_encode($Data, JSON_NUMERIC_CHECK);
    // }