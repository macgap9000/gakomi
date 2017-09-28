<?php

    // Definicja klasy obliczeniowej komiwojażera 
    // (zajmującej się realizacją obliczeń na podstawie dostarczonych danych):
    class OrderComputer
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

        // Zdumpowanie danych (obiektu obliczeniowego) w celach debuggowania:
        public function debugValidator()
        {
            var_dump($this);
        }

        // Zdumpowanie danych (obiektu odebranego zamówienia) w celach debuggowania:
        public function debugReceivedOrder()
        {
            var_dump($this->objOrder);
        }

        // Wykonanie obliczeń dla komiwojażera:
        public function compute()
        {
            // Przepisanie do zmiennej roboczej ilości miast:
            $numberOfCities = (int) $this->objOrder->numberOfCities;
            // Przepisanie do tablicy roboczej listy miast:
            $cities = (array) $this->objOrder->cities;
            // Przepisanie do tablicy roboczej listy połączeń (linii) między miastami:
            $lines = (array) $this->objOrder->lines;
            // Przepisanie do zmiennej roboczej nazwy miasta startowego:
            $initialCityName = (string) $this->objOrder->initialCityName;

            // Znajdź wszystkie permutacje zbioru miast (możliwych tras):
            $permutationsArray["routes"] = $this->pc_permute($cities);

            // Określ liczbę odnalezionych permutacji (możliwych tras):
            echo "Liczba tras: ".$numberOfRoutes = count($permutationsArray["routes"]);

            // Przygotuj pustą tablicę na wyliczone kilometraże (całowite długości tras):
            $permutationsArray["mileages"] = "";

            
            echo "<br><br>";

            echo "<pre>";
            print_r($lines);
            echo "</pre>";



            // Przelicz metodą siłową odległości dla każdej z permutacji (możliwej trasy):
            // UWAGA! Ponieważ numerowanie indeksów jest tu od zera, należy od ilości
            // wszystkich permutacji (tras) odjąć jeden:
            for ($i = 0; $i <= $numberOfRoutes-1; $i++)
            {
                // Ustawienie licznika kilometrażówki dla danej wybranej trasy:
                $mileage = 0;

                // Przelicz długość danej wybranej permutacji (danej wybranej trasy):
                // (UWAGA! Każda permutacja ma dokładnie tyle ile miast, 
                // ile zostało zadeklarowanych w numberOfCities. 
                // Ilość obrotów pętli jest mniejsza o jeden od numberOfCities, gdyż
                // w przypadku trzech miast o indeksach: 0, 1, 2, 3, 4 - tworzone są
                // następujące pary: 0-1, 1-2, 2-3, 3-4. Dodatkowo należy ich ilość
                // zmniejszyć o kolejne 1 gdyż podczas łączenia miast w pary,
                // nie można wypaść poza zakres z indeksem drugiego miasta.)
                for ($j = 0; $j <= $numberOfCities-1-1; $j++)
                {
                    // Przepisz do zmiennej nazwę miasta A (np. "Kutno"):
                    $cityA_name = $permutationsArray["routes"][$i][$j];
                    // Przepisz do zmiennej nazwę miasta B (np. "Warszawa"):
                    $cityB_name = $permutationsArray["routes"][$i][$j+1];
                    // Połącz (konkatenuj) nazwy miast (np. "Kutno-Warszawa"):
                    echo $cityABmatch = $cityA_name."-".$cityB_name;
                    echo "<br>";

                    // Zweryfikuj czy takie miasto znajduje się na liście połączeń (lines):
                    $cityABmatch_isInLines = array_key_exists($cityABmatch, $lines);
                    // Jeżeli taka linia między miastami została odnaleziona:
                    if ($cityABmatch_isInLines == true)
                    {
                        // Dodaj dystans dzielący te miasta do ogólnego licznika kilometrażówki.
                        // Znajduje się on w tablicy $lines, pod kluczem o takim brzmieniu jak
                        // nazwa aktualnie przetwarzaneej linii, np.: $lines['Kutno-Warszawa'] => 130.
                        $mileage = $mileage + $lines[$cityABmatch];
                    }
                    else
                    {
                        // Ponieważ nie została odnaleziona linia pomiędzy miastami A-B
                        // to musi istnieć też połączenie między miastami B-A które jest
                        // tym samym co A-B tylko, że wpisem w tablicy o odwróconej kolejności:
                        $cityBAmatch = $cityB_name."-".$cityA_name;
                        // Odszukaj więc takie "odwrócone" połączenie miast na liście połączeń (lines):
                        $cityBAmatch_isInLines = array_key_exists($cityBAmatch, $lines);
                        // Jeśli taka linia między miastami została odnaleziona:
                        if($cityBAmatch_isInLines == true)
                        {
                            // Dodaj dystans dzielący te miasta do ogólnego licznika kilometrażówki:
                            $mileage = $mileage + $lines[$cityBAmatch];
                        }
                        else
                        {
                            // Błąd krytyczny!
                        }
                    }
                }

                // Przepisz do tablicy $mileages uzyskaną kilometrażówkę dla danej trasy całkowitej:
                // ($i - to indeks aktualnie przetwarzanej trasy, celem zachowania spójności danych)
                $permutationsArray["mileages"][$i] = $mileage;

                echo 'Długość kilometrażówki: $mileage = '.$mileage.'<br>';
                echo "----------<br>";
                echo "<br>";
                
            }


            // Wszystkie długości tras zostały wyliczone.
            // Teraz należy odszukać najkrótszą trasę dla komiwojażera i jednocześnie taką,
            // która rozpoczyna się w jego startowym mieście (initialCityName):

            // Przygotowanie podtablicy w tablicy na miasta wybranej permutacji:
            $theShortestRoute["route"] = "";
            // Przygotowanie pola w tablicy na kilometrażówkę wybranej permutacji:
            $theShortestRoute["mileage"] = "";


            // $aaaaa = empty($theShortestRoute["route"]);
            // if ($aaaaa == true)
            // {
            //     echo "PUSTEEEEEEEEEEEEEEEEEEE!<br>";
            // }
            

            // Poszukiwanie najkrótszej trasy dla komiwojażera z miasta startowego:
            for ($i = 0; $i <= $numberOfRoutes-1; $i++)
            {
                // W danej permutacji miasto startowe musi znaleźć się na "zerowej" pozycji:
                // ($i - numer kolejnej permutacji przechowywanej w tablicy):
                if ($permutationsArray["routes"][$i][0] == $initialCityName)
                {
                    // Sprawdzenie została wpisana już jakakolwiek trasa i kilometrażówka:
                    if (empty($theShortestRoute["route"]) && (empty($theShortestRoute["mileage"])))
                    {
                        // Pusto! Nie ma żadnej trasy i kilometrażówki!
                        // Przepisz więc tam tą aktualnie przetwarzaną trasę (permutację miast):
                        $theShortestRoute["route"] = $permutationsArray["routes"][$i];
                        // oraz także jej kilometrażówkę (długość tej całej trasy):
                        $theShortestRoute["mileage"] = $permutationsArray["mileages"][$i];
                    }
                    else
                    {
                        // W takim razie została wpisana już jakakolwiek trasa tam i kilometrażówka.
                        // Jeśli kilometrażówka tej trasy jest krótsza (lub równa) od tej aktualnie
                        // przechowywanej w tablicy $theShortestRoute, to przepisz trasę tam!
                        if ($theShortestRoute["mileage"] >= $permutationsArray["mileages"][$i])
                        {
                            // Przepisywanie odnalezionej nowej najkrótszej trasy:
                            $theShortestRoute["route"] = $permutationsArray["routes"][$i];
                            // Przepisywanie odległości tej najkrótszej trasy:
                            $theShortestRoute["mileage"] = $permutationsArray["mileages"][$i];
                        }
                    }


                }

            }




            // foreach($permutationsArray["routes"] as $route)
            // {
            //     echo "<br>".var_dump($route)."<br>";
            // }






            // Odległość dla pierwszej permutacji:
            
            echo "<pre>";
            print_r($permutationsArray);
            echo "</pre>";



            echo "<pre>";
            print_r($theShortestRoute);
            echo "</pre>";
        }

        // Funkcja wykonująca permutację zbioru:
        private function pc_permute($items, $perms = array( )) {
            if (empty($items)) {
                $return = array($perms);
            }  else {
                $return = array();
                for ($i = count($items) - 1; $i >= 0; --$i) {
                     $newitems = $items;
                     $newperms = $perms;
                 list($foo) = array_splice($newitems, $i, 1);
                     array_unshift($newperms, $foo);
                     $return = array_merge($return, self::pc_permute($newitems, $newperms));
                 }
            }
            return $return;
        }
    }

?>