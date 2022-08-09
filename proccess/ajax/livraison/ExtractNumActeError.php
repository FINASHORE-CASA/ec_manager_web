<?php
// Require des données
require_once "../../../config/checkConfig.php";

try {
    $result[] = "success";

    // Récupération des id_lots concernés        
    $qry = $bdd->prepare("  SELECT af.id_lot,id_acte,num_acte,imagepath,af.id_tome_registre,status_acte  
                                from acte a inner  
                                join affectationregistre af on af.id_tome_registre = a.id_tome_registre  
                                where af.id_lot in (select id_lot from lot where status_lot = 'A')   
                                and a.imagepath not like concat('%-',a.num_acte,'.jpg')
                                and a.imagepath not like concat('%-',a.num_acte,'_P%')
                                and a.num_acte not like '%/%'
                                union
                                SELECT af.id_lot,id_acte,num_acte,imagepath,af.id_tome_registre,status_acte  
                                from acte a inner  
                                join affectationregistre af on af.id_tome_registre = a.id_tome_registre  
                                where af.id_lot in (select id_lot from lot where status_lot = 'A') and                                
                                (a.num_acte is null or a.num_acte = '')
                                union
                                select af.id_lot,id_acte,num_acte,imagepath,af.id_tome_registre,status_acte
                                from acte a
                                inner join affectationregistre af on a.id_tome_registre = af.id_tome_registre
                                where af.id_lot in (select id_lot from lot where status_lot = 'A')  
                                and num_acte like '%/%' and
                                REGEXP_REPLACE(replace(replace(replace(replace(replace(imagepath,'NA-',''),'DE-',''),'.jpg',''),'_P1',''),'_P2',''),';;(.*);;','') <> replace(num_acte,'/','_')");

    $qry->execute();
    $listError = $qry->fetchAll(PDO::FETCH_OBJ);

    $liste_el_select;
    $nbCorr = 0;

    foreach ($listError as $lot) {
        // Recupération du bon Num_acte grâçe au imagepath
        $jpegList = explode(";", str_replace(";;", ";", $lot->imagepath));

        $num_acte = str_replace("_P2", "", str_replace("_P1", "", str_replace("DE-", "", str_replace(".jpeg", "", str_replace(".jpg", "", str_replace("NA-", "", $jpegList[0] . ''))))));

        if (str_contains($num_acte, "_")) {
            $num_acte_split = explode("_", $num_acte);
            if (count($num_acte_split) == 2) {
                // Correction automatique
                $new_num_acte = $num_acte_split[0] . "/" . $num_acte_split[1];
                $qry = $bdd->prepare("UPDATE acte set num_acte = :num_acte where id_acte = :id_acte");
                $qry->bindParam(':id_acte', $lot->id_acte);
                $qry->bindParam(':num_acte', $new_num_acte);
                $qry->execute();
                $nbCorr++;

                $liste_el_select[] = [
                    "id_lot" => $lot->id_lot, "id_acte" => $lot->id_acte, "old_num_acte" => $lot->num_acte, "num_acte" => ($num_acte_split[0] . "/" . $num_acte_split[1]), "imagepath" => $lot->imagepath, "observation" => "CORRIGE"
                ];
            } else {
                $liste_el_select[] = [
                    "id_lot" => $lot->id_lot, "id_acte" => $lot->id_acte, "old_num_acte" => $lot->num_acte, "num_acte" => $lot->num_acte, "imagepath" => $lot->imagepath, "observation" => "NON CORRIGE"
                ];
            }
        } else {
            // Correction automatique
            $qry = $bdd->prepare("UPDATE acte set num_acte = :num_acte where id_acte = :id_acte");
            $qry->bindParam(':num_acte', $num_acte);
            $qry->bindParam(':id_acte', $lot->id_acte);
            $qry->execute();
            $nbCorr++;

            $liste_el_select[] = [
                "id_lot" => $lot->id_lot, "id_acte" => $lot->id_acte, "old_num_acte" => $lot->num_acte, "num_acte" => $num_acte, "imagepath" => $lot->imagepath, "observation" => "CORRIGE"
            ];
        }
    }

    $result[] = isset($liste_el_select) ? $liste_el_select : [];

    echo (json_encode($result));
} catch (Exception $e) {
    $fail[] = "fail";
    $fail[] = $e->getMessage();
    echo (json_encode($fail));
}
