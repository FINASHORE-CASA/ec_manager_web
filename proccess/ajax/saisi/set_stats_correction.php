<?php
    // Require des données
    require_once "../../../config/checkConfig.php";    

    try 
    {
        $formData = json_decode($_POST['myData']);
        $result[] = "success";    

        $qry = $bdextra->prepare("INSERT into stats_ctr_crr_acte (id_acte,id_sup_user,champs_corriges,id_saisi_user,date_ctr_crr) values(?,?,?,?,NOW());");
        $isAff = $qry->execute(array($formData->id_acte,$formData->id_sup_user,$formData->champs_corriges,$formData->id_saisi_user));

        if($isAff == true) 
        {
            $result[] = $isAff;
            echo(json_encode($result));
        }
        else
        {
            $result[0] = "fail";            
            echo(json_encode($result));
        }                                
    }
    catch(Exception $e)
    {
        $fail[0] = "fail";
        $fail[1] = $e->getMessage();
        echo(json_encode($fail));
    }
?>