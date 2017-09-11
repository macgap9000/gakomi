<?php
    
    /*
        Klasa generująca unikalne tokeny.
        Po wygenerowaniu tokena, sprawdza czy jest on unikalny
        (na podstawie tego czy taki token już istnieje w bazie danych).
    */

    // Definicja klasy:
    class TokenGenerator
    {
        // Konstruktor obiektu:
        public function __construct()
        {
            // Podłączenie biblioteki kryptograficznej:
            require_once __DIR__ . '../../components/random_compat/lib/random.php';
        }

        // Metoda przygotowująca unikatowy token:
        public function getToken()
        {
            // Generowanie losowego tokena:
            $token = $this->generateToken();

            // Sprawdzanie czy token nie jest już czasem zajęty:
            //isTokenUsed($token);

            return $token;
        }

        // Metoda generująca i zwracająca losowy token:
        private function generateToken()
        {
            try
            {
                // Generowanie losowych wartości binarnych z użyciem biblioteki kryptograficznej:
                $string = random_bytes(32);
            }
            catch (Exception $ex)
            {
                // Przerwanie działania skryptu. Wystąpił błąd krytyczny:
                die("Nie można wygenerować losowego tokena. Wystąpił nieznany błąd.");
            }
            
            // Konwersja wygenerowanych wartości binarnych do heksadecymalnych:
            $token = bin2hex($string);

            // Przykład:
            // string(64) "5787c41ae124b3b9363b7825104f8bc8cf27c4c3036573e5f0d4a91ad2eeac6f"

            // Zwrócenie wygenerowanego losowego tokena:
            return $token;
        }

        // Metoda sprawdzająca czy przykładowy token istnieje już w bazie:
        private function isTokenUsed($token)
        {
            echo "Sprawdzanie tokena...";
        }
    }

?>