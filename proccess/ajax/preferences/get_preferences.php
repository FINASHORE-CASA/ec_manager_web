<?php 
    try 
    {                   
        if(file_exists("../../../config/preferences.json"))
        {
            $jsonData = json_decode(file_get_contents("../../../config/preferences.json"));            
            echo(json_encode($jsonData));
        }
        else
        {
            echo(json_encode(['status' => 'fail','error' => "fichier introuvable"]));
            return false;
        }
    }
    catch(Exception $e)
    {
        echo(json_encode(['status' => 'fail','error' => $e->getMessage()]));
        return false;
    }
?>