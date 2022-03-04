<?php
session_start();

if (!isset($_SESSION['user'])) {
	header('Location: ./login.php');
} else {


	// Définition des modules de la sidebar
	$modules_saisie = [
		"saisie_controle_acte_lot" => "Contrôle Acte", "correction_acte" => "Correction Acte", "correction_reqs" => "Vigilance", "correction_acte" => "Correction Acte",
		"initialisation_lot" => "Initialisation d'un Lot", "validation_lot" => "Validation Lot", "division_lot" => "Division Lot"
	];
	$modules_action_IEC = ["saisie_controle_acte_lot_auto" => " Contrôle IEC", "actioniec_controle_unitaire" => "Contrôle Unitaire"];
	$modules_action_OEC_POPF = ["controle_oec_popf" => "Contrôle OEC-POPF"];
	$modules_livraison = [
		"purge_lot" => "Purge Lot", "controle_inventaire_liv" => "Contrôle Inventaire", "controle_general_liv" =>
		"Contrôle Général", "transfert_lot" => "Transfert Lot", "split_bd" => "Split BD",
		"livraison_extraction_stats" => "Extraction BD"
	];
	$modules_gestion_ecm = [
		"gestion_users" => "Gestion utilisateur", "gestion_group_users" => "Gestion Group Util.", "gestion_db_setting" => "Gestion BD",
		"gestion_pref_setting" => "Préférences", "gestion_impotation_setting" => "Gestion Importation", "script_gestion" => "Script de gestion"
	];

	// Définition des règles d'accès
	$scripts_name = explode("/", $_SERVER["SCRIPT_NAME"]);
	// var_dump($scripts_name);
	// die();

	if (isset($_SESSION['user']->list_role)) {
		$array_fake[0] = "";
		$list_roles = explode(",", $_SESSION['user']->list_role);
		$list_roles[] = "index";
		$list_roles[] = "404";

		// utilisé pour ajouter un element vide pour le array_search
		$list_roles = array_merge($array_fake, $list_roles);

		if (!array_search(str_replace(".php", "", $scripts_name[count($scripts_name) - 1]), $list_roles)) {
			header('Location: ./404.php');
		}
	} else {
		header('Location: ./404.php');
	}

	// Chargement de la couleur de session
	if (isset($_SESSION["style"])) {
		$main_app_color = $_SESSION["style"];
	} else {
		// Récupération de la couleur			
		$json = json_decode(file_get_contents("config/preferences.json"));

		if (isset($json->color)) {
			$_SESSION["style"] = $json->color;
			$main_app_color = $_SESSION["style"];
		} else {
			$_SESSION["style"] = "#3b2106";
			$json->color = $_SESSION["style"];
			file_put_contents("config/preferences.json", json_encode($json));
		}
	}
}
