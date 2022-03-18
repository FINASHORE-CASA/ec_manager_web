<?php
    session_start();
    $formData = json_decode($_POST['myData']);
    $_SESSION['SideBar'] = $formData->sideBar;

    echo 'success';
?>