<html>
    <head>
        <script type="text/javascript" src="/application/assets/js/jquery-1.6.1.min.js"></script>
        <script type="text/javascript" src="/application/assets/js/jquery-ui-1.8.13.min.js"></script>
        <script type="text/javascript" src="/application/assets/js/home_functions.js"></script>
        <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>

        <link rel=stylesheet href="/application/assets/css/home.css" type="text/css" />
    </head>

    <body>
    <center>
        <div class="container">

            <div class="leftpanel">
                <ol id="test">
                    <?php
                    for ($i = 1; $i < 10; ++$i)
                    {
                        ?>
                        <li class="ui-widget-content">Item  <?php echo($i); ?></li>;
                        <?php
                    }
                    ?>
                    </ul>
            </div>

            <div class="centerpanel">
                <div class="centergraph"></div>
            </div>

            <div class="rightpanel">
                <div class="logout">
                    <a href="/home/logout">Log out.</a>
                </div>
            </div>

        </div>
    </center>
</body>
</html>