<?php
    // Require des données
    require_once "../../../config/checkConfig.php";    

    try 
    {
        $formData = json_decode($_POST['myData']);
        $result[] = "success" ;
        
        $qry = $bdextra->prepare("SELECT COUNT(*) FROM base_ctr_acte WHERE ((prenom_fr <> '' AND prenom_fr = ?) OR 
                                                                            (prenom_ar <> '' AND prenom_ar = ?)) 
                                                                     AND genre_prenom = ?");
        $qry->execute(array($formData->prenom_fr,$formData->prenom_ar,$formData->genre_prenom));
        $bd_acte = $qry->fetch()[0];

        if($bd_acte == 0)
        {
            $qry = $bdextra->prepare("INSERT into base_ctr_acte (prenom_fr,genre_prenom,prenom_ar,id_user_add,date_add) values(?,?,?,?,NOW());");
            $isAff = $qry->execute(array($formData->prenom_fr,$formData->genre_prenom,$formData->prenom_ar,$formData->id_user));

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
        else
        {    
            $result[0] = "exist";            
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