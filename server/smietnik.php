<?php

            //$dir = dirname(__FILE__);
            //require_once("../components/random_compat/lib/random.php");
            
            //$path = __DIR__ . "../components/random_compat/lib/random.php";
            // $path = "D:\\xampp\\htdocs\\gakomi\\gakomi\\server\\components\\random_compat\\lib\\random.php";
            // echo "Path : $path";
            // require "$path";
            // echo "<br><br><br>";
            // $sciezka = __DIR__ . '../components/random_compat/lib/random.php';
            // require $sciezka;
            // echo $sciezka;


            
    // Podłączenie biblioteki:
    //require_once("../api.php");
    //$dir= dirname(__FILE__)."../components/random_compat/lib/random.php";

    try {
        $string = random_bytes(32);
    } catch (TypeError $e) {
        // Well, it's an integer, so this IS unexpected.
        die("An unexpected error has occurred"); 
    } catch (Error $e) {
        // This is also unexpected because 32 is a reasonable integer.
        die("An unexpected error has occurred");
    } catch (Exception $e) {
        // If you get this message, the CSPRNG failed hard.
        die("Could not generate a random string. Is our OS secure?");
    }
    
    $token = bin2hex($string);
    // string(64) "5787c41ae124b3b9363b7825104f8bc8cf27c4c3036573e5f0d4a91ad2eeac6f"

    return $token;




?>