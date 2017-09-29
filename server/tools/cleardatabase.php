<?php

    // Skrypt umożliwiający wyczyszczenie bazy danych:

    // Podłączenie pliku konfiguracyjnego:
    require_once __DIR__ . '../../settings/config.php';

    // Utworzenie obiektu PDO:
    $pdo = new PDO($dsn, $db_user, $db_password);

    // Wprowadzenie parametrów połączenia:
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Wyczyszczenie bazy:
    $pdo->exec("
    
                    DELETE FROM gakomi.resultmileage;
                    DELETE FROM gakomi.resultroute;
                    DELETE FROM gakomi.lines;
                    DELETE FROM gakomi.cities;
                    DELETE FROM gakomi.orders;
                    
                    ALTER TABLE gakomi.resultmileage AUTO_INCREMENT=1;
                    ALTER TABLE gakomi.resultroute AUTO_INCREMENT=1;
                    ALTER TABLE gakomi.lines AUTO_INCREMENT=1;
                    ALTER TABLE gakomi.cities AUTO_INCREMENT=1;
                    ALTER TABLE gakomi.orders AUTO_INCREMENT=1;
    
    ");

    // Komunikat:
    echo "Czyszczenie bazy zakończone!";

?>