
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

    <title>Gakomi - Autor</title>

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
            <li><a href="results.php">Wyniki</a></li>
            <li class="active"><a href="author.php">Autor</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>

    <div class="container">

      <div class="starter-template"  style="padding-bottom: 0px; !important;">
        <h1>Projekt Gakomi</h1>
        <p class="lead">
            Oprogramowanie rozwiązujące problem komiwojażera<br> 
            działające w architekturze klient-serwer w oparciu o technologię REST.<br>
        </p>
      </div>

      <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title">O autorze</h3>
          </div>
          <div class="panel-body">
            <b>Autorem systemu Gakomi jest:</b><br>
            <br>
            Maciej Gapiński<br>
            student II roku informatyki studiów magisterskich<br>
            Wydziału Matematyki i Informatyki<br>
            Uniwersytetu Łódzkiego<br>
            <br>
            <b>Kontakt z autorem systemu:</b><br>
            <ul>
              <li>mail: maciejgapinski90 [na] gmail.com</li>
            </ul>
            <br>
            <b>Strona projektu:</b><br>
            <ul>
              <li style="word-wrap:break-word;">
                <a href="https://github.com/macgap9000/gakomi" target="_blank">https://github.com/macgap9000/gakomi</a>
              </li>
            </ul>
            <br>
            <b>Wykorzystane technologie:</b>
            <ul>
              <li>
                strona serwerowa:
                <ul>
                  <li>
                    serwer aplikacyjny: XAMPP 5.6.12
                    <ul>
                      <li>Apache 2.4.16</li>
                      <li>PHP 5.6.12 + cURL</li>
                      <li>MySQL 5.6.26</li>
                    </ul>
                  </li>
                  <li>
                    wykorzystane oprogramowanie:
                    <ul>
                      <li>random_compat 2.0.10 (MIT)</li>
                      <li>pc_permute (O'Reilly),<br> modified by dAngelov</li>
                    </ul>
                  </li>
                </ul>
              </li>
              <li>
                strona kliencka:
                <ul>
                  <li>
                    wykorzystane oprogramowanie:
                    <ul>
                      <li>HTML5/CSS3 + Bootstrap</li>
                      <li>JavaScript + jQuery</li>
                    </ul>
                  </li>                 
                </ul>
              </li>
            </ul>
            <br>
            <b>Pozostałe informacje:</b>
            <ul>
              <li>
                edytor kodu z obsługą Git:
                <ul>
                  <li>Visual Studio Code 1.15.1</li>
                </ul>
              </li>
              <li>
                dodatkowe oprogramowanie:
                <ul>
                  <li>Postman (Free/EULA)</li>
                  <li>Fiddler (Free/EULA)</li>
                </ul>
              </li>
            </ul>

          </div>
      </div>

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