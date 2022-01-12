<?php
    // Require des données
    require_once "../../../config/checkConfig.php";    

    try 
    {
        $result[] = "success" ;

        // Récupération des id_lots concernés        
        $qry = $bdd->prepare("SELECT id_lot from lot where status_lot='A'");

        $qry->execute();
        $liste_lots = $qry->fetchAll(PDO::FETCH_OBJ);

        // Récupération des id_lots concernés        
        $qry = $bdextra->prepare("SELECT lot,total from lotstats");

        $qry->execute();
        $liste_extraDb = $qry->fetchAll(PDO::FETCH_OBJ);

        $liste_el_select;
        $nbLot = 0;

        foreach ($liste_lots as $lot) 
        {      
            $qry = $bdd->prepare("SELECT af.id_lot,a.num_acte,a.id_acte,a.imagepath
                                from acte a
                                inner join affectationregistre af on a.id_tome_registre = af.id_tome_registre
                                where af.id_lot = :id_lot
                                order by a.id_acte");
            $qry->bindParam(':id_lot',$lot->id_lot);
            $qry->execute();
            $listImageLot = $qry->fetchAll(PDO::FETCH_OBJ);

            // Calcul du nombre d'image dans la base
            $nbImageLot = 0;
            $doubleImage = 0;   
            foreach ($listImageLot as $ImageLot) 
            {
                $nbImageSlipt = explode(";",str_replace(";;",";",$ImageLot->imagepath));
                $nbImageLot += count(array_values(array_filter($nbImageSlipt,function($e){ return !empty(trim($e)); }))); 
                $doubleImage += ($nbImageLot > 1) ? count(array_values(array_filter($nbImageSlipt,function($e){ return !empty(trim($e)); }))) - 1 : 0;
            }  

            // récupération du nombre d'image dans les stats
			$lot_parse = $lot->id_lot;
            $currentLot = array_values(array_filter($liste_extraDb,function($e) use($lot_parse){ return $e->lot.'' == $lot_parse.''; }));
            $nbImageLotStats = 0;
            if (count($currentLot) != 0 && $currentLot[0]->total != 0)
            {
                $nbImageLotStats = $currentLot[0]->total + $doubleImage;
            }                           

            //Comparaison final avec le répertoire
            //Récupération du répertoire
            $repChemin = getFullPathLot($lot->id_lot, $bdextra,$ListPathImages);
            if (!empty($repChemin) && is_dir($repChemin))
            {
                $Dirfiles = scandir($repChemin);
                $nbImageDirectory = array_values(array_filter($Dirfiles,function($e){ return ($e != '.' && $e != '..') ;})); 
                if ((count($nbImageDirectory) - 2) != $nbImageLot  || (count($nbImageDirectory) - 2)  != $nbImageLotStats || $nbImageLot != $nbImageLotStats)
                {
                    $nbLot++;
                    $liste_el_select[] = ["id_lot" => $lot->id_lot,"nb_image_inv" => $nbImageLotStats , "nb_image_db" => $nbImageLot
                    ,"nb_image_repos" =>  count($nbImageDirectory),"chemin_lot" =>  $repChemin ,"observation" => ''];      
                }
            }
            else
            {
                $nbLot++;
                $liste_el_select[] = ["id_lot" => $lot->id_lot,"nb_image_inv" => $nbImageLotStats , "nb_image_db" => $nbImageLot
                ,"nb_image_repos" =>  "0","chemin_lot" =>  $repChemin ,"observation" => 'REPERTOIRE INTRROUVABLE'];   
            }
        }

        $result[] = isset($liste_el_select) ? $liste_el_select : [];                                              

        echo(json_encode($result));
    }
    catch(Exception $e)
    {
        $fail[] = "fail";
        $fail[] = $e->getMessage();
        echo(json_encode($fail));
    }
?>