<?php

    // Definicja obiektu zawierającego wynik obliczeń
    // przez obiekt klasy OrderComputer.

    // Definicja klasy:
    class OrderComputationResult
    {
        // Definicja pól klasy:
        
            // Definicja statusu (sukcesu) wykonywania obliczeń:
            // true - obliczenia wykonane (sukces)
            // false - obliczenia nieudane (porażka)
            public $success;

            // Definicja kodu błędu:
            // każdy rodzaj błędu ma swoje oznaczenie
            public $errorCode;

            // Definicja wiadomości do przekazania użytkownikowi:
            public $message;

            // Definicja pola przechowującego obiekt wyniku obliczeń:
            public $objResult;
    }

?>