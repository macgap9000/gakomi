<?php
    
    // Definicja klasy generującej unikalne tokeny:
    class TokenGenerator
    {
        // Konstruktor obiektu:
        public function __construct()
        {
            // Podłączenie biblioteki kryptograficznej:
            require_once __DIR__ . '../../components/random_compat/lib/random.php';
        }

        // Metoda generująca i zwracająca losowy token:
        public function generateToken()
        {
            // Podłączenie pliku klasy obiektu wyniku generowania tokena:
            require_once __DIR__ . '/TokenGenerationResult.php';

            // Próba wygenerowania tokena:
            try
            {
                // Generowanie losowych wartości binarnych z użyciem biblioteki kryptograficznej:
                $string = random_bytes(16);
            }
            catch (Exception $ex)
            {
                // Przerwanie działania skryptu. Wystąpił błąd krytyczny:
                die("Nie można wygenerować losowego tokena. Wystąpił nieznany błąd.");
            }
            
            // Konwersja wygenerowanych wartości binarnych do heksadecymalnych:
            $token = bin2hex($string);

            // Przykład:
            // string(32) "89684b51ff2d6464e6c08b653d34e8ab"

            // Zwrócenie wygenerowanego losowego tokena:
            return $token;
        }
    }

?>