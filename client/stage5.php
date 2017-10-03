<?php

  // echo "<pre>";
  // echo '<b>$_GET:</b><br>';
  // print_r($_GET);
  // echo "</pre>";

  // Sprawdzanie czy formularz został poprawnie przesłany:
  if (isset($_GET['numberOfCities']))
  {
    // Przepisanie zawartości do zmiennej:
    $numberOfCities = $_GET['numberOfCities'];

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
  if(isset($_GET['cities']))
  {
    // Przepisanie zawartości tablicy miast:
    $cities = $_GET['cities'];

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
    // W ogóle nie pojawiła się tablica miast w tablicy $_GET.
    // Wykonaj natychmiastowe przekierowanie do poprzedniego dokumentu PHP:
    header("Location: stage2.php?numberOfCities=$numberOfCities");
    exit();
  }

?>

<?php

  // Sprawdzanie czy zostały przesłane odległości między miastami:
  if (isset($_GET['lines']))
  {
    // Przepisanie zawartości tablicy miast:
    $lines = $_GET['lines'];
   
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
      $array['numberOfCities'] = $_GET['numberOfCities'];
      $array['cities'] = $_GET['cities'];
      $query = http_build_query($array);

      // Wykonaj faktyczne przekierowanie:
      header('Location: stage3.php'.'?'.$query);
    }
  }
  else
  {
    // W ogóle nie pojawiła się tablica połączeń (linii) w tablicy $_GET.
    // Wykonaj natychmiastowe przekierowanie do poprzedniego dokumentu PHP:
    // Ale najpierw przygotuj zbiór parametrów do przekierowania:
    $array['numberOfCities'] = $_GET['numberOfCities'];
    $array['cities'] = $_GET['cities'];
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
  if (isset($_GET['initialCityName']))
  {
    // Przepisanie zawartości do zmiennej:
    $initialCityName = $_GET['initialCityName'];

    // Weryfikacja przy przesłano jakikolwiek tekst:
    if (empty($initialCityName))
    {
      // Przesłano pusty tekst. Przekierowanie do poprzedniego dokumentu PHP.
      // Ale najpierw przygotuj zbiór parametów do przekierowania:
      $array['numberOfCities'] = $_GET['numberOfCities'];
      $array['cities'] = $_GET['cities'];
      $array['lines'] = $_GET['lines'];
      $query = http_build_query($array);

      // Wykonaj faktyczne przekierowanie:
      header('Location: stage4.php'.'?'.$query);
      exit();
    }    
  }
  else
  {
    // Nie przesłano oczekiwanej wartości w tablicy $_GET.
    // Wykonaj natychmiastowe przekierowanie do poprzedniego dokumentu PHP.
    // Ale najpierw przygotuj zbiór parametrów do przekierowania:
    $array['numberOfCities'] = $_GET['numberOfCities'];
    $array['cities'] = $_GET['cities'];
    $array['lines'] = $_GET['lines'];
    $query = http_build_query($array);

    // Wykonaj faktyczne przekierowanie:
    header('Location: stage4.php'.'?'.$query);
    exit();
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

    <title>Gakomi - Obliczenia (5)</title>

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
                Potwierdź wprowadzone przez Ciebie informacje.<br>
            </p>
          </div>
        </div>

        <div class="well">
          Etap 1 &raquo; Etap 2 &raquo; Etap 3 &raquo; Etap 4 &raquo; <b>Etap 5</b>
        </div>

        <form action="stage6.php" method="POST">
          <div class="panel panel-default">
              <div class="panel-heading">
                <h3 class="panel-title">Podsumowanie</h3>
              </div>
              <div class="panel-body" style="padding-top:32px; padding-bottom:32px;">
              
                <div class="form-group">
                  <p style="font-weight:bold;">Zbiór dotychczas zgromadzonych informacji:</p>
                </div>              

                <div class="well">
                  Ilość wprowadzonych miast: <b><?php echo $numberOfCities; ?></b>
                  <input type="hidden" name="numberOfCities" value="<?php if (isset($numberOfCities)) echo $numberOfCities; ?>"><br>
                  Wprowadzone miasta:<b>
                  <?php 
                    // Ustawienie iteratora na pierwszą wartość:
                    $i = 1;

                    // Drukowanie na ekran listy miast:
                    foreach($cities as $city)
                    {
                      // Odpowiednie drukowanie tekstu:
                      if (!($i == $numberOfCities))
                      {
                        // Jeśli 1, 2, ... przedostatnie miasto, to drukuj:
                        // "Miasto, Miasto, "
                        echo $city['name'].", ";
                      }
                      else
                      {
                        // Jeśli ostatnie miasto, to drukuj:
                        // "Miasto.
                        echo $city['name'].".";
                      }

                      // Dodanie ukrytego pola formularza, by można było raz wypisane miasta
                      // przesłać ponownie do tego samego pliku za pomocą $_GET:
                      echo '<input type="hidden" name="cities['.$i.'][name]" value="'.$city['name'].'">';

                      // Inkrementacja licznika - w celu przetwarzania następnego wpisu:
                      $i++;
                    }
                  ?></b><br>
                  Odległości pomiędzy miastami (punktami na mapie):<b>
                  <ul style="margin-bottom:0px;">
                  <?php
                    // Ustawienie iteratora na pierwszą wartość:
                    $i = 1;

                    // Drukowanie na ekran listy miast/punktów na mapie/linii:
                    foreach($lines as $line)
                    {
                      // Przepisanie do zmiennych:
                      $lineName = $line['name'];
                      $lineDistance = $line['distance'];

                      // Drukowanie na ekran:
                      echo '<li>'.$lineName.' ('.$lineDistance.' km)</li>
                      <input type="hidden" name="lines['.$i.'][name]" value="'.$lineName.'">
                      <input type="hidden" name="lines['.$i.'][distance]" value="'.$lineDistance.'">';

                      // Inkrementacja licznika - w celu przetwarzania następnego wpisu:
                      $i++;
                    }
                  ?>
                  </ul></b>
                Miasto początkowe: <b><?php echo $initialCityName; ?></b>
                <input type="hidden" name="initialCityName" value="<?php if (isset($initialCityName)) echo $initialCityName; ?>"><br>
                </div>

                <div class="form-group">
                  <p>Wszystkie niezbędne dane, konieczne do rozwiązania problemu komiwojażera zostały zebrane są gotowe do dalszego przetwarzania.</p>
                  <p>Zostaną one specjalnie opakowane a następnie przesłane do serwera API wykonującego obliczenia.</b></p>
                  <p>Upewnij się czy zostały one wpisane prawidłowo, gdyż w dalszym etapie poprawienie ich nie będzie możliwe.</p>
                  <p>Aby kontynuować, naciśnij przycisk <b>Dalej</b></p>
                </div>

              </div>
              <div class="panel-footer">
                <div style="float:left;">
                  <button type="button" class="btn btn-default btn-xs" onclick="javascript:history.back()"><i class="glyphicon glyphicon-menu-left"></i> Wstecz</button>
                </div>
                <div style="float:right;">
                  <button type="submit" class="btn btn-default btn-xs">Dalej <i class="glyphicon glyphicon-menu-right"></i></button>
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