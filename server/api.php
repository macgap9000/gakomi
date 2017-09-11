<?php

    require_once("settings/config.php");

    try
    {
        $pdo = new PDO($dsn, $db_user, $db_password);
        echo "Połączenie nawiązane!";
    }
    catch (PDOException $ex)
    {
        echo 'Połączenie nie mogło zostać utworzone: ' . $ex->getMessage();
    }

?>