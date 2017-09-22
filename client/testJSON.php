<?php

    $response = file_get_contents("http://api.fotka.pl/v2/cams/get?page=1&limit=10&gender=f");

    $result = json_decode($response);

    echo "<pre>";
    print_r($result);
    echo "</pre>";


?>