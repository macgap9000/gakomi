<?php

    // Definicja obiektu zawierającego wynik operacji zarządzania na bazie danych
    // przez obiekt klasy DatabaseController.

    // Definicja klasy:
    class DatabaseManagementResult 
    {
        // Definicja pól klasy:
        
            // Definicja statusu (sukcesu) operacji na bazie:
            // true - operacja na bazie udana
            // false - operacja na bazie nieudana
            public $success;

            // Definicja kodu błędu:
            // każdy rodzaj błędu ma swoje oznaczenie
            public $errorCode;

            // Definicja wiadomości do przekazania użytkownikowi:
            public $message;
    }

?>