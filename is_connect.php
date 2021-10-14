<?php  
	session_start();

	if (!isset($_SESSION['user']))
	{
		header('Location: ./login.php');
	} 
	else 
	{
		// Définition des règles d'accès
		if ($_SESSION['user']->type_grant != '0') {
			switch ($_SERVER["SCRIPT_NAME"]) {
				case '/gestion_users.php':
					header('Location: ./404.php');
					break;
				default:
					# code...
					break;
			}
		}
	}
?>