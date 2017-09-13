<?php

    /*
        Klasa kontrolera bazy danych.
        Umożliwia dostęp do bazy danych i manipulację na danych:
    */

    // Definicja klasy:
    class DatabaseController
    {
        // Definicja pól/obiektów klasy:
        private $pdo;

        // Konstruktor obiektu:
        public function __construct()
        {
            // Podłączenie konfiguracji bazy danych:
            require_once __DIR__ . '../../settings/config.php';

            // Przygotowanie obiektu PDO:
            try
            {
                // Utworzenie obiektu PDO:
                $this->pdo = new PDO($dsn, $db_user, $db_password);
                // Wprowadzenie parametrów połączenia:
                $this->pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
                echo 'Połączenie nawiązane!';
            }
            catch (PDOException $ex)
            {
                echo 'Połączenie nie mogło zostać utworzone: ' . $ex->getMessage();
            }
        }

        // Przykładowe listowanie danych:
        public function getAllTokens()
        {
            $statement = $this->pdo->query("SELECT * FROM orders");
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } 
    }

?>