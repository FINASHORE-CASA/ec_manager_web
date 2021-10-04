<?php

    try
    {
        require_once "./config/checkConfig.php";
    }
    catch(Exception $e)
    {
        header("location: ../login.php?log=Problème de connexion à la Base de Données"); 
        die();
    }
 
    try
    {
        // Récupération des informations du Dashboard
        $date_actuel = date("d/m/Y");

        // récupération de tous les registres
        $qry = $bdd->prepare("SELECT COUNT(*) FROM {$base_de_donnees}.dbo.Registres");
        $qry->execute();
        $nb_registre_all = $qry->fetch();
        $qry->closeCursor();
        // ---------------------------
    
        // Récupération registre scanné
        $qry = $bdd->prepare("SELECT COUNT(*) FROM {$base_de_donnees}.dbo.Registres WHERE StatutActuel >= 2");
        $qry->execute();
        $nb_registre_scan = $qry->fetch();
        $qry->closeCursor();
            
        if($nb_registre_all[0] > 0)
        {
            $percent_registre_scan = number_format($nb_registre_scan[0]/$nb_registre_all[0] * 100,2);
        }
        else
        {
            $percent_registre_scan = 0;
        }
        // ---------------------------
    
        // Récupération registre indexé
        $qry = $bdd->prepare("SELECT COUNT(*) FROM {$base_de_donnees}.dbo.Registres WHERE StatutActuel >= 3");
        $qry->execute();
        $nb_registre_index = $qry->fetch();
        $qry->closeCursor();
           
        if($nb_registre_all[0] > 0)
        {
            $percent_registre_index = number_format($nb_registre_index[0]/$nb_registre_all[0] * 100,2);
        }
        else
        {
            $percent_registre_index = number_format($nb_registre_index[0]/$nb_registre_all[0] * 100,2);
        }
        // ---------------------------


        // Nombre de Registre Total Contrôlé par Tranche
        // Récupération des Tranches
        $qry = $bdd->prepare("SELECT * FROM {$base_de_donnees}.dbo.Tranches");
        $qry->execute();
        $tranches = $qry->fetchAll(PDO::FETCH_OBJ);
        $trs_infos_ph1 = array();

        foreach($tranches as $tr)
        {
            // Consistance de la tranche en Registre
            $qry = $bdd->prepare("SELECT COUNT(DISTINCT re.RegistreID)
                FROM {$base_de_donnees}.dbo.Registres re
                INNER JOIN Unites un ON re.ID_Unite = un.UniteID
                WHERE un.TrancheID = ?");
            $qry->execute(array($tr->TrancheID));
            $vol_en_reg = $qry->fetch();
            
            // Nombre registre contrôlé en ph1
            $qry = $bdd->prepare("SELECT COUNT(DISTINCT ct.RegistreID) 
                                    FROM Controles ct
                                    INNER JOIN Registres re ON ct.RegistreID = re.RegistreID
                                    INNER JOIN Unites un ON re.ID_Unite = un.UniteID
                                    INNER JOIN Traitements tr ON tr.TableID = un.UniteID
                                    WHERE tr.TypeTraitement = 9 AND un.TrancheID = ? AND lower(tr.TableSelect) = lower('Unites')
                                    AND ct.ImageID IS NULL AND ct.SequenceID IS NULL AND PhaseControle = 1 AND re.StatutActuel > 3");
            $qry->execute(array($tr->TrancheID));
            $nb_reg_ct_ph1_tr = $qry->fetch();
            
            $tr->vol_en_reg = $vol_en_reg[0];
            $tr->nb_reg_ct_ph1_tr = $nb_reg_ct_ph1_tr[0];

            $trs_infos_ph1[] = $tr;               
        }
        // -------------------------------

        // Nombre Moyen de Registre Contrôlé   
        $qry = $bdd->prepare("SELECT COUNT(DISTINCT ct.RegistreID) 
                              FROM Controles ct
                              INNER JOIN Registres re ON ct.RegistreID = re.RegistreID
                              INNER JOIN Unites un ON re.ID_Unite = un.UniteID
                              INNER JOIN Traitements tr ON tr.TableID = un.UniteID
                              INNER JOIN StatutRegistres st ON st.RegistreID = ct.RegistreID 
                              WHERE tr.TypeTraitement = 9 AND lower(tr.TableSelect) = lower('Unites')
                              AND ct.ImageID IS NULL AND ct.SequenceID IS NULL AND PhaseControle = 1 AND re.StatutActuel > 3
                              AND CONVERT(date,st.DateDebut,101) <=  GETDATE()
                              AND CONVERT(date,st.DateDebut,101) >= (GETDATE() - 7) AND st.Code = 4 ");
        $qry->execute(array($tr->TrancheID));
        $nb_reg_all_ct_ph1_tr = $qry->fetch();

        // Nombre d'agent de contrôle
        $qry = $bdd->prepare("SELECT COUNT(DISTINCT AgentID) 
                              FROM Agents 
                              WHERE Affectation = 3");
        $qry->execute(array($tr->TrancheID));
        $nb_agent_ct = $qry->fetch();

        $nb_moyen_ct = number_format($nb_reg_all_ct_ph1_tr[0]/$nb_agent_ct[0],2);
        // -----------------------------------

        // Nombre registre contrôlé en ph1 à la date
        $qry = $bdd->prepare("SELECT COUNT(DISTINCT ct.RegistreID) 
                              FROM Controles ct
                              INNER JOIN Registres re ON ct.RegistreID = re.RegistreID
                              INNER JOIN Unites un ON re.ID_Unite = un.UniteID
                              INNER JOIN Traitements tr ON tr.TableID = un.UniteID
                              INNER JOIN StatutRegistres st ON st.RegistreID = ct.RegistreID 
                              WHERE tr.TypeTraitement = 9 AND lower(tr.TableSelect) = lower('Unites')
                              AND ct.ImageID IS NULL AND ct.SequenceID IS NULL AND PhaseControle = 1 AND re.StatutActuel > 3
                              AND CONVERT(date,st.DateDebut,101) = GETDATE() AND st.Code = 4");
        $qry->execute();
        $nb_reg_ct_ph1_today = $qry->fetch();
    }
    catch(PDOException $e)
    {
	    echo $e->getMessage();         
    }   