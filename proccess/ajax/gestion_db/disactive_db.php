<?php
    // Require des données
    require_once "../../../config/checkConfig.php";    
    require_once "../../../function/handle_function.php";    

    try 
    {        
        $formData = json_decode($_POST['myData']);
        $result[] = "success" ;

        // i database
        $bdextra->exec("DELETE FROM mg_db_list;");                                                                                                    

        echo(json_encode($result));
    }
    catch(Exception $e)
    {
        $fail[] = "fail";
        $fail[] = $e->getMessage();
        echo(json_encode($fail));
    }
?>