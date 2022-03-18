<?php
    // Require des données
    require_once "../../../config/checkConfig.php";    

    try 
    {
        $formData = json_decode($_POST["myData"]);
        $result[] = "success";

        //Vérification/Création de la sauvegarde
        if($bdd_status != "undefined") 
        {
            $nameSave = $Base_url."\\fichier\\cache\\bd_save\\".$bdd_status."_".date("d_m_Y").".backup";                    

            // lancement de la sauvegarde
            if(file_exists($nameSave))
            {
                // Création de la sauvegarde
                unlink($nameSave);
            }
        }                                           
        else
        {
            // Lancement d'une notification
            $result[0] = "fail";    
            $result[1] = "BD_INEXISTANTE";
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