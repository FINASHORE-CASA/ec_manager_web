<?php

    session_start();
    // Require des données
    require_once "../../../config/checkConfig.php";  
    require_once "./schema_acte.php";  

    try 
    {
        $formData = json_decode($_POST['myData']);
        $result[] = "success" ;

        // Enregistrement de ce Contrôle;
        $qry = $bdextra->prepare("insert into acte_ctr_fina(id_acte,id_user,date_ctr,no_correction) values(?,?,NOW(),true);");
        $qry->execute(array($formData->id_acte,$_SESSION["user"]->id_user));                                 

        echo(json_encode($result));
    }
    catch(Exception $e)
    {
        $fail[] = "fail";
        $fail[] = $e->getMessage();
        echo(json_encode($fail));
    }
?>