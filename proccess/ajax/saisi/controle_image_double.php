<?php
    // Require des données
    require_once "../../../config/checkConfig.php";    

    try 
    {
        $formData = json_decode($_POST['myData']);
        $result[] = "success" ;

        // Récupération des id_lots concernés        
        $qry = $bdd->prepare("  SELECT id_acte,num_acte,imagepath,prenom_ar,nom_ar,status_acte,id_tome_registre 
                                from acte
                                where concat(id_tome_registre,imagepath) 
                                in(
                                    select concat(a.id_tome_registre,imagepath)
                                    from acte a
                                    inner join affectationregistre af on a.id_tome_registre = af.id_tome_registre
                                    where af.id_lot in ($formData->id_lot)
                                    group by concat(a.id_tome_registre,imagepath)
                                    having count(*)>1
                                ) order by concat(id_tome_registre,imagepath)");

        $qry->execute();
        $num_image_double = $qry->fetchAll(PDO::FETCH_OBJ);   

        // Correction des num_acte vide
        // $bdd->query("UPDATE acte SET status_acte = 'I',num_acte = 'Image_Double-' + num_acte
        //              WHERE id_acte in 
        //              (  
        //                SELECT id_acte
        //                 from acte
        //                 where concat(id_tome_registre,imagepath) 
        //                 in(
        //                     select concat(a.id_tome_registre,imagepath)
        //                     from acte a
        //                     inner join affectationregistre af on a.id_tome_registre = af.id_tome_registre
        //                     where af.id_lot in ($formData->id_lot)
        //                     group by concat(a.id_tome_registre,imagepath)
        //                     having count(*)>1
        //                 ) order by concat(id_tome_registre,imagepath))");

        $result[] = $num_image_double;                                      

        echo(json_encode($result));
    }
    catch(Exception $e)
    {
        echo $e->getMessage();
    }
?>