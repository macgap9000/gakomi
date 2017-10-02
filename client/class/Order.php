<?php

    // Definicja klasy zawierającej treść zamówienia do przesłania do API.
    class Order
    {
        // Definicja pól/obiektów klasy:

            // Pole zawierające ilość miast (numberOfCities):
            public $numberOfCities;

            // Pole zawierające listę miast (cities):
            public $cities;

            // Pole zawierające tablicę linii (połączeń) między miastami (lines):
            public $lines;

            // Pole zawierające nazwę miasta startowego (initialCityName):
            public $initialCityName;        

        // Definicja konstruktora obiektu:
        public function __construct()
        {
            // Utworzony obiekt!
        }

        // Zdumpowanie danych (obiektu zamówienia) w celach debuggowania:
        public function debugOrder()
        {
            var_dump($this);
        }
    }

?>