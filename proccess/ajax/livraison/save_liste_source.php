<?php
    // Require des données
    require_once "../../../config/checkConfig.php";    

    try 
    {
        $formData = json_decode($_POST['myData']);        
        $result[] = "success";

        $file = fopen("../../../fichier/liste/liste_source.txt","w");        
            
        fwrite($file,$formData->liste_source);
        fclose($file);     

        $result[] = $formData->liste_source;           
                
        echo(json_encode($result));
    }
    catch(Exception $e)
    {
        $fail[] = "fail";
        $fail[] = $e->getMessage();
        echo(json_encode($fail));
    }
?>