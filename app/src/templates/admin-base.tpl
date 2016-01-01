{*{strip}*}
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{$title|default:"Booya! Amplifier Services"}</title>

        {literal}<!-- build:css({.,app}) styles/admin-vendor.css -->{/literal}
        <!-- bower:css -->
        <link rel="stylesheet" href="/bower_components/normalize-css/normalize.css" />
        <!-- endbower -->
        <!-- endbuild -->
        <!-- build:css(.tmp) styles/admin-main.css -->
        <link rel="stylesheet" href="/styles/admin.css">
        <!-- endbuild -->
        <link rel="stylesheet" href="/bower_components/codemirror/theme/solarized.css" />
        {block name=extra-css}{/block}

    </head>
    <body id="csstyle">

       <div id="wrapper">

            <header role="banner">
                <div class="logo">
                    <img src="/img/admin-logo.png" />
                </div>

                <nav role="navigation">
                    <ul>
                        <li data-page="{path_for name="ampsAdmin"}" >Amps</li>
                        <li data-page="{path_for name="modsAdmin"}" >Mods</li>
                        <li data-page="{path_for name="editor"}" >Editor</li>
                    </ul>
                </nav>
            </header>

            <main role="main">
                <div>
                {block name=mainContent}Main{/block}
                </div>
            </main>

        </div>
        <script 
            data-main="/scripts/common.js"
            src="/scripts/require.js">
        </script>
        {block name=scripts}{/block}

    </body>
</html>
{*{/strip}*}

