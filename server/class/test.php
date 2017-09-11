<?php

    require_once("../components/random_compat/lib/random.php");

    echo "<br><br><br>";
    $filename = "test.php";
    if (file_exists($filename)) {
        echo "The file $filename exists";
    } else {
        echo "The file $filename does not exist";
    }

?>