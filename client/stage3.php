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

  // Przepisanie do tablicy listy miast z tablicy $_GET:
  $cities = $_GET['cities'];

  // Przygotowanie pustej tablicy na linie (połączenia między miastami):
  $lines = [];

  // Przygotowanie iteratora ilości linii:
  $lineNumber = 1;

  // Przygotowywanie możliwych kombinacji miast:
  for ($i = 1; $i <= $numberOfCities; $i++)
  {
    // Możliwe kombinacje bez powtórzeń z danym miastem:
    $j = $i + 1;
    for($j; $j <= $numberOfCities; $j++)
    {
      // Dodanie połaczenia miast do tablicy linii:
      $lineName = $cities[$i]['name']."-".$cities[$j]['name'];
      $lines[$lineNumber]['name'] = $lineName;
      $lineNumber++;
    }
  }

?>

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
      if (!(is_numeric($line['distance'])))
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
      // Wszystkie parametry z tablicy $_GET zaakceptowane.
      // Pobierz jej zawartość i przygotuj treść parametrów do przekierowania:
      $query = http_build_query($_GET);

      // Wykonaj faktyczne przekierowanie:
      header('Location: stage4.php'.'?'.$query);
      exit();
    }
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

    <title>Gakomi - Obliczenia (3)</title>

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
                Wprowadź zbiór podstawowych informacji, koniecznych do przeprowadzenia obliczeń.<br>
            </p>
          </div>
        </div>

        <div class="well">
          Etap 1 &raquo; Etap 2 &raquo; <b>Etap 3</b>
        </div>

        <form action="" method="GET">
          <div class="panel panel-default">
              <div class="panel-heading">
                <h3 class="panel-title">Wprowadź odległości między miastami</h3>
              </div>
              <div class="panel-body" style="padding-top:32px; padding-bottom:32px;">

                <div class="form-group">
                  <p style="font-weight:bold;">Dotychczas zgromadzone informacje:</p>
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
                  ?></b>
                </div>
                <div class="form-group">
                  <label>Wprowadź odległości pomiędzy miastami (punktami na mapie) wyrażone w kilometrach:</label>
                  <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" style="margin-bottom:5px;">
                      <tbody>
                        <?php
                          // Określenie ilości połączeń (linii):
                          $numberOfLines = count($lines);

                          // Drukowanie tabelki w zależności od ilości połączeń (linii):
                          for($i=1; $i<=$numberOfLines; $i++)
                          {
                            // Przygotowanie wartości:
                            if (isset($lines))
                            {
                              // Przygotowanie nazwy miasta:
                              $lineName = $lines[$i]['name'];

                              // Przepisanie danych do zmiennych (o ile one istnieją:
                              if (isset($lines[$i]['distance'])) 
                              { 
                                $lineDistance = $lines[$i]['distance'];
                              }
                              else
                              {
                                $lineDistance = "";
                              }
                              
                              if (isset($lines[$i]['isEmpty']))
                              {
                                $lineDistanceNotEnteredError = $lines[$i]['isEmpty'];
                              }
                              else
                              {
                                $lineDistanceNotEnteredError = "";
                              }
                              
                              if (isset($lines[$i]['isNotNumeric']))
                              {
                                $lineDistanceNotNumericError = $lines[$i]['isNotNumeric'];
                              }
                              else
                              {
                                $lineDistanceNotNumericError = "";
                              }

                              // Analiza błędów:
                              if ($lineDistanceNotEnteredError == true)
                              {
                                // Nie wprowadzono długości linii (odległości między miastami):
                                $lineErrorClass = 'has-error';
                                $lineErrorMessage = '<label class="control-label" for="inputError">Wprowadź odległość.</label>';
                              }
                              elseif ($lineDistanceNotNumericError == true)
                              {
                                // Wprowadzona długość linii jest wartością nieliczbową:
                                $lineErrorClass = 'has-error';
                                $lineErrorMessage = '<label class="control-label" for="inputError">Wprowadź wartość liczbową.</label>';
                              }
                              else
                              {
                                // Nie wystąpiły żadne błędy:
                                $lineErrorClass = '';
                                $lineErrorMessage = '';
                              }
                            }

                            echo '
                                    <tr>
                                      <td style="vertical-align:middle;">
                                      '.$lineName.':
                                      <input type="hidden" name="lines['.$i.'][name]" value="'.$lineName.'">
                                      </td>
                                      <td>
                                        <div class="form-group '.$lineErrorClass.'" style="margin:0px;">
                                          <input class="form-control" name="lines['.$i.'][distance]" value="'.$lineDistance.'">'.$lineErrorMessage.'
                                        </div>
                                      </td>
                                    </tr>
                            ';
                          }
                        ?>
                      </tbody>
                    </table>
                  </div>
                  

                  <p>Oczekiwana wartość liczbowa kilometrów. Na przykład: 50</p>
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