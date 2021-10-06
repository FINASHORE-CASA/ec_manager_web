<?php
    // Require des données
    require_once "../../../config/checkConfig.php";    

    try 
    {
        $formData = json_decode($_POST['myData']);
        $result[] = "success" ;

        // Récupération des id_lots concernés        
        $qry = $bdd->prepare("  SELECT af.id_lot,id_acte,num_acte,imagepath,af.id_tome_registre,status_acte  
                                from acte a  
                                inner join affectationregistre af on af.id_tome_registre = a.id_tome_registre  
                                where af.id_lot in ($formData->id_lot)   
                                and (a.imagepath is null or a.imagepath = '')");

        $qry->execute();
        $imagepath_vide = $qry->fetchAll(PDO::FETCH_OBJ);   

        // $bdd->exec("DELETE from acte where id_acte in (select id_acte
        //             from acte a  
        //             inner join affectationregistre af on af.id_tome_registre = a.id_tome_registre  
        //             where af.id_lot in ($formData->id_lot)   
        //             and (a.imagepath is null or a.imagepath = ''))");

        $result[] = $imagepath_vide;                                      

        echo(json_encode($result));
    }
    catch(Exception $e)
    {
        echo $e->getMessage();
    }
?>