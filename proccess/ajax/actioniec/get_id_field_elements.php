<?php
// Require des données
require_once "../../../config/checkConfig.php";

try {
    $result[] = "success";

    //Récupération des nationalites    
    $qry = $bdd->prepare("  SELECT id_nationalite,nationalite from nationalite ");
    $qry->execute();
    $nationalies = $qry->fetchAll(PDO::FETCH_OBJ);

    //Récupération des professions
    $qry = $bdd->prepare("SELECT id_profession,profession from profession ");
    $qry->execute();
    $professions = $qry->fetchAll(PDO::FETCH_OBJ);

    //Récupération des oec (officiers)
    $qry = $bdd->prepare("SELECT id_officier,nom_officier_ar from oec");
    $qry->execute();
    $officiers = $qry->fetchAll(PDO::FETCH_OBJ);

    //Récupération des villes
    $qry = $bdd->prepare("SELECT id_ville,lib_ville from ville");
    $qry->execute();
    $villes = $qry->fetchAll(PDO::FETCH_OBJ);

    $result[] = isset($nationalies) ? $nationalies : [];
    $result[] = isset($professions) ? $professions : [];
    $result[] = isset($officiers) ? $officiers : [];
    $result[] = isset($villes) ? $villes : [];

    echo (json_encode($result));
} catch (Exception $e) {
    $fail[] = "fail";
    $fail[] = $e->getMessage();
    echo (json_encode($fail));
}
