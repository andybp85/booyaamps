<?php /* Smarty version 3.1.27, created on 2015-10-06 12:58:41
         compiled from "/Users/andrew/Sites/booyahamps/app/templates/base.tpl" */ ?>
<?php
/*%%SmartyHeaderCode:11989002625613c581d5f920_97049813%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '37baf36ee4c8dcf58b8db5f827b0c5a9ab5b8a99' => 
    array (
      0 => '/Users/andrew/Sites/booyahamps/app/templates/base.tpl',
      1 => 1444136289,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '11989002625613c581d5f920_97049813',
  'variables' => 
  array (
    'media_url' => 0,
    'app_title' => 0,
    'page_name' => 0,
  ),
  'has_nocache_code' => false,
  'version' => '3.1.27',
  'unifunc' => 'content_5613c581e717b8_06477029',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_5613c581e717b8_06477029')) {
function content_5613c581e717b8_06477029 ($_smarty_tpl) {

$_smarty_tpl->properties['nocache_hash'] = '11989002625613c581d5f920_97049813';
?>
<!DOCTYPE html>
<!--[if lt IE 7 ]> <html lang="de" class="no-js ie ie6"> <![endif]-->
<!--[if IE 7 ]>    <html lang="de" class="no-js ie ie7"> <![endif]-->
<!--[if IE 8 ]>    <html lang="de" class="no-js ie ie8"> <![endif]-->
<!--[if IE 9 ]>    <html lang="de" class="no-js ie ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html lang="de" class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">

        <link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['media_url']->value;?>
stylesheets/all.css?1" media="all">
        

        <title>Willkommen! | <?php echo $_smarty_tpl->tpl_vars['app_title']->value;?>
</title>
        <meta name="description" content="Beschreibung">
        <meta name="author" content="">

        <?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['media_url']->value;?>
scripts/libs/modernizr/modernizr-1.6.min.js"><?php echo '</script'; ?>
>
    </head>

    <body>
        <div id="page" class="<?php echo $_smarty_tpl->tpl_vars['page_name']->value;?>
">
            <header>
                <hgroup>
                    <h1><a href="./"><?php echo $_smarty_tpl->tpl_vars['app_title']->value;?>
</a></h1>
                </hgroup>

                
                <nav>
                    <ul class="group">
                        <li id="index"<?php if ($_smarty_tpl->tpl_vars['page_name']->value == 'index') {?> class="active"<?php }?>><a href="./">Index</a></li>
                        <li id="my-subpage"<?php if ($_smarty_tpl->tpl_vars['page_name']->value == 'my-subpage') {?> class="active"<?php }?>><a href="index.php?action=my-subpage">My subpage</a></li>
                    </ul>
                </nav>
                
            </header>

            <div id="main" class="group">
                <div id="content">
                    

                    
                </div> <!-- end #content -->

                <div id="sidebar">
                    

                    
                </div> <!-- end #sidebar -->
            </div> <!-- end #main -->

            <footer>
                
                    <a href="index.php?action=impressum">Impressum</a>
                
            </footer>
        </div> <!-- end #page -->

        <?php echo '<script'; ?>
 src="//ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js"><?php echo '</script'; ?>
>
        <?php echo '<script'; ?>
>!window.jQuery && document.write(unescape('%3Cscript src="<?php echo $_smarty_tpl->tpl_vars['media_url']->value;?>
scripts/libs/jquery/jquery-1.4.4.min.js"%3E%3C/script%3E'))<?php echo '</script'; ?>
>
        
        <!--[if lt IE 7 ]>
            <?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['media_url']->value;?>
scripts/libs/dd_belatedpng/dd_belatedpng.js"><?php echo '</script'; ?>
>
            <?php echo '<script'; ?>
>DD_belatedPNG.fix('img, .trans-bg');<?php echo '</script'; ?>
>
        <![endif]-->
    </body>
</html>
<?php }
}
?>