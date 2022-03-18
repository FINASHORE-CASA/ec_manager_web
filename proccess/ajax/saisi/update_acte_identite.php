<?php
    // Require des données
    require_once "../../../config/checkConfig.php";    

    try 
    {
        $formData = json_decode($_POST['myData']);
        $result[] = "success" ;

        // Correction des num_acte 
        $qry = $bdd->prepare("UPDATE acte SET prenom_fr = :prenom_fr,prenom_ar = :prenom_ar
                              ,nom_fr = :nom_fr,nom_ar = :nom_ar,prenom_marge_fr = :prenom_marge_fr
                              ,prenom_marge_ar = :prenom_marge_ar,nom_marge_ar = :nom_marge_ar
                              ,nom_marge_fr = :nom_marge_fr,sexe = :sexe
                              ,jd_naissance_g = :jd_naissance_g,md_naissance_g = :md_naissance_g
                              ,ad_naissance_g = :ad_naissance_g,jd_naissance_h = :jd_naissance_h
                              ,md_naissance_h = :md_naissance_h,ad_naissance_h = :ad_naissance_h
                              ,prenom_pere_fr = :prenom_pere_fr,prenom_pere_ar = :prenom_pere_ar
                              ,prenom_mere_fr = :prenom_mere_fr,prenom_mere_ar = :prenom_mere_ar
                              WHERE id_acte = $formData->id_acte");
        $qry->bindParam(":prenom_fr",$formData->prenom_fr);        
        $qry->bindParam(":prenom_ar",$formData->prenom_ar);        
        $qry->bindParam(":nom_fr",$formData->nom_fr);        
        $qry->bindParam(":nom_ar",$formData->nom_ar);        
        $qry->bindParam(":prenom_marge_fr",$formData->prenom_marge_fr);        
        $qry->bindParam(":prenom_marge_ar",$formData->prenom_marge_ar);        
        $qry->bindParam(":nom_marge_ar",$formData->nom_marge_ar);        
        $qry->bindParam(":nom_marge_fr",$formData->nom_marge_fr);  
        $qry->bindParam(":prenom_pere_fr",$formData->prenom_pere_fr);        
        $qry->bindParam(":prenom_pere_ar",$formData->prenom_pere_ar); 
        $qry->bindParam(":prenom_mere_fr",$formData->prenom_mere_fr);        
        $qry->bindParam(":prenom_mere_ar",$formData->prenom_mere_ar);       
        $qry->bindParam(":sexe",$formData->sexe);        
        $qry->bindParam(":jd_naissance_g",$formData->jd_naissance_g);        
        $qry->bindParam(":md_naissance_g",$formData->md_naissance_g);        
        $qry->bindParam(":ad_naissance_g",$formData->ad_naissance_g);        
        $qry->bindParam(":jd_naissance_h",$formData->jd_naissance_h);        
        $qry->bindParam(":md_naissance_h",$formData->md_naissance_h);        
        $qry->bindParam(":ad_naissance_h",$formData->ad_naissance_h);        
        $Aff = $qry->execute();     

        // Récupération des id_lots concernés        
        $qry = $bdd->prepare("  SELECT af.id_lot,id_acte,num_acte,imagepath,nom_fr,prenom_fr,nom_ar,prenom_ar
                                from acte a  
                                inner join affectationregistre af on af.id_tome_registre = a.id_tome_registre  
                                where af.id_acte in ($formData->id_acte)");

        $qry->execute();
        $update_acte = $qry->fetchAll(PDO::FETCH_OBJ);  

        $result[] = $Aff; // $nbAff;                                      
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