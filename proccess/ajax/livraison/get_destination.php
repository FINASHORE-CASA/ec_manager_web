<?php
    // Require des données
    require_once "../../../config/checkConfig.php";    

    try 
    {
        $result[] = "success";

        $file = fopen("../../../fichier/liste/liste_destination_transfert.txt","r");        
            
        $result[] =  fread($file,filesize("../../../fichier/liste/liste_destination_transfert.txt"));

        echo(json_encode($result));
    }
    catch(Exception $e)
    {
        $fail[] = "fail";
        $fail[] = $e->getMessage();
        echo(json_encode($fail));
    }
?>