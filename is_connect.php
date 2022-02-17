<?php  
	session_start();

	if (!isset($_SESSION['user']))
	{
		header('Location: ./login.php');
	} 
	else 
	{
		// Définition des règles d'accès
		$scripts_name = explode("/",$_SERVER["SCRIPT_NAME"]);
		if ($_SESSION['user']->type_grant != '0') 
		{
			switch ($scripts_name[count($scripts_name)-1]) {
				case 'gestion_db_setting.php':
					header('Location: ./404.php');
					break;
				case 'gestion_pref_setting.php':
					header('Location: ./404.php');
					break;
				case 'gestion_users.php':
					header('Location: ./404.php');
					break;
				case 'purge_lot.php':
					header('Location: ./404.php');
					break;
				case 'transfert_lot.php':
					header('Location: ./404.php');
					break;
				case 'controle_inventaire_liv.php':
					header('Location: ./404.php');
					break;
				case 'controle_general_liv.php':
					header('Location: ./404.php');
					break;
				default:
					# code...
					break;
			}

			if($_SESSION['user']->type_grant != '2')
			{
				switch ($scripts_name[count($scripts_name)-1]) {
					case 'division_lot.php':
						header('Location: ./404.php');
						break;
					default:
						# code...
						break;
				}
			}
		}

		// Chargement de la couleur de session
		if(isset($_SESSION["style"]))
		{
			$main_app_color = $_SESSION["style"];
		}
		else
		{
			// Récupération de la couleur			
            $json = json_decode(file_get_contents("config/preferences.json"));

			if(isset($json->color))
			{
				$_SESSION["style"] = $json->color; 
				$main_app_color = $_SESSION["style"];
			}
			else
			{				
				$_SESSION["style"] = "#3b2106"; 
				$json->color = $_SESSION["style"];
				file_put_contents("config/preferences.json",json_encode($json));
			}
		}
	}
?>