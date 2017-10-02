<?php

    // Skrypt umożliwiający instalację bazy danych:

    // Podłączenie pliku konfiguracyjnego:
    require_once __DIR__ . '../../settings/config.php';

    // Nadpisanie domyślnego DSN:
    $dsn = "mysql:host=$db_hostaddress;port=$db_port;charset=utf8;";

    // Utworzenie obiektu PDO:
    $pdo = new PDO($dsn, $db_user, $db_password);

    // Wprowadzenie parametrów połączenia:
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Podłączenie treści SQL instalującej bazę:
    $SQL_location = __DIR__ . '/installdatabase_withoutcomments.sql';
    $SQL_command = file_get_contents($SQL_location);

    // Instalacja bazy:
    try
    {
        $pdo->beginTransaction();
        $pdo->exec("CREATE DATABASE $db_name;");
        $pdo->exec("USE $db_name");
        $pdo->exec($SQL_command);
        $pdo->commit();

        // Komunikat:
        echo "Baza <b>$db_name</b> zainstalowana!<br>";
    }
    catch (PDOException $ex)
    {
        $pdo->rollBack();
        echo "Wystąpił problem z instalacją bazy. ".$ex->getMessage();
    }

?>