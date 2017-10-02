<?php

    // Skrypt umożliwiający reinstalację bazy danych:

    // Podłączenie pliku kasującego bazę danych:
    require_once __DIR__ . '/dropdatabase.php';

    // Podłączenie pliku instalującego bazę danych:
    require_once __DIR__ . '/installdatabase.php';
    
    // Reinstalacja powinna zostać ukończona po wywołaniu tego pliku!
    echo "Zakończono działanie skryptu reinstalacyjnego!";

?>