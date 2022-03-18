<?php
require_once "./proccess/ajax/liste_module_app.php";

session_start();

if (!isset($_SESSION['user'])) {
	header('Location: ./login.php');
} else {

	// Définition des règles d'accès
	$scripts_name = explode("/", $_SERVER["SCRIPT_NAME"]);

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
		$_SESSION['user']->list_role = "index,404";
		header('Location: ./index.php');
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
