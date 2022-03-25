<?php
// Require des données
require_once "../../../config/checkConfig.php";

try {
    $formData = json_decode($_POST['myData']);
    $result[] = "success";

    // Acte update statement
    foreach ($formData as $key => $value) {
        $update_statement_controle_acte_key[] = "{$key}";
        $update_statement_controle_acte_value[] = "{$value}";
    }

    $update_statement_controle_acte_key = isset($update_statement_controle_acte_key) ? join(", ", $update_statement_controle_acte_key) : "";
    $update_statement_controle_acte_value = isset($update_statement_controle_acte_value) ? join(", ", $update_statement_controle_acte_value) : "";

    if ($update_statement_controle_acte_key != "" && $update_statement_controle_acte_value) {
        $qry = $bdextra->prepare("INSERT into audit_acte ($update_statement_controle_acte_key) values($update_statement_controle_acte_value);");
        $qry = $qry->execute();
        $result[] = $qry;
    }

    echo (json_encode($result));
} catch (Exception $e) {
    $fail[] = "fail";
    $fail[] = $e->getMessage();
    echo (json_encode($fail));
}
