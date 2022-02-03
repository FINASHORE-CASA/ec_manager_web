<?php
    // Require des données
    require_once "../../../config/checkConfig.php";    

    try 
    {   
        $Result[] = 'success';     
        // Récupération des id_lots concernés        
        $qry = $bdd->prepare("SELECT id_lot FROM lot");    
        $qry->execute();
        $id_lots = $qry->fetchAll(PDO::FETCH_OBJ); 

        $Result[] = $id_lots;
        echo(json_encode($Result));
    }
    catch(Exception $e)
    {
        $fail[] = "fail";
        $fail[] = $e->getMessage();
        echo(json_encode($fail));
    }
?>