<?php
    require_once "./config/checkConfig.php";

    if(isset($_GET["id_agent"]))
    {
        // Récupération du registre et de ses indicateurs
        $qry = $bdd->prepare("SELECT *
                              FROM {$base_de_donnees}.dbo.Agents ag 
                              WHERE AgentID = ?");
        $qry->execute(array($_GET["id_agent"]));
        $agent = $qry->fetch(PDO::FETCH_OBJ);
        $qry->closeCursor();
    }