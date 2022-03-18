<?php
    // Require des données
    require_once "../../../config/checkConfig.php";    
    require_once "../../../function/handle_function.php";    

    try 
    {
        $FormData = json_decode($_POST["myData"]);
        $result[] = "success" ;                    

        // transfert du lot 
        // Récupération du Chemin
        $BaseCheminLot = getPathLot($FormData->id_lot,$bdextra);
        $cheminLotDestination = trim($FormData->destination)."\\".$BaseCheminLot;
        $cheminLotSource = "";

        $SourceTable = explode(",",$FormData->source);

        foreach($SourceTable as $src)
        {
            if(is_dir(trim($src)."\\".$BaseCheminLot))
            {
                $cheminLotSource = trim($src)."\\".$BaseCheminLot;
                break;
            }
        }

        if(is_dir($cheminLotSource))
        {
            rcopy($cheminLotSource,$cheminLotDestination);
            $result[] = "Copie terminee";
        }
        else
        {
            $result[0] = "fail" ;                    
            $result[1] = "Chemin lot introuvable ";
            $result[2] = $cheminLotSource;
        }                                                

        echo(json_encode($result));
    }
    catch(Exception $e)
    {
        $fail[] = "fail";
        $fail[] = $e->getMessage();
        echo(json_encode($fail));
    }
?>