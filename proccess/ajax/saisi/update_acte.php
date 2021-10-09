<?php
    // Require des données
    require_once "../../../config/checkConfig.php";    

    try 
    {
        $formData = json_decode($_POST['myData']);
        $result[] = "success" ;

        // Correction des num_acte 
        $nbAff = $bdd->exec("UPDATE acte SET num_acte = '$formData->num_acte' WHERE id_acte = $formData->id_acte");

        // Récupération des id_lots concernés        
        $qry = $bdd->prepare("  SELECT af.id_lot,id_acte,num_acte,imagepath,nom_fr,prenom_fr,nom_ar,prenom_ar
                                from acte a  
                                inner join affectationregistre af on af.id_tome_registre = a.id_tome_registre  
                                where af.id_acte in ($formData->id_acte)");

        $qry->execute();
        $update_acte = $qry->fetchAll(PDO::FETCH_OBJ);  

        $result[] = $nbAff; // $nbAff;                                      
        $result[] = $update_acte;                                     

        echo(json_encode($result));
    }
    catch(Exception $e)
    {
        $fail[] = "fail";
        $fail[] = $e->getMessage();
        echo(json_encode($fail));
    }
?>