<?php
    // Require des données
    require_once "../../../config/checkConfig.php";    

    try 
    {
        $formData = json_decode($_POST['myData']);
        $result[] = "success" ;  

        // Correction des num_acte vide
        $nbAff = $bdd->exec("UPDATE acte SET status_acte = 'I',num_acte = (CASE WHEN LENGTH(concat('Numero_Double-',num_acte)) < 21 THEN concat('Num_Errone-',num_acte)
                                ELSE concat('Numero_Double-',REGEXP_REPLACE(replace(replace(replace(replace(replace(imagepath,'NA-',''),'DE-',''),'.jpg',''),'_P1',''),'_P2',''),';;(.*);;',''))
                                END)
                                WHERE id_acte in 
                                (  
                                    SELECT id_acte
                                    from acte
                                    where concat(id_tome_registre,num_acte) in(
                                    select concat(a.id_tome_registre,num_acte)
                                    from acte a
                                    inner join affectationregistre af on a.id_tome_registre = af.id_tome_registre
                                    where af.id_lot in ($formData->id_lot)
                                    group by concat(a.id_tome_registre,num_acte)
                                    having count(*)>1
                                    ) order by concat(id_tome_registre,num_acte)
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