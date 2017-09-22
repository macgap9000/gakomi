<?php


    $obj = new stdClass();

    $obj->numberOfCities = 3;
    $obj->cities = ["Kutno", "Warszawa", "Poznan"];
    $obj->lines = [
        "Kutno-Warszawa" => 10,
        "Kutno-Poznan" => 20,
        "Warszawa-Poznan" => 30
    ];
    $obj->initialCityName = "Kutno";

    // echo "<pre>";
    // print_r($obj);
    // echo "</pre>";

    $json = json_encode($obj, JSON_PRETTY_PRINT);
    // echo "<pre>";
    // print_r($json);
    // echo "</pre>";
echo $json;

    /////////////////////////////////
    $query = http_build_query($obj);
    $query_humanreadable = urldecode($query);
    echo "<br><br><br>";
    echo "<b>Query:</b> ".$query_humanreadable;
    echo "<br><br>";
    echo '<a href="printGET.php?'.$query.'">link</a>';

?>

<?php

    class Order
    {
        public $numberOfCities;
        public $cities = [];
        public $lines = [];
        public $initialCityName;
    }

?>