<?php
    require_once "./config/checkConfig.php";

    if(isset($_GET["id_registre"]) || isset($_GET["code_registre"]))
    {
        $whereclause = "";   
        $whereparam;   
        if(isset($_GET["code_registre"]))
        {
            $whereclause = "WHERE re.QrCode = ?";
            $whereparam = trim($_GET["code_registre"]);  
        } 
        else 
        {
            $whereclause = "WHERE re.RegistreID = ?";
            $whereparam = trim($_GET["id_registre"]);     
        }

        // Récupération du registre et de ses indicateurs
        $qry = $bdd->prepare("  SELECT *, re.CheminDossier as CheminDossierRegistre
                                      ,li.Numero as NumeroLivraison
                                      ,rg.Nom as NomRegion
                                      ,se.Nom as NomService
                                      ,re.Numero as NumeroRegistre
                                FROM {$base_de_donnees}.dbo.Registres re
                                INNER JOIN {$base_de_donnees}.dbo.Versements ve ON ve.VersementID = re.VersementID
                                INNER JOIN {$base_de_donnees}.dbo.Livraisons li ON li.LivraisonID = ve.LivraisonID
                                INNER JOIN {$base_de_donnees}.dbo.Services se ON se.ServiceID = li.ServiceID
                                INNER JOIN {$base_de_donnees}.dbo.Regions rg ON rg.RegionID = se.RegionID
                                {$whereclause}");
        $qry->execute(array($whereparam));
        $registre = $qry->fetch(PDO::FETCH_OBJ);
        $qry->closeCursor();

        if($registre != null)
        {
            //Récupération des agents ayant traités ce registre
            $qry = $bdd->prepare("SELECT DISTINCT TypeTraitement,ag.*,tr.DateCreation as DateActionAgent,Observation
            FROM Agents ag 
            INNER JOIN Traitements tr ON ag.AgentID = tr.AgentID
            WHERE tr.TableSelect = 'Registres' AND tr.TableID = ?
            ORDER BY tr.TypeTraitement ASC");
            $qry->execute(array($registre->RegistreID));
            $agents = $qry->fetchAll(PDO::FETCH_OBJ);
            $qry->closeCursor();
        }
        else
        {
            header("location: ".$_SERVER['HTTP_REFERER']);
        }
    }   