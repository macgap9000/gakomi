<?php

    // Definicja klasy walidacji tokena
    // (odpowiada za walidację przesłanego tokena przez użytkownika):
    class TokenValidator
    {
        // Definicja pól/obiektów klasy:

            // Pole zawierające tokena:
            private $token;

        // Definicja konstruktora::
        public function __construct($token)
        {
            // Przepisanie odebranych danych:
            $this->token = $token;
        }

        // Zdumpowanie danych (obiektu walidatora) w celach debuggowania:
        public function debugValidator()
        {
            var_dump($this);
        }

        // Wykonanie walidacji tokena:
        public function validate()
        {
            // Podłączenie pliku klasy obiektu wyniku walidacji:
            require_once __DIR__ . '/TokenValidationResult.php';
            // Powołanie obiektu wyniku walidacji:
            $objTokenValidationResult = new TokenValidationResult();

            // Sprawdzenie czy przekazane dane do pola "token"
            // stanowią faktycznie string (a nie np. tablicę czy obiekt).
            // Jeśli nie, to nie można go dalej walidować:
            // Pobranie typu, jakiego jest wskazane pole "token":
            $token_isString = is_string($this->token);
            
            // Jeśli dane pole jest stringiem, to kontynuuj walidację:
            if ($token_isString == true)
            {
                // Pobranie długości tokena (ilości znaków które zawiera):
                $strlen = strlen($this->token);

                // Sprawdzenie długości tokena. Powinien mieć on długość 32 znaków:
                if ($strlen == 32)
                {
                    // Długość tokena faktycznie ma 32 znaki. To dobrze.
                    // Teraz należy sprawdzić czy stanowi on w całości wartość heksadecymalną.
                    // Powinien więc zawierać tylko znaki w przedziale 0-9 i A-F oraz a-f.
                    
                    // Sprawdzenie czy token jest wartością hekadecymalną:
                    $isHex = ctype_xdigit($this->token);

                    // Podjęcie decyzji czy jest to wartość heksadecymalna:
                    if ($isHex == true)
                    {
                        // Wskazany token jest wartością heksadecymalną.
                        // Przygotuj pozytywny wynik weryfikacji do zwrotu:
                        $objTokenValidationResult->success = true;
                        $objTokenValidationResult->errorCode = "ET199";
                        $objTokenValidationResult->message = "Token zaakceptowany";
                        // Zwróć obiekt wyniku walidacji:
                        return $objTokenValidationResult;
                    }
                    else
                    {
                        // Wskazany token nie jest wartością heksadecymalną.
                        // Przygotuj informację o błędzie podczas walidacji tokena:
                        $objTokenValidationResult->success = false;
                        $objTokenValidationResult->errorCode = "ET102";
                        $objTokenValidationResult->message = "Przesłany token nie jest wartością heksadecymalną.";
                        // Zwróć obiekt wyniku walidacji:
                        return $objTokenValidationResult;
                    }
                }
                else
                {
                    // Długość przesłanego tokena jest inna od oczekiwanej.
                    // Przygotuj informację o błędzie podczas walidacji tokena:
                    $objTokenValidationResult->success = false;
                    $objTokenValidationResult->errorCode = "ET101";
                    $objTokenValidationResult->message = "Przesłany token jest ma inną długość od oczekiwanej. Oczekiwano 32 znaków.";
                    // Zwróć obiekt wyniku walidacji:
                    return $objTokenValidationResult;
                    
                }
            }
            else
            {
                // Przesłane dane nie są typu string. Trudno więc mówić tu o tokenie.
                // Przygotuj informację o błędzie podczas walidacji tokena:
                $objTokenValidationResult->success = false;
                $objTokenValidationResult->errorCode = "ET100";
                $objTokenValidationResult->message = "Przesłany token nie jest stringiem.";
                // Zwróć obiekt wyniku walidacji:
                return $objTokenValidationResult;
            }
        }
    }

?>