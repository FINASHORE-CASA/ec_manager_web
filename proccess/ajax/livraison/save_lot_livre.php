<?php
    // Require des données
    require_once "../../../config/checkConfig.php";    

    try 
    {
        $formData = json_decode($_POST['myData']);        
        $result[] = "success";

        $file = fopen("../../../fichier/liste/liste_lot_livre.txt","w");        
            
        fwrite($file,$formData->id_lot);
        fclose($file);     

        $result[] = $formData->id_lot;           
                
        echo(json_encode($result));
    }
    catch(Exception $e)
    {
        $fail[] = "fail";
        $fail[] = $e->getMessage();
        echo(json_encode($fail));
    }
?>