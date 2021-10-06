<?php
    // Require des données
    require_once "../../../config/checkConfig.php";    

    try 
    {
        $formData = json_decode($_POST['myData']);
        $result[] = "success" ;

        // Récupération des id_lots concernés        
        $qry = $bdd->prepare("  SELECT af.id_lot,id_acte,num_acte,imagepath,status_acte
                                from acte a
                                inner join affectationregistre af on af.id_tome_registre = a.id_tome_registre  
                                where af.id_lot in ($formData->id_lot)
                                and (num_acte is null or trim(num_acte) ='')");

        $qry->execute();
        $num_acte_vide = $qry->fetchAll(PDO::FETCH_OBJ);   

        // Correction des num_acte vide
        // $bdd->query("UPDATE acte SET status_acte = 'I',num_acte = 'Num_Vide-' + REGEXP_REPLACE(replace(replace(replace(replace(replace(imagepath,'NA-',''),'DE-',''),'.jpg',''),'_P1',''),'_P2',''),';;(.*);;','')
        //              WHERE id_acte in 
        //              (  
        //                 SELECT id_acte
        //                 from acte a
        //                 inner join affectationregistre af on af.id_tome_registre = a.id_tome_registre  
        //                 where af.id_lot in ($formData->id_lot)
        //                 and (num_acte is null or trim(num_acte) =''))");

        $result[] = $num_acte_vide;                                      

        echo(json_encode($result));
    }
    catch(Exception $e)
    {
        echo $e->getMessage();
    }
?>