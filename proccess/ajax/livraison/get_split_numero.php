<?php
// Require des données
require_once "../../../config/checkConfig.php";

try {
    $result[] = "success";

    $qry = $bdextra->prepare("SELECT MAX(num_liv)
                          FROM (SELECT datname, try_cast_int(REPLACE(datname,'LIV','')) as num_liv 
                                FROM pg_database pg LEFT JOIN mg_db_list mg on upper(mg.nom_db) = upper(pg.datname) 
                                WHERE datname LIKE '%LIV%' 	
                          ) as data1");
    $qry->execute();
    $result[] = $qry->fetch()[0];

    echo (json_encode($result));
} catch (Exception $e) {
    $fail[] = "fail";
    $fail[] = $e->getMessage();
    echo (json_encode($fail));
}
