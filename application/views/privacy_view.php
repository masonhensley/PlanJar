<!doctype html>
<html>  
    <head> 
        <title>Privacy</title>

        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

        <!-- CSS -->
        <link rel=stylesheet href="/application/assets/css/privacy.css" type="text/css" />
        <link href='http://fonts.googleapis.com/css?family=Ubuntu' rel='stylesheet' type='text/css' />
    </head>

    <body>
        <div class ="top_panel">
            <div class = "inside_top_panel">
                <a href="/home">
                    <img src='/application/assets/images/pj_logo_white_text.png' style="float: left; margin-left:30px; height:35px; position:relative; top:5px;"/>
                </a>
                <div class="top_links">
                    <?php
                    if ($this->ion_auth->logged_in())
                    {
                        ?>
                        <a href="/home" id="profile_link" style="position:absolute; top:11px; left:225px;">Home</a>
                        <a href='/auth/logout' id="profile_link" style="position:absolute; top: 11px; left: 293px;">Log Out</a>
                        <?php
                    }
                    ?>
                </div>  
            </div>
        </div>
        <div id="container">

            <h2> Privacy Policy <span class="arrow"></span> </h2>

            <div id="main">  

                At Jarof, Inc., parent company of PlanJar, we value your privacy a great deal. Almost as much as we value the ability to take the data you give us and slice, dice, julienne, mash,
                puree and serve it to our business partners, which may include third-party advertising networks, data brokers, networks of affiliate sites, parent companies, subsidiaries, and other entities,
                none of which we'll bother to list here because they can change from week to week and, besides, we know you're not really paying attention. You will notice on our site that we do our
                best to shield your identity to the masses by lumping you into your social groups, and we do our best to apply that philosophy across the board.
                <br><br> 
                We'll also share all of this information with the government as bound by applicable laws or in accordance with proper court subpoenas.
                <br><br> 
                Remember, when you visit our Web site, our Web site is also visiting you, and we've brought a dozen or more friends with us, depending on how many ad networks and third-party data services
                we use. We're not going to tell which ones, though you could probably figure this out by carefully watching the different URLs that flash across the bottom of your browser as each page loads or
                when you mouse over various bits.  We use some data analytics packages developed by guys who specialize in that kind of stuff so we can interpret information quickly to make our product better
                for you, the user.
                <br><br>  
                Furthermore, each of these sites may leave behind a little gift known as a cookie -- a text file filled with inscrutable gibberish that allows various computers around the globe to identify you, including
                your preferences, browser settings, which parts of the site you visited, which ads you clicked on, and whether you actually use a part of our site or not.
                <br><br> 
                Those same cookies may let our advertising and data broker partners track you across other site you visit, then dump all of your information into a huge database attached to a unique ID number,
                which they may sell without ever notifying you or asking for permission.
                <br><br> 
                Also, we collect your IP address, which might change every time you log on but probably doesn't. At the very least, your IP address tells us the name of your ISP and the city where you live; with a
                legal court order, it can also give us your name and billing address (see subpoenas, above).
                <br><br> 
                Besides your IP, we record some specifics about your operating system and browser. Amazingly, this information (known as your user agent string) can be enough to narrow you down to one of a
                few hundred people on the internet all by its lonesome. We didn't come up with this stuff and don't really use it, but we may employ this information to make the site perform best for you, the user.
                Isn't technology wonderful?
                <br><br> 
                The data we collect is <b>strictly anonymous</b>, unless you've been kind enough to give us your name, email address, or other identifying information. And even if you have been that kind, we
                promise <b>we won't sell your contact information to anyone</b> else because spam sucks. Of course our impossibly obtuse privacy policy can always change our minds tomorrow, but we do unto
                others as we would have them do to us. We use the web all day everyday, so trust us, we REALLY HATE SPAM.
                <br><br> 
                We store this information for an indefinite amount of time for reasons even we (debatably) don't fully understand. And when we do eventually get around to deleting it, you can bet it's still kicking
                around on some network backup drives in a storage closet. So once we have it, there's really no getting it back. 
                <br><br> 
                Not to worry, though, because we use some cutting edge security measures to protect your data against hackers and identity thieves. You'll pretty much just have to take our word for it.
                <br><br> 
                So just to recap, your information is extremely valuable to us. Our business model would totally collapse without it. No IPO, no stock options... All those 83-hour weeks and nothing to show for it. So we'll
                do our very best to use it in as many potentially profitable ways as we can conjure, over and over, while attempting to convince you there's nothing to worry about. Sorry, we have to afford to eat and
                pound beers to the face too.
                <br><br> 
                (Did somebody hold a gun to your head and force you to visit this site? No, they did not. Did you run into a pay wall on the home page demanding your Visa number? No, you did not. You think we just give
                all this stuff away because we're nice guys?  We are going to have to explore every humanly decent way to keep the lights and servers running here at PlanJar. Please be patient with us... The ride should
                be fun.)
                <br><br> 
                This privacy policy may change at any time. In fact, it's changed multiple times since we first started typing. Good luck figuring out how, because we're sure as hell not going to spam you every time we
                update it. But then, you probably stopped reading after paragraph three. Speaking of which... If you made it this far, here's a cookie, and the cake is a lie.
                <br><br>
            </div>
        </div> 
        <div class="bottom_links">
            <a href="/help" id="bottom_link">FAQ</a>
            <a href="/tutorial" id="bottom_link">Tutorial</a>
            <a href="/about" id="bottom_link">About Us</a>
            <a href="/privacy" id="bottom_link">Privacy</a>
            <!--<a href="http://blog.planjar.com/" id="profile_link">Blog</a>-->
        </div>

    </body>  
</html>  