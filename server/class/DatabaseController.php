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

        // Zdumpowanie danych (tokena) w celach debuggowania:
        public function debugReceivedToken()
        {
            var_dump($this->token);
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


            // Jeśli flaga poprawności nawiązania połączenia do bazy została 
            // podniesiona do góry, można spróbować dodać dane do bazy danych:
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
                    // Przepisanie do tablicy roboczej wyniku (najkrótszej trasy)
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

                        // Koniec działań kontrolera! :)
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
                // Jeśli uruchamia się ta część kodu, oznacza to, że flaga jest opuszczona 
                // i ktoś próbował uruchomić procedurę dodawania danych do bazy danych
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

        // Metoda odczytująca dane (zamówienie i wynik obliczeń) z bazy danych:
        public function readFromDatabase($token)
        {
            // Podłączenie konfiguracji bazy danych:
            require __DIR__ . '../../settings/config.php';

            // Podłączenie pliku klasy obiektu wyniku zarządzania na bazie danych:
            require_once __DIR__ . '/DatabaseManagementResult.php';
            // Powołanie obiektu wyniku zarządzania danymi na bazie danych:
            $objDatabaseManagementResult = new DatabaseManagementResult();

            // Przepisanie odebranego tokena
            // do pola prywatnego kontrolera bazy danych:
            $this->token = $token;
            
            // Sprawdzenie integralności stringa nie jest wymagane,
            // bo ten już został ówcześniej sprawdzony przez walidatora tokenów.

            // Jeśli flaga poprawności nawiązania połączenia do bazy została
            // podniesiona do góry, to można spróbować odczytać dane z bazy:
            if ($this->isConnected == true)
            {
                // Przepisanie do zmiennej roboczej przygotowanego tokena:
                $token = (string) $this->token;

                // Przygotowanie zmiennych roboczych do przechowywania odebranych danych z bazy:

                    // Tablica robocza dla całego zamówienia:
                    $orders = null;
                    // Tablica robocza dla listy miast:
                    $cities = null;
                    // Tablica robocza dla listy połączeń (linii) między miastami:
                    $lines = null;  
                    // Tablica robocza dla najkrótszej trasy dla komiwojażera:
                    $resultroute = null;
                    // Zmienna robocza dla odległości trasy dla komiwojażera:
                    $resultmileage = null;

                // Wykonaj próbę odczytu danych z bazy danych:
                try
                {
                    // Rozpoczęcie transakcji:
                    $this->pdo->beginTransaction();

                    // KROK 1. Odczytanie z tabeli [orders] wpisu o zamówieniu:

                        // Przygotowanie treści SELECT SQL:
                        $statement = $this->pdo->prepare("SELECT *
                                                          FROM $db_name.orders
                                                          WHERE (token = :token);
                                                         ");

                        // Bindowanie parametrów:
                        // (tym samym zabezpieczenie przed SQL Injection):
                        $statement->bindParam(":token", $token);

                        // Wykonanie zapytania:
                        $statement->execute();

                        // Przepisanie wyniku zapytania do tablicy:
                        $orders = $statement->fetchAll(PDO::FETCH_ASSOC);

                        // Oczyszczenie obiektu zapytania:
                        $statement = null;

                    // Dalsze kroki mają sens wtedy, gdy istnieje jakiekolwiek zamówienie.
                    // Ilość wierszy, które znalazły się w wyniku zapytania musi być większa od zera.

                    // Oblicz ilość wierszy:
                    $orders_numberOfRows = count($orders);

                    // Kontynuuj działania, jeśli ilość wierszy jest większa od zera
                    // (jeśli odnaleziono zamówienie w bazie danych):
                    if ($orders_numberOfRows > 0)
                    {
                        // Przepisanie do zmiennej identyfikatora zamówienia:
                        $orderID = $orders[0]['orderID'];                        

                        // KROK 2. Odczytanie z tabeli [cities] listy miast zamówienia:

                            // Przygotowanie treści SELECT SQL:
                            $statement = $this->pdo->query("SELECT cityName
                                                            FROM $db_name.cities
                                                            WHERE (orderID = $orderID);
                                                           ");

                            // Bindowanie niepotrzebne. Brak niebezpiecznych parametrów.

                            // Wykonanie zapytania:
                            $statement->execute();

                            // Przepisanie wyniku zapytania do tablicy:
                            $cities = $statement->fetchAll(PDO::FETCH_ASSOC);

                            // Oczyszczenie obiektu zapytania:
                            $statement = null;

                        // KROK 3. Odczytanie z tabeli [lines] listy linii (połączeń) zamówienia:

                            // Przygotowanie treści SELECT SQL:
                            $statement = $this->pdo->query("SELECT lineName, distanceValue
                                                            FROM $db_name.lines
                                                            WHERE (orderID = $orderID);
                                                           ");

                            // Bindowanie niepotrzebne. Brak niebezpiecznych parametrów.

                            // Wykonanie zapytania:
                            $statement->execute();

                            // Przepisanie wyniku zapytania do tablicy:
                            $lines = $statement->fetchAll(PDO::FETCH_ASSOC);

                            // Oczyszczenie obiektu zapytania:
                            $statement = null;                            

                        // KROK 4. Odczytanie z tabeli [resultroute] listy miast trasy wynikowej:

                            // Przygotowanie treści SELECT SQL:
                            $statement = $this->pdo->query("SELECT cityName
                                                            FROM $db_name.resultroute
                                                            WHERE (orderID = $orderID);
                                                           ");

                            // Bindowanie niepotrzebne. Brak niebezpiecznych parametrów.

                            // Wykonanie zapytania:
                            $statement->execute();

                            // Przepisanie wyniku zapytania do tablicy:
                            $resultroute = $statement->fetchAll(PDO::FETCH_ASSOC);

                            // Oczyszczenie obiektu zapytania:
                            $statement = null;

                        // KROK 5. Odczytanie z tabeli [resultmileage] długości trasy wynikowej:

                            // Przygotowanie treści SELECT SQL:
                            $statement = $this->pdo->query("SELECT mileageValue
                                                            FROM $db_name.resultmileage
                                                            WHERE (orderID = $orderID);
                                                           ");

                            // Bindowanie niepotrzebne. Brak niebezpiecznych parametrów.

                            // Wykonanie zapytania:
                            $statement->execute();

                            // Przepisanie wyniku zapytania do tablicy:
                            $resultmileage = $statement->fetchAll(PDO::FETCH_ASSOC);

                            // Oczyszczenie obiektu zapytania:
                            $statement = null;                        
                    }                  

                    // Finalizacja! Wykonaj transakcję.
                    // Status transakcji zostanie zapisany do zmiennej:
                    $commitStatus = $this->pdo->commit();
                }
                catch (PDOException $ex)
                {
                    // Poniewaz ta transakcja tylko odczytywała dane z bazy,
                    // a niczego w niej nie zmieniała, nie ma więc żadnych zmian do wycofania.
                    
                    // Transakcja nie powiodła się. Problem z odczytem z bazy.
                    // Przygotuj informację o błędzie:
                    $objDatabaseManagementResult->success = false;
                    $objDatabaseManagementResult->errorCode = "ED998";
                    $objDatabaseManagementResult->message = "Wystąpił błąd podczas odczytu z bazy danych.";
                    // Jeśli chcesz dołączyć wiadomość z treścią przechwyconego wyjątku, wykorzystaj poniższą składnię:
                    // echo 'Wystąpił błąd: ' . $ex->getMessage();

                    // Zwróć obiekt wyniku transakcji:
                    return $objDatabaseManagementResult;
                }


                // Sprawdzenie stanu transakcji. Kontynuuj jeśli zakończyła się powodzeniem:
                if ($commitStatus == true)
                {
                    // Transakcja zakończyła się. Jeśli zlecenie zostało odnalezione na podstawie
                    // dostarczonego tokenu, to zostały pobrane wszystkie informacje o zleceniu
                    // (co szczególnie ważne) w jednej transakcji.
                    
                    // Dalsze postępowanie w zależności od tego czy zostało odnalezione zlecenie:
                    if ($orders_numberOfRows > 0)
                    {
                        // Teraz należy sprawdzić integralność danych, pobranych z bazy danych.
                        // Trzeba sprawdzić czy w ogóle pobrano dane z pozostałych tabel:
                        
                            // Sprawdzenie danych pozyskanych z tabeli bazodanowej miast (tabela 'cities'):

                                // Pobranie ilości wpisów w tablicy:
                                $cities_numberOfRows = count($cities);

                                // Jeśli nie odnaleziono wpisów (ilość mniejsza od 1) to zwróć błąd:
                                if (!($cities_numberOfRows > 0))
                                {
                                    // Brak pobranych danych z tabeli bazodanowej miast (tabela 'cities').
                                    // Przygotuj informację o błędzie:
                                    $objDatabaseManagementResult->success = false;
                                    $objDatabaseManagementResult->errorCode = "ED901";
                                    $objDatabaseManagementResult->message = "Problem z integralnością danych. Nie odnaleziono danych w tabeli miast.";
                                    // Zwróć obiekt wyniku odczytu z bazy:
                                    return $objDatabaseManagementResult;
                                }

                            // Sprawdzenie danych pozyskanych z tabeli bazodanowej linii (połączeń między miastami) (tabela 'lines'):

                                // Pobranie ilości wpisów w tablicy:
                                $lines_numberOfRows = count($lines);

                                // Jeśli nie odnaleziono wpisów (ilość mniejsza od 1) to zwróć błąd:
                                if (!($lines_numberOfRows > 0))
                                {
                                    // Brak pobranych danych z tabeli bazodanowej linii (połączeń między miastmai) (tabela 'lines').
                                    // Przygotuj informację o błędzie:
                                    $objDatabaseManagementResult->success = false;
                                    $objDatabaseManagementResult->errorCode = "ED902";
                                    $objDatabaseManagementResult->message = "Problem z integralnością danych. Nie odnaleziono danych w tabeli linii (połączeń między miastami).";
                                    // Zwróć obiekt wyniku odczytu z bazy:
                                    return $objDatabaseManagementResult;
                                }

                            // Sprawdzenie danych pozyskanych z tabeli bazodanowej trasy wynikowej (tabela 'resultroute'):

                                // Pobranie ilości wpisów w tablicy:
                                $resultroute_numberOfRows = count($resultroute);

                                // Jeśli nie odnaleziono wpisów (ilość mniejsza od 1) to zwróć błąd:
                                if (!($resultroute_numberOfRows > 0))
                                {
                                    // Brak pobranych danych z tabeli bazodanowej trasy wynikowej (tabela 'resultroute').
                                    // Przygotuj informację o błędzie:
                                    $objDatabaseManagementResult->success = false;
                                    $objDatabaseManagementResult->errorCode = "ED903";
                                    $objDatabaseManagementResult->message = "Problem z integralnością danych. Nie odnaleziono danych w tabeli trasy wynikowej.";
                                    // Zwróć obiekt wyniku odczytu z bazy:
                                    return $objDatabaseManagementResult;
                                }

                            // Sprawdzenie danych pozyskanych z tabeli bazodanowej długości trasy wynikowej (tabela 'resultmileage'):
                            
                                // Pobranie ilości wpisów w tablicy:
                                $resultmileage_numberOfRows = count($resultmileage);
                                
                                // Jeśli nie odnaleziono wpisów (ilość mniejsza od 1) to zwróć błąd:
                                if (!($resultmileage_numberOfRows > 0))
                                {
                                    // Brak pobranych danych z tabeli bazodanowej długości trasy wynikowej (tabela 'resultmileage').
                                    // Przygotuj informację o błędzie:
                                    $objDatabaseManagementResult->success = false;
                                    $objDatabaseManagementResult->errorCode = "ED904";
                                    $objDatabaseManagementResult->message = "Problem z integralnością danych. Nie odnaleziono danych w tabeli długości trasy wynikowej.";
                                    // Zwróć obiekt wyniku odczytu z bazy:
                                    return $objDatabaseManagementResult;
                                }
                                
                        // Żądane dane odnalezione zlecenie w bazie danych. 
                        // Integralność danych potwierdzona.
                        // Pora opakować odebrane dane w obiekt typu "paczka" (bundle):

                        // Podłączenie pliku klasy obiektu paczki:
                        require_once __DIR__ . '/Bundle.php';
                        // Powołanie obiektu paczki:
                        $Bundle = new Bundle();

                        // Uzupełnianie obiektu pobranymi danymi:

                            // Wpisanie do obiektu zdefiniowanej ilości miast (numberOfCities):
                            $Bundle->numberOfCities = $orders[0]['numberOfCities'];
                        
                            // Wpisanie do obiektu listy miast:
                            foreach ($cities as $city)
                            {
                                // Dodaj kolejne miasto do listy:
                                $Bundle->cities[] = $city['cityName'];
                            }

                            // Wpisanie do obiektu listy linii (połączeń między miastami):
                            foreach ($lines as $line)
                            {
                                // Pobranie nazwy miasta:
                                $cityName = $line['lineName'];
                                // Pobranie długości linii:
                                $distanceValue = $line['distanceValue'];

                                // Dodaj kolejną linię do listy:
                                $Bundle->lines[$cityName] = $distanceValue;
                            }

                            // Wpisanie do obiektu nazwy miasta startowego:
                            $Bundle->initialCityName = $orders[0]['initialCityName'];

                            // Wpisanie do obiektu miast trasy dla komiwojażera:
                            foreach ($resultroute as $city)
                            {
                                // Dodaj kolejne miasto do listy:
                                $Bundle->route[] = $city['cityName'];
                            }

                            // Wpisanie do obiektu długości trasy dla komiwojażera:
                            $Bundle->mileage = $resultmileage[0]['mileageValue'];
                        

                        // Zwrócenie użytkownikowi informacji o sukcesie i wyników odczytu z bazy:
                        // Przygotowanie informacji:
                        $objDatabaseManagementResult->success = true;
                        $objDatabaseManagementResult->errorCode = "ED999";
                        $objDatabaseManagementResult->message = "Dane zostały pomyślnie odczytane z bazy danych.";
                        // Dołącz obiekt paczki:
                        $objDatabaseManagementResult->bundle = $Bundle;

                        // Zwróć obiekt użytkownikowi:
                        return $objDatabaseManagementResult;

                        // Koniec działań kontrolera! :)
                    }
                    else
                    {
                        // Zlecenie nie zostalo odnalezione w bazie danych.
                        // Przygotuj informację o tym fakcie dla użytkownika:
                        $objDatabaseManagementResult->success = false;
                        $objDatabaseManagementResult->errorCode = "ED900";
                        $objDatabaseManagementResult->message = "Wybrany token nie został odnaleziony w bazie danych.";

                        // Zwróć obiekt wyniku transakcji:
                        return $objDatabaseManagementResult;
                    }
                }
                else
                {
                    // Transakcja nie powiodła się. Problem z odczytem z bazy.
                    // Przygotuj informację o błędzie:
                    $objDatabaseManagementResult->success = false;
                    $objDatabaseManagementResult->errorCode = "ED998";
                    $objDatabaseManagementResult->message = "Wystąpił błąd podczas odczytu z bazy danych.";

                    // Zwróć obiekt wyniku transakcji:
                    return $objDatabaseManagementResult;                    
                }
            }
            else
            {
                // Jeśli uruchamia się ta część kodu, oznacza to, że flaga jest opuszczona
                // i ktoś próbował uruchomić procedurę odczytu danych z bazy danych
                // bez ówcześniejszego sprawdzenia czy połączenie zostało nawiązane.
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
    }

?>