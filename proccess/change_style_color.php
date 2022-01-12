<?php 
    session_start();
    require_once "../config/defines.inc.php";

    if(isset($_GET['style']) && $_GET['style'] != "")
    {
        try 
        {   
            $json = json_decode(file_get_contents("../config/preferences.json"));
            $json->color = "#".$_GET['style'];
            file_put_contents("../config/preferences.json",json_encode($json));

            $_SESSION["style"] = "#".$_GET['style'];
                                    
            header("Location: ../gestion_pref_setting.php?log=1");
        }
        catch(PDOException $e)
        {
            header("Location: ../gestion_pref_setting.php?style=".$_GET['style']."&log=".$e->getMessage());
        }
    }
?>