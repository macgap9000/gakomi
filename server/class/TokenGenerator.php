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
            // Powołanie obiektu wyniku generowania tokena:
            $objTokenGenerationResult = new TokenGenerationResult();

            // Próba wygenerowania tokena:
            try
            {
                // Generowanie losowych wartości binarnych 
                // z użyciem biblioteki kryptograficznej:
                $string = random_bytes(16);

                // Konwersja wygenerowanych wartości binarnych do heksadecymalnych:
                $token = bin2hex($string);

                // Przykład wygenerowanego tokena:
                // string(32) "89684b51ff2d6464e6c08b653d34e8ab"

                // Generowanie tokena zakończone powodzeniem.
                // Wypełnij obiekt wyniku generowania i go zwróć:
                $objTokenGenerationResult->success = true;
                $objTokenGenerationResult->errorCode = "ET999";
                $objTokenGenerationResult->message = "Generowanie tokena zakończone pomyślnie.";
                // Wpisanie do obiektu także wygenerowanego tokena:
                $objTokenGenerationResult->token = $token;

                // Zwrócenie wyniku generowania tokena:
                return $objTokenGenerationResult;
            }
            catch (Exception $ex)
            {
                // Przerwanie działania skryptu. Wystąpił błąd krytyczny.
                // Nie można wygenerować tokena. Nieznany błąd.

                // Wypełnij obiekt wyniku generowania i go zwróć:
                $objTokenGenerationResult->success = false;
                $objTokenGenerationResult->errorCode = "ET000";
                $objTokenGenerationResult->message = "Problem z wygenerowaniem tokena.";

                // Zwróć wynik generowania tokena:
                return $objTokenGenerationResult;
            }
        }
    }

?>