<?php /* Smarty version 3.1.27, created on 2015-10-13 02:39:00
         compiled from "/Users/andrew/Sites/booyaamps/src/templates/base.tpl" */ ?>
<?php
/*%%SmartyHeaderCode:402401226561c6ec4071df8_31303095%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'd03eaf23fc5d8428eb7a16e1aa340cc37360ceb7' => 
    array (
      0 => '/Users/andrew/Sites/booyaamps/src/templates/base.tpl',
      1 => 1444703939,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '402401226561c6ec4071df8_31303095',
  'variables' => 
  array (
    'title' => 0,
  ),
  'has_nocache_code' => false,
  'version' => '3.1.27',
  'unifunc' => 'content_561c6ec40aeb64_45895650',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_561c6ec40aeb64_45895650')) {
function content_561c6ec40aeb64_45895650 ($_smarty_tpl) {

$_smarty_tpl->properties['nocache_hash'] = '402401226561c6ec4071df8_31303095';
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

    <!-- build:css({.,app}) styles/vendor.css -->
    <!-- bower:css -->
    <link rel="stylesheet" href="/bower_components/normalize-css/normalize.css">
    <!-- endbower -->
    <!-- endbuild -->
    <!-- build:css(.tmp) styles/main.css -->
    <link rel="stylesheet" href="styles/main.css">
    <!-- endbuild -->

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
 src="js/scripts.js"><?php echo '</script'; ?>
>
  </body>
</html>

<?php }
}
?>