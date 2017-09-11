<?php

    // require_once("settings/config.php");

    require_once __DIR__ . "/class/DatabaseController.php";

    $DC = new DatabaseController();
    $allOrders = $DC->getAllTokens();

    echo "<pre>";
    print_r($allOrders);
    echo "</pre>";



    require_once("class/TokenGenerator.php");

    $TG = new TokenGenerator();
    $token = $TG->getToken();
    echo $token;

?>