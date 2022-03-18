<?php
    // Require des données
    require_once "../../../config/checkConfig.php";    

    try 
    {
        $formData = json_decode($_POST['myData']);
        $result[] = "success" ; 
        
        // Correction des num_acte vide
        $nbAff = $bdd->exec("UPDATE acte SET status_acte = 'I',num_acte = (CASE WHEN LENGTH(concat('Num_Errone-',num_acte)) < 21 THEN concat('Num_Errone-',num_acte)
                                ELSE concat('Num_Errone-',REGEXP_REPLACE(replace(replace(replace(replace(replace(imagepath,'NA-',''),'DE-',''),'.jpg',''),'_P1',''),'_P2',''),';;(.*);;',''))
                                END)
                                WHERE id_acte in 
                                (
                                    select distinct id_acte
                                    from acte a inner  
                                    join affectationregistre af on af.id_tome_registre = a.id_tome_registre  
                                    where af.id_lot in ($formData->id_lot)
                                    and a.imagepath not like concat('%-',a.num_acte,'.%')  
                                    and a.imagepath not like concat( '%-',a.num_acte,'_%') 
                                    and a.num_acte not like '%/%'
                                    union
                                    select distinct id_acte
                                    from acte a
                                    inner join affectationregistre af on a.id_tome_registre = af.id_tome_registre
                                    where af.id_lot in ($formData->id_lot)
                                    and num_acte like '%/%' and
                                    REGEXP_REPLACE(replace(replace(replace(replace(replace(imagepath,'NA-',''),'DE-',''),'.jpg',''),'_P1',''),'_P2',''),';;(.*);;','') <> replace(num_acte,'/','_')
                                )
                                and num_acte not like '%Num_Errone%'
                                and num_acte not like '%Numero_Double%'
                                and num_acte not like '%Image_Double%'
                                and num_acte not like '%Num_Vide%'");
                                     
        $result[] = $nbAff;                                       

        echo(json_encode($result));
    }
    catch(Exception $e)
    {
        $fail[] = "fail";
        $fail[] = $e->getMessage();
        echo(json_encode($fail));
    }
?>