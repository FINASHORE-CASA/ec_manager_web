<?php
// Require des données
require_once "../../../config/checkConfig.php";
require_once "../../../function/handle_function.php";

try {
    $formData = json_decode($_POST['myData']);
    $result[] = "success";

    // Récupération de l'acte concerné
    $qry = $bdd->prepare("  SELECT af.id_lot,a.id_acte,num_acte,imagepath,nom_fr,prenom_fr,nom_marge_fr,prenom_marge_fr,nom_ar,prenom_ar
	                            ,nom_marge_ar,prenom_marge_ar,sexe,jd_naissance_g,md_naissance_g,ad_naissance_g,jd_naissance_h,md_naissance_h,ad_naissance_h
                                ,prenom_pere_fr,prenom_pere_ar,prenom_mere_fr,prenom_mere_ar
                                ,ascendant_pere_fr,ascendant_pere_ar,ascendant_mere_fr,ascendant_mere_ar
                                ,jd_etabli_acte_h,md_etabli_acte_h,ad_etabli_acte_h
                                ,jd_etabli_acte_g,md_etabli_acte_g,ad_etabli_acte_g
                                ,jd_etabli_acte_h,md_etabli_acte_h,ad_etabli_acte_h
                                ,jd_etabli_acte_g,md_etabli_acte_g,ad_etabli_acte_g
                                ,jd_deces_h,md_deces_h,ad_deces_h,jd_deces_g,md_deces_g,ad_deces_g
                                ,cast(a.utilisateur_creation as integer) as id_saisi_user
                                ,(select count(*) from mention m where m.id_acte = a.id_acte) as mention
                                from acte a  
                                left join deces d on d.id_acte = a.id_acte
                                inner join affectationregistre af on af.id_tome_registre = a.id_tome_registre  
                                where a.id_acte = $formData->id_acte");
    $qry->execute();
    $acte = $qry->fetch(PDO::FETCH_OBJ);

    if ($formData->is_mention_void != "0") {
        $qry = $bdd->prepare("SELECT id_acte,id_mention,txtmention from mention m where id_acte = $formData->id_acte");
        $qry->execute();
        $mentions = $qry->fetchAll(PDO::FETCH_OBJ);
    }

    // Récupération du Chemin
    $BaseCheminLot = getPathLot($acte->id_lot, $bdextra);
    $cheminLotSource = "";

    $SourceTable = $ListPathImages;

    foreach ($SourceTable as $src) {
        if (is_dir(trim($src) . "\\" . $BaseCheminLot)) {
            $cheminLotSource = trim($src) . "\\" . $BaseCheminLot;
            break;
        }
    }

    // copie de l'image vers le repertoire d'affichage   
    // $chemin = getPathLot($acte->id_lot,$bdextra,$Base_Folder);    
    $chemin = $cheminLotSource;
    $image = $acte->imagepath;
    $isCopy = "no";

    if (strpos($image, ';;') !== false) {
        $images = explode(";;", $acte->imagepath);

        foreach ($images as $img) {
            $src = $chemin . "\\" . $img;
            $dest = $Base_url . "\\" . $Temp_folder . "\\" . $acte->id_acte . "_" . $img;

            if (trim($img) != "") {
                if (!file_exists($dest)) {
                    if (file_exists($src)) {
                        if (copy($src, $dest)) {
                            $isCopy = "yes";
                        } else {
                            $isCopy = "no";
                        }
                    } else {
                        $isCopy = "no";
                    }
                } else {
                    $isCopy = "yes";
                }
            }
        }
    } else {
        $src = $chemin . "\\" . $image;
        $dest = $Base_url . "\\" . $Temp_folder . "\\" . $acte->id_acte . "_" . $image;

        if (!file_exists($dest)) {
            if (file_exists($src)) {
                if (copy($src, $dest)) {
                    $isCopy = "yes";
                } else {
                    $isCopy = "no";
                }
            } else {
                $isCopy = "no";
            }
        } else {
            $isCopy = "yes";
        }
    }

    $result[] = $acte;
    $result[] = $isCopy;
    $result[] = $Temp_folder;
    $result[] = isset($mentions) ? $mentions : [];

    echo (json_encode($result));
} catch (Exception $e) {
    $fail[] = "fail";
    $fail[] = $e->getMessage();
    echo (json_encode($fail));
}
