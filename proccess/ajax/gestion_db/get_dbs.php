<?php
    // Require des données
    require_once "../../../config/checkConfig.php";    
    require_once "../../../function/handle_function.php";    

    try 
    {
        $result[] = "success" ;

        // Récupération de l'acte concerné
        $qry = $bdextra->prepare("SELECT pg.oid,datname,is_active_db FROM pg_database pg 
                                  LEFT JOIN mg_db_list mg on upper(mg.nom_db) = upper(pg.datname) 
                                  WHERE (datname NOT LIKE '%ECV_EXTRA%'
                                         AND datname NOT LIKE '%postgres%'
                                         AND datname NOT LIKE '%template%')
                                  ORDER BY is_active_db asc,datname");

        $qry->execute();
        $dbs = $qry->fetchAll(PDO::FETCH_OBJ);  
                                                   
        $result[] = $dbs;                                                                                                        

        echo(json_encode($result));
    }
    catch(Exception $e)
    {
        $fail[] = "fail";
        $fail[] = $e->getMessage();
        echo(json_encode($fail));
    }
?>