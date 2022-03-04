<?php
// Require des données
require_once "../../../config/checkConfig.php";
require_once "../../../function/handle_function.php";

try {
    $formData = json_decode($_POST['myData']);
    $result[] = "success";
    $result[] = $formData;

    $qry = $bdextra->prepare("SELECT count(id_type_grant) from mg_group_user where name_group = ?");
    $qry->execute(array($formData->name_group));
    $group_exist = $qry->fetch();

    if ($group_exist[0] == 0) {
        //Add User Roles

        $list_roles = "";
        foreach ($formData->list_role as $key => $value) {
            $list_roles .= ($key == (count($formData->list_role) - 1)) ? "{$value}" : "{$value},";
        }

        $qry = $bdextra->prepare("INSERT into mg_group_user (name_group,list_role,date_creat,date_modif) 
                                    values(?,?,NOW(),NOW())");
        $qry->execute(array($formData->name_group, $list_roles));
    } else {
        $result[0] = "role_find";
    }

    echo (json_encode($result));
} catch (Exception $e) {
    $fail[] = "fail";
    $fail[] = $e->getMessage();
    echo (json_encode($fail));
}
