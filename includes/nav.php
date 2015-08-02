<?php include_once(dirname(__DIR__)."/includes/db.php"); ?>
<!DOCTYPE html>
<html>
    <head>
        <link href="//fonts.googleapis.com/css?family=Roboto:100,300,400" rel="stylesheet" type="text/css" />
        <script src="//code.jquery.com/jquery-1.11.0.min.js" type="text/javascript"></script>
        <link rel="stylesheet" type="text/css" href="assets/style.css" />
        <script src="assets/script.js" type="text/javascript"></script>
        <link rel="icon" href="assets/images/logo-bottle.png" />
        <meta name="viewport" content="width=device-width" />
        <meta name="google" value="notranslate" />
        <title><?php echo isset($page_title) ? "$page_title | Shyr Wines" : "Shyr Wines"; ?></title>
    </head>
    <body>
        <nav>
            <a id="nav-logo" href="/">
                <div id="nav-img-wrapper">
                    <img id="nav-img" src="assets/images/logo.gif" alt="Shyr Wines Logo" />
                </div>
            </a>
            <div class="nav-item"><a href="about">ABOUT</a></div>
            <div class="nav-item"><a href="contact-us">CONTACT US</a></div>
            <div class="nav-item">
                <a href="cart">CART</a>
                <div id="popover-content">
                    <div id="popover-arrow"></div>
                    <div id="popover-box">
                        <div id="wine-added">
                            <img id="wine-added-pic" src="assets/images/bottle.svg">
                            <div id="center">
                                <div id="wine-added-name"></div>
                                <div id="added-to-cart">added to cart</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <form method="get" action="all-wines">
                <input autocomplete="off" name="q" id="nav-search" type="text" value='<?php if (isset($_GET["q"])) { echo $_GET["q"]; } ?>' placeholder="Search by wine name, keyword..." />
                <div id="mag-circle"></div>
                <div id="mag-handle"></div>
                <div id="auto-container"></div>
            </form>
            <div id="social-container">
               <div id="nav-facebook" class="nav-social">
                    <a class="title" href="https://www.facebook.com/shyrwines" target="_blank">FACEBOOK</a>
                    <div class="fb-like widget" data-href="https://www.facebook.com/shyrwines" data-layout="button" data-action="like" data-show-faces="true" data-share="false"></div>
                </div>
                <div id="nav-twitter" class="nav-social">
                    <a class="title" href="https://twitter.com/shyrwines" target="_blank">TWITTER</a>
                    <a href="https://twitter.com/ShyrWines" class="twitter-follow-button widget" data-show-count="false" data-show-screen-name="false">Follow @ShyrWines</a>
                </div>
                <div id="nav-instagram" class="nav-social">
                    <a class="title" target="_blank" href="https://instagram.com/shyrwines">INSTAGRAM</a>
                </div>
                <div id="nav-linkedin" class="nav-social">
                    <a class="title" href="https://www.linkedin.com/company/shyr-wines" target="_blank">LINKEDIN</a>
                    <script src="//platform.linkedin.com/in.js" type="text/javascript"> lang: en_US</script>
                    <script type="IN/FollowCompany" data-id="3648850"></script>
                </div>
            </div>
        </nav>
        <div id="nav-border"></div>
        <div id="dimmer"></div>
