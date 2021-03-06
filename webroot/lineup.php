<?php
    session_start();

print_r( $_SESSION, 1 );
?>
<html>
    <head>
        <link rel="stylesheet" href="main.css" type="text/css"/>
        <title>SoundVille 2019</title>
    </head>
    <body>
        <img src="https://files.soundville.co.uk/logo_png.png" class="main-logo"/>
        <div class="signInWidget">
            <?php include 'signInWidget.php'; ?>
        </div>
        <div class="links" align="right">
            <?php include 'menu.php'; ?>
        </div>
        <div class="mainBodyContainer" align="center">
            <h1 class="noMargin">The line-up so far...</h1><br />
            <div class="lineupPoster">
                <img src="https://files.soundville.co.uk/logo_png.png" width="75" height="75" />
                <img src="https://files.soundville.co.uk/tomClementsBanner.jpg" class="headlineAct"/>
                <br /><br />
                <div>
                    <div class="halfWidth">
                        <img src="https://files.soundville.co.uk/dj_harvo_logo.png" class="largeAct" />
                    </div>
                    <div class="halfWidth">
                        <img src="https://files.soundville.co.uk/alex_krupa_banner_png.png" class="largeAct" />
                    </div>
                </div>
				<img src="https://files.soundville.co.uk/lizzie_cullen_name_png.png" class="largeActNotHeadline"/><br /><br />
				<img src="https://files.soundville.co.uk/logo_maddision_douch.png" class="largeActNotHeadline"/><br />
            </div>
        </div>
    </body>
</html>
