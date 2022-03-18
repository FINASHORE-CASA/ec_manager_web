<?php
// Require des données
require_once "../../../config/checkConfig.php";
require_once "../../../function/handle_function.php";

try {
    $formData = json_decode($_POST['myData']);
    $result[] = "success";

    $qry = $bdextra->prepare("SELECT count(id_type_grant) from mg_group_user where name_group = ? and id_type_grant <> ?");
    $qry->execute(array($formData->name_group, $formData->id_type_grant));
    $group_exist = $qry->fetch();

    if ($group_exist[0] == 0) {

        $list_roles = "";
        foreach ($formData->list_role as $key => $value) {
            $list_roles .= ($key == (count($formData->list_role) - 1)) ? "{$value}" : "{$value},";
        }

        //Update User
        $qry = $bdextra->prepare("UPDATE mg_group_user SET name_group = ?,list_role = ? where id_type_grant = ?");
        $qry->execute(array($formData->name_group, $list_roles, $formData->id_type_grant));
    } else {
        $result[0] = "group_user_find";
    }

    echo (json_encode($result));
} catch (Exception $e) {
    $fail[] = "fail";
    $fail[] = $e->getMessage();
    echo (json_encode($fail));
}
