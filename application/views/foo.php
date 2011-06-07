<html>
    <head>
        <?php
        // Add the includes from js-css-includes.
        echo(add_includes());
        ?>
    </head>

    <body>
        <div id="container">
            <div id="rightside">
                <fieldset>

                    <legend>Enter site</legend>

                    <font color="purple" >
                    <div id="li_error" class ="error_message">
                        <!-- Errors will be displayed here -->
                    </div>
                    </font>
                    <form id="log_in" class="form">

                        <div class="div_texbox">
                            <p>
                                <label for="li_email">Email</label>
                                <input id="li_email" name="li_email" type="text" class="textbox" id="li_email" />
                            </p>
                        </div>

                        <div class="div_texbox">
                            <p>
                                <label for="li_password">Password</label>
                                <input name="li_password" type="password" class="textbox" id="password" />
                            </p>
                        </div>

                        <div class="button_div">
                            <input type="submit" class="buttons" value="Log In" />
                            <div style="position:relative; font-family:Arial, Helvetica, sans-serif;">
                                <input type="checkbox" name="li_remember" value="1" />&nbsp;Stay logged in
                            </div>
                        </div>
                    </form>
                </fieldset>
            </div>
        </div>
    </body>
</html>