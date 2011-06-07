<html>
    <head>
        <script type="text/javascript" src="/application/assets/js/jquery-1.6.1.min.js"></script>
        <script type="text/javascript" src="/application/assets/js/jquery-ui-1.8.13.min.js"></script>
        <script type="text/javascript" src="/application/assets/js/home_functions.js"></script>

        <link rel=stylesheet href="/application/assets/css/home.css" type="text/css" />
    </head>

    <body>
    <center>
        <div class="container">

            <div class="left_panel">
                <ul id="test">
                    <?php
                    for ($i = 1; $i < 10; ++$i)
                    {
                        echo("<li>Item " . $i . "</li>");
                    }
                    ?>
                </ul>
            </div>

            <div class="centerpanel">

                <div class="centergraph">

                </div>
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