<?php

  // Niniejszy dokument PHP służy do wyświetlania zleceń obliczeniowych
  // i ich wyników na podstawie dostarczonego tokena za pomocą metody GET.

  // Możliwe stany w dokumencie results.php:
  // 1. Przesłany formularz (+ przesłanie do API)
  //      - poprawne wyniki
  //      - wystąpił błąd
  // 2. Nieprzesłany formularz (zaproponuj wprowadzenie tokena)

  // echo "<pre>";
  // echo '<b>$_GET:</b><br>';
  // print_r($_GET);
  // echo "</pre>";

  // Sprawdzanie czy formularz został poprawnie przesłany:
  if (isset($_GET['token']))
  {
    // Przesłano tekst zawierający tokena. Należy podnieść flagę, 
    // że dostarczono tokena. Oznacza to tym samym, że formularz został przesłany:
    $tokenReceived = true;

    // Przepisanie zawartości do zmiennej:
    $token = $_GET['token'];

    // Oczyszczenie zawartości z pustych znaków:
    $token = trim($token);

    // Oczyszczenie zawartości z ewentualnego złośliwego kodu:
    $token = htmlentities($token, ENT_QUOTES, "UTF-8");

    // Weryfikacja, czy przesłano jakikolwiek tekst:
    if (!(empty($token)))
    {
      // Przetworzenie odebranego tokena na query-stringa GET:

        // Utworzenie tablicy parametrów:
        $parameters['token'] = $token;

        // Przygotowanie query-stringa na podstawie tablicy parametrów:
        $query = http_build_query($parameters);

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

        // Ustawienie adresu API oraz dołączenie do niego query-stringa GET:
        curl_setopt($c, CURLOPT_URL, $api_location.'?'.$query);

        // Ustawienie metody przesyłu danych (wybranie metody GET):
        ## nie trzeba nic ustawiać (GET jest domyślną metodą)

        // Wskazanie jakie dane mają zostać przesłane (tablica pametrów zawierająca token):
        ## nie trzeba nic wskazywać - jest to dołączane jako query-string GET do adresu URL API

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

      // Podejmowanie dalszych działań na podstawie odpowiedzi od API.
      // Najpierw należy sprawdzić kod HTTP otrzymany w odpowiedzi od API:
      if ($httpcode == 200)
      {
        // Otrzmano kod: HTTP 200 - OK - zwrócenie zawartości żądanego dokumentu.
        // Zlecenie wraz z wynikami zostało odnalezione w bazie danych na podstawie przesłanego tokena.
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

          // Przepisanie do obiektu lokalnego odebranej paczki danych (bundle)
          // zawierającej treść zamówienia obliczeniowego oraz wyniki działań:
          $objBundle = $response->bundle;

          // Podniesienie flagi, że paczka danych została odebrana i wszystko jest w porządku.
          // Na podstawie tej flagi zostanie wyświetlony odpowiedni interfejs użytkownika:
          $bundleReceived = true;

          // Ponieważ łatwiej się operuje na danych tablicowych niż obiektach,
          // należy przepisać dane z obiektu do lokalnych zmiennych (w tym tablicowych):

            // Przepisanie do zmiennej roboczej ilości miast:
            $numberOfCities = (int) $objBundle->numberOfCities;
            // Przepisanie do tablicy roboczej listy miast:
            $cities = (array) $objBundle->cities;
            // Przepisanie do tablicy roboczej listy połączeń (linii) między miastami:
            $lines = (array) $objBundle->lines;
            // Przepisanie do zmiennej roboczej nazwy miasta startowego:
            $initialCityName = (string) $objBundle->initialCityName;
            // Przepisanie do tablicy roboczej wyniku (najkrótszej trasy - złożona jest z miast):
            $route = (array) $objBundle->route;
            // Przepisanie do zmiennej roboczej wyniku (długości trasy):
            $mileage = (double) $objBundle->mileage;
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

          // Opuszczenie flagi, gdyż paczka danych nie została odebrana:
          $bundleReceived = false;
        }
      }
      else
      {
        // Otrzymano każdy dowolny inny kod HTTP niż 200.
        // Oznacza to, że wystąpił jakiś błąd - mniej lub bardziej poważny.

        // Wpisanie do zmiennej lokalnej informacji o tym, że wystąpił błąd:
        $state = "Błąd przetwarzania";

        // Przepisanie do zmiennej lokalnej otrzymanego kodu błędu:
        $errorCode = $response->errorCode;

        // Przepisanie do zmiennej lokalnej treści otrzymanego komunikatu:
        $message = $response->message;
        
        // Opuszczenie flagi, gdyż paczka danych nie została odebrana:
        $bundleReceived = false;
      }

      // Teraz kolejnym krokiem będzie narysowanie odpowiedniego interfejsu użytkownika ...
      // Ale to już poniżej.
    }
    else
    {
      // Przesłano pusty tekst. Przekierowanie do początkowego formularza wynikowego:
      header("Location: results.php");
      exit();
    }
  }
  else
  {
    // Nie przesłano tekstu zawierającego tokena. Należy opuścić flagę, 
    // Oznacza to tym samym, że formularz nie został przesłany:
    $tokenReceived = false;
  }

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

    <title>Gakomi - Wyniki</title>

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
            <li><a href="stage1.php">Obliczenia</a></li>
            <li class="active"><a href="results.php">Wyniki</a></li>
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
                <?php
                  // Wyświetlenie odpowiedniego tekstu w zależności od tego czy przesłano formularz (tokena):
                  if ($tokenReceived == true)
                  {
                    // Formularz (token) został przesłany:
                    echo 'Zapoznaj się z przygotowanymi dla Ciebie informacjami zwrotnymi.<br>';
                  }
                  else
                  {
                    // Formularz (token) nie został jeszcze przesłany:
                    echo 'Uzyskaj dostęp do wyników obliczeń dzięki otrzymanemu tokenowi.<br>';
                  }
                ?>
            </p>
          </div>
        </div>

        <form action="" method="GET">
          <div class="panel panel-default">
              <div class="panel-heading">
                <h3 class="panel-title">
                  <?php
                    // Wyświetlenie odpowiedniego tekstu w zależności od tego czy przesłano formularz (tokena):
                    if ($tokenReceived == true)
                    {
                      // Formularz (token) został przesłany:
                      echo 'Informacja zwrotna od API';
                    }
                    else
                    {
                      // Formularz (token) nie został jeszcze przesłany:
                      echo 'Wprowadź swój token';
                    }
                  ?>
                </h3>
              </div>
              <div class="panel-body" style="padding-top:32px; padding-bottom:32px;">
              
                <div class="form-group">
                  <?php
                    // Sprawdzenie czy formularz został w ogóle przesłany:
                    if ($tokenReceived == true)
                    {
                      // Formularz został przesłany.
                      // Teraz należy sprawdzić, czy otrzymana odpowiedź jest pozytywna czy negatywna:
                      if ($bundleReceived == true)
                      {
                        // Paczka danych na temat zlecenia i wyników została otrzymana.
                        // Wydrukuj informacje o paczce danych użytkownikowi na ekran:
                        echo '
                                <p>
                                  <b>Użytkowniku aplikacji Gakomi,</b><br>
                                  uprzejmie informujemy, iż przesłany przez Ciebie token został odnaleziony w bazie danych aplikacji Gakomi. 
                                  Poniżej zostały zaprezentowane wprowadzone przez Ciebie informacje związanie z zamówieniem obliczeniowym oraz wyniki obliczeń.<br>
                                </p>

                                <br>

                                <div class="form-group">
                                  <p style="font-weight:bold;">Token zamówienia:</p>
                                </div>

                                <div class="well" style="word-wrap:break-word;">
                                  Token: <span class="token" style="font-weight: bold;">'.$token.'</span><br>
                                </div>

                                <div class="form-group">
                                  <p style="font-weight:bold;">Treść zamówienia obliczeniowego:</p>
                                </div>
                                
                                <div class="well">
                                  Ilość wprowadzonych miast: <b>'.$numberOfCities.'</b><br>
                                  Wprowadzone miasta: <b>';
                                  // Drukowanie wszystkich miast na ekran:
                                  // np. "Kutno, Warszawa, Poznań."
                                  for ($i = 0; $i <= $numberOfCities-1; $i++)
                                  {
                                    // Odpowiednie drukowanie tekstu:
                                    if (!($i == $numberOfCities-1))
                                    {
                                      // Jeśli 1, 2, ... przedostatnie miasto, to drukuj:
                                      // np. "Kutno, Warszawa "
                                      echo $cities[$i].", ";
                                    }
                                    else
                                    {
                                      // Jeśli ostatnie miasto, to drukuj:
                                      // np. "Poznań."
                                      echo $cities[$i].".";
                                    }
                                  } 
                        echo '    </b><br>';
                        echo '    Odległości pomiędzy miastami (punktami na mapie):<b>
                                  <ul style="margin-bottom:0px;">';
                                    // Drukowanie wszystkich linii (połączeń między miastami)
                                    // na ekran, np. "Kutno-Warszawa (130 km)"
                                    foreach ($lines as $line => $distance)
                                    {
                                      echo '<li>'.$line.' ('.$distance.' km)</li>';
                                    }
                        echo '    </ul></b>';
                        echo '    Miasto początkowe: <b>'.$initialCityName.'</b><br>';
                        echo '  </div>';

                        echo '  <div class="form-group">
                                  <p style="font-weight:bold;">Wyniki obliczeń:</p>
                                </div>

                                <div class="well">
                                  Najkrótsza odnaleziona trasa: <b>
                                  <ol type="1" style="margin-bottom:0px;">';
                                    // Drukowanie kolejnych miast trasy:
                                    foreach ($route as $city)
                                    {
                                      echo '<li>'.$city.'</li>';
                                    }
                        echo '    </ol></b>';
                        echo '    Długość tej trasy wynosi: <b>'.$mileage.' km</b><br>';
                        echo '  </div>';
                                
                        echo '
                                <p>
                                  Możesz powrócić ponownie do prezentowanych wyników w późniejszym czasie. Token jest przeznaczony do wielokrotnego użytku i nigdy nie wygasa.<br>
                                  Polecane jest dodanie aktualnie wyświetlanej strony do zakładek by szybciej powrócić do prezentowanych wyników w przyszłości.<br>
                                </p>
    
                                <p>
                                  Kliknięcie przycisku <b>Zakończ</b> spowoduje przeniesienie Cię do strony powitalnej aplikacji.<br>
                                </p>

                                <p>
                                  Dziękujemy za skorzystanie z aplikacji Gakomi.
                                </p>
                        ';
                      }
                      else
                      {
                        // Paczka danych nie została otrzymana. Otrzymano błąd ze strony API.
                        // Wydrukuj informacje o zainstniałym problemie użytkownikowi na ekran:
                        echo '
                                <p>
                                  <b>Użytkowniku aplikacji Gakomi,</b><br>
                                  uprzejmie informujemy, iż wystąpił błąd podczas przetwarzania Twojego żądania. Dotyczy ono uzyskania informacji o zleceniu i jego wynikach. 
                                  Powodów takiego stanu rzeczy może być wiele, np. podany przez Ciebie token może nie istnieć, 
                                  został on przesłany w nieodpowiednim formacie lub wystąpił problem z dostępem do bazy danych. 
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
                                  Jeżeli problem z obsługą Twojego żądania jest zwiazany z tokenem, wykonaj krok <b>Wstecz</b> i sprawdź czy został on podany poprawnie
                                  a następnie spróbuj ponownie. Ponieważ token jest złożony z 32-znakowego ciągu losowych znaków, istnieje duże prawdopodobieństwo, że został on wprowadzony z błędem.<br>
                                  Jeżeli problem będzie się powtarzał skontaktuj się z autorem programu.<br>
                                </p>
                                
                                <p>
                                Jeśli chcesz porzucić przetwarzanie tego żądania naciśnij przycisk <b>Porzuć żądanie</b>.<br>
                                Spowoduje to przekierowanie Cię do strony powitalnej aplikacji.<br>
                                </p>
                        ';                        
                      }
                    }
                    else
                    {
                      // Formularz nie został przesłany. Zaproponuj użytkownikowi wprowadzenie tokena:
                      echo '
                              <p>
                                Aby uzyskać informacje na temat złożonego zamówienia obliczeniowego i jego wyników, wprowadź poniżej
                                swój token zamówienia.<br> Jeśli aktualnie posiadasz token zapisany w swoim schowku systemowym, możesz go wkleić
                                używając kombinacji klawiszy <b>Ctrl+V</b>.
                              </p>

                              <div class="input-group" style="margin-bottom: 10px;">
                                <span class="input-group-addon" id="basic-addon1"><i class="glyphicon glyphicon-tag"></i></span>
                                <input type="text" class="form-control tokenbox" placeholder="token" aria-describedby="basic-addon1" id="tokenbox" name="token">
                                <div class="input-group-btn">
                                  <button type="button" class="btn btn-default" id="tokenbutton" data-toggle="modal" data-target="#myModal">Wklej token</button>
                                </div>
                              </div>
                              
                              <!-- Ukryty div okna modal-->
                              <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                                  <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                      <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                                        <h4 class="modal-title" id="myModalLabel">Komunikat</h4>
                                      </div>
                                      <div class="modal-body">

                                        <p>Funkcjonalność automatycznego wklejania zawartości schowka do pola tokena jest niedostępna.
                                        Powodem takiego stanu rzeczy są względy <b>bezpieczeństwa</b> pracy systemu operacyjnego.
                                        Bezpośredni dostęp do zawartości schowka użytkownika byłby dużym nadużyciem.</p>

                                        <p>Jeśli korzystasz aktualnie z aplikacji Gakomi za pośrednictwem swojego komputera PC,
                                        i posiadasz token zapisany w swoim schowku, możesz go wkleić do pola tokena, 
                                        poprzez zaznaczenie pola i wykonanie kombinacji klawiszy <b>Ctrl+V</b>.</p>

                                        <p><img src="graphics/token/paste_token_on_pc.png" height="54" width="204"></p>
                                        
                                        <br>

                                        <p>Jeśli korzystasz aktualnie z aplikacji Gakomi za pośrednictwem swojego komputera Mac,
                                        i posiadasz token zapisany w swoim schowku, możesz go wkleić do pola tokena,
                                        poprzez zaznaczenie pola i wykonanie kombinacji klawiszy <b>Command+V</b>, czyli: <b>&#8984;Cmd+V</b>.</p>

                                        <p><img src="graphics/token/paste_token_on_mac.png" height="54" width="204"></p>

                                        <br>

                                        <p>Jeśli korzystasz aktualnie z aplikacji za pomocą urządzenia mobilnego (np. Android, iOS, Windows)
                                        i posiadasz token zapisany w swoim schowku, możesz go wkleić poprzez dłuższe dotknięcie palcem
                                        pola tokenu a następnie wybranie polecenia <b>Wklej</b> lub równoważnego.</p>

                                        <p><img src="graphics/token/paste_token_on_mobile.png" height="81" width="204"></p>

                                        <br>

                                        <p>Inną alternatywną formą wprowadzenia tokena jest ręczne wpisanie go za pomocą klawiatury komputera
                                        lub telefonu. Ze względu jednak na to iż token składa się z 32-znaków losowych, istnieje duże
                                        prawdopodobieństwo pomyłki przy jego wprowadzaniu.</p>
                                        
                                        <p>Jeśli z góry wiesz, iż będziesz potrzebował później dostępu do wyników swojego zamówienia obliczeniowego, 
                                        dodaj do zakładek stronę prezentującą Twój wynik.</p>

                                      </div>
                                      <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Zrozumiano</button>
                                      </div>
                                    </div>
                                  </div>
                              </div>                              

                              <p>
                                Po wprowadzeniu tokena, naciśnij przycisk <b>Prześlij token</b> aby przesłać żądanie.
                              </p>
                      ';
                    }
                  ?>
                </div>
              </div>
              <div class="panel-footer">
                <div style="float:left;">
                  <?php
                    // Wyświetlenie właściwych przycisków akcji w zależności od tego czy przeslano formularz (tokena):
                    if ($tokenReceived == true)
                    {
                      // Formularz (token) został przesłany:
                      // (dostępny przycisk "Wstecz")
                      echo '<button type="button" class="btn btn-default btn-xs" onclick="javascript:history.back()"><i class="glyphicon glyphicon-menu-left"></i> Wstecz</button>';
                    }
                  ?>
                </div>
                <div style="float:right;">
                  <?php
                    // Wyświetlenie właściwych przycisków akcji w zależności od tego czy przeslano formularz (tokena):
                    if ($tokenReceived == false)
                    {
                      // Formularz (token) nie został przesłany:
                      // (dostępny więc przycisk "Prześlij token")
                      echo '<button type="submit" class="btn btn-default btn-xs"><i class="glyphicon glyphicon-send"></i> Prześlij token</button>';                      
                    }
                    else
                    {
                      // Formularz (token) został przesłany.
                      // Sprawdzenie czy otrzymano paczkę danych:
                      if ($bundleReceived == true)
                      {
                        // Otrzymano paczkę danych. Została ona wyświetlona prawidłowo.
                        // Zaproponuj użytkownikowi opuszczenie aktualnego widoku wyniku:
                        echo '<a href="index.php" class="btn btn-default btn-xs" role="button"><i class="glyphicon glyphicon-flag"></i> Zakończ</a>';
                      }
                      else
                      {
                        // Nie otrzymano paczki danych. Wystąpił więc bład przetwarzania.
                        // Zaproponuj także użytkownikowi przerwanie przetwarzania żądania:
                        echo '<a href="index.php" class="btn btn-default btn-xs" role="button"><i class="glyphicon glyphicon-remove"></i> Porzuć źądanie</a>';
                      }
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