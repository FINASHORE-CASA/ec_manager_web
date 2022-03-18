<?php
    // Require des données
    require_once "../../../config/checkConfig.php";    

    try 
    {
        $formData = json_decode($_POST['myData']);
        $result[] = "success" ;
        $tomeregistre_columns_locked = [ 
            "","id_lot","id_tome_registre","id_registre","id_commune","id_bureau","utilisateur_modification","utilisateur_creation","id_procureur","id_officier","date_creation","date_modification","preimprime","affecte","status","ancien_commune","ancien_bureau","nbre_correction_niv_un","nbre_correction_niv_deux"
            ];
        $update_statement = "";

        $i = 1;
        foreach(get_object_vars($formData) as $key => $prop)
        {
            if(!array_search($key,$tomeregistre_columns_locked))
            {
                if(!empty($prop) && $prop != "")
                {
                    $update_statement .= ($i == count(get_object_vars($formData))) ? $key." = '{$prop}'" : $key." = '{$prop}', " ;
                }
                else
                {
                    $update_statement .= ($i == count(get_object_vars($formData))) ? $key." = null" : $key." = null, " ;
                }
            }
            $i++;
        }              

        // Correction des num_acte 
        $nbAff = $bdd->exec("UPDATE tomeregistre SET {$update_statement} WHERE id_tome_registre = $formData->id_tome_registre");                                            
        $result[] = $nbAff; // $nbAff;                                      
        $result[] = $update_statement; // $nbAff;                                                                        
        $result[] = $formData; // $nbAff;                                                                        

        echo(json_encode($result));
    }
    catch(Exception $e)
    {
        $fail[] = "fail";
        $fail[] = $e->getMessage();
        echo(json_encode($fail));
    }
