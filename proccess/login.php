<?php
    session_start();

    try
    {
        require_once "../config/checkConfig.php";
    }
    catch(Exception $e)
    {
        header("location: ../login.php?log=Problème de connexion à la Base de Données"); 
        die();
    }

    if(isset($_POST['login']) && isset($_POST['mot_de_passe']))
    {        
        if($_POST['login'] == $user_login && $_POST['mot_de_passe'] == $user_mdp)
        {                        
            $_SESSION['user'] = $_POST; 
            header('Location: ../index.php');     
        }
        else
        {
            header('Location: ../login.php?log=Login ou Mot de passe incorrecte');     
        }    
    }
    else
    {
        header('Location: ../login.php?log=Veuillez bien renseigner les champs');     
    }   