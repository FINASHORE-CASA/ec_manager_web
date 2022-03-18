<?php 
    try 
    {   
        $jsondata = json_decode($_POST["data"]);                        
        file_put_contents("../../../config/preferences.json",json_encode($jsondata));
        echo(json_encode($jsondata));
    }
    catch(Exception $e)
    {
        echo(json_encode(['status' => 'fail','error' => $e->getMessage()]));
        return false;
    }
?>