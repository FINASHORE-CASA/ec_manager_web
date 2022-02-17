<?php
    // Require des données
    require_once "../../../config/checkConfig.php";    
    require_once "../../../function/handle_function.php";    

    try 
    {        
        $formData = json_decode($_POST['myData']);
        $result[] = "success" ;

        // insert the active database
        $bdextra->exec("DELETE FROM mg_db_list;");
        $qry = $bdextra->prepare("INSERT INTO mg_db_list (oid,nom_db,is_active_db) VALUES(?,?,cast(1 as bit));");
        $qry->execute([$formData->oid,$formData->datname]);                                                                                    

        echo(json_encode($result));
    }
    catch(Exception $e)
    {
        $fail[] = "fail";
        $fail[] = $e->getMessage();
        echo(json_encode($fail));
    }
?>