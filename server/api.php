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
        //require_once __DIR__ . "/class/DatabaseController.php";

        // Generator tokenów:
        //require_once __DIR__ . "/class/TokenGenerator.php";


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
                $computingResult = $OrderComputer->compute();
            }
            else
            {

            }
            
            echo "<pre>".var_dump($computingResult)."</pre>";
            

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