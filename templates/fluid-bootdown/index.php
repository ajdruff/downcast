

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>{SITE_NAME} - {TAG_LINE}</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">

        <!-- styles -->
        {SKIN_CSS}
        
         <!-- script -->

        <style type="text/css">
            body {
                padding-top: 60px;
                padding-bottom: 40px;
            }
            .sidebar-nav {
                padding: 9px 0;
            }

            @media (max-width: 980px) {
                /* Enable use of floated navbar text */
                .navbar-text.pull-right {
                    float: none;
                    padding-left: 5px;
                    padding-right: 5px;
                }
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
                <div class="container-fluid">
                    <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="brand" href="/">{SITE_NAME} </a>
                    <div class="nav-collapse collapse">


                        {NAV_BAR}


                    </div><!--/.nav-collapse -->
                </div>
            </div>
        </div>

        <div class="container-fluid">

            <div class="row-fluid">
                <div class="span2">
                    <div class="well sidebar-nav">

                        {SIDE_BAR}


                    </div><!--/.well -->
                </div><!--/span-->
                <div class="span9">

              {CONTENT}

                </div><!--/span-->

            </div><!--/row-->
            <hr>

            <footer>
                <p>&copy; Company 2013</p> {POWERED_BY}
            </footer>
        </div><!--/.fluid-container-->


        <!-- javascript
================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->

                {SKIN_JS}

</body>
</html>


