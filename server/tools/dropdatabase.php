<?php

    // Skrypt umożliwiający skasowanie bazy danych:

    // Podłączenie pliku konfiguracyjnego:
    require_once __DIR__ . '../../settings/config.php';

    // Utworzenie obiektu PDO:
    $pdo = new PDO($dsn, $db_user, $db_password);

    // Wprowadzenie parametrów połączenia:
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Kasowanie bazy:
    try
    {
        $pdo->exec("
                        DROP DATABASE $db_name;
       
        ");
    
        // Komunikat:
        echo "Baza <b>$db_name</b> usunięta pomyślnie!<br>";
    }
    catch (PDOException $ex)
    {
        echo "Wystąpił problem z kasowaniem bazy. ".$ex->getMessage();
    }

?>