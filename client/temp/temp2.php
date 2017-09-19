<?php

  // Sprawdzanie czy zostąły przesłane odległości między miastami:
  if (isset($_GET['lines']))
  {
    // Przepisanie zawartości tablicy miast:
    $lines = $_GET['lines'];

    // Przygotowanie wskaźnika błędów "nie wpisano nic":
    $numberOfErrorsNotEntered = 0;

    // Weryfikacja, czy wpisano wszystkie odległości (wartości tekstowe):
    foreach($lines as &$line)
    {
      // Jeśli długość linii jest pusta:
      if (empty($line['distance']))
      {
        // To podnieś flagę błędu:
        $line['isEmpty'] = true;

        // Podbij wskaźnik ilości błędów:
        $numberOfErrorsNotEntered++;
      }
      else
      {
        // Podano odległość między miastami:
        $line['isEmpty'] = false;
      }
    }

    // Przygotowanie wskaźnika błędów "wpisano wartość nieliczbową":
    $numberOfErrorsNotNumeric = 0;

    // Weryfikacja, czy wpisano wszystkie odległości (jako wartości liczbowe):
    foreach($lines as &$line)
    {
      // Jeśli długość linii nie jest wartością liczbową:
      if (is_numeric($line['distance']))
      {
        // To podnieś flagę błędu:
        $line['isNotNumeric'] = true;

        // Podbij wskaźnik ilości błędów:
        $numberOfErrorsNotNumeric++;
      }
      else
      {
        // Podano odległość między miastami jako wartość liczbowa:
        $line['isNotNumeric'] = false;
      }
    }

    // Sprawdzenie czy można przeslać fornularz
    // na podstawie ilości błędów które wystąpiły:
    if (($numberOfErrorsNotEntered == 0) && ($numberOfErrorsNotNumeric == 0))
    {
      // Wykonaj przekierowanie z QUERY ...
    }

  }



?>