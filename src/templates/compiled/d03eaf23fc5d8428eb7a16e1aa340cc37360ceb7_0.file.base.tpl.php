<?php /* Smarty version 3.1.27, created on 2015-10-12 13:44:30
         compiled from "/Users/andrew/Sites/booyaamps/src/templates/base.tpl" */ ?>
<?php
/*%%SmartyHeaderCode:1190545290561bb93e0fce80_39593324%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'd03eaf23fc5d8428eb7a16e1aa340cc37360ceb7' => 
    array (
      0 => '/Users/andrew/Sites/booyaamps/src/templates/base.tpl',
      1 => 1444657469,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1190545290561bb93e0fce80_39593324',
  'variables' => 
  array (
    'title' => 0,
  ),
  'has_nocache_code' => false,
  'version' => '3.1.27',
  'unifunc' => 'content_561bb93e180994_18695519',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_561bb93e180994_18695519')) {
function content_561bb93e180994_18695519 ($_smarty_tpl) {

$_smarty_tpl->properties['nocache_hash'] = '1190545290561bb93e0fce80_39593324';
?>
<!DOCTYPE html><html lang="en"><head><meta charset="utf-8"><meta http-equiv="X-UA-Compatible" content="IE=edge"><meta name="viewport" content="width=device-width, initial-scale=1"><title><?php echo (($tmp = @$_smarty_tpl->tpl_vars['title']->value)===null||$tmp==='' ? "Booya! Amplifier Services" : $tmp);?>
</title><meta name="description" content="Source code generated using layoutit.com"><meta name="author" content="LayoutIt!"><!-- build:css({.,app}) styles/main.css --><link rel="stylesheet" href="styles/main.css"><!-- bower:css --><link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.css" /><!-- endbower --><!-- endbuild --></head><body><div class="container-fluid"><div class="row"><div class="col-md-3"></div><div class="col-md-3"></div><div class="col-md-6"></div></div><div class="row"><div class="col-md-12">Main</div></div></div><?php echo '<script'; ?>
 src="js/jquery.min.js"><?php echo '</script'; ?>
><?php echo '<script'; ?>
 src="js/bootstrap.min.js"><?php echo '</script'; ?>
><?php echo '<script'; ?>
 src="js/scripts.js"><?php echo '</script'; ?>
></body></html>
<?php }
}
?>