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

  // Sprawdzenie czy zostały przesłane nazwy miast:
  if (isset($_GET['cities']))
  {
    // Przepisanie zawartości tablicy miast:
    $cities = $_GET['cities'];

    // Przygotowanie wskaźnika błędów:
    $numberOfErrors = 0;

    // Weryfikacja, czy wpisano nazwy wszystkich miast:
    foreach($cities as &$city)
    {
      // Jeśli nazwa miasta jest pusta:
      if (empty($city['name']))
      {
        // To podnieś flagę błędu:
        $city['isEmpty'] = true;

        // Podbij wskaźnik ilości błedów:
        $numberOfErrors++;
      }
      else
      {
        // Nazwa miasta nie jest pusta:
        $city['isEmpty'] = false;
      }
    }

    // Sprawdzenie czy można przesłać formularz
    // na podstawie ilości błędów które wystąpiły:
    if ($numberOfErrors == 0)
    {
      // Wszystkie parametry z tablicy $_GET zaakceptowane.
      // Pobierz jej zawartość i przygotuj treść parametrów do przekierowania:
      $query = http_build_query($_GET);

      // Wykonaj faktyczne przekierowanie:
      header('Location: stage3.php'.'?'.$query);
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

    <title>Gakomi - Obliczenia (2)</title>

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
          Etap 1 &raquo; <b>Etap 2</b>
        </div>

        <form action="" method="GET">
          <input type="hidden" name="numberOfCities" value="<?php if (isset($numberOfCities)) echo $numberOfCities; ?>">
          <div class="panel panel-default">
              <div class="panel-heading">
                <h3 class="panel-title">Wprowadź nazwy miast</h3>
              </div>
              <div class="panel-body" style="padding-top:32px; padding-bottom:32px;">

                <div class="form-group">
                  <p style="font-weight:bold;">Dotychczas zgromadzone informacje:</p>
                </div>                       

                <div class="well">
                  Ilość wprowadzanych miast: <b><?php echo $numberOfCities; ?></b><br>
                </div>
                <div class="form-group">                
                  <label>Wprowadź nazwy poszczególnych miast (punktów na mapie):</label>
                  <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" style="margin-bottom:5px;">
                      <tbody>
                        <?php
                          // Drukowanie tabelki w zależności od ilości miast:
                          for($i=1; $i<=$numberOfCities; $i++)
                          {
                            // Przygotowanie wartości:
                            if (isset($cities)) 
                            { 
                              $cityName = $cities[$i]['name'];
                              $cityError = $cities[$i]['isEmpty'];

                              // Jeśli nie wprowadzono nazwy miasta, wydrukuj błąd:
                              if ($cityError == true)
                              {
                                $cityErrorClass = 'has-error';
                                $cityErrorMessage = '<label class="control-label" for="inputError">Wprowadź nazwę miasta.</label>';
                              }
                              else
                              {
                                $cityErrorClass = '';
                                $cityErrorMessage = '';
                              }
                            }
                            else
                            {
                              $cityName = '';
                              $cityErrorClass = '';
                              $cityErrorMessage = '';
                            }

                            echo '
                                    <tr>
                                      <td style="vertical-align:middle;">Nazwa miasta '.$i.':</td>
                                      <td>
                                        <div class="form-group '.$cityErrorClass.'" style="margin:0px;">
                                          <input class="form-control" name="cities['.$i.'][name]" value="'.$cityName.'">'.$cityErrorMessage.'
                                        </div>
                                      </td>
                                    </tr>
                            ';

                          }
                        ?>
                      </tbody>
                    </table>
                  </div>
                  

                  <p>Oczekiwane wartości tekstowe w postaci nazw miast.<br> Na przykład: Płock</p>
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