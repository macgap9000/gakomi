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

                    // Krok 5A. Sprawdzenie czy pole liczby miast "numberOfCities" jest w ogóle liczą (jakąkolwiek):
                    $numberOfCities_isNumeric = is_numeric($this->objOrder->numberOfCities);
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

                    // Krok 5B. Sprawdzenie czy pole liczby miast "numberOfCities" jest wartością całkowitą:
                    $numberOfCities_isInteger = is_int($this->objOrder->numberOfCities);
                    // Jeśli liczba nie jest całkowita (intigerem), zwróć odpowiedni błąd:
                    if ($numberOfCities_isInteger == false)
                    {
                        // Zwróć stosowny wynik walidacji (ilość miast nie jest liczbą całkowitą):
                        $objOrderValidationResult->success = false;
                        $objOrderValidationResult->errorCode = "EV015";
                        $objOrderValidationResult->message = "Pole ilości miast 'numberOfCities' nie jest liczbą całkowitą.";
                        // Zwróć obiekt wyniku walidacji:
                        return $objOrderValidationResult; 
                    }

                    // Krok 5C. Sprawdzenie czy pole liczby miast "numberOfCities" jest wartością większą od zera (dodatnią):
                    // Jeśli liczba ta nie jest dodatnia:
                    if (!($this->objOrder->numberOfCities > 0))
                    {
                        // Zwróć stosowny wynik walidacji (ilość miast nie jest liczbą dodatnią):
                        $objOrderValidationResult->success = false;
                        $objOrderValidationResult->errorCode = "EV016";
                        $objOrderValidationResult->message = "Pole ilości miast 'numberOfCities' nie jest liczbą dodatnią.";
                        // Zwróć obiekt wyniku walidacji:
                        return $objOrderValidationResult; 
                    }
                    
                // Krok 6. Sprawdzenie czy dane przechowywane w polu listy miast (cities)
                // są zgodne z oczekiwanymi typami i wartościami. Jeśli nie, to nie można prowadzić dalej walidacji:

                    // Krok 6A. Sprawdzenie czy pole listy miast "cities" zawiera listę typu tablicowego:
                    $cities_isArray = is_array($this->objOrder->cities);
                    // Jeśli pole nie jest tablicą:
                    if ($cities_isArray == false)
                    {
                        // Zwróć stosowny wynik walidacji (lista miast nie jest tablicą):
                        $objOrderValidationResult->success = false;
                        $objOrderValidationResult->errorCode = "EV017";
                        $objOrderValidationResult->message = "Pole listy miast 'cities' nie zawiera listy typu tablicowego.";
                        // Zwróć obiekt wyniku walidacji:
                        return $objOrderValidationResult;                         
                    }

                    // Krok 6B. Sprawdzenie czy pole listy miast "cities" zawiera tylko elementy typu string:
                    // Przygotowanie licznika elementów, które nie są stringami:
                    $cities_numberOfElements_areNotString = 0;
                    // Przeszukiwanie tablicy pod kątem elementów, które nie są stringami:
                    foreach($this->objOrder->cities as $city)
                    {
                        // Sprawdzenie typu elementu (czy jest stringiem):
                        $elementIsString = is_string($city);
                        // Jeśli dany element nie jest typu string:
                        if ($elementIsString == false)
                        {
                            // Podbij licznik elementów, które nie są stringami:
                            $cities_numberOfElements_areNotString++;
                        }
                    }
                    // Podjęcie decyzji. Jeśli jakikolwiek z elementów nie jest stringiem, zwróć błąd i przerwij dalszą walidację:
                    if ($cities_numberOfElements_areNotString > 0)
                    {
                        // Zwróć stosowny wynik walidacji (nie wszystkie elementy są typu string):
                        $objOrderValidationResult->success = false;
                        $objOrderValidationResult->errorCode = "EV018";
                        $objOrderValidationResult->message = "Pole listy miast 'cities' zawiera elementy, które nie są typu string.";
                        // Zwróć obiekt wyniku walidacji:
                        return $objOrderValidationResult;                 
                    }

                    // Krok 6C. Sprawdzenie czy pole listy miast "cities" zawiera tyle miast (elementów) ile
                    // zostało zadeklarowane w postaci wartości liczbowej zapisanej w polu "numberOfCities":
                    // Pobranie liczby elementów w liście (tablicy) miast "cities":
                    $cities_numberOfElements = count($this->objOrder->cities);
                    // Porównanie ilości miast na liście z ilością zadeklarowaną. Jeśli jest ona różna, to przerwij walidację.
                    if (!($cities_numberOfElements == $this->objOrder->numberOfCities))
                    {
                        // Zwróć stosowny wynik walidacji (ilość miast na liście miast nie jest zgodna z ilością zadeklarowaną):
                        $objOrderValidationResult->success = false;
                        $objOrderValidationResult->errorCode = "EV019";
                        $objOrderValidationResult->message = "Pole listy miast 'cities' zawiera odmienną ilość miast, niż ta zareklarowana w 'numberOfCities'.";
                        // Zwróć obiekt wyniku walidacji:
                        return $objOrderValidationResult;  
                    }

                // Krok 7. Sprawdzenie czy dane przechowywane w polu listy połączeń (linii) między miastami (lines)
                // są zgodne z oczekiwanymi typami i wartościami. Jeśli nie, to nie można prowadzić dalej walidacji:

                    // Krok 7A. Sprawdzenie czy pole listy połączeń (linii) między miastami "lines" jest typu obiektowego:
                    $lines_isObject = is_object($this->objOrder->lines);
                    // Jeśli pole nie jest obiektem:
                    if ($lines_isObject == false)
                    {
                        // Zwróć stosowny wynik walidacji (połączeń połączeń (linii) między miastami 'lines' nie):
                        $objOrderValidationResult->success = false;
                        $objOrderValidationResult->errorCode = "EV020";
                        $objOrderValidationResult->message = "Pole listy połączeń (linii) między miastami 'lines' nie jest typem obiektowym.";
                        // Zwróć obiekt wyniku walidacji:
                        return $objOrderValidationResult;                         
                    }

                    // Krok 7B. Sprawdzenie czy pole listy połączeń (linii) między miastami "lines"
                    // zawiera tylko i wyłącznie elementy będące typu "klucz - wartość":
                    //
                    // Wprowadzenie mechanizmu refleksji obiektu:
                    $reflect = new ReflectionObject($this->objOrder->lines);
                    // Uzyskanie listy wszystkich dostępnych pól obiektu:
                    $properties = $reflect->getProperties();
                    // Zliczenie liczby wszystkich dostępnych pól obiektu:
                    $lines_numberOfElements = count($properties);
                    //
                    // Wprowadzenie licznika liczby pól typu "klucz - wartość":
                    $lines_numberOfElements_keyValuePairs = 0;
                    // Przeliczenie ilości pól typu "klucz - wartość":
                    foreach ($this->objOrder->lines as $key => $value)
                    {
                        $lines_numberOfElements_keyValuePairs++;
                    }
                    //
                    // Jeżeli ilość wszystkich elementów jest różna od ilości elementów typu "klucz - wartość"
                    // oznacza to, że wśród wszystkich elemenów były jeszcze inne elementy, które nie były typu "klucz-wartość":
                    if (!($lines_numberOfElements == $lines_numberOfElements_keyValuePairs))
                    {
                        // Zwróć stosowny wynik walidacji (lista linii (połączeń między miastami) nie jest obiektem):
                        $objOrderValidationResult->success = false;
                        $objOrderValidationResult->errorCode = "EV021";
                        $objOrderValidationResult->message = "Pole listy połączeń (linii) między miastami 'lines' zawiera dane innego typu niż 'klucz - wartość'.";
                        // Zwróć obiekt wyniku walidacji:
                        return $objOrderValidationResult;  
                    }

                    // Krok 7C. Sprawdzenie czy pole listy połączeń (linii) między miastami "lines"
                    // zawiera tylko i wyłącznie klucze będącymi typu string
                    // oraz wartości, będące tylko i wartościami liczbowymi (dozwolone są liczby całkowite i zmiennoprzecinkowe):
                    //
                    // Przygotowanie kolejno liczników - licznik kluczy nie-stringowych i licznik wartości nie-liczbowych:
                    $lines_numberOfKeys_notStrings = 0;
                    $lines_numberOfValues_notNumeric = 0;
                    // Przeszukiwanie elementów pod kątem wspomnianych wyżej warunków:
                    foreach ($this->objOrder->lines as $key => $value)
                    {
                        // Jeśli klucz nie jest typu string:
                        if (!(is_string($key)))
                        {
                            // Podbij wartość licznika kluczy nie-stringowych:
                            $lines_numberOfKeys_notStrings++;
                        }
                        // Jeśli wartość nie jest liczbowa:
                        if (!(is_numeric($value)))
                        {
                            // Podbij wartość licznika wartości nie-liczbowy:
                            $lines_numberOfValues_notNumeric++;
                        }
                    }
                    // Jeśli wartość któregokolwiek z liczników (a więc odnalezionych błędnych wartości) jest większa od zera
                    // oznacza to, że zostały wprowadzone błędne wartości i należy przerwać proces walidacji:
                    if ($lines_numberOfKeys_notStrings > 0)
                    {
                        // Zwróć stosowny wynik walidacji (lista linii (połączeń między miastami) zawiera klucze nie będące typu string):
                        $objOrderValidationResult->success = false;
                        $objOrderValidationResult->errorCode = "EV022";
                        $objOrderValidationResult->message = "Pole listy połączeń (linii) między miastami 'lines' zawiera klucze, które nie są typu string.";
                        // Zwróć obiekt wyniku walidacji:
                        return $objOrderValidationResult;
                    }
                    if ($lines_numberOfValues_notNumeric > 0)
                    {
                        // Zwróć stosowny wynik walidacji (lista linii (połączeń między miastami) zawiera wartości nie będące wartościami liczbowymi):
                        $objOrderValidationResult->success = false;
                        $objOrderValidationResult->errorCode = "EV023";
                        $objOrderValidationResult->message = "Pole listy połączeń (linii) między miastami 'lines' zawiera wartości, które nie są liczbowe.";
                        // Zwróć obiekt wyniku walidacji:
                        return $objOrderValidationResult;
                    }

                    
                // Krok 8. Sprawdzenie czy dane przechowywane w polu nazwy miasta startowego "initialCityName"
                // są zgodne z oczekiwanymi typami i wartościami. Jeśli nie, to nie można prowadzić dalej walidacji:

                    // Krok 8A. Sprawdzenie czy wartość przechowywana w polu nazwy miasta startowego
                    // jest wartością typu string:
                    $initialCityName_isString = is_string($this->objOrder->initialCityName);
                    // Jeśli wartość ta nie jest typu string:
                    if ($initialCityName_isString == false)
                    {
                        // Zwróć stosowny wynik walidacji (wartość przechowana w polu nazwa miasta startowego nie jest stringiem):
                        $objOrderValidationResult->success = false;
                        $objOrderValidationResult->errorCode = "EV030";
                        $objOrderValidationResult->message = "Pole nazwy miasta startowego 'initialCityName' nie jest typu string.";
                        // Zwróć obiekt wyniku walidacji:
                        return $objOrderValidationResult;
                    }

                    // Krok 8B. Sprawdzenie czy wartość przechowywana w polu nazwy miasta startowego
                    // znajduje się w zadeklarowanej liście miast 'cities':
                    $initialCityName_existsOnCitiesList = in_array($this->objOrder->cities)
                    // Jeśli nie znajduje się miasto na tamtej liście miast:
                    if ($initialCityName_existsOnCitiesList == false)
                    {
                        // Zwróć stosowny wynik walidacji (nazwa miasta przechowana w polu nazwa miasta startowego nie znajduje się na zadeklarowanej liście miast 'cities'):
                        $objOrderValidationResult->success = false;
                        $objOrderValidationResult->errorCode = "EV031";
                        $objOrderValidationResult->message = "Pole nazwy miasta startowego 'initialCityName' zawiera nazwę miasta, nie znajdującego się na liście miast 'cities'.";
                        // Zwróć obiekt wyniku walidacji:
                        return $objOrderValidationResult;
                    }

            // UWAGA! Ponieważ walidacja nie zatrzymała się do tej pory, oznacza to iż nie wystąpił żaden błąd walidacji.
            // Można przygotować obiekt wyniku walidacji zwracający pozytywne wieści o uwalidacji i go zwrócić:
            $objOrderValidationResult->success = true;
            $objOrderValidationResult->errorCode = "EV999";
            $objOrderValidationResult->message = "Walidacja zakończona powodzeniem.";
            // Zwróć obiekt wyniku walidacji:
            return $objOrderValidationResult;
        }
    }

?>