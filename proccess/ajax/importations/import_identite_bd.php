<?php
    // Require des données
    require_once "../../../config/checkConfig.php";    

    try 
    {
        $formData = json_decode($_POST['myData']);
        $result[] = "success" ;
        
        foreach($formData as $dt)
        {
            if(isset($dt->prenom_fr) && isset($dt->genre_prenom) && isset($dt->prenom_ar) 
                && $dt->prenom_fr != "" && $dt->genre_prenom != "" && $dt->prenom_ar != "")
            {
                $qry = $bdextra->prepare("SELECT COUNT(*) FROM base_ctr_acte WHERE prenom_fr = ? AND genre_prenom = ? AND prenom_ar = ?");
                $qry->execute(array($dt->prenom_fr,$dt->genre_prenom,$dt->prenom_ar));
                $bd_acte = $qry->fetch()[0];

                if($bd_acte == 0)
                {
                    $qry = $bdextra->prepare("INSERT into base_ctr_acte (prenom_fr,genre_prenom,prenom_ar,id_user_add,date_add) values(?,?,?,?,NOW());");
                    $isAff = $qry->execute(array($dt->prenom_fr,$dt->genre_prenom,$dt->prenom_ar,0));

                    if($isAff == true) 
                    {
                        $result[] = $dt->prenom_fr." inserted";
                    }
                    else
                    {
                        $result[] = $dt->prenom_fr." fail";        
                    }                                    
                }
                else
                {
                    $result[] = $dt->prenom_fr." already";        
                }
            }
        }

        echo(json_encode($result));        
    }
    catch(Exception $e)
    {
        $fail[0] = "fail";
        $fail[1] = $e->getMessage();
        echo(json_encode($fail));
    }
?>