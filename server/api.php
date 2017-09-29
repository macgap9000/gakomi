<?php

    /* 
        Gakomi Projekt - serwer

        API.php 
        - główny plik "frontowy" aka kontroler do web serwisu,
        odpowiedzialny za realizację zgłoszonych żądań, zleceń obliczeń, itp.
    */

    // Podłączanie zalezności:
    
        // Walidator danych wejściowych:
        require_once __DIR__ . "/class/OrderValidator.php";

        // Obliczeniowiec komiwojażera:
        require_once __DIR__ . "/class/OrderComputer.php";

        // Kontroler bazy danych:
        require_once __DIR__ . "/class/DatabaseController.php";

    // Ustawienie stałej wersji protokołu HTTP:
    define("HTTP_VERSION", "HTTP/1.1");

    // Odbieranie konkretnych żądań na podstawie użytej metody HTTP:
    // POST - przyjmuje zlecenie (order) na wykonanie obliczeń trasy komiwojażera
    // GET - zwraca wyniki obliczeń na podstawie dostarczonego tokena

    // Przepisanie nazwy rodzaju metody HTTP (tzw. "verb") (np. GET, POST):
    $verb = $_SERVER['REQUEST_METHOD'];

    // Wybór dalszego działania w zależności od wybranej metody:
    switch($verb)
    {
        // Wybrano metodę HTTP typu POST. Użytkownik więc chciałby
        // zlecić API wykonanie obliczeń problemu komiwojażera
        // (znalezienie najkrótszej możliwej trasy):
        case "POST":

            // Przepisanie do zmiennej zawartości POST (odebranych danych):
            $postBody = file_get_contents("php://input");

            // Ponieważ odebrane dane były zapisane w formacie JSON, należy
            // je teraz zdekodować i utworzyć na podstawie ich obiekt:
            $objOrder = json_decode($postBody);

            // Przekazanie obiektu zamówienia (zawierającego dostarczone dane)
            // do obiektu, który zajmie się walidacją danych:
            $OrderValidator = new OrderValidator($objOrder);

            // Wykonanie walidacji danych wejściowych:
            $validationResult = $OrderValidator->validate();
            
            // Jeśli walidacja zakończyła się powodzeniem to rozpocznij obliczenia,
            // w przeciwnym razie zwróć numer błędu i komunikat użytkownikowi:
            if ($validationResult->success == true)
            {
                // Przekazanie obiektu zamówienia (zawierającego dostarczone dane)
                // do obiektu, który zajmie się obliczeniem problemu komiwojażera: 
                // (compute - obliczać; computer - pracownik od obliczeń aka "obliczeniowiec")
                $OrderComputer = new OrderComputer($objOrder);

                // Uruchomienie procesu obliczeniowego:
                $computationResult = $OrderComputer->compute();

                // Jeśli obliczenia zakończyły się powodzeniem to rozpocznij
                // zapis wytworzonych danych do bazy danych (zamówienia i wyników obliczeń):
                if ($computationResult->success == true)
                {
                    // Utworzenie obiektu kontrolera bazy danych:
                    $DatabaseController = new DatabaseController();

                    // Inicjalizuj połączenie do bazy:
                    $initConnectionResult = $DatabaseController->initConnection();

                    // Jeśli połączenie zostało nawiązane pomyślnie to zleć dodanie danych do bazy:
                    if ($initConnectionResult->success == true)
                    {
                        // Przepisanie obiektu wyniku obliczeń:
                        $objComputationResult = $computationResult->objResult;

                        // Przekazanie obiektu zamówienia (zawierającego dostarczone dane)
                        // oraz obiektu wyniku obliczeń do zapisania ich w bazie danych:
                        $savingToDatabaseResult = $DatabaseController->saveToDatabase($objOrder, $objComputationResult);

                        // Sprawdzenie czy zapis do bazy się udał:
                        if ($savingToDatabaseResult->success == true)
                        {
                            // Powodzenie w zapisie danych do bazy. Token przygotowany.
                            // Należy zwrócić informację o zakończeniu prac użytkownikowi:
                            
                                // Konwersja obiektu do formatu JSON:
                                $json = json_encode($savingToDatabaseResult, JSON_UNESCAPED_UNICODE);

                                // Ustawienie nagłówka HTTP i kodu błędu:  
                                // (bład nr 201 - Created - utworzono)
                                // (treść zamówienia jak i wyniki jego zostały zapisane na serwerze
                                // a klientowi zaraz zostanie zwrócona informacja o tym i token):
                                http_response_code(201);

                                // Ustawienie typu danych (JSON) i kodowania):
                                header("Content-Type: application/json; charset=utf-8");

                                // Wydrukowanie odpowiedzi (m.in. nagłówek HTTP, kod błędu Gakomi, token):
                                echo $json;

                            // To koniec działania! :)
                        }
                        else
                        {
                            // Niepowodzenie w zapisie do bazy danych. Brak więc tokena.
                            // Należy zwrócić informację o zakończeniu prac użytkownikowi:

                                // Konwersja obiektu do formatu JSON:
                                $json = json_encode($savingToDatabaseResult, JSON_UNESCAPED_UNICODE);

                                // Ustawienie nagłówka HTTP i kodu błędu:
                                // (błąd nr 500 - Internal Server Error - wewnętrzny błąd serwera)
                                // (Serwer napotkał niespodziewane trudności, które uniemożliwiły
                                // zrealizowanie żądania. Jest to problem z zapisem danych do bazy danych
                                // lub problem z wygenerowaniem tokena,)
                                http_response_code(500);

                                // Ustawienie typu danych (JSON) i kodowania):
                                header("Content-Type: application/json; charset=utf-8");

                                // Wydrukowanie odpowiedzi (m.in. nagłówek HTTP, kod błędu Gakomi, token):
                                echo $json;                                

                            // To koniec działania! :(
                            // Użytkownik może przesłać jeszcze raz dane by ponowić próbę
                            // przetwarzania danych jego zamówienia. 
                        }
                    }
                    else
                    {
                        // Niepowodzenie w nawiązywaniu połączenia z bazą danych.
                        // Należy zwrócić informację o błędzie połączenia użytkownikowi:
                        $json = json_encode($initConnectionResult, JSON_UNESCAPED_UNICODE);

                        // Ustawienie nagłówka HTTP i kodu błędu:
                        // (błąd nr 500 - Internal Server Error - wewnętrzny błąd serwera)
                        // (Serwer napotkał niespodziewane trudności, które uniemożliwiły
                        // zrealizowanie żądania. Jest to problem w łączności z bazą danych.)
                        http_response_code(500);

                        // Ustawienie typu danych (JSON) i kodowania):
                        header("Content-Type: application/json; charset=utf-8");

                        // Wydrukowanie odpowiedzi:
                        echo $json;

                        // Zakończ działanie skryptu:
                        break;
                    }
                }
                else
                {
                    // Obliczenia zakończyły się niepowodzeniem.
                    // Należy zwrócić informację o błędzie obliczeń użytkownikowi:
                    
                    // Konwersja obiektu wyniku obliczeń do formatu JSON:
                    $json = json_encode($computationResult, JSON_UNESCAPED_UNICODE);

                    // Ustawienie nagłówka HTTP i kodu błędu:
                    // (błąd nr 500 - Internal Server Error - wewnętrzny błąd serwera)
                    // (Serwer napotkał niespodziewane trudności, które uniemożliwiły
                    // zrealizowanie żądania. Najprawdopodobniej zawiodła walidacja,
                    // ktoś próbował przypuścić atak na system Gakomi, albo wystąpił
                    // problem z integralnością przesłanych danych.)
                    http_response_code(500);

                    // Ustawienie typu danych (JSON) i kodowania):
                    header("Content-Type: application/json; charset=utf-8");

                    // Wydrukowanie odpowiedzi:
                    echo $json;

                    // Zakończ działanie skryptu:
                    break;
                }
            }
            else
            {
                // Walidacja zakończyła się niepowodzeniem.
                // Należy zwrócić informację o błędzie walidacji użytkownikowi:
                
                // Konwersja obiektu wyniku walidacji do formatu JSON:
                $json = json_encode($validationResult, JSON_UNESCAPED_UNICODE);

                // Ustawienie nagłówka HTTP i kodu błędu:
                // (błąd nr 400 - Bad Request - nieprawidłowe zapytanie)
                // (Zapytanie nie może zostać zrealizowane przez serwer 
                // ponieważ nadesłane dane były nieprawidłowe
                // w efekcie czego walidacja nie została zakończona prawidłowo.)
                http_response_code(400);

                // Ustawienie typu danych (JSON) i kodowania):
                header("Content-Type: application/json; charset=utf-8");

                // Wydrukowanie odpowiedzi:
                echo $json;

                // Zakończ działanie skryptu:
                break;
            }
            // Zakończenie działania tego przełącznika (bezpiecznik):
            break;


        // Wybrano metodę HTTP typu GET. Użytkownik więc chciałby
        // uzyskać od API wyniki swoich obliczeń na postawie dostarczonego tokena:
        case "GET":
            //echo "GET!";
            /*
            http_response_code(200);
            header("Content-Type: application/json; charset=utf-8");
            $json = json_encode($_GET);
            echo $json;

                    //  echo "<pre>".var_dump($savingToDatabaseResult)."</pre>";
            */
            break;

        // Wybrano każdą inną metodę HTTP. Ponieważ są one nieobsługiwane,
        // należy zwrócić informację o błędzie i nieobsługiwanej metodzie.
        // Nosi ona numer 405. Opis wszystkich metod HTTP:
        // https://www.tutorialspoint.com/http/http_methods.htm
        // https://www.tutorialspoint.com/http/http_status_codes.htm
        default:
            /*
            // Ustawienie nagłówka HTTP i kodu błędu:
            http_response_code(405);
            // Ustawienie typu danych (JSON) i kodowania):
            header("Content-Type: application/json; charset=utf-8");
            // Przygotowanie treści odpowiedzi:
            $response = [ "error_message" => "Nieobsługiwana metoda HTTP!" ];
            // Konwersja na format JSON:
            $json = json_encode($response);
            // Wydrukowanie odpowiedzi:
            echo $json;
            */
            break;
    }







    // echo $verb;






/* 


    $DC = new DatabaseController();
    $allOrders = $DC->getAllTokens();

    echo "<pre>";
    print_r($allOrders);
    echo "</pre>";



    require_once("class/TokenGenerator.php");

    $TG = new TokenGenerator();
    $token = $TG->getToken();
    echo $token;

    echo "<br><br><br>";

    echo "<pre>";
    print_r($_GET);
    echo "</pre>";


 */


?>