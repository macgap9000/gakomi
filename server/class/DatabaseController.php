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

            // Pole zawierające tokena:
            private $token;

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
            require __DIR__ . '../../settings/config.php';

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
                $objDatabaseManagementResult->errorCode = "ED001";
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
            // Podłączenie konfiguracji bazy danych:
            require __DIR__ . '../../settings/config.php';

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
                // Przygotowanie tokena dla przetwarzanego zamówienia:

                    // Podłączenie pliku klasy obiektu generatora tokenów:
                    require_once __DIR__ . '/TokenGenerator.php';
                    // Powołanie obiektu generatora tokenów:
                    $TokenGenerator = new TokenGenerator();
                    // Próba wygenerowania nowego tokena (bo będzie potrzebny):
                    $tokenGenerationResult = $TokenGenerator->generateToken();
                    // Jeśli token wygenerowanie tokena się powiodło, to go odbierz:
                    if ($tokenGenerationResult->success == true)
                    {
                        // Przepisz tokena do pola obiektu kontrolera:
                        $this->token = $tokenGenerationResult->token;
                    }
                    else
                    {
                        // Generowanie tokena się nie powiodło. Zwróć informację o błędzie:
                        return $tokenGenerationResult;
                    }


                // Przepisz do zwykłych zmiennych tablicowych obiekty zamówienia i wyników obliczeń.
                // Na tablicach znacznie wygodniej operuje się na danych:

                    // Przepisanie do zmiennej roboczej ilości miast:
                    $numberOfCities = (int) $this->objOrder->numberOfCities;
                    // Przepisanie do tablicy roboczej listy miast:
                    $cities = (array) $this->objOrder->cities;
                    // Przepisanie do tablicy roboczej listy połączeń (linii) między miastami:
                    $lines = (array) $this->objOrder->lines;
                    // Przepisanie do zmiennej roboczej nazwy miasta startowego:
                    $initialCityName = (string) $this->objOrder->initialCityName;
                    // Przepisanie do zmiennej roboczej wyniku (najkrótszej trasy)
                    $route = (array) $this->objComputationResult->route;
                    // Przepisanie do zmiennej roboczej wyniku (długości trasy):
                    $mileage = (double) $this->objComputationResult->mileage;
                    // Przepisanie do zmiennej roboczej przygotowanego tokena:
                    $token = (string) $this->token;


                // Wykonaj próbę dodania danych do bazy danych:
                try
                {
                    // Rozpoczęcie transakcji:
                    $this->pdo->beginTransaction();

                    // KROK 1. Tworzenie w tabeli [orders] wpisu o zamówieniu:

                        // Przygotowanie treści INSERT SQL:
                        $statement = $this->pdo->prepare("INSERT INTO $db_name.orders 
                                                            (
                                                                token, 
                                                                numberOfCities, 
                                                                initialCityName
                                                            )
                                                            VALUES 
                                                            (
                                                                '$token',
                                                                :numberOfCities,
                                                                :initialCityName
                                                            );
                                                        ");

                        // Bindowanie parametrów 
                        // (tym samym zabezpieczenie przed SQL Injection):
                        $statement->bindParam(":numberOfCities", $numberOfCities);
                        $statement->bindParam(":initialCityName", $initialCityName);

                        // Wykonanie zapytania:
                        $statement->execute();

                        // Odebranie [orderID] czyli "identyfikatora zamówienia",
                        // które zostało zapisane w bazie:
                        $orderID = (int) $this->pdo->lastInsertId();

                        // Oczyszczenie obiektu zapytania:
                        $statement = null;

                    // KROK 2. Tworzenie w tabeli [cities] wpisów będących listą miast w zamówieniu:
                    
                        // Przygotowanie treści INSERT-ów SQL, iterując po wszystkich miastach:
                        foreach($cities as $city)
                        {
                            // Przygotowanie treści INSERT SQL:
                            $statement = $this->pdo->prepare("INSERT INTO $db_name.cities 
                                                                (
                                                                    cityName, 
                                                                    orderID
                                                                )
                                                                VALUES 
                                                                (
                                                                    :cityName,
                                                                    $orderID
                                                                );
                                                            ");
                            
                            // Bindowanie parametrów
                            // (tym samym zabezpieczenie przed SQL Injection):
                            $statement->bindParam(":cityName", $city);

                            // Wykonanie zapytania:
                            $statement->execute();

                            // Oczyszczenie obiektu zapytania:
                            $statement = null;
                        }

                    // KROK 3. Tworzenie w tabeli [lines] wpisów będących listą linii (połączeń między mistami):

                        // Przygotowanie treści INSERT-ów SQL, iterując po wszystkich liniach (połączeniach):
                        foreach($lines as $line => $distance)
                        {
                            // Przygotowanie treści INSERT SQL:
                            $statement = $this->pdo->prepare("INSERT INTO $db_name.lines 
                                                                (
                                                                    lineName, 
                                                                    distanceValue, 
                                                                    orderID
                                                                )
                                                                VALUES 
                                                                (
                                                                    :lineName,
                                                                    :distanceValue,
                                                                    $orderID
                                                                );
                                                            ");
                            // Bindowanie parametrów
                            // (tym samym zabezpieczenie przed SQL Injection):
                            $statement->bindParam(":lineName", $line);
                            $statement->bindParam(":distanceValue", $distance);

                            // Wykonanie zapytania:
                            $statement->execute();

                            // Oczyszczenie obiektu zapytania:
                            $statement = null;
                        }

                    // KROK 4. Tworzenie w tabeli [resultroute] wpisów będących trasą dla komiwojażera:

                        // Przygotowanie treści INSERT-ów SQL, iterując po wszystkich miastach trasy:
                        foreach($route as $city)
                        {
                            // Przygotowanie treści INSERT SQL:
                            $statement = $this->pdo->prepare("INSERT INTO $db_name.resultroute 
                                                                (
                                                                    cityName, 
                                                                    orderID
                                                                )
                                                                VALUES 
                                                                (
                                                                    :cityName,
                                                                    $orderID
                                                                );"
                                                            );
                            
                            // Bindowanie parametrów
                            // (tym samym zabezpieczenie przed SQL Injection):
                            $statement->bindParam(":cityName", $city);

                            // Wykonanie zapytania:
                            $statement->execute();

                            // Oczyszczenie obiektu zapytania:
                            $statement = null;
                        }

                    // KROK 5. Tworzenie w tabeli [resultmileage] wpisu będącym długością trasy komiwojażera:

                        // Przygotowanie treści INSERT SQL:
                        $statement = $this->pdo->prepare("INSERT INTO $db_name.resultmileage 
                                                            (
                                                                mileageValue, 
                                                                orderID
                                                            )
                                                            VALUES 
                                                            (
                                                                :mileageValue,
                                                                $orderID
                                                            );"
                                                        );

                        // Bindowanie parametrów
                        // (tym samym zabezpieczenie przed SQL Injection):
                        $statement->bindParam(":mileageValue", $mileage);

                        // Wykonanie zapytania:
                        $statement->execute();

                        // Oczyszczenie obiektu zapytania:
                        $statement = null;

                    // Finalizacja! Wykonaj transakcję:
                    $commitStatus = $this->pdo->commit();
                    
                    // Zwróć informację o sukcesie, jeśli transakcja się udała.
                    if ($commitStatus == true)
                    {
                        // Zwróć stosowny wynik przetwarzania (zawierający m.in. token dla użytkownika):
                        $objDatabaseManagementResult->success = true;
                        $objDatabaseManagementResult->errorCode = "ED899";
                        $objDatabaseManagementResult->message = "Dane zostały pomyślnie zapisane do bazy danych.";
                        // Dołącz token dla użytkownika:
                        $objDatabaseManagementResult->token = $this->token;

                        // Zwróć obiekt wyniku transakcji:
                        return $objDatabaseManagementResult;
                    }
                }
                catch (PDOException $ex)
                {
                    // Coś poszło nie tak przy dodawaniu danych do bazy danych.
                    // Wycofaj natychmiast zmiany:
                    $this->pdo->rollBack();

                    // Zwróć informację o błędzie jako wyniku przetwarzania:
                    $objDatabaseManagementResult->success = false;
                    $objDatabaseManagementResult->errorCode = "ED898";
                    $objDatabaseManagementResult->message = "Wystąpił błąd podczas zapisu danych do bazy danych.";    
                    // Jeśli chcesz dołączyć wiadomość z treścią przechwyconego wyjątku, wykorzystaj poniższą składnię:
                    // echo 'Wystąpił błąd: ' . $ex->getMessage();

                    // Zwróć obiekt wyniku transakcji:
                    return $objDatabaseManagementResult;
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
                $objDatabaseManagementResult->errorCode = "ED004";
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