<?php

    // Definicja obiektu zawierającego wynik operacji walidowania tokenu
    // przez obiekt klasy TokenValidator.

    // Definicja klasy:
    class TokenValidationResult
    {
        // Definicja pól klasy:
        
            // Definicja statusu (sukcesu) walidowania tokena:
            // true - walidowanie tokena zakończone sukcesem
            // false - walidowanie tokena zakończone porażką
            public $success;

            // Definicja kodu błędu:
            // każdy rodzaj błędu ma swoje oznaczenie
            public $errorCode;

            // Definicja wiadomości do przekazania użytkownikowi:
            public $message;
    }

?>