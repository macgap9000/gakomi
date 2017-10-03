<?php

  // Dane dostarczane do tego pliku zostają za pomocą metody POST.
  // Plik ten jest tym, który wywołuje API. Dane do niego dostarcza stage5.php.

  // W odróżnieniu do pozostałych plików stage, dane do tego pliku zostają
  // przesłane za pomocą metody POST a nie GET, ponieważ zostają one
  // finalnie przetworzone i wysłane na serwer do API.
  // Ogranicza się w ten sposób manipulację danymi
  // oraz możliwość wielokrotnego ich przesylania.

  // Należy ponownie sprawdzić (tym razem w tablicy $_POST)
  // czy dane poprawnie dotarły do niniejszego dokumentu PHP. Jeśli nie,
  // oznacza to, że ktoś manipulował danymi podczas przesyłania ich z stage5.php (z podsumowania).
  // W takim wypadku należy wykonać natychmiastowe przekierowane do wskazanych plików PHP,
  // pełniących rolę kolejnych kroków w formularzu pobierającym dane od użytkownika.


  // echo "<pre>";
  // echo '<b>$_POST:</b><br>';
  // print_r($_POST);
  // echo "</pre>";

  // Sprawdzanie czy formularz został poprawnie przesłany:
  if (isset($_POST['numberOfCities']))
  {
    // Przepisanie zawartości do zmiennej:
    $numberOfCities = $_POST['numberOfCities'];

    // Oczyszczenie zawartości z pustych znaków:
    $numberOfCities = trim($numberOfCities);

    // Oczyszczenie zawartości z ewentualnego złośliwego kodu:
    $numberOfCities = htmlentities($numberOfCities, ENT_QUOTES, "UTF-8");

    // Weryfikacja, czy przesłano jakikolwiek tekst:
    if(!(empty($numberOfCities)))
    {
      // Weryfikacja, czy wprowadzona wartość, jest wartością liczbową:
      if(!(is_numeric($numberOfCities)))
      {
        // Wartość nie jest liczbowa. Przekierowanie do poprzedniego dokumentu PHP:
        header("Location: stage1.php");
        exit();
      }
    }
    else
    {
      // Przesłano pusty tekst. Przekierowanie do poprzedniego dokumentu PHP:
      header("Location: stage1.php");
      exit();
    }
  }
  else
  {
    // Nie przesłano wartości liczbowej ilości miast.
    // Przekieruj na poprzedni dokument PHP:
    header("Location: stage1.php");
    exit();
  }

?>

<?php

  // Sprawdzenie czy zostały przesłane poprawnie nazwy miast:
  if(isset($_POST['cities']))
  {
    // Przepisanie zawartości tablicy miast:
    $cities = $_POST['cities'];

    // Przygotowanie wskaźnika błędów:
    $numberOfErrors = 0;

    // Weryfikacja czy wpisano nazwy wszystkich miast:
    foreach($cities as $city)
    {
      // Jesli nazwa miasta jest pusta:
      if(empty($city['name']))
      {
        // Podbij wskaźnik ilości błędów:
        $numberOfErrors++;
      }
    }

    // Sprawdzenie stanu wskaźnika błędów.
    // Jeśli jest on większy od zera, oznacza to, że nie przesłano wszystkich oczekiwanych miast:
    if ($numberOfErrors > 0)
    {
      // Wykonaj przekierowanie do poprzedniego dokumentu PHP:
      header("Location: stage2.php?numberOfCities=$numberOfCities");
      exit();
    }
  }
  else
  {
    // W ogóle nie pojawiła się tablica miast w tablicy $_POST.
    // Wykonaj natychmiastowe przekierowanie do poprzedniego dokumentu PHP:
    header("Location: stage2.php?numberOfCities=$numberOfCities");
    exit();
  }

?>

<?php

  // Sprawdzanie czy zostały przesłane odległości między miastami:
  if (isset($_POST['lines']))
  {
    // Przepisanie zawartości tablicy miast:
    $lines = $_POST['lines'];
   
    // Przygotowanie wskaźnika błędów "nie wpisano nic":
    $numberOfErrorsNotEntered = 0;

    // Weryfikacja, czy wpisano wszystkie odległości (wartości tekstowe):
    foreach($lines as $line)
    {
      // Jeśli nazwa linii długość i/lub linii jest pusta:
      if ((empty($line['name'])) || (empty($line['distance'])))
      {
        // Podbij wskaźnik ilości błędów:
        $numberOfErrorsNotEntered++;
      }
    }

    // Przygotowanie wskaźnika błędów "wpisano wartość nieliczbową":
    $numberOfErrorsNotNumeric = 0;

    // Weryfikacja, czy wpisano wszystkie odległości (jako wartości liczbowe):
    foreach($lines as $line)
    {
      // Jeśli długość linii nie jest wartością liczbową:
      if (!(is_numeric($line['distance'])))
      {
        // Podbij wskaźnik ilości błędów:
        $numberOfErrorsNotNumeric++;
      }
    }

    // Sprawdzenie stanu wskaźników błędów.
    // Jeśli któryś z nich jest większy od zera, oznacza to, że nie przesłano wszystkich oczekiwanych linii (połączeń):
    if (($numberOfErrorsNotEntered > 0) || ($numberOfErrorsNotNumeric > 0))
    {
      // Wystąpiły błędy podczas walidacji. Przekieruj do poprzedniego dokumentu:
      // Ale najpierw przygotuj zbiór parametrów dla przekierowania:
      $array['numberOfCities'] = $_POST['numberOfCities'];
      $array['cities'] = $_POST['cities'];
      $query = http_build_query($array);

      // Wykonaj faktyczne przekierowanie:
      header('Location: stage3.php'.'?'.$query);
    }
  }
  else
  {
    // W ogóle nie pojawiła się tablica połączeń (linii) w tablicy $_POST.
    // Wykonaj natychmiastowe przekierowanie do poprzedniego dokumentu PHP:
    // Ale najpierw przygotuj zbiór parametrów do przekierowania:
    $array['numberOfCities'] = $_POST['numberOfCities'];
    $array['cities'] = $_POST['cities'];
    $query = http_build_query($array);

    // Wykonaj faktyczne przekierowanie:
    header('Location: stage3.php'.'?'.$query);
  }

  // echo "<pre>";
  // print_r($cities);
  // print_r($lines);
  // echo "</pre>";

?>

<?php

  // Sprawdzenie czy zostało przesłane (wskazane) miasto startowe:
  if (isset($_POST['initialCityName']))
  {
    // Przepisanie zawartości do zmiennej:
    $initialCityName = $_POST['initialCityName'];

    // Weryfikacja przy przesłano jakikolwiek tekst:
    if (empty($initialCityName))
    {
      // Przesłano pusty tekst. Przekierowanie do poprzedniego dokumentu PHP.
      // Ale najpierw przygotuj zbiór parametów do przekierowania:
      $array['numberOfCities'] = $_POST['numberOfCities'];
      $array['cities'] = $_POST['cities'];
      $array['lines'] = $_POST['lines'];
      $query = http_build_query($array);

      // Wykonaj faktyczne przekierowanie:
      header('Location: stage4.php'.'?'.$query);
      exit();
    }    
  }
  else
  {
    // Nie przesłano oczekiwanej wartości w tablicy $_POST.
    // Wykonaj natychmiastowe przekierowanie do poprzedniego dokumentu PHP.
    // Ale najpierw przygotuj zbiór parametrów do przekierowania:
    $array['numberOfCities'] = $_POST['numberOfCities'];
    $array['cities'] = $_POST['cities'];
    $array['lines'] = $_POST['lines'];
    $query = http_build_query($array);

    // Wykonaj faktyczne przekierowanie:
    header('Location: stage4.php'.'?'.$query);
    exit();
  }

?>

<?php

  // Weryfikacja czy formularz został prawidłowo przesłany zakoczona pomyślnie.
  // Teraz należy uprościć strukturę dostarczonych danych poprzez przepisanie
  // ich do predefiniowanego obiektu zamówienia, które zostanie wysłane API.

  // Podłączenie pliku klasy obiektu zamówienia:
  require_once __DIR__ . '/class/Order.php';
  // Powołanie obiektu zamówienia:
  $Order = new Order();

  // Uzupełnianie obiektu uzyskanymi danymi od użytkownika:

    // Przepisanie do obiektu zdefiniowanej ilości miast (numberOfCities):
    $Order->numberOfCities = (int) $numberOfCities;

    // Przepisanie do obiektu listy miast:
    foreach ($cities as $city)
    {
      // Dodaj kolejne miasto do listy:
      $Order->cities[] = (string) $city['name'];
    }

    // Przepisanie do obiektu listy linii (połączeń między miastami):
    foreach ($lines as $line)
    {
      // Pobranie nazwy miasta:
      $name = (string) $line['name'];
      // Pobranie długości linii:
      $distance = (double) $line['distance'];

      // Dodaj kolejną linię do listy:
      $Order->lines[$name] = $distance;
    }

    // Wpisanie do obiektu nazwy miasta startowego:
    $Order->initialCityName = (string) $initialCityName;

  // Serializacja gotowego obiektu zamówienia do formatu JSON:
  $json = json_encode($Order, JSON_UNESCAPED_UNICODE);

  // Podłączenie pliku konfiguracji dostępowej do API:
  require_once __DIR__ . '/settings/config.php';

  // Biblioteka cURL:
  // "Umożliwia wysyłanie zapytań HTTP, w tym pobieranie z serwerów stron i plików, 
  // a także wysyłanie treści formularzy. Ułatwia tworzenie aplikacji korzystających z protokołu HTTP."

  // Inicjalizacja cURL-a i utworzenie uchwytu:
  $c = curl_init();

  // Konfiguracja cURL-a:
  
    // Ustawienie odczytu nagłówka HTTP (opcjonalne - do debuggowania)!:
    ## curl_setopt($c, CURLOPT_HEADER, 1);

    // Ustawienie adresu API:
    curl_setopt($c, CURLOPT_URL, $api_location);

    // Ustawienie metody przesyłu danych (wybranie metody POST):
    curl_setopt($c, CURLOPT_POST, 1);

    // Wskazanie jakie dane mają zostać przesłane (zserializowany obiekt JSON):
    curl_setopt($c, CURLOPT_POSTFIELDS, $json);

    // Ustawienie, że informacje zwrotne mają trafić do zmiennej (zamiast wyrzucone na ekran):
    curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);

  // Wykonanie sesji cURL-a. Wynik zostanie zwrócony do zmiennej $response:
  $response = curl_exec($c);

  // Pobranie kodu błędu HTTP:
  $httpcode = curl_getinfo($c, CURLINFO_HTTP_CODE); 

  // Zamknięcie sesji cURL-a:
  curl_close($c);

  // Deserializacja odebranego od API obiektu JSON na obiekt "PHP-owy":
  $response = json_decode($response);

?>

<?php

  // Podejmowanie dalszych działań na podstawie odpowiedzi od API.
  // Najpierw należy sprawdzić kod HTTP otrzymany w odpowiedzi od API:
  if ($httpcode == 201)
  {
    // Otrzmano kod: HTTP 201 - Created - utworzono.
    // Zlecenie zostało utworzone przez API i zlecone do obliczeń.
    if ($response->success == true)
    {
      // W odpowiedzi otrzymano także w obiekcie "sukces".
      // Można kontynuować dalsze działania.

      // Wpisanie do zmiennej lokalnej informacji o tym, że przetworzono pomyślnie:
      $state = "Przetworzono pomyślnie";
      
      // Przepisanie do zmiennej lokalnej otrzymanego kodu błędu:
      $errorCode = $response->errorCode;

      // Przepisanie do zmiennej lokalnej treści otrzymanego komunikatu:
      $message = $response->message;

      // Przepisanie do zmiennej lokalnej odebranego tokena:
      $token = $response->token;

      // Podniesienie flagi, że wszystko zostało jest w porządku.
      // Na podstawie tej flagi zostanie wyświetlony odpowiedni interfejs użytkownika:
      $orderAccepted = true;
    }
    else
    {
      // W odpowiedzi pomimo kodu HTTP nie otrzymano sukcesu.
      // Wystąpił w takim wypadku nieznany błąd.

      // Wpisanie do zmiennej lokalnej informacji o tym, że wystąpił błąd:
      $state = "Błąd przetwarzania";

      // Przepisanie do zmiennej lokalnej otrzymanego kodu błędu:
      $errorCode = $response->errorCode;

      // Przepisanie do zmiennej lokalnej treści otrzymanego komunikatu:
      $message = $response->message;      

      // Opuszczenie flagi, gdyż zamówienie nie zostało przetworzone:
      $orderAccepted = false;
    }
  }
  else
  {
    // Otrzymano każdy dowolny inny kod HTTP niż 201.
    // Oznacza to, że wystąpił jakiś błąd - mniej lub bardziej poważny.

    // Wpisanie do zmiennej lokalnej informacji o tym, że wystąpił błąd:
    $state = "Błąd przetwarzania";

    // Przepisanie do zmiennej lokalnej otrzymanego kodu błędu:
    $errorCode = $response->errorCode;

    // Przepisanie do zmiennej lokalnej treści otrzymanego komunikatu:
    $message = $response->message;
    
    // Opuszczenie flagi, gdyż wystąpił błąd przetwarzania przesłanych danych do API:
    $orderAccepted = false;
  }

  // Teraz kolejnym krokiem będzie narysowanie odpowiedniego interfejsu użytkownika ...
  // Ale to już poniżej.
  
?>



<!DOCTYPE html>
<html lang="pl">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="graphics/icon/gakomi.ico">

    <title>Gakomi - Obliczenia (6)</title>

    <!-- Bootstrap core CSS -->
    <link href="components/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="components/bootstrap/assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="components/bootstrap/theme/starter-template.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <?php
      // Podłączenie kodu JavaScript do obsługi funkcjonalności kopiowania tokena,
      // w przypadku gdy zamówienie zostało zaaakceptowane i jego przetwarzanie zakończyło się sukcesem:
      if ($orderAccepted == true)
      {
        echo '
                <!-- Podłączenie kodu JavaScript koniecznego do kopiowania tokena -->
                <script src="stage6.js"></script>
        ';
      }
    ?>

  </head>

  <body>

    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="index.php"><span class="glyphicon glyphicon-map-marker" style="font-size:medium;"></span> Gakomi</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li><a href="index.php">Start</a></li>
            <li class="active"><a href="stage1.php">Obliczenia</a></li>
            <li><a href="results.php">Wyniki</a></li>
            <li><a href="author.php">Autor</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>

    <div class="container">

        <div class="starter-template" style="padding-bottom: 0px; !important;">
          <div>
            <h1>Projekt Gakomi</h1>
            <p class="lead">
                Zapoznaj się z przygotowanymi dla Ciebie informacjami zwrotnymi.<br>
            </p>
          </div>
        </div>

        <div class="well">
          Etap 1 &raquo; Etap 2 &raquo; Etap 3 &raquo; Etap 4 &raquo; Etap 5 &raquo; <b>Etap 6</b>
        </div>

        <form action="" method="GET">
          <div class="panel panel-default">
              <div class="panel-heading">
                <h3 class="panel-title">Informacja zwrotna od API</h3>
              </div>
              <div class="panel-body" style="padding-top:32px; padding-bottom:32px;">
              
                <div class="form-group">
                  <?php
                    // Wyświetlenie właściwej treści w zależności od tego czy zamówienie
                    // zostało zaakceptowane i jego przetwarzanie zakończyło się sukcesem:
                    if ($orderAccepted == true)
                    {
                      // Zgłoszenie zaakceptowane. Wydrukuj na ekran informację o sukcesie:
                      echo '
                              <p>
                                <b>Użytkowniku aplikacji Gakomi,</b><br>
                                uprzejmie informujemy, iż przesłane przez Ciebie dane były prawidłowe. Obliczenie najkrótszej trasy
                                dla komiwojażera zakończyło się powodzeniem. Poniżej został prezentowany Twój indywidualny żeton (ang. <i>token</i>),
                                który umożliwi Ci późniejszy dostęp do Twojego zlecenia obliczeniowego i jego wyników.
                              </p>
                              <p>
                                Przygotowany dla Ciebie token możesz skopiować do schowka poprzez naciśnięcie przycisku "Kopiuj token".
                              </p>
    
                              <div class="input-group" style="margin-bottom: 10px;">
                                <span class="input-group-addon" id="basic-addon1"><i class="glyphicon glyphicon-tag"></i></span>
                                <input type="text" class="form-control tokenbox" placeholder="token" aria-describedby="basic-addon1" id="tokenbox" value="'.$token.'">
                                <div class="input-group-btn">
                                  <button type="button" class="btn btn-default" id="tokenbutton" onclick="copyTokenFromBox()">Kopiuj token</button>
                                </div>
                              </div>
    
                              <p>
                                Po skopiowaniu tokena możesz udać się do zakładki <b><a href="results.php">Wyniki</a></b>, gdzie będziesz mógł go użyć by ujrzeć swoje wyniki.<br>
                                Token jest wielokrotnego użytku i pozwala na wielokrotny dostęp do wyników, również w późniejszym czasie.<br>
                              </p>
    
                              <p>
                                Możesz również od razu obejrzeć swoje wyniki, klikając tutaj: <b><a href="result.php?token='.$token.'">pokaż wyniki</a></b>.
                              </P>
    
                              <p>
                                Kliknięcie przycisku <b>Zakończ</b> spowoduje przeniesienie Cię do strony powitalnej aplikacji.<br>
                                Jeśli chcesz dodać kolejne zlecenie, naciśnij przycisk <b>Nowe zlecenie</b>.<br>
                              </p>
                      ';
                    }
                    else
                    {
                      // Zgłoszenie nie zostało zaakceptowane. Wystąpił błąd:
                      echo '
                              <p>
                                <b>Użytkowniku aplikacji Gakomi,</b><br>
                                uprzejmie informujemy, iż wystąpił błąd podczas realizacji Twojego zgłoszenia. Powodów takiego stanu rzeczy może być wiele, 
                                np. przesył danych o nieprawidłowym typie lub formacie, problem z przeprowadzeniem obliczeń lub dostępem do bazy danych 
                                lub problem z wygenerowaniem tokena dla zamówienia obliczeniowego złożonego przez Ciebie. 
                                Aby dowiedzieć się więcej na temat błędu, który wystąpił, przeczytaj przygotowane dla Ciebie poniżej informacje.
                              </p>                          

                              <div class="panel panel-default">
                                <div class="panel-heading">
                                  Informacja zwrotna od API
                                </div>
                                <table class="table table-bordered">
                                  <tr>
                                    <td style="max-width: 100px;">Stan:</td>
                                    <td style="font-weight: bold;">'.$state.'</td>
                                  </tr>
                                  <tr>
                                    <td style="max-width: 100px;">Kod błędu:</td>
                                    <td style="font-weight: bold;">'.$errorCode.'</td>
                                  </tr>
                                  <tr>
                                    <td style="max-width: 100px;">Treść komunikatu:</td>
                                    <td style="font-weight: bold;">'.$message.'</td>
                                  </tr>
                                </table>
                              </div>
                              
                              <p>
                                Jeżeli Twoje zamówienie wymaga poprawienia wprowadzonych danych udaj się <b>Wstecz</b> i popraw dane a następnie spróbuj ponownie.<br> 
                                Jeżeli problem będzie się powtarzał skontaktuj się z autorem programu.<br>
                              </p>
                              
                              <p>
                                Jeśli chcesz porzucić przetwarzanie tego zlecenia naciśnij przycisk <b>Porzuć zlecenie</b>.<br>
                                Spowoduje to przekierowanie Cię do strony powitalnej aplikacji.<br>
                              </p>
                      ';
                    }
                  ?>
                </div>
              </div>
              <div class="panel-footer">
                <div style="float:left;">
                  <button type="button" class="btn btn-default btn-xs" onclick="javascript:history.back()"><i class="glyphicon glyphicon-menu-left"></i> Wstecz</button>
                </div>
                <div style="float:right;">
                  <?php
                    // Wyświetlenie właściwych przycisków akcji w zależności od tego czy zamówieni
                    // zostąło zaakceptowane i jego przetwarzanie zakończyło się sukcesem:
                    if ($orderAccepted == true)
                    {
                      // Zgłoszenie zaakceptowane. Wydrukuj na ekran informację o sukcesie:
                      echo '
                            <a href="stage1.php" class="btn btn-default btn-xs" role="button"><i class="glyphicon glyphicon-plus"></i> Nowe zlecenie</a>
                            <a href="index.php" class="btn btn-default btn-xs" role="button"><i class="glyphicon glyphicon-flag"></i> Zakończ</a>
                      ';                      
                    }
                    else
                    {
                      // Zgłoszenie nie zostało zaakceptowane. Wystąpił błąd:
                      echo '
                            <a href="index.php" class="btn btn-default btn-xs" role="button"><i class="glyphicon glyphicon-remove"></i> Porzuć zlecenie</a>  
                      ';
                    }
                  ?>
                </div>
                <div style="clear:both;"></div>
              </div>
          </div>
        </form>

    </div><!-- /.container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="components/jquery/js/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="components/jquery/js/jquery.min.js"><\/script>')</script>
    <script src="components/bootstrap/js/bootstrap.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="components/bootstrap/assets/js/ie10-viewport-bug-workaround.js"></script>
  </body>
</html>