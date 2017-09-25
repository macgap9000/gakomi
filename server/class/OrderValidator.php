<?php

    // Definicja klasy walidatora danych wejściowych (zamówienia obliczeń):
    class OrderValidator
    {
        // Definicja pól klasy:

            // - obiekt zamówienia:
            private $objOrder;

        // Definicja konstruktora:
        public function __construct($objOrder)
        {   
            // Przepisanie odebranych danych:
            $this->objOrder = $objOrder;
        }

        // Zdumpowanie danych (obiektu walidatora) w celach debuggowania:
        public function debugValidator()
        {
            var_dump($this);
        }

        // Zdumpowanie danych (obiektu odebranego zamówienia) w celach debuggowania:
        public function debugReceivedOrder()
        {
            var_dump($this->objOrder);
        }

        // Wykonanie walidacji danych:
        public function validate()
        {
            // Podłączenie pliku klasy obiektu wyniku walidacji:
            require_once __DIR__ . '/OrderValidationResult.php';
            // Powołanie obiektu wyniku walidacji:
            $objOrderValidationResult = new OrderValidationResult();

            // Rozpoczęcie walidacji:

                // Krok 0. Sprawdzenie czy przekazane dane do pola "objOrder"
                // faktycznie stanowią obiekt. Jeśli nie, to nie można go dalej walidować:
                // Pobranie typu, jakiego jest wskazane pole "objOrder":
                $objOrder_isObject = is_object($this->objOrder);
                // Jeśli dane pole nie jest obiektem:
                if ($objOrder_isObject == false)
                {
                    // Pobranie typu danych zapisanych w polu "objOrder":
                    $type = gettype($this->objOrder);

                    // Zwróć stosowny wynik walidacji (pole nie jest obiektem):
                    $objOrderValidationResult->success = false;
                    $objOrderValidationResult->errorCode = "EV000";
                    $objOrderValidationResult->message = "Przesłane dane nie są obiektem. Przesłano dane są typu: $type";
                    // Zwróć obiekt wyniku walidacji:
                    return $objOrderValidationResult;
                }

                // Krok 1. Sprawdzenie czy przesłane dane (obiekt na ich podstawie)
                // jest pusty czy też może wypełniony jakimikolwiek danymi.
                // Zostanie do tego wykorzystany mechanizm refleksji obiektu:
                $reflect = new ReflectionObject($this->objOrder);
                // Pobranie listy wszystkich właściwości (pól) obiektu:
                $properties = $reflect->getProperties();
                // Policzenie ile pól zawiera obiekt:
                $howManyProperties = count($properties);
                // Jeśli obiekt zawiera 0 pól, oznacza to iż obiekt jest pusty:
                if ($howManyProperties == 0)
                {
                    // Zwróć stosowny wynik walidacji (obiekt pusty):
                    $objOrderValidationResult->success = false;
                    $objOrderValidationResult->errorCode = "EV001";
                    $objOrderValidationResult->message = "Przesłane puste dane.";
                    // Zwróć obiekt wyniku walidacji:
                    return $objOrderValidationResult;
                }

                // Krok 2. Sprawdzenie czy w obiekcie istnieją kolejne wymagane pola:
                    
                    // Krok 2A: Sprawdzenie czy istnieje pole "numberOfCities":
                    $numberOfCities_existing = property_exists($this->objOrder, "numberOfCities");
                    // Jeśli pole "numberOfCities" nie istnieje:
                    if ($numberOfCities_existing == false)
                    {
                        // Zwróć stosowny wynik walidacji (brak ilości miast):
                        $objOrderValidationResult->success = false;
                        $objOrderValidationResult->errorCode = "EV002";
                        $objOrderValidationResult->message = "Nie przesłano pola ilości miast 'numberOfCities'.";
                        // Zwróć obiekt wyniku walidacji:
                        return $objOrderValidationResult;
                    }
                    
                    // Krok 2B: Sprawdzenie czy istnieje pole "cities":
                    $cities_existing = property_exists($this->objOrder, "cities");
                    // Jeśli pole "cities" nie istnieje:
                    if ($cities_existing == false)
                    {
                        // Zwróć stosowny wynik walidacji (brak listy miast):
                        $objOrderValidationResult->success = false;
                        $objOrderValidationResult->errorCode = "EV003";
                        $objOrderValidationResult->message = "Nie przesłano pola zawierającego listę miast 'cities'.";
                        // Zwróć obiekt wyniku walidacji:
                        return $objOrderValidationResult;
                    }

                    // Krok 2C: Sprawdzenie czy istnieje pole "lines":
                    $lines_existing = property_exists($this->objOrder, "lines");
                    // Jeśli pole "lines" nie istnieje:
                    if ($lines_existing == false)
                    {
                        // Zwróć stosowny wynik walidacji (brak listy połączeń (linii)):
                        $objOrderValidationResult->success = false;
                        $objOrderValidationResult->errorCode = "EV004";
                        $objOrderValidationResult->message = "Nie przesłano pola zawierającego listę połączeń (linii) między miastami 'lines'.";
                        // Zwróć obiekt wyniku walidacji:
                        return $objOrderValidationResult;
                    }                    

                    // Krok 2D: Sprawdzenie czy istnieje pole "initialCityName":
                    $initialCityName_existing = property_exists($this->objOrder, "initialCityName");
                    // Jeśli pole "initialCityName" nie istnieje:
                    if ($initialCityName_existing == false)
                    {
                        // Zwróć stosowny wynik walidacji (brak miasta startowego):
                        $objOrderValidationResult->success = false;
                        $objOrderValidationResult->errorCode = "EV005";
                        $objOrderValidationResult->message = "Nie przesłano pola zawierającego nazwy miasta startowego 'initialCityName'.";
                        // Zwróć obiekt wyniku walidacji:
                        return $objOrderValidationResult;
                    }                          

                // Krok 3. Kolejne pola obiektu istnieją, co zostało sprawdzone w kroku 2.
                // Teraz należy sprawdzić czy w te pola zostały wprowadzone jakiekolwiek informacje:

                    // Krok 3A: Sprawdzenie czy pole "numberOfCities" zostało wypełnione:
                    $numberOfCities_emptiness = empty($this->objOrder->numberOfCities);
                    // Jeśli pole "numberOfCities" jest puste:
                    if ($numberOfCities_emptiness == true)
                    {
                        // Zwróć stosowny wynik walidacji (ilość miast jest pusta):
                        $objOrderValidationResult->success = false;
                        $objOrderValidationResult->errorCode = "EV006";
                        $objOrderValidationResult->message = "Pole ilości miast 'numberOfCities' nie może być puste.";
                        // Zwróć obiekt wyniku walidacji:
                        return $objOrderValidationResult;
                    }

                    // Krok 3B: Sprawdzenie czy pole "cities" zostało wypełnione:
                    $cities_emptiness = empty($this->objOrder->cities);
                    // Jeśli pole "cities" jest puste:
                    if ($cities_emptiness == true)
                    {
                        // Zwróć stosowny wynik walidacji (lista miast jest pusta):
                        $objOrderValidationResult->success = false;
                        $objOrderValidationResult->errorCode = "EV007";
                        $objOrderValidationResult->message = "Pole listy miast 'cities' nie może być puste.";
                        // Zwróć obiekt wyniku walidacji:
                        return $objOrderValidationResult;
                    }

                    // Krok 3C: Sprawdzenie czy pole "lines" zostało wypełnione:
                    $lines_emptiness = empty($this->objOrder->lines);
                    // Jeśli pole "lines" jest puste:
                    if ($lines_emptiness == true)
                    {
                        // Zwróć stosowny wynik walidacji (lista linii (połączeń między miastami) jest pusta):
                        $objOrderValidationResult->success = false;
                        $objOrderValidationResult->errorCode = "EV008";
                        $objOrderValidationResult->message = "Pole listy połączeń (linii) między miastami 'lines' nie może być puste.";
                        // Zwróć obiekt wyniku walidacji:
                        return $objOrderValidationResult;
                    }

                    // Krok 3D: Sprawdzenie czy pole "initialCityName" zostało wypełnione:
                    $initialCityName_emptiness = empty($this->objOrder->initialCityName);
                    // Jeśli pole "initialCityName" jest puste:
                    if ($initialCityName_emptiness == true)
                    {
                        // Zwróć stosowny wynik walidacji (nazwa miasta początkowego jest pusta):
                        $objOrderValidationResult->success = false;
                        $objOrderValidationResult->errorCode = "EV009";
                        $objOrderValidationResult->message = "Pole nazwy miasta startowego 'initialCityName' nie może być puste.";
                        // Zwróć obiekt wyniku walidacji:
                        return $objOrderValidationResult;
                    }

                // Krok 4. Kolejne pola obiektu istnieją, co zostało sprawdzone w kroku 2.
                // Nie są one także puste (zostało to sprawdzone w kroku 3). 
                // Należy teraz sprawdzić czy są one NULL, bo nie mogą one być NULL:
                    
                    // Krok 4A: Sprawdzenie czy pole "numberOfCities" jest NULL:
                    $numberOfCities_isNull = is_null($this->objOrder->numberOfCities);
                    // Jeśli pole "numberOfCities" jest NULL:
                    if ($numberOfCities_isNull == true)
                    {
                        // Zwróć stosowny wynik walidacji (ilość miast jest NULL):
                        $objOrderValidationResult->success = false;
                        $objOrderValidationResult->errorCode = "EV010";
                        $objOrderValidationResult->message = "Pole ilości miast 'numberOfCities' nie może być NULL.";
                        // Zwróć obiekt wyniku walidacji:
                        return $objOrderValidationResult;
                    }
                
                    // Krok 4B: Sprawdzenie czy pole "cities" jest NULL:
                    $cities_isNull = is_null($this->objOrder->cities);
                    // Jeśli pole "cities" jest NULL:
                    if ($cities_isNull == true)
                    {
                        // Zwróć stosowny wynik walidacji (lista miast jest NULL):
                        $objOrderValidationResult->success = false;
                        $objOrderValidationResult->errorCode = "EV011";
                        $objOrderValidationResult->message = "Pole listy miast 'cities' nie może być NULL.";
                        // Zwróć obiekt wyniku walidacji:
                        return $objOrderValidationResult;
                    }

                    // Krok 4C: Sprawdzenie czy pole "lines" jest NULL:
                    $lines_isNull = is_null($this->objOrder->lines);
                    // Jeśli pole "lines" jest NULL:
                    if ($lines_isNull == true)
                    {
                        // Zwróć stosowny wynik walidacji (lista linii (połączeń między miastami) jest NULL):
                        $objOrderValidationResult->success = false;
                        $objOrderValidationResult->errorCode = "EV012";
                        $objOrderValidationResult->message = "Pole listy połaczeń (linii) między miastami 'lines' nie może być NULL.";
                        // Zwróć obiekt wyniku walidacji:
                        return $objOrderValidationResult;
                    }                

                    // Krok 4D: Sprawdzenie czy pole "initialCityName" jest NULL:
                    $initialCityName_isNull = is_null($this->objOrder->initialCityName);
                    // Jeśli pole "initialCityName" jest NULL:
                    if ($initialCityName_isNull == true)
                    {
                        // Zwróć stosowny wynik walidacji (nazwa miasta początkowego jest NULL):
                        $objOrderValidationResult->success = false;
                        $objOrderValidationResult->errorCode = "EV013";
                        $objOrderValidationResult->message = "Pole nazy miasta startowego 'initialCityName' nie może być NULL.";
                        // Zwróć obiekt wyniku walidacji:
                        return $objOrderValidationResult;
                    }     

                // Krok 5. Sprawdzenie czy dane przechowywane w polu liczby miast (numberOfCities)
                // stanowią wartość liczbową. Jeśli nie, to nie można prowadzić dalej walidacji:
                // Sprawdzenie typu danych przechowywanych w polu "numberOfCities":
                $numberOfCities_isNumeric = is_numeric($this->objOrder->numberOfCities)
                // Jeśli dane te nie są numerczyne, zwróć odpowiedni błąd:
                if ($numberOfCities_isNumeric == false)
                {
                    // Zwróć stosowny wynik walidacji (ilość miast nie jest wartością liczbową):
                    $objOrderValidationResult->success = false;
                    $objOrderValidationResult->errorCode = "EV014";
                    $objOrderValidationResult->message = "Pole ilości miast 'numberOfCities' nie jest wartością liczbową.";
                    // Zwróć obiekt wyniku walidacji:
                    return $objOrderValidationResult;                    
                }

                // Krok 6. Sprawdzenie czy 

                    

            // var_dump($this->objOrder);

            // echo "<br><br>:";
            // echo "Czy obiekt jest pusty: ".(int) empty($this->objOrder);
         

            // $piesek = new stdClass();
            // $piesek->iloscNog = 4;
            // $piesek->ileGlow = 1;
            // $piesek->imiona = array("Aleks", "Reksio", "Rad");
            // $piesek->wlasciciel = "";

            // if (isset($piesek->ustawione))
            // {
            //     echo "<br><br>USTAWIONE!!!<br><br>";
            // }

            // if (is_null($piesek->wlasciciel))
            // {
            //     echo "<br><br>Właściwość jest nuullem!!!<br><br>";
            // }

            // if (empty($piesek->wlasciciel))
            // {
            //     echo "<br><br>Właściwość pusta!!!<br><br>";
            // }

            // $reflect = new ReflectionObject($this->objOrder);

            
            // echo '<pre>'.print_r(get_class_methods($reflect), true).'</pre>';
            
            // $properties = $reflect->getProperties();

            // echo "<pre>";
            // print_r($properties);
            // echo "</pre>";

            // echo "Ilość pól: ".count($properties);





                    // $KW = new stdClass();
                    // $KW->name = "Kutno-Warszawa";
                    // $KW->distance = 10;

                    // $KL = new stdClass();
                    // $KL->name = "Kutno-Lodz";
                    // $KL->distance = 20;


                    // $this->objOrder->kupa = [];
                    // $this->objOrder->kupa[] = $KW;
                    // $this->objOrder->kupa[] = $KL;

                    // echo "<pre>".print_r($this->objOrder->kupa)."</pre>";                
        }






    }

?>