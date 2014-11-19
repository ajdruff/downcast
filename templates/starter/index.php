<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
        <title>{SITE_NAME} - {TAG_LINE}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
       {SKIN_CSS}
    <style>
      body {
        padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
      }
    </style>
         

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="../assets/js/html5shiv.js"></script>
    <![endif]-->

    <!-- Fav and touch icons -->

  </head>

  <body>

    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="brand" href="/">{SITE_NAME}</a>
          <div class="nav-collapse collapse">
{NAV_BAR}
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>

    <div class="container">

  {CONTENT}	
            <footer>
                <p>&copy; Company 2013</p> {POWERED_BY}
            </footer>
    </div> <!-- /container -->

    <!-- Le javascript
    ================================================== -->
                {SKIN_JS}

  </body>
</html>
