{*{strip}*}
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{$title|default:"Booya! Amplifier Services"}</title>

        {literal}<!-- build:css({.,app}) styles/vendor.css -->{/literal}
        <!-- bower:css -->
        <link rel="stylesheet" href="../../../bower_components/normalize-css/normalize.css" />
        <!-- endbower -->
        <!-- endbuild -->
        <!-- build:css(.tmp) styles/main.css -->
        <link rel="stylesheet" href="styles/main.css">
        <!-- endbuild -->

    </head>
    <body id="csstyle">

        <header role="banner">
            <div class="logo">
                <img src="img/logo.png" />
            </div>

            <nav role="navigation">
                <img src="img/knob.png" />
                <ul>
                    <li data-page="/" data-rotation="25" class="active">Home</li>
                    <li data-page="/about" data-rotation="0" >About</li>
                    <li data-page="/news" data-rotation="-25" >News</li>
                    <li data-page="/amps" data-rotation="-70" >Amps</li>
                    <li data-page="/mods" data-rotation="-120" > Mods</li>
                    <li data-page="/contact" data-rotation="-160" >Contact</li>
                </ul>
            </nav>

            <hr />
        </header>

        <main role="main">
            <div>
            {block name=main-content}Main{/block}
            </div>
        </main>

        <div id="footer-wapper">
            <footer role="contentinfo">
                <aside id="copyright" aria-label="copyright">
                    <img id="booya" src="img/booya.jpg" />
                    <span>&copy; Jamie Simpson 2015</span>
                </aside>
                <aside id="socialLinks" aria-label="social media links">
                    <a class="socialLink --instagram" href="https://instagram.com/booyaamplifiers"></a>
                    <a class="socialLink --facebook" href="https://www.facebook.com/booyaamps"></a>

                </aside>
            </footer>
        </div>

        <script
            data-module="scripts/modules/main.js"
            data-main="scripts/common.js"
            src="scripts/require.js">
        </script>

    </body>
</html>
{*{/strip}*}
