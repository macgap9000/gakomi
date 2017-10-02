<?php

    // Skrypt umożliwiający wyczyszczenie bazy danych:

    // Podłączenie pliku konfiguracyjnego:
    require_once __DIR__ . '../../settings/config.php';

    // Utworzenie obiektu PDO:
    $pdo = new PDO($dsn, $db_user, $db_password);

    // Wprowadzenie parametrów połączenia:
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Wyczyszczenie bazy:
    try
    {
        $pdo->exec("
        
                        DELETE FROM $db_name.resultmileage;
                        DELETE FROM $db_name.resultroute;
                        DELETE FROM $db_name.lines;
                        DELETE FROM $db_name.cities;
                        DELETE FROM $db_name.orders;
                        
                        ALTER TABLE $db_name.resultmileage AUTO_INCREMENT=1;
                        ALTER TABLE $db_name.resultroute AUTO_INCREMENT=1;
                        ALTER TABLE $db_name.lines AUTO_INCREMENT=1;
                        ALTER TABLE $db_name.cities AUTO_INCREMENT=1;
                        ALTER TABLE $db_name.orders AUTO_INCREMENT=1;
        
        ");
    
        // Komunikat:
        echo "Czyszczenie bazy <b>$db_name</b> zakończone!<br>";
    }
    catch (PDOException $ex)
    {
        echo "Wystąpił problem z czyszczeniem bazy. ".$ex->getMessage();
    }

?>