<?php

// -- Version App
$version = "v.1.2";

// -- Nom De L'Application
$app_name = "ec manager web ".$version;

// -- Adresse du serveur
$nom_serveur = "";

// -- Chemin Base des Images
$Base_Folder = "C:\\images\\actesScanes\\72"; 

// -- Chemin Enregistrement des fichiers de sorties
$Download_Folder = "\\fichier\cache\global";

// -- Chemin de L'Application
$Base_url = "C:\\laragon\\www\\ec_manager_web";

// -- Chemin Enregistrement des Images Temporaires
$Temp_folder = "fichier\\cache\\tempimage";

// -- Nom de la base de Données D'Accès
$base_de_donnees = "ECV_EXTRA_N";

// -- Nom d'utilisateur du Serveur
$utilisateur = "";

// Mot de Passe du Serveur
$mot_passe = '';

// Nombre de Lot de Répartition
$nb_lots_repartis = 10;

// Chemin installation pgsql
$path_bin_pgsql = "C:\\Program Files\\PostgreSQL\\12\\bin";

// Chemin Livraison
$ListPathImages = [
    "C:\\images\\actesScanes\\72",
    "C:\\images\\Final\\72"
];

// Chemin Destination 
$RacinePathDestination = "D:\\";

// Desactivation des warning deprecated version PHP : 8.0
// error_reporting(E_ALL ^ E_DEPRECATED);

Header('Access-Control-Allow-Origin', 'http://ec_manager_web.by/');
//Header('Access-Control-Allow-Origin', '*');