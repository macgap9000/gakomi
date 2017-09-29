<?php

    $pustyObj = new stdClass();


    $obj = new stdClass();

    $obj->numberOfCities = 3;
    $obj->cities = ["Kutno", "Warszawa", "Poznan"];
    $obj->lines = [
        "Kutno-Warszawa" => 130,
        "Kutno-Poznan" => 180,
        "Warszawa-Poznan" => 320
        //,"asd" => "kupa"
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


    $c = curl_init();
    //curl_setopt($c, CURLOPT_HTTPHEADER, array('Content-Type: text/json')); 
    curl_setopt($c, CURLOPT_HEADER, 1);
    curl_setopt($c, CURLOPT_URL, 'http://localhost/gakomi/gakomi/server/api.php');
    curl_setopt($c, CURLOPT_POST, 1);
    curl_setopt($c, CURLOPT_POSTFIELDS, $json);
    curl_exec($c);





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