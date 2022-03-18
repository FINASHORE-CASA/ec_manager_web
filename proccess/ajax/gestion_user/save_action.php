<?php
// Require des données
require_once "../../../config/checkConfig.php";
require_once "../../../function/handle_function.php";

try {
    $formData = json_decode($_POST['myData']);
    $result[] = "success";

    //insert action
    $qry = $bdextra->prepare("INSERT into action_user_ctr (id_acte,id_lot,type_action,date_action,id_user_ctr) 
                                values(?,?,?,NOW(),?)");
    $qry->execute(array($formData->id_acte, $formData->id_lot, $formData->type_action, $formData->id_user_ctr));

    echo (json_encode($result));
} catch (Exception $e) {
    $fail[] = "fail";
    $fail[] = $e->getMessage();
    echo (json_encode($fail));
}
