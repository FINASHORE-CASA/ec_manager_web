<?php
// Require des données
require_once "../../../config/checkConfig.php";
require_once "../../../function/handle_function.php";

try {
    $formData = json_decode($_POST['myData']);
    $result[] = "success";

    $fields = $formData->fields;

    // Begining of transaction
    $bdextra->beginTransaction();
    //insert action
    $qry = $bdextra->prepare("INSERT into action_user_ctr (id_acte,id_lot,type_action,date_action,id_user_ctr) 
                                values(?,?,?,NOW(),?);");
    $resInserted = $qry->execute(array($formData->id_acte, $formData->id_lot, $formData->type_action, $formData->id_user_ctr));
    if ($resInserted) {
        $qry = $bdextra->prepare("SELECT currval(pg_get_serial_sequence('action_user_ctr','id')) as id;");
        $qry->execute();
        $res = $qry->fetch(PDO::FETCH_OBJ);
        $result[0] = $res;

        if ($res) {
            // Insertion des champs
            foreach ($fields as $field) {
                $qry = $bdextra->prepare("INSERT into action_field (id_action,field,old_value,new_value) 
                                        values (?,?,?,?)");
                $qry->execute(array($res->id, $field->field, $field->old_value, $field->new_value));
            }
            $bdextra->commit();
        } else {
            $result[0] = $res;
            $bdextra->rollback();
        }
    } else {
        $bdextra->rollback();
    }

    echo (json_encode($result));
} catch (Exception $e) {
    $bdextra->rollback();
    $fail[] = "fail";
    $fail[] = $e->getMessage();
    echo (json_encode($fail));
}
