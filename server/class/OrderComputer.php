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
            // Przpeisanie do zmiennej roboczej ilości miast:
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


            // Przelicz metodą siłową odległości dla każdej z permutacji (możliwej trasy):
            // UWAGA! Ponieważ numerowanie indeksów jest tu od zera, należy od ilości
            // wszystkich permutacji (tras) odjąć jeden:
            for ($i = 0; $i <= $numberOfRoutes-1; $i++)
            {
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
                    // Przepisz do zmiennej nazwę miasta A:
                    echo $cityA_name = $permutationsArray["routes"][$i][$j];
                    echo "-";
                    // Przepisz do zmiennej nazwę miasta B:
                    echo $cityB_name = $permutationsArray["routes"][$i][$j+1];
                    echo "<br>";
                    
                }

                echo "----------<br>";
                echo "<br>";
                
            }





            // foreach($permutationsArray["routes"] as $route)
            // {
            //     echo "<br>".var_dump($route)."<br>";
            // }






            // Odległość dla pierwszej permutacji:
            
            echo "<pre>";
            print_r($permutationsArray);
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