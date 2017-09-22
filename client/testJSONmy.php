<?php

    // $json = '{
    //     "numberOfCities": 3,
    //     "cities": [
    //       "Kutno",
    //       "Warszawa",
    //       "Poznań"
    //     ],
    //     "lines": {
    //       "Kutno-Warszawa": 10,
    //       "Kutno-Poznań": 20,
    //       "Warszawa-Poznań": 30
    //     },
    //     "initialCityName": "Kutno"
    //   }';

    // $result = json_decode($json);

    // echo "<pre>";
    // print_r($result);
    // echo "</pre>";

    
    $json = file_get_contents('http://localhost/gakomi/gakomi/client/printGET.php');
    $result = json_decode($json);
    echo "<pre>";
    print_r($result);
    echo "</pre>"; 


?>