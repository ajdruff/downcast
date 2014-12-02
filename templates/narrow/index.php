<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
        <title>{SITE_NAME} - {SITE_TAG_LINE}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
     {CSS}
    <style type="text/css">
      body {
        padding-top: 20px;
        padding-bottom: 40px;
      }

      /* Custom container */
      .container-narrow {
        margin: 0 auto;
        max-width: 700px;
      }
      .container-narrow > hr {
        margin: 30px 0;
      }

      /* Main marketing message and sign up button */
      .jumbotron {
        margin: 60px 0;
        text-align: center;
      }
      .jumbotron h1 {
        font-size: 72px;
        line-height: 1;
      }
      .jumbotron .btn {
        font-size: 21px;
        padding: 14px 24px;
      }

      /* Supporting marketing content */
      .marketing {
        margin: 60px 0;
      }
      .marketing p + h4 {
        margin-top: 28px;
      }
    </style>
      

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="../assets/js/html5shiv.js"></script>
    <![endif]-->

    <!-- Fav and touch icons -->

  </head>

  <body>

    <div class="container-narrow">

      <div class="masthead">
{NAV_BAR}
<h3 class="muted"><a href="/">{SITE_NAME}</a></h3>
      </div>

      <hr>

      <div class="jumbotron">
          {CONTENT}

      </div>

      <hr>

      <div class="row-fluid marketing">
        <div class="span6">
{SUBHEADING1}
        </div>

        <div class="span6">
{SUBHEADING2}
        </div>
      </div>

      <hr>

      <div class="footer">
         <p>&copy; Company 2013</p> {POWERED_BY}
      </div>

    </div> <!-- /container -->

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
               {JS}
  </body>
</html>
