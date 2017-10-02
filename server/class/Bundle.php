<?php

    // Definicja klasy zawierającej treść zamówienia i wyniki obliczeń.
    // Nazywana roboczo paczką "bundle" - jako paczką danych zwracaną użytkownikowi
    // w przypadku gdy ten ich żąda podając żeton (token) zamówienia.
    class Bundle
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

            // Pole zawierające listę miast będących częścią trasy dla komiwojażera (resultroute):
            public $route;

            // Pole zawierające długość trasy dla komiwojażera (resultmileage):
            public $mileage;

        // Definicja konstruktora obiektu:
        public function __construct()
        {
            // Utworzony obiekt!
        }

        // Zdumpowanie danych (obiektu paczki) w celach debuggowania:
        public function debugBundle()
        {
            var_dump($this);
        }
    }

?>