<?php

    // Definicja obiektu zawierającego wynik operacji generowania tokenu
    // przez obiekt klasy TokenGenerator.

    // Definicja klasy:
    class TokenGenerationResult 
    {
        // Definicja pól klasy:
        
            // Definicja statusu (sukcesu) generowania tokena:
            // true - generowanie tokena zakończone sukcesem
            // false - generowanie tokena zakończone porażką
            public $success;

            // Definicja kodu błędu:
            // każdy rodzaj błędu ma swoje oznaczenie
            public $errorCode;

            // Definicja wiadomości do przekazania użytkownikowi:
            public $message;

            // Definicja pola przechowującego token:
            public $token;
    }

?>