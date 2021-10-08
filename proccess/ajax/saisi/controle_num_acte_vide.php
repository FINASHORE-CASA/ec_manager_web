<?php
    // Require des données
    require_once "../../../config/checkConfig.php";    

    try 
    {
        $formData = json_decode($_POST['myData']);
        $result[] = "success" ;

        // Correction des num_acte vide
        $nbAff = $bdd->exec("UPDATE acte SET status_acte = 'I',num_acte = concat('Num_Vide-',REGEXP_REPLACE(replace(replace(replace(replace(replace(imagepath,'NA-',''),'DE-',''),'.jpg',''),'_P1',''),'_P2',''),';;(.*);;',''))
                                WHERE id_acte in 
                                (  
                                    SELECT id_acte
                                    from acte a
                                    inner join affectationregistre af on af.id_tome_registre = a.id_tome_registre  
                                    where af.id_lot in ($formData->id_lot)
                                    and (num_acte is null or trim(num_acte) ='')
                                )");

        // Récupération des id_lots concernés        
        $qry = $bdd->prepare("  SELECT af.id_lot,id_acte,num_acte,imagepath,nom_fr,prenom_fr,nom_ar,prenom_ar
                                from acte a  
                                inner join affectationregistre af on af.id_tome_registre = a.id_tome_registre  
                                where af.id_lot in ($formData->id_lot)   
                                and num_acte like '%Num_Vide%' ");

        $qry->execute();
        $num_acte_vide = $qry->fetchAll(PDO::FETCH_OBJ);  

        $result[] = $nbAff; // $nbAff;                                      
        $result[] = $num_acte_vide;                                     

        echo(json_encode($result));
    }
    catch(Exception $e)
    {
        $fail[] = "fail";
        $fail[] = $e->getMessage();
        echo(json_encode($fail));
    }
?>