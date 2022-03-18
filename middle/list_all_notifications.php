<?php
    // Récupération des registres ayant des images en instance
    $qry = $bdd->prepare("SELECT re.QrCode , COUNT(im.ImageID) AS nbpageInstance,re.RegistreID, (ag.Nom+' '+ag.Prenom) AS NomAgent,ag.AgentID
                            FROM {$base_de_donnees}.dbo.Registres re
                            INNER JOIN  {$base_de_donnees}.dbo.Images im ON im.RegistreID = re.RegistreID 
                            INNER JOIN  {$base_de_donnees}.dbo.Traitements tr ON tr.TableID = re.RegistreID
                            INNER JOIN  {$base_de_donnees}.dbo.Agents ag ON ag.AgentID = tr.AgentID
                            WHERE re.StatutActuel = 2 AND im.StatutActuel = 2 AND tr.TableSelect = 'Registres' AND tr.TypeTraitement = 7
                            GROUP BY re.QrCode,re.RegistreID,ag.Nom,ag.Prenom,ag.AgentID");
    $qry->execute();
    $registres_instance_notif = $qry->fetchAll(PDO::FETCH_OBJ);
    $qry->closeCursor();

    // Récupération registre scannée en indexation
    $qry = $bdd->prepare("SELECT *,(ag.Nom+' '+ag.Prenom) AS NomAgent,ag.AgentID
                          FROM {$base_de_donnees}.dbo.Registres re
                          INNER JOIN  {$base_de_donnees}.dbo.Traitements tr ON re.RegistreID = tr.TableID 
                          INNER JOIN  {$base_de_donnees}.dbo.Agents ag ON ag.AgentID = tr.AgentID
                          WHERE StatutActuel = 3 AND TableSelect = 'Registres' 
                          AND TypeTraitement = 8
                          AND CONVERT(date,tr.DateModif,101) = CONVERT(date,GETDATE(),101)
                          ORDER BY tr.DateModif desc");
    $qry->execute();
    $registres_index_notif = $qry->fetchAll(PDO::FETCH_OBJ);
    $qry->closeCursor();

    // Nombre de Notifications
    $nb_notification = count($registres_instance_notif) + count($registres_index_notif);