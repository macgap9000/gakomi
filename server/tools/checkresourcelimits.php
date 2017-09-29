<?php

    // Skrypt umożliwiający sprawdzenie ustawionych limitów zasobów dla PHP.
    // Gdyby standardowe nie wystarczały, można zmienić ich domyślne wartości w php.ini.
    // Domyślne wartości to:
    //
    // maksymalny czas wykonywania skryptu (w sekundach):
    // max_execution_time = 30
    //
    // maksymalne użycie pamięci (w megabajtach):
    // memory_limit = 128M

    // Wydrukowanie domyślnych wartości:
    $allocatedMemoryInBytes = memory_get_usage();
    $allocatedMemoryHumanReadable = convert($allocatedMemoryInBytes);
    $realUsedMemory = memory_get_usage(true);
    $realUsedMemoryHumanReadable = convert($realUsedMemory);

    $j = 0;
    for($i=0; $i<=10000; $i++)
    {
        echo "Hello World!";
        $j++;
    }

    echo "<b>Aktualnie używana ilość pamięci: $allocatedMemoryInBytes B, czyli: $allocatedMemoryHumanReadable</b><br>";
    echo "<b>Rzeczywiste użycie pamięci: $realUsedMemory B, czyli: $realUsedMemoryHumanReadable</b><br>";


    // Funkcja konwertująca jednostki:
    // http://php.net/manual/en/function.memory-get-usage.php
    function convert($size)
    {
        $unit=array('B','kB','MB','GB','TB','PB');
        return @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
    }
    

?>