<?php
    if(isset($_GET["id_agent"]))
    {
        // Récupération de tous les traitements du jours de cet Agent
        $qry = $bdd->prepare("SELECT *
                              FROM {$base_de_donnees}.dbo.Traitements tr 
                              WHERE tr.AgentID = ?
                              ORDER BY DateModif DESC");
        $qry->execute(array($agent->AgentID));        
        $traitements = $qry->fetchAll(PDO::FETCH_OBJ);

        foreach($traitements as $traitement)
        {
            echo "<tr>
                    <td> ".getTypeTraitement($traitement->TypeTraitement)." </td>
                    <td> {$traitement->TableSelect} </td>
                    <td> ".date('d/m/Y à H:i', strtotime($traitement->DateCreation))." </td>                      
                    <td> ".date('d/m/Y à H:i', strtotime($traitement->DateModif))." </td>
                    <td data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"".((strlen($traitement->Observation) > 30) == true ? $traitement->Observation : "")."\"> 
                        ".shortText($traitement->Observation,30)."  
                    </td>
                  </tr>";
        }
    }