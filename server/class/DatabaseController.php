<?php

    // Definicja klasy kontrolera bazy danych
    // (umożliwiającej dostęp do bazy danych i manipulację na danych):
    class DatabaseController
    {
        // Definicja pól/obiektów klasy:

            // Obiekt połączenia bazodanowego (PDO - PHP Data Objects):
            private $pdo;

            // Pole wskazujące na stan połączenia z bazą danych:
            private $isConnected;

            // Obiekt zamówienia:
            private $objOrder;

            // Obiekt wyniku obliczeń:
            private $objComputationResult;

        // Konstruktor obiektu:
        public function __construct()
        {
            // Utworzony obiekt!            
        }

        // Zdumpowanie danych (obiektu kontrolera bazy danych) w celach debuggowania:a
        public function debugDatabaseController()
        {
            var_dump($this);
        }

        // Zdumpowanie danych (obiektu odebranego zamówienia) w celach debuggowania:
        public function debugReceivedOrder()
        {
            var_dump($this->objOrder);
        }
        
        // Zdumpowanie danych (obiektu wyniku obliczeń) w celach debuggowania:
        public function debugComputationResult()
        {
            var_dump($this->objComputationResult);
        }

        // Metoda inicjalizacji połączenia z bazą danych:
        public function initConnection()
        {
            // Podłączenie konfiguracji bazy danych:
            require_once __DIR__ . '../../settings/config.php';

            // Podłączenie pliku klasy obiektu wyniku zarządzania na bazie danych:
            require_once __DIR__ . '/DatabaseManagementResult.php';

            // Powołanie obiektu wyniku zarządzania danymi na bazie danych:
            $objDatabaseManagementResult = new DatabaseManagementResult();

            // Przygotowanie obiektu PDO:
            try
            {
                // Utworzenie obiektu PDO:
                $this->pdo = new PDO($dsn, $db_user, $db_password);

                // Wprowadzenie parametrów połączenia:
                $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // Ponieważ nie został do tej pory przechwyony błąd połączenia,
                // ustaw flagę mówiącą o tym, że połączenie zostało nawiązane:
                $this->isConnected = true;

                // Wypełnij informację o wyniku połączenia z bazą danych:
                $objDatabaseManagementResult->success = true;
                $objDatabaseManagementResult->errorCode = "ED100";
                $objDatabaseManagementResult->message = "Nawiązano pomyślnie połączenie z bazą danych.";

                // Zwróć stosowną informację o stanie połączenia:
                return $objDatabaseManagementResult;                
            }
            catch (PDOException $ex)
            {
                // Wystąpił błąd z połączeniem z bazą danych. Ustawienie flagi:
                $this->isConnected = false;

                // Wypełnij informację o problemie w połączeniu z bazą danych:
                $objDatabaseManagementResult->success = false;
                $objDatabaseManagementResult->errorCode = "ED000";
                $objDatabaseManagementResult->message = "Problem z nawiązaniem połączenia z bazą danych.";
                
                // Jeśli chcesz dołączyć wiadomość z treścią przechwyconego wyjątku, wykorzystaj poniższą składnię:
                // echo 'Połączenie nie mogło zostać utworzone: ' . $ex->getMessage();

                // Zwróć stosowną informację o stanie połączenia:
                return $objDatabaseManagementResult;                        
            }
        }

        // Metoda zapisujące dane (zamówienie i wynik obliczeń) do bazy danych:
        public function saveToDatabase($objOrder, $objComputationResult)
        {
            // Podłączenie pliku klasy obiektu wyniku zarządzania na bazie danych:
            require_once __DIR__ . '/DatabaseManagementResult.php';

            // Powołanie obiektu wyniku zarządzania danymi na bazie danych:
            $objDatabaseManagementResult = new DatabaseManagementResult();

            // Przepisanie odebranego zamówienia
            // do pola prywatnego kontrolera bazy danych:
            $this->objOrder = $objOrder;
            // Przepisanie odebranych wyników obliczeń 
            //do pola prywatnego obiektu kontrolera bazy danych:
            $this->objComputationResult = $objComputationResult;

            // Sprawdzenie integralności odebranych obiektów:

                // Sprawdzenie czy przekazane dane do pola "objOrder"
                // faktycznie stanowią obiekt. Jeśli nie, to nie można go dalej przetwarzać:
                // Pobranie typu, jakiego jest wskazane pole "objOrder":
                $objOrder_isObject = is_object($this->objOrder);
                // Jeśli dane pole nie jest obiektem:
                if ($objOrder_isObject == false)
                {
                    // Zwróć stosowny wynik przetwarzania (błąd przetwarzania - pole obiektu zamówienia nie jest obiektem):
                    $objDatabaseManagementResult->success = false;
                    $objDatabaseManagementResult->errorCode = "ED002";
                    $objDatabaseManagementResult->message = "Problem z integralnością obiektu zamówienia podczas próby zapisu danych do bazy danych.";
                    // Zwróć obiekt wyniku obliczeń:
                    return $objDatabaseManagementResult;
                }
                // Sprawdzenie czy przekazane dane do pola "objComputationResult"
                // faktycznie stanowią obiekt. Jeśli nie, to nie można go dalej przetwarzać:
                // Pobranie typu, jakiego jest wskazane pole "objComputationResult":
                $objComputationResult_isObject = is_object($this->objComputationResult);
                // Jeśli dane pole nie jest obiektem:
                if ($objComputationResult_isObject == false)
                {
                    // Zwróć stosowny wynik przetwarzania (błąd przetwarzania - pole wyniku obliczeń nie jest obiektem):
                    $objDatabaseManagementResult->success = false;
                    $objDatabaseManagementResult->errorCode = "ED003";
                    $objDatabaseManagementResult->message = "Problem z integralnością obiektu wyniku obliczeń podczas próby zapisu danych do bazy danych.";
                    // Zwróć obiekt wyniku obliczeń:
                    return $objDatabaseManagementResult;
                }


            // Jeśli flaga poprawności została podniesiona do góry,
            // można spróbować dodać dane do bazy danych:
            if ($this->isConnected == true)
            {
                // Wykonaj próbę dodania danych do bazy danych:
                try
                {
                    // Rozpoczęcie transakcji:
                    $this->pdo->beginTransaction();

                    // KROK 1. Tworzenie w tabeli wpisu o zamówieniu:

                }
                catch (PDOException $ex)
                {

                }
            }
            else
            {
                // Jeśli uruchamia się ta część kodu, oznacza to flaga jest opuszczona i ktoś
                // próbował uruchomić procedurę dodawania danych do bazy danych
                // bez ówcześniejczego sprawdzenia czy połączenie zostało nawiązane.
                // Widać to poprzez to, że flaga była podniesiona do góry.
                // Należy zwrócić odpowiedni komunikat użytkownikowi.

                // Wypełnij informację o problemie w połączeniu z bazą danych:
                $objDatabaseManagementResult->success = false;
                $objDatabaseManagementResult->errorCode = "ED001";
                $objDatabaseManagementResult->message = "Nie zostało przetestowane połączenie z bazą danych. Należy je przetestować przed próbą dostępu do bazy.";

                // Zwróć stosowną informację o stanie łączności z bazą danych:
                return $objDatabaseManagementResult;    
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