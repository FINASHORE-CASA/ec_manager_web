<?php
// Require des données
require_once "../../../config/checkConfig.php";

try {
    $formData = json_decode($_POST['myData']);
    $result[] = "success";

    foreach ($formData as $dt) {
        $qry = $bdextra->prepare("SELECT COUNT(*) FROM lotstats WHERE lot = ?");
        $qry->execute(array($dt->lot));
        $lot_stats = $qry->fetch()[0];

        if ($lot_stats == 0) {
            $qry = $bdextra->prepare("INSERT into lotstats (id_bec,lot,tome,indice,g,h,naissance,deces,mariage,divorce,total) 
                                          values(?,?,?,?,?,?,?,?,?,?,?);");
            $isAff = $qry->execute(array(
                $dt->id_bec, $dt->lot, $dt->tome, $dt->indice, $dt->annee_g, $dt->annee_h, $dt->naissance, $dt->deces, $dt->mariage, $dt->divorce, $dt->total
            ));

            if ($isAff == true) {
                $result[] = $dt->lot . " inserted";
            } else {
                $result[] = $dt->lot . " fail";
            }
        } else // Modification
        {
            $qry = $bdextra->prepare("UPDATE lotstats set id_bec = :id_bec, tome = :tome, indice = :indice, \"g\" = :g ,\"h\" = :h ,naissance = :naissance
                                                ,deces = :deces , mariage = :mariage , divorce = :divorce , total = :total
                                          where lot = :lot;");
            $qry->bindParam(":id_bec", $dt->id_bec);
            $qry->bindParam(":tome", $dt->tome);
            $qry->bindParam(":indice", $dt->indice);
            $qry->bindParam(":g", $dt->annee_g);
            $qry->bindParam(":h", $dt->annee_h);
            $qry->bindParam(":naissance", $dt->naissance);
            $qry->bindParam(":deces", $dt->deces);
            $qry->bindParam(":mariage", $dt->mariage);
            $qry->bindParam(":divorce", $dt->divorce);
            $qry->bindParam(":total", $dt->total);
            $qry->bindParam(":lot", $dt->lot);
            $isAff = $qry->execute();

            if ($isAff == true) {
                $result[] = $dt->lot . " update";
            } else {
                $result[] = $dt->lot . " fail";
            }
        }
    }

    echo (json_encode($result));
} catch (Exception $e) {
    $fail[0] = "fail";
    $fail[1] = $e->getMessage();
    echo (json_encode($fail));
}
