<?php


    require_once __DIR__ . '/TokenGenerator.php';
    $TokenGenerator = new TokenGenerator();

    $token = $TokenGenerator->generateToken();
    var_dump($token);



?>