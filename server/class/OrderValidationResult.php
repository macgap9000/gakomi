<?php

    // Definicja obiektu zawierającego wynik walidacji
    // przez obiekt klasy OrderValidator.

    // Definicja klasy:
    class OrderValidationResult
    {
        // Definicja pól klasy:
        
            // Definicja statusu (sukcesu) walidacji:
            // true - walidacja ustana
            // false - walidacja nieudana
            public $success;

            // Definicja kodu błędu:
            // każdy rodzaj błędu ma swoje oznaczenie
            public $errorCode;

            // Definicja wiadomości do przekazania użytkownikowi:
            public $message;
    }

?>