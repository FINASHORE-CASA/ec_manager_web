<?php
    // Require des données
    require_once "../../../config/checkConfig.php"; 
    require_once "./schema_acte.php";          

    try 
    {
        // $formData = json_decode($_POST['myData']);
        $formData = $_POST['myData'];
        $result[] = "success" ;
        $reqs = $formData;

        if(strtolower(substr(trim($reqs),0,6)) == "select" && 
           !str_contains(strtolower($reqs),"update") && 
           !str_contains(strtolower($reqs),"delete") && 
           !str_contains(strtolower($reqs),"insert") && 
           !str_contains(strtolower($reqs),"alter")  &&  
           !str_contains(strtolower($reqs),"modify") && 
           !str_contains(strtolower($reqs),"drop"))
        {
            //Récupération des id_lots concernés        
            $qry = $bdd->prepare($reqs);

            $qry->execute();
            $liste_acte = $qry->fetchAll(PDO::FETCH_OBJ);  

            $result[] = $liste_acte; 
        }
        else
        {
            $result[0] = "fail";
            $result[1] = "Requete non permise";    
        }

        echo(json_encode($result));
    }
    catch(Exception $e)
    {
        $fail[] = "fail";
        $fail[] = $e->getMessage();
        echo(json_encode($fail));
    }
?>