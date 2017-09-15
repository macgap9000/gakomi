<?php

  // echo "<pre>";
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
      if(is_numeric($numberOfCities))
      {
        // Przekierowanie do następnego dokumentu PHP:
        header("Location: stage2.php?numberOfCities=$numberOfCities");
        exit();
      }
      else
      {
        // Wprowadzona wartość NIE JEST WARTOŚCIĄ LICZBOWĄ:
        $numberOfCitiesErrorIsNotNumeric = true;
      }
    }
    else
    {
      // Przesłano pusty tekst. Podnieś flagę błędu:
      $numberOfCitiesErrorEmpty = true;
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

    <title>Gakomi - Obliczenia (1)</title>

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
          <b>Etap 1</b>
        </div>

        <form action="" method="GET">
          <div class="panel panel-default">
              <div class="panel-heading">
                <h3 class="panel-title">Określenie ilości miast</h3>
              </div>
              <div class="panel-body" style="padding-top:32px; padding-bottom:32px;">
                <div class="form-group <?php if (($numberOfCitiesErrorEmpty == true) || ($numberOfCitiesErrorIsNotNumeric == true)) echo "has-error"; ?>">
                  <label>Wprowadź ilość miast (punktów na mapie):</label>
                  <input class="form-control" name="numberOfCities">
                  
                  <?php
                    
                    // Jeśli wystąpił błąd, bo nie wpisano żadnej wartości:
                    if ((isset($numberOfCitiesErrorEmpty)) && ($numberOfCitiesErrorEmpty == true))
                    {
                      echo '<p class="help-block" style="margin-bottom:0px;">Nie wprowadzono żadnej wartości. Wprowadź wartość liczbową i spróbuj ponownie.</p>';
                    }

                    // Jeśli wystąpił błąd, bo nie wprowadzono wartości liczbowej
                    if ((isset($numberOfCitiesErrorIsNotNumeric)) && ($numberOfCitiesErrorIsNotNumeric == true))
                    {
                      echo '<p class="help-block" style="margin-bottom:0px;">Wprowadzono wartość nieliczbową. Wprowadź liczbę miast i spróbuj ponownie.</p>';
                    }

                  ?>

                  <p>Oczekiwana wartość liczbowa. Na przykład: 4</p>
                </div>
              </div>
              <div class="panel-footer">
                <div style="float:left;">
                  <!-- Brak dostępnego kroku wstecz -->
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