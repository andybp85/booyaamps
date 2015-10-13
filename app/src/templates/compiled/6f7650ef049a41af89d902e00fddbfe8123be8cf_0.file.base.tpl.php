<?php /* Smarty version 3.1.27, created on 2015-10-11 18:22:39
         compiled from "/Users/andrew/Sites/booyaamps/app/templates/base.tpl" */ ?>
<?php
/*%%SmartyHeaderCode:2087633830561aa8ef212a43_69650592%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '6f7650ef049a41af89d902e00fddbfe8123be8cf' => 
    array (
      0 => '/Users/andrew/Sites/booyaamps/app/templates/base.tpl',
      1 => 1444581488,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2087633830561aa8ef212a43_69650592',
  'variables' => 
  array (
    'title' => 0,
  ),
  'has_nocache_code' => false,
  'version' => '3.1.27',
  'unifunc' => 'content_561aa8ef5e06e3_29894351',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_561aa8ef5e06e3_29894351')) {
function content_561aa8ef5e06e3_29894351 ($_smarty_tpl) {

$_smarty_tpl->properties['nocache_hash'] = '2087633830561aa8ef212a43_69650592';
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?php echo (($tmp = @$_smarty_tpl->tpl_vars['title']->value)===null||$tmp==='' ? "Booya! Amplifier Services" : $tmp);?>
</title>

    <meta name="description" content="Source code generated using layoutit.com">
    <meta name="author" content="LayoutIt!">

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">

  </head>
  <body>

    <div class="container-fluid">
	<div class="row">
		<div class="col-md-3">
		</div>
		<div class="col-md-3">
		</div>
		<div class="col-md-6">
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
            Main
		</div>
	</div>
</div>

    <?php echo '<script'; ?>
 src="js/jquery.min.js"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 src="js/bootstrap.min.js"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 src="js/scripts.js"><?php echo '</script'; ?>
>
  </body>
</html>
<?php }
}
?>