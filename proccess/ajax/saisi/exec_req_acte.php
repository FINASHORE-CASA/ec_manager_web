<?php
    // Require des données
    require_once "../../../config/checkConfig.php"; 
    require_once "./schema_acte.php";          

    try 
    {
        $formData = json_decode($_POST['myData']);
        $result[] = "success" ;

        //Récupération des id_lots concernés        
        $qry = $bdd->prepare($formData->reqs);

        $qry->execute();
        $liste_acte = $qry->fetchAll(PDO::FETCH_OBJ);  

        $result[] = $liste_acte;
        // $result[] = $liste_champs_actes;

        echo(json_encode($result));
    }
    catch(Exception $e)
    {
        $fail[] = "fail";
        $fail[] = $e->getMessage();
        echo(json_encode($fail));
    }
?>