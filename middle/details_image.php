<?php
    require_once "./config/checkConfig.php";

    if(isset($_GET["id_image"]))
    {
        // Récupération des séquences
        $qry = $bdd->prepare("SELECT *
                              FROM Images 
                              WHERE ImageID = ?");
        $qry->execute(array($_GET["id_image"]));
        $image = $qry->fetch(PDO::FETCH_OBJ);
        $qry->closeCursor();

        // Récupération des séquences
        $qry = $bdd->prepare("SELECT *
                              FROM Sequences
                              WHERE ImageID = ?");
        $qry->execute(array($_GET["id_image"]));
        $sequences = $qry->fetchAll(PDO::FETCH_OBJ);
        $qry->closeCursor();
    }