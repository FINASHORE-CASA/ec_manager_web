<?php
require_once "defines.inc.php";
// Ajout des fonctions de gestion de handle function
require_once __DIR__."/../function/handle_function.php";

// Tentative de connexion à la base de données 
try 
{	
	// Récupération du nom de la base de données
	$base_de_donnees_json = "";
	if(file_exists(__DIR__."/preferences.json"))
	{
		$jsonData = json_decode(file_get_contents(__DIR__.'/preferences.json'));
		$base_de_donnees_json =  $jsonData->base_donnees_annex;
		$ListPathImages = $jsonData->list_path_images;
		// var_dumper($ListPathImages,true);
	}		
	else
	{
		$base_de_donnees_json = $base_de_donnees;
	}		

	$bdextra;
	try 
	{
		$bdextra = new PDO("pgsql:host={$nom_serveur};port=5432;dbname={$base_de_donnees_json}",  $utilisateur,  $mot_passe);	
		
	} catch(PDOException $e) 
	{
		$bdextra = new PDO("pgsql:host={$nom_serveur};port=5432;dbname={$base_de_donnees}",  $utilisateur,  $mot_passe);	
	}

	// Récupération de la base de données active
	$qry = $bdextra->prepare("SELECT pg.oid,datname,is_active_db 
								FROM pg_database pg 
								LEFT JOIN mg_db_list mg on upper(mg.nom_db) = upper(pg.datname) 
								WHERE is_active_db = cast(1 as bit)");	
	$qry->execute();
	$db_active = $qry->fetch(PDO::FETCH_OBJ);  	

	// Connexion à la base de données active
	try 
	{		
		if($db_active)
		{			
			$bdd = new PDO("pgsql:host={$nom_serveur};port=5432;dbname={$db_active->datname}",  $utilisateur,  $mot_passe);	
			$bdd_status = $db_active->datname;
		}
		else
		{
			$bdd = null;
			$bdd_status = "undefined";				
		}
	} 
	catch (PDOException $e) 
	{
		throw new Exception($e->getMessage(),1002);   
	}
}
catch(PDOException $e) 
{
	header("location: ../login.php?log=Problème de connexion à la Base de Données"); 
	// throw new Exception($e->getMessage(),1001); 
	var_dump($e);  
}